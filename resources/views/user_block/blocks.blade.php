<x-app-layout>
    <x-slot name="title">Oshi Graphy | ブロック一覧</x-slot>

    <x-slot name="header">
        <div class="max-w-3xl mx-auto flex items-center gap-10 justify-center">
            <h2 class="text-base font-semibold text-brand-dark">ブロック一覧</h2>
        </div>
    </x-slot>

    {{-- パンくず --}}
    <nav class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 sm:mt-5 flex justify-between items-center text-[11px] text-gray-600 dark:text-gray-300 sm:text-base no-print">
        <div>
            <a href="{{ route('user.profile.show', auth()->user()) }}" class="underline">プロフィールへ</a>
            <span class="mx-1">/</span>
            <span>ブロック一覧({{ $blocks?->total() ?? 0 }}人)</span>
        </div>
        <div x-data @click="history.back()" class="underline cursor-pointer">戻る</div>
    </nav>

    {{-- ブロックしている人の一覧 --}}
    <div class="motion-safe:animate-fade-up">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form method="POST" action="{{ route('blocks.bulk-destroy') }}" x-data="{ selectMode : false }">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2 text-xs">
                    <button type="button" @click="selectMode=!selectMode" class="px-2 py-1 bg-gray-200 rounded-2xl shadow">
                        <template x-if="!selectMode"><span>選択</span></template>
                        <template x-if="selectMode"><span>キャンセル</span></template>
                    </button>
                    <button type="submit" x-show="selectMode" class="px-2 py-1 bg-gray-200 rounded-2xl shadow text-blue-500">ブロック解除</button>
                </div>
                <ul class="space-y-4">
                    @forelse($blocks as $user)
                    <li class="flex items-center gap-2 border-b border-b-gray-400">
                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" x-show="selectMode">
                        <a href="{{ route('user.profile.show', $user) }}" class="flex gap-3">
                            <img src="{{ $user->icon_url ?? asset('images/icon_placeholder.png') }}" alt="アイコン画像" class="inline-block size-10 rounded-full object-cover border">
                            <div>
                                <div class="font-semibold">{{ $user->name ?? '退会ユーザー' }}</div>
                                <div class="line-clamp-2 min-h-8 text-xs">{{ $user->profile }}</div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li class="text-base text-gray-500 ml-4">ブロックしている人はいません。</li>
                    @endforelse
                </ul>
            </form>
            <div class="mt-6">{{ $blocks->links() }}</div>
        </div>
    </div>

</x-app-layout>