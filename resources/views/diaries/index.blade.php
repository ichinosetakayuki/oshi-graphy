<x-app-layout>
    <x-slot name="title">Oshi Graphy | マイページ（日記一覧）</x-slot>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-slot name="header">
            <div class="flex items-center gap-1">
                <img src="{{ auth()->user()->icon_url }}" alt="アイコン画像" class="inline-block w-8 h-8 rounded-full object-cover border">
                <h2 class="text-2xl font-semibold">{{ auth()->user()->name }}さんの日記</h2>
            </div>
        </x-slot>

        <form method="GET" class="flex flex-wrap items-center gap-3 mb-5 md:gap-5">
            <div class="flex gap-3 items-center">
                <label class="font-semibold">年</label>
                <select name="year" class="border rounded px-3 py-1 w-24">
                    <option value="">すべて</option>
                    @foreach($years as $y)
                    <option value="{{ $y }}" @selected($year==$y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 items-center">
                <label class="font-semibold">アーティスト</label>
                <select name="artist" class="border rounded px-3 py-1">
                    <option value="">すべて</option>
                    @foreach($artists as $a)
                    <option value="{{ $a->id }}" @selected($artist==$a->id)>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>


            <button class="rounded px-4 py-1 bg-brand">絞り込み</button>
            @if($year || $artist)
            <a href="{{ route('diaries.index') }}" class="text-sm underline">条件クリア</a>
            @endif
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 motion-safe:animate-fade-up">
            @forelse($diaries as $diary)
            <article onclick="window.location='{{ route('diaries.show', $diary) }}'" class="bg-white border border-gray-600 rounded-2xl shadow overflow-hidden hover:shadow-lg transition">
                <img src="{{ $diary->coverImage ? Storage::url($diary->coverImage->path) : asset('images/placeholder.png')}}" class="w-full h-48 object-cover" alt="日記サムネイル画像">
                <div class="flex flex-col justify-between h-32 p-3">
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            {{-- happened_onがnullなら空文字を返す↓ --}}
                            <span>{{ $diary->happened_on?->format('Y年n月j日') }}</span>
                            <span class="text-red-500">{{ $diary->artist->name ?? '-' }}</span>
                        </div>
                        {{-- line-clamp-2:テキストを２行で切り取り、あふれた分は...で省略 --}}
                        <p class="text-sm line-clamp-3 mb-2">{{ $diary->body }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] px-2 py-0.5 rounded {{ $diary->is_public ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                            {{ $diary->is_public ? '公開' : '非公開' }}
                        </span>
                        <span class="text-sm">⭐️コメント({{ $diary->comments_count }})</span>
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

</x-app-layout>