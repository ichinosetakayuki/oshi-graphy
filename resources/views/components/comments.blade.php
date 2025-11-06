@props([
'diary',
'comments',
])


<ul x-data="{ openByRoot: {} }">
    @forelse($comments as $comment)
    @php $isReply = $comment->depth > 0; @endphp
    <li
        x-data="{ rid: {{ $comment->root_id ?? 'null' }} }"
        x-show="{{ $isReply ? '!!openByRoot[rid]' : 'true' }}"
        style="margin-left: {{ $comment->depth * 1.5 }}rem"
        class="{{ $isReply ? 'mt-0' : 'mt-2 mb-2' }}"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90">
        <div class="flex justify-between">
            <div class="flex items-center gap-1">
                <img src="{{ $comment->user->icon_url ?? asset('images/icon_placeholder.png') }}" alt="アイコン画像" class="inline-block rounded-full object-cover border {{ $isReply ? 'size-4' : 'size-5' }}">
                <span class="{{ $isReply ?  'text-xs' : 'text-sm font-semibold' }}">
                    @if($comment->user->name)
                    <a href="{{ route('user.profile.show', $comment->user) }}">{{ $comment->user->name }}</a>
                    @else
                    退会ユーザー
                    @endif
                    {{-- $comment->user->name ?? '退会ユーザー' --}}
                </span>
                <span class="text-xs ml-1">{{ $comment->created_at->diffForHumans() }}</span>
                {{-- diffForHumans():人間感覚○分前などで表示 --}}
                {{-- いいねボタン --}}
                <x-comment-like-button :comment="$comment" :liked="$comment->liked_by_me" :count="$comment->likes_count" :isReply="$isReply" />
            </div>
            @if( auth()->id() === $comment->user_id )
            <button
                type="button"
                x-data
                @click="$dispatch('confirm-delete', {
                            name: 'confirm-delete',
                            title: '{{ $isReply ? '返信削除' : 'コメント削除' }}',
                            action: '{{ $isReply ? route('replies.destroy', $comment) : route('comments.destroy', $comment) }}',
                            message: '{{ $isReply ? 'この返信を削除します。よろしいですか？' : 'このコメントを削除します。よろしいですか？' }}'
                        })"
                title="削除"
                class="flex items-end">
                <x-icons.trash size="{{ $isReply ? 'size-3' : 'size-4' }}" class="text-brand-dark" />
            </button>
            @endif
        </div>
        <p class="whitespace-pre-wrap shadow-md rounded-lg {{ $isReply ? 'bg-yellow-100 p-2 text-xs' : 'bg-brand-light p-4 text-sm' }}">{{ $comment->body }}</p>
        {{-- 返信ボタン --}}
        <button type="button" class="mb-2 pl-2 text-xs"
            x-data
            @click="$dispatch('open-reply', {
                parentId: {{ $comment->id }}
                })">-返信する-</button>
        @if(!$isReply && $comment->replies_count > 0)
        <button
            type="button"
            class="mb-2 pl-2 text-xs"
            @click="openByRoot[{{ $comment->id }}]=!openByRoot[{{ $comment->id }}]">
            <span x-show="!openByRoot[{{ $comment->id }}]">-返信を見る({{ $comment->replies_count }})-</span>
            <span x-show="openByRoot[{{ $comment->id }}]">-返信を隠す-</span>
        </button>
        @endif
    </li>
    @empty
    <li class="text-sm text-gray-500">まだコメントはありません</li>
    @endforelse
</ul>
<x-reply-modal :diary="$diary" name="reply-modal" maxWidth="md" />