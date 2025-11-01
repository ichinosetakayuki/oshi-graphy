@props([
'diary',
])

<ul class="space-y-4">
    @forelse($diary->comments as $comment)
    <li>
        <div class="flex justify-between">
            <div class="flex items-center gap-1">
                <img src="{{ $comment->user->icon_url ?? asset('images/icon_placeholder.png') }}" alt="アイコン画像" class="inline-block size-5 rounded-full object-cover border">
                <span class="text-sm font-semibold">
                    @if($comment->user->name)
                    <a href="{{ route('user.profile.show', $comment->user) }}">{{ $comment->user->name }}</a>
                    @else
                    退会ユーザー
                    @endif
                    {{-- $comment->user->name ?? '退会ユーザー' --}}
                </span>
                <span class="text-xs ml-1">{{ $comment->updated_at->diffForHumans() }}</span>
                {{-- diffForHumans():人間感覚○分前などで表示 --}}
                {{-- いいねボタン --}}
                <x-comment-like-button :comment="$comment" :liked="$comment->liked_by_me" :count="$comment->likes_count" />
            </div>
            @if( auth()->id() === $comment->user_id )
            <button
                type="button"
                x-data
                @click="$dispatch('confirm-delete', {
                            name: 'confirm-delete',
                            title: 'コメント削除',
                            action: '{{ route('comments.destroy', $comment) }}',
                            message: 'このコメントを削除します。よろしいですか？'
                        })"
                title="削除"
                class="flex items-end">
                <x-icons.trash size="size-4" class="text-brand-dark" />
            </button>
            @endif
        </div>
        <p class="whitespace-pre-wrap bg-brand-light shadow-md rounded-lg p-4 text-sm">{{ $comment->body }}</p>
        <div x-data="{ open: false }">
            {{-- 返信ボタン --}}
            <button type="button" class="mb-2 pl-2 text-xs"
                x-data
                @click="$dispatch('open-reply', {
                parentId: {{ $comment->id }}
            })">-返信する-</button>
            <button type="button" class="mb-2 pl-2 text-xs" @click="open=!open">-返信を見る({{ $comment->replies_count }})-</button>
            {{-- 返信一覧 --}}
            @foreach($comment->replies as $reply)
            <div class="ml-8 mb-2 text-xs"
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90">
                <div class="flex justify-between">
                    <div>
                        <span class="pl-2">{{ $reply->user->name }}</span>
                        <span class="ml-1">{{ $reply->updated_at->diffForHumans() }}</span>
                    </div>
                    @if( auth()->id() === $reply->user_id )
                    <button
                        type="button"
                        x-data
                        @click="$dispatch('confirm-delete', {
                                    name: 'confirm-delete',
                                    title: '返信削除',
                                    action: '{{ route('replies.destroy', $reply) }}',
                                    message: 'この返信を削除します。よろしいですか？'
                                })"
                        title="削除">
                        <x-icons.trash size="size-3" class="text-brand-dark" />
                    </button>
                    @endif
                </div>
                <p class="whitespace-pre-wrap bg-yellow-100 shadow-md rounded-lg p-2">{{ $reply->body }}</p>
            </div>
            @endforeach
        </div>
    </li>
    @empty
    <li class="text-sm text-gray-500">まだコメントはありません</li>
    @endforelse
</ul>
<x-reply-modal :diary="$diary" name="reply-modal" maxWidth="md" />