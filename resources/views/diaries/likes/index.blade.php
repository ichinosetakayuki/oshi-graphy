<x-app-layout>
    <x-slot name="title">Oshi Graphy | いいねユーザー一覧</x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-semibold">いいねユーザー一覧</h2>
    </x-slot>

    {{-- パンくず --}}
    <nav class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 sm:mt-5 flex items-center text-xs text-gray-600 sm:text-base no-print">
        <a href="{{ route('diaries.index') }}" class="underline">マイページ</a>
        <span class="mx-1">/</span>
        <a href="{{ route('diaries.show', $diary) }}" class="underline">{{ $diary->happened_on->format('Y年n月j日') }}の日記</a>
        <span class="mx-1">/</span>
        <span>いいねユーザー一覧</span>
    </nav>

    {{-- いいねユーザー一覧 --}}
    <div class="motion-safe:animate-fade-up">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <ul class="space-y-4">
                @forelse( $likers as $user )
                <li class="border-b border-b-gray-400">
                    <a href="{{ route('user.profile.show', $user) }}" class="flex gap-3">
                        <img src="{{ $user->icon_url ?? asset('images/icon_placeholder.png') }}" alt="アイコン画像" class="inline-block size-10 rounded-full object-cover border">
                        <div>
                            <div class="font-semibold">{{ $user->name ?? '退会ユーザー' }}</div>
                            <div class="line-clamp-2">{{ $user->profile }}</div>
                        </div>
                    </a>
                </li>
                @empty
                <li class="text-sm text-gray-500">まだいいねがありません。</li>
                @endforelse
            </ul>
            <div class="mt-6">{{ $likers->links() }}</div>
        </div>
    </div>


</x-app-layout>