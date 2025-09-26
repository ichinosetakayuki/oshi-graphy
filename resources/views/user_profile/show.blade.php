<x-app-layout>
    <x-slot name="title">Oshi Graphy | {{ $user->name }}プロフィール</x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-semibold">{{ $user->name }}さんのプロフィール</h2>
    </x-slot>

    <div class="max-w-5xl w-full mx-auto px-4 py-4 sm:py-6">
        <div class="max-w-3xl mx-auto border rounded-2xl px-4 sm:px-8 py-6 shadow bg-white space-y-4">
            <div class="flex items-center gap-6">
                <img src="{{ $user->icon_url }}" alt="アイコン" class="w-32 h-32 rounded-full object-cover border">
                <div class="text-2xl font-semibold">{{ $user->name }}</div>
            </div>
            <div class="bg-brand-light min-h-16 rounded-lg p-6 whitespace-pre-line">{{ $user->profile ?: '(未設定)'}}</div>
            <div class="text-lg text-gray-800">
                ⭐️公開日記数：{{ $user->public_diaries_count }}
            </div>
            <div class="flex justify-end">
                <a x-data @click="location.href='{{ route('public.diaries.user', $user) }}'" class="underline text-gray-600 text-xs sm:text-base cursor-pointer">{{ $user->name }}さんの日記一覧に戻る</a>
            </div>
        </div>
    </div>


</x-app-layout>