@props([
'diary',
'liked' => null,
'count' => null,
])

<x-like-toggle :likeable="$diary" :liked="$liked" :count="$count" :likeUrl="route('diaries.like.store', $diary)" :unlikeUrl="route('diaries.like.destroy', $diary)" :ownerId="$diary->user_id" :indexUrl="route('diaries.likes.index', $diary)" />