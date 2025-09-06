<x-app-layout>
    <x-slot name="title">Oshi Graphy | みんなの日記</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-slot name="header">
            <h2 class="text-2xl font-semibold">みんなの日記</h2>
        </x-slot>

        <form method="GET" class="flex flex-wrap items-center gap-3 mb-5">
            {{-- 年 --}}
            <div class="flex gap-3 items-center">
                <label class="font-semibold">年</label>
                <select name="year" class="border rounded px-3 py-1 w-24">
                    <option value="">すべて</option>
                    @foreach($years as $y)
                    <option value="{{ $y }}" @selected($year==$y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            {{-- 月 --}}
            <div class="flex gap-3 items-center">
                <label class="font-semibold">月</label>
                <select name="month" class="border rounded px-3 py-1 w-24">
                    <option value="">すべて</option>
                    @foreach($months as $m)
                    <option value="{{ $m }}" @selected($month==$m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            {{-- アーティスト --}}
            <div class="flex gap-3 items-center">
                <label for="artist_id" class="w-40 font-semibold">アーティスト</label>
                <select name="artist_id" id="artist_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @if(!empty($artistId) && !empty($artistName))
                    <option value="{{ $artistId }}" selected>{{ $artistName }}</option>
                    @endif
                </select>
            </div>

            <button class="rounded px-4 py-1 bg-brand">絞り込み</button>
            @if($year || $month || $artistName)
            <a href="{{ route('public.diaries.index') }}" class="text-sm underline">条件クリア</a>
            @endif
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($diaries as $diary)
            <article onclick="window.location='{{ route('public.diaries.show', $diary) }}'" class="bg-white rounded-2xl shadow overflow-hidden hover:shadow-lg transition">
                <!-- <a href="{{ route('public.diaries.show', $diary) }}" class="block bg-white rounded-2xl shadow overflow-hidden hover:shadow-lg transition"> -->
                <img src="{{ $diary->coverImage ? Storage::url($diary->coverImage->path) : asset('images/placeholder.png')}}" class="w-full h-48 object-cover" alt="日記サムネイル画像">
                <div class="p-3">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>{{ $diary->happened_on?->format('Y年n月j日') }}</span><!-- happened_onがnullなら空文字を返す -->
                        <span class="text-red-500">{{ $diary->artist->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center"><span class="text-[11px] px-2 py-0.5 rounded bg-green-500 text-white"><a href="{{ route('public.diaries.index', ['user' => $diary->user_id]) }}" class="hover:underline" @click.stop>{{ $diary->user->name }}</a></span>
                    </div>
                    <p class="text-sm line-clamp-2 mb-2">{{ $diary->body }}</p><!-- line-clamp-2:テキストを２行で切り取り、あふれた分は...で省略 -->
                    <div class="flex justify-between items-center">
                        <!-- <span class="text-[11px] px-2 py-0.5 rounded {{ $diary->is_public ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                            {{ $diary->is_public ? '公開' : '非公開' }}
                        </span> -->
                        <span class="text-sm">⭐️コメント(){{-- / {{ $diary->comments_count ?? 0 }} ←実装後に表示 --}}</span>
                    </div>
                </div>
                <!-- </a> -->
            </article>

            @empty
            <p class="text-gray-500">まだ日記はありません</p>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $diaries->links() }} {{-- ページネーション --}}
        </div>



    </div>
    {{-- Select2のCSS/JSをこのページだけに読み込む --}}
    @push('vendor-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    @endpush



    @push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            const ARTIST_SEARCH_URL = @json(route('artists.search'));

            const $sel = $("#artist_id").select2({
                width: '100%',
                placeholder: 'アーティストを検索...',
                allowClear: true,
                ajax: {
                    url: ARTIST_SEARCH_URL,
                    dataType: 'json',
                    delay: 200,
                    data: params => ({
                        q: params.term || ''
                    }),
                    processResults: data => ({
                        results: (data.items || []).map(it => ({
                            id: it.id,
                            text: it.name
                        })),
                    }),
                },
                minimumInputLength: 1,
                language: {
                    inputTooShort: () => '1文字以上入力してください',
                    searching: () => '検索中...',
                    noResults: () => '該当するアーティストが見つかりません'
                },
                containerCssClass: 'og-select2-tall',
                selectionCssClass: 'og-select2-tall',
            });
            const oldId = @json(old('artist_id'));
            const oldName = @json(old('artist_name'));
            if (oldId && oldName) {
                const opt = new Option(oldName, oldId, true, true);
                $sel.append(opt).trigger('change');
            }

            // 選択変更時に hidden の artist_name へラベル名を入れる（バリデーション戻りで使える）
            $sel.on('select2:select', (e) => {
                $("#artist_name_old").val(e.params.data.text || '');
            });
            $sel.on('select2:clear', () => {
                $("#artist_name_old").val('');
            });
        });
    </script>
    @endpush


</x-app-layout>