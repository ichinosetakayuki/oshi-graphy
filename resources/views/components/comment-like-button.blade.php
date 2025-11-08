@props([
'comment',
'liked' => null,
'count' => null,
'isReply' => false,
])

<x-like-toggle :likeable="$comment" :liked="$liked" :count="$count" :likeUrl="route('comments.like.store', $comment)" :unlikeUrl="route('comments.like.destroy', $comment)" :ownerId="$comment->user_id" :indexUrl="route('comments.likes.index', $comment)" :isReply="$isReply" />