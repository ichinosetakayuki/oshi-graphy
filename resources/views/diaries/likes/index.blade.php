<x-app-layout>
    <x-slot name="title">Oshi Graphy | いいねユーザー一覧</x-slot>

    <x-slot name="header">
        <h2 class="text-lg sm:text-2xl font-semibold">いいねユーザー一覧</h2>
    </x-slot>

    {{-- パンくず --}}
    <nav class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 sm:mt-5 flex justify-between items-center text-[11px] text-gray-600 dark:text-gray-300 sm:text-base no-print">
        <div>
            <a href="{{ route('diaries.index') }}" class="underline">マイページ</a>
            <span class="mx-1">/</span>
            <a href="{{ route('diaries.show', $diary) }}" class="underline">{{ $diary->happened_on->format('Y年n月j日') }}の日記</a>
            <span class="mx-1">/</span>
            <span>いいねユーザー一覧</span>
        </div>
        <div x-data @click="history.back()" class="underline cursor-pointer">戻る</div>

    </nav>

    {{-- いいねユーザー一覧 --}}
    <x-likers-grid :likers='$likers' />


</x-app-layout>