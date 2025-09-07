<x-app-layout>
    <x-slot name="title">Oshi Graphy | みんなの日記 詳細</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-slot name="header">
            <h2 class="text-2xl font-semibold">{{ $diary->user->name }}さんの日記詳細</h2>
        </x-slot>

        <div class="flex justify-between">
            <div class="flex gap-3">
                <span class="bg-brand px-3 py-3 rounded-3xl font-semibold text-center align-middle">{{ $diary->happened_on->format('Y年n月j日') }}</span>
                <span class="bg-brand px-3 py-3 rounded-3xl font-semibold text-center">{{ $diary->artist->name }}</span>

            </div>
            <div class="flex flex-col md:flex-row">
                <x-secondary-button><a href="{{ route('public.diaries.user', $diary->user) }}">一覧に戻る</a></x-secondary-button>
            </div>
        </div>
        <div class="flex mt-3 gap-4">
            <p class="flex-1 bg-brand-light p-2 rounded-lg m-2">{{ $diary->body }}</p>
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