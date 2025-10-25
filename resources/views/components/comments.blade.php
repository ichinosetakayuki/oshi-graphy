@props([
    'comments' => null,
])

<ul class="space-y-4">
    @forelse($comments as $comment)
    <li>
        <div class="flex justify-between">
            <div class="flex items-center gap-1">
                <img src="{{ $comment->user->icon_url ?? asset('images/icon_placeholder.png') }}" alt="アイコン画像" class="inline-block size-5 rounded-full object-cover border">
                <span class="text-sm font-semibold">{{ $comment->user->name ?? '退会ユーザー' }}</span>
                <span class="text-xs ml-1">{{ $comment->updated_at->diffForHumans() }}</span>
                {{-- diffForHumans():人間感覚○分前などで表示 --}}
                {{-- いいねボタン --}}
                <x-comment-like-button :comment="$comment" :liked="$comment->liked_by_me" :count="$comment->likes_count" />
            </div>
            @if( auth()->id() === $comment->user_id )
            <button
                type="button"
                x-data
                x-on:click="
                                    window.dispatchEvent(new CustomEvent('confirm-delete', {
                                        detail: {
                                            name: 'confirm-delete',
                                            title: 'コメント削除',
                                            action: '{{ route('comments.destroy', $comment) }}',
                                            message: 'このコメントを削除します。よろしいですか？'
                                        }
                                    }))"
                title="削除"
                class="flex items-end">
                <x-icons.trash size="size-4" class="text-brand-dark" />
            </button>
            @endif
        </div>
        <p class="whitespace-pre-wrap bg-brand-light shadow-md rounded-lg p-4 text-sm">{{ $comment->body }}</p>

    </li>
    @empty
    <li class="text-sm text-gray-500">まだコメントはありません</li>
    @endforelse
</ul>