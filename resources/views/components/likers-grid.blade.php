@props([
'likers'
])

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
                        <div class="line-clamp-2 min-h-8 text-xs">{{ $user->profile }}</div>
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