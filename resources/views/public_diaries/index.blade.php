<x-app-layout>
    <x-slot name="title">Oshi Graphy | みんなの日記</x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-semibold">📖 みんなの日記</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

        <form method="GET" class="flex flex-col lg:flex-row lg:flex-wrap lg:items-center gap-3 mb-5">
            <div class="flex gap-3">
                {{-- 年 --}}
                <div class="flex gap-3 items-center">
                    <label for="year" class="font-semibold">年</label>
                    <select id="year" name="year" class="border rounded px-3 py-1 w-24">
                        <option value="">すべて</option>
                        @foreach($years as $y)
                        <option value="{{ $y }}" @selected($year==$y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- 月 --}}
                <div class="flex gap-3 items-center">
                    <label for="month" class="font-semibold">月</label>
                    <select id="month" name="month" class="border rounded px-3 py-1 w-24">
                        <option value="">すべて</option>
                        @foreach($months as $m)
                        <option value="{{ $m }}" @selected($month==$m)>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                {{-- アーティスト --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    <label for="artist_id" class="w-28 lg:text-right font-semibold shrink-0">アーティスト</label>
                    <div class="w-full md:w-64">
                        <select name="artist_id" id="artist_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @if(!empty($artistId) && !empty($artistName))
                            <option value="{{ $artistId }}" selected>{{ $artistName }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div>
                    <button class="rounded px-4 py-1 bg-brand">絞り込み</button>
                    @if($year || $month || $artistName)
                    <a href="{{ route('public.diaries.index') }}" class="text-sm underline">条件クリア</a>
                    @endif
                </div>
            </div>

        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 motion-safe:animate-fade-up">
            @forelse($diaries as $diary)
            <article x-data @click="window.location='{{ route('public.diaries.show', $diary) }}'" class="bg-amber-50 border border-brand-dark rounded-2xl shadow-md overflow-hidden transform transition-transform duration-200 hover:scale-105 hover:shadow-xl ">
                <img src="{{ $diary->coverImage ? Storage::url($diary->coverImage->path) : asset('images/placeholder.png')}}" class="w-full h-48 object-cover" alt="日記サムネイル画像">
                <div class="flex flex-col justify-between h-32 p-3">
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>{{ $diary->happened_on?->format('Y年n月j日') }}</span>
                            <span class="text-red-500">{{ $diary->artist->name ?? '-' }}</span>
                        </div>
                        <p class="text-sm line-clamp-2 lg:line-clamp-3 mb-2">{{ $diary->body }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center"><a href="{{ route('public.diaries.user', $diary->user) }}" class="text-[11px] px-2 py-0.5 rounded bg-green-500 text-white hover:underline" @click.stop>{{ $diary->user->name }}</a>
                        </div>
                        <div class="flex items-center gap-1">
                            <x-like-button :diary="$diary" :liked="$diary->liked_by_me" :count="$diary->likes_count" />
                            <span class="text-sm">⭐️コメント({{ $diary->comments_count }})</span>
                        </div>
                    </div>
                </div>
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