<x-app-layout>
    <x-slot name="title">Oshi Graphy | みんなの日記 詳細</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-slot name="header">
            <h2 class="text-2xl font-semibold">{{ $diary->user->name }}さんの日記詳細</h2>
        </x-slot>

        <div class="flex justify-between">
            <div class="flex gap-3">
                <span class="bg-brand px-3 py-3 rounded-3xl font-semibold text-center align-middle">{{ $diary->happened_on->format('Y年n月j日') }}</span>
                <span class="bg-brand px-3 py-3 rounded-3xl font-semibold text-center">{{ $diary->artist->name }}</span>

            </div>
            <div class="flex flex-col md:flex-row">
                <x-secondary-button><a href="{{ route('public.diaries.user', $diary->user) }}">一覧に戻る</a></x-secondary-button>
            </div>
        </div>
        <div class="flex mt-3 gap-4">
            <p class="flex-1 bg-brand-light p-2 rounded-lg m-2">{{ $diary->body }}</p>
        </div>
        <p class="text-sm ml-2">更新日時：{{ $diary->updated_at->format('Y-m-d H:i') }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-3">
            @forelse($diary->images as $image)
            <img src="{{ Storage::url($image->path) }}" alt="日記写真">


            @empty
            <p class="text-gray-500">写真はありません</p>

            @endforelse
        </div>

        {{-- コメント部分 --}}
        <h3 class="text-lg font-semibold my-2">⭐️コメント({{ $diary->comments->count() }})</h3>

        {{-- コメント入力ボタン＆モーダル本体 --}}
        <x-comment-modal :diary="$diary" :name="'commentModal-'.$diary->id" maxWidth="md" />

        {{-- コメント一覧 --}}
        <ul class="space-y-4">
            @forelse($diary->comments as $comment)
            <li>
                <div>
                    <span class="text-sm font-semibold">{{ $comment->user->name ?? '退会ユーザー' }}</span>
                    <span class="text-xs ml-1">{{ $comment->updated_at->diffForHumans() }}</span>
                    {{-- diffForHumans():人間感覚○分前などで表示 --}}
                </div>
                <p class="whitespace-pre-wrap bg-brand-light shadow-md rounded-lg p-4 text-sm">{{ $comment->body }}</p>
                @if( auth()->id() === $comment->user_id )
                <form method="post" action="{{ route('comments.destroy', $comment) }}">
                    @csrf
                    @method('DELETE')
                    <x-secondary-button type="submit" onclick="return confirm('このコメントを削除しますか？')">削除</x-secondary-button>
                </form>
                @endif
            </li>
            @empty
            <li class="text-sm text-gray-500">まだコメントはありません</li>
            @endforelse
        </ul>

    </div>

</x-app-layout>