<x-app-layout>
    <x-slot name="title">Oshi Graphy | マイページ（日記一覧）</x-slot>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-slot name="header">
            <h2 class="text-2xl font-semibold mb-4">{{ auth()->user()->name }}さんの日記</h2>
        </x-slot>

        <form method="GET" class="flex flex-wrap items-center gap-3 mb-5">
            <label>年</label>
            <select name="year" class="border rounded px-3 py-1">
                <option value="">すべて</option>
                @foreach($years as $y)
                <option value="{{ $y }}" @selected($year==$y)>{{ $y }}年</option>
                @endforeach
            </select>

            <label class="ml-4">アーティスト</label>
            <select name="artist" class="border rounded px-3 py-1">
                <option value="">すべて</option>
                @foreach($artists as $a)
                <option value="{{ $a->id }}" @selected($artist==$a->id)>{{ $a->name }}</option>
                @endforeach
            </select>

            <button class="rounded px-4 py-1 bg-brand">絞り込み</button>
            @if($year || $artist)
            <a href="{{ route('diaries.index') }}" class="text-sm underline">条件クリア</a>
            @endif
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($diaries as $diary)
            <a href="{{ route('diaries.show', $diary) }}" class="block bg-white rounded-2xl shadow overflow-hidden hover:shadow-lg transition">
                <img src="{{ $diary->coverImage ? Storage::url($diary->coverImage->path) : asset('images/placeholder.png')}}" class="w-full h-48 object-cover" alt="日記メイン画像">
                <!-- <img src="{{ asset('images/placeholder.png')}}" class="w-full h-48 object-cover" alt="ダミー画像"> -->
                <div class="p-3">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>{{ $diary->happened_on?->format('Y年n月j日') }}</span><!-- happened_onがnullなら空文字を返す -->
                        <span class="text-red-500">{{ $diary->artist->name ?? '-' }}</span>
                    </div>
                    <p class="text-sm line-clamp-2 mb-2">{{ $diary->body }}</p><!-- line-clamp-2:テキストを２行で切り取り、あふれた分は...で省略 -->
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] px-2 py-0.5 rounded {{ $diary->is_public ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                            {{ $diary->is_public ? '公開' : '非公開' }}
                        </span>
                        <span class="text-sm">⭐️コメント(){{-- / {{ $diary->comments_count ?? 0 }} ←実装後に表示 --}}</span>
                    </div>
                </div>
            </a>
            @empty
            <p class="text-gray-500">まだ日記はありません</p>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $diaries->links() }} {{-- ページネーション --}}
        </div>
    </div>

</x-app-layout>