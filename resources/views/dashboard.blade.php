<x-app-layout>
    <x-slot name="title">Oshi Graphy | ダッシュボード</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ auth()->user()->name }}さんのダッシュボード
        </h2>
    </x-slot>

    {{-- <div class="max-w-5xl w-full mx-auto px-4 py-4 sm:py-6">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("You're logged in!") }}
    </div>
    </div>
    </div>
    </div>
    </div> --}}

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white dark:bg-gray-900 shadow rounded-2xl mx-2 my-6 p-4 motion-safe:animate-fade-up">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">最近の通知</h3>
                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                    @csrf
                    <x-primary-button>すべて既読にする</x-primary-button>
                </form>
            </div>

            <ul class="divide-y mt-6">
                @forelse($notifications as $n)
                @php $data = $n->data; @endphp
                <li class="py-3 flex items-center gap-3">
                    @if(($data['type'] ?? '' ) === 'comment')
                    <x-icons.chat-bubble-left class="size-5 mt-1 text-blue-600" />
                    @else
                    <x-icons.heart class="size-5 mt-1 text-rose-600" />
                    @endif
                    <div class="flex-1">
                        <a href="{{ route('notifications.go', $n->id) }}" class="{{ is_null($n->read_at) ? 'underline font-semibold text-gray-900 dark:text-gray-100' : '' }}">
                            {{ $data['message'] ?? '通知'}}
                        </a>
                        <div class="text-xs text-gray-500">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="flex items-center">
                        {{-- @if(is_null($n->read_at))
                        <form method="POST" action="{{ route('notifications.markRead', $n->id) }}">
                            @csrf
                            <button class="text-xs underline">既読</button>
                        </form>
                        @endif --}}
                        <form method="POST" action="{{ route('notifications.destroy', $n->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs text-gray-500 underline">削除</button>
                        </form>
                    </div>
                </li>
                @empty
                <li class="py-6 text-gray-500">新しい通知はありません。</li>
                @endforelse
            </ul>
        </div>
    </div>

    @push('scripts')
    <script>
        window.addEventListener('pageshow', (e) => {
            // back forwardキャッシュから復元された場合のみリロード
            if (e.persisted) location.reload();
        });
    </script>
    @endpush
</x-app-layout>