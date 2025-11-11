<x-app-layout>
    <x-slot name="title">Oshi Graphy | フォロワー一覧</x-slot>

    <x-slot name="header">
        <div class="max-w-3xl mx-auto flex items-center gap-10 justify-center">
            <h2 class="text-base font-semibold text-brand-dark">フォロワー一覧</h2>
        </div>
    </x-slot>

    {{-- フォロワー一覧 --}}
    <div class="motion-safe:animate-fade-up">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <ul class="space-y-4">
                @forelse($followers as $user)
                <li class="border-b border-b-gray-400">
                    <a href="{{ route('user.profile.show', $user) }}" class="flex gap-3">
                        <img src="{{ $user->icon_url ?? asset('images/icon_placeholder.png') }}" alt="アイコン画像" class="inline-block size-10 rounded-full object-cover border">
                        <div>
                            <div class="font-semibold">{{ $user->name ?? '退会ユーザー' }}</div>
                            <div class="line-clamp-2 min-h-8 text-xs">{{ $user->profile }}</div>
                        </div>
                    </a>
                </li>
                @empty
                <li class="text-sm text-gray-500">まだフォロワーがいません。</li>
                @endforelse
            </ul>
            <div class="mt-6">{{ $followers->links() }}</div>
        </div>
    </div>
</x-app-layout>