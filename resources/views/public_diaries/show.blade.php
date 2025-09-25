<x-app-layout>
    <x-slot name="title">Oshi Graphy | みんなの日記 詳細</x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-semibold">{{ $diary->user->name }}さんの日記詳細</h2>
    </x-slot>

    {{-- パンくず --}}
    <nav class="flex items-center text-xs sm:text-base mb-3 sm:mb-5">
        <a href="{{ route('public.diaries.index') }}" class="underline">みんなの日記</a>
        <span class="mx-1">/</span>
        <a href="{{ route('public.diaries.user', $diary->user) }}" class="underline">{{ $diary->user->name }}さん</a>
        <span class="mx-1">/</span>
        <span>{{ $diary->happened_on->format('Y年n月j日') }}の日記</span>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 motion-safe:animate-fade-up">

        <div class="flex flex-wrap items-center gap-3">
            <span class="bg-brand p-2 rounded-lg font-semibold text-xs sm:text-base text-center shadow">{{ $diary->happened_on->format('Y年n月j日') }}</span>
            <span class="bg-brand p-2 rounded-lg font-semibold text-xs sm:text-base text-center shadow">{{ $diary->artist->name }}</span>
        </div>

        <div class="flex mt-3">
            <p class="flex-1 bg-brand-light p-2 my-2 rounded-lg shadow">{{ $diary->body }}</p>
        </div>
        <p class="text-sm ml-2">更新日時：{{ $diary->updated_at->format('Y-m-d H:i') }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-3">
            @forelse($diary->images as $image)
            <img src="{{ Storage::url($image->path) }}" alt="日記写真">


            @empty
            <p class="text-gray-500">写真はありません</p>

            @endforelse
        </div>

        {{-- コメント部分 --}}
        <h3 class="text-lg font-semibold my-2">⭐️コメント({{ $diary->comments->count() }})</h3>

        {{-- コメント入力ボタン＆モーダル本体 --}}
        <x-comment-modal :diary="$diary" :name="'commentModal-'.$diary->id" maxWidth="md" />

        {{-- コメント削除確認モーダル --}}
        <x-confirm-modal name="confirm-delete" title="確認" message="本当に削除しますか？" maxWidth="md" />

        {{-- コメント一覧 --}}
        <ul class="space-y-4">
            @forelse($diary->comments as $comment)
            <li>
                <div>
                    <img src="{{ $comment->user->icon_url ?? asset('images/icon_placeholder.png') }}" alt="アイコン" class="inline-block size-5 rounded-full object-cover border align-middle">
                    <span class="text-sm font-semibold">{{ $comment->user->name ?? '退会ユーザー' }}</span>
                    <span class="text-xs ml-1">{{ $comment->updated_at->diffForHumans() }}</span>
                    {{-- diffForHumans():人間感覚○分前などで表示 --}}
                </div>
                <p class="whitespace-pre-wrap bg-brand-light shadow-md rounded-lg p-4 text-sm">{{ $comment->body }}</p>
                @if( auth()->id() === $comment->user_id )
                <x-secondary-button type="button"
                    x-data
                    x-on:click="
                        window.dispatchEvent(new CustomEvent('confirm-delete', {
                        detail: {
                            name: 'confirm-delete',
                            title: 'コメント削除',
                            action: '{{ route('comments.destroy', $comment) }}',
                            message: 'このコメントを削除します。よろしいですか？'
                        }
                    }))">削除</x-secondary-button>
                @endif
            </li>
            @empty
            <li class="text-sm text-gray-500">まだコメントはありません</li>
            @endforelse
        </ul>

    </div>

</x-app-layout>