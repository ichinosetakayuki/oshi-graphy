@php
$commentExcerpt = mb_strimwidth((string)$comment->body, 0, 20, '...');
@endphp

<x-app-layout>
    <x-slot name="title">Oshi Graphy | いいねユーザー一覧</x-slot>

    <x-slot name="header">
        <h2 class="text-lg sm:text-2xl font-semibold">いいねユーザー一覧</h2>
    </x-slot>

    {{-- パンくず --}}
    <nav class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-10 mt-3 sm:mt-5 text-xs text-gray-600 dark:text-gray-300 sm:text-base flex justify-between items-end no-print">
        <div>
            <a href="{{ route('public.diaries.show', $comment->diary->id) }}" class="underline">
                {{ $comment->diary->user->name}}さんのコメント「{{ $commentExcerpt }}」
            </a>
            <div class="ml-2">/ いいねユーザー一覧</div>
        </div>
        <div x-data @click="history.back()" class="underline cursor-pointer">戻る</div>

    </nav>

    {{-- いいねユーザー一覧 --}}
    <x-likers-grid :likers='$likers' />


</x-app-layout>