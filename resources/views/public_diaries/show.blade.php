<x-app-layout>
    <x-slot name="title">Oshi Graphy | みんなの日記 詳細</x-slot>

    <x-slot name="header">
        <h2 class="text-lg sm:text-2xl font-semibold">{{ $diary->user->name }}さんの日記詳細</h2>
    </x-slot>

    {{-- パンくず --}}
    <nav class="max-w-5xl mx-auto flex items-center text-xs text-gray-600 dark:text-gray-300 sm:text-base px-4 sm:px-6 lg:px-8 my-3 sm:my-5 ">
        <a href="{{ route('public.diaries.index') }}" class="underline">みんなの日記</a>
        <span class="mx-1">/</span>
        <a href="{{ route('public.diaries.user', $diary->user) }}" class="underline">{{ $diary->user->name }}さん</a>
        <span class="mx-1">/</span>
        <span>{{ $diary->happened_on->format('Y年n月j日') }}の日記</span>
    </nav>

    <div class="motion-safe:animate-fade-up">
        <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="bg-brand dark:bg-brand-dark p-2 rounded-lg font-semibold text-xs sm:text-base lg:text-lg text-center shadow">{{ $diary->happened_on->format('Y年n月j日') }}</span>
                <span class="bg-brand dark:bg-brand-dark p-2 rounded-lg font-semibold text-xs sm:text-base lg:text-lg text-center shadow">{{ $diary->artist->name }}</span>
            </div>

            <div class="flex mt-3">
                <p class="flex-1 bg-brand-light dark:bg-brand-dark p-4 my-2 rounded-lg shadow lg:text-lg">{{ $diary->body }}</p>
            </div>
            <div class="flex items-center gap-1">
                <p class="text-sm ml-2">更新日時：{{ $diary->updated_at->format('Y-m-d H:i') }}</p>
                <x-like-button :diary="$diary" :liked="$diary->liked_by_me" :count="$diary->likes_count" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-3">
                @forelse($diary->images as $image)
                <img src="{{ Storage::url($image->path) }}" alt="日記写真">


                @empty
                <p class="text-gray-500">写真はありません</p>

                @endforelse
            </div>
        </section>

        <section class="bg-slate-100 py-6 dark:bg-slate-300 dark:text-gray-800">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- コメント部分 --}}
                <h3 class=" text-lg font-semibold my-2">⭐️コメント({{ $diary->comments->count() }})</h3>

                {{-- コメント入力ボタン＆モーダル本体 --}}
                <x-comment-modal :diary="$diary" :name="'commentModal-'.$diary->id" maxWidth="md" />

                {{-- コメント削除確認モーダル --}}
                <x-confirm-modal name="confirm-delete" title="確認" message="本当に削除しますか？" maxWidth="md" />

                {{-- コメント一覧 --}}
                <x-comments :comments="$diary->comments" />

            </div>
        </section>

    </div>


</x-app-layout>