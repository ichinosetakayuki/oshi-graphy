<x-app-layout>
    <x-slot name="title">Oshi Graphy | 日記詳細</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-slot name="header">
            <h2 class="text-2xl font-semibold mb-4">{{ auth()->user()->name }}さんの日記詳細</h2>
        </x-slot>

        <div class="flex justify-between">
            <div class="flex gap-3">
                <span>{{ $diary->happened_on->format('Y年n月j日') }}</span>
                <span>{{ $diary->artist_name }}</span>
                @if(auth()->id() == $diary->user_id)
                <span>{{ $diary->is_public ? '公開' : '非公開' }}</span>
                @endif
            </div>
            <div>
                <x-primary-button>PDFにする</x-primary-button>
                <x-secondary-button><a href="{{ route('diaries.index') }}">一覧に戻る</a></x-secondary-button>
            </div>
        </div>
        <div class="flex">
            <p>{{ $diary->body }}</p>
            @if(auth()->id() == $diary->user_id)
            <div>
                <button class="bg-brand-light text-xs rounded p-2 text-blue-600 shadow-sm drop-shadow"><a href="{{ route('diaries.edit', $diary) }}">編集</a></button>
                <form action="{{ route('diaries.destroy', $diary) }}" method="post" onsubmit="return confirm('本当に削除しますか？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-orange-200 w-auto p-2 rounded text-xs text-red-500 shadow-sm drop-shadow">削除</button>
                </form>
            </div>
            @endif
        </div>
        <div class="flex justify-between">
            <p class="text-sm">更新日時：{{ $diary->updated_at->format('Y-m-d H:i') }}</p>
            <p class="text-sm">⭐️コメント(){{-- / {{ $diary->comments_count ?? 0 }} ←実装後に表示 --}}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($diary->images as $image)
            <img src="{{ Storage::url($image->path) }}" alt="日記写真">


            @empty
            <p class="text-gray-500">写真はありません</p>

            @endforelse
        </div>




    </div>



</x-app-layout>