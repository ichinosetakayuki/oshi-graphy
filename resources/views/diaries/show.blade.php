<x-app-layout>
    <x-slot name="title">Oshi Graphy | （マイページ）日記詳細</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-slot name="header">
            <h2 class="text-2xl font-semibold">{{ auth()->user()->name }}さんの日記詳細</h2>
        </x-slot>

        <div class="flex justify-between">
            <div class="flex gap-3">
                <span class="bg-brand px-3 py-3 rounded-3xl font-semibold text-center align-middle">{{ $diary->happened_on->format('Y年n月j日') }}</span>
                <span class="bg-brand px-3 py-3 rounded-3xl font-semibold text-center">{{ $diary->artist->name }}</span>
                @if(auth()->id() == $diary->user_id)
                <span class="{{ $diary->is_public ? 'bg-brand' : 'bg-gray-400' }} px-3 py-3 rounded-3xl font-semibold text-center">{{ $diary->is_public ? '公　開' : '非公開' }}</span>
                @endif
            </div>
            <div class="flex flex-col md:flex-row">
                <x-primary-button>PDFにする</x-primary-button>
                <x-secondary-button><a href="{{ route('diaries.index') }}">一覧に戻る</a></x-secondary-button>
            </div>
        </div>
        <div class="flex mt-3 gap-4">
            <p class="flex-1 bg-brand-light p-2 rounded-lg m-2">{{ $diary->body }}</p>
            @if(auth()->id() == $diary->user_id)
            <div class="flex flex-col w-10 gap-2 mt-2">
                <button class="bg-brand-light text-xs w-auto p-2 rounded text-blue-600 shadow-sm drop-shadow "><a href="{{ route('diaries.edit', $diary) }}">編集</a></button>
                <form action="{{ route('diaries.destroy', $diary) }}" method="post" onsubmit="return confirm('本当に削除しますか？')" class="w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-orange-200 text-xs w-full p-2 rounded text-red-500 shadow-sm drop-shadow">削除</button>
                </form>
            </div>
            @endif
        </div>
        <div class="flex justify-between mt-3">
            <p class="text-sm">更新日時：{{ $diary->updated_at->format('Y-m-d H:i') }}</p>
            <p class="text-sm">⭐️コメント(){{-- / {{ $diary->comments_count ?? 0 }} ←実装後に表示 --}}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-3">
            @forelse($diary->images as $image)
            <img src="{{ Storage::url($image->path) }}" alt="日記写真">


            @empty
            <p class="text-gray-500">写真はありません</p>

            @endforelse
        </div>

    </div>

</x-app-layout>