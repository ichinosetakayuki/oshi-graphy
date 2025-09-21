<x-app-layout>
    <x-slot name="title">Oshi Graphy | （マイページ）日記詳細</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 motion-safe:animate-fade-up">
        <x-slot name="header">
            <h2 class="text-2xl font-semibold">{{ auth()->user()->name }}さんの日記詳細</h2>
        </x-slot>

        <div class="flex justify-between">
            <div class="flex gap-3">
                <span class="bg-brand px-3 py-3 rounded-3xl font-semibold text-center align-middle">{{ $diary->happened_on->format('Y年n月j日') }}</span>
                <span class="bg-brand px-3 py-3 rounded-3xl font-semibold text-center">{{ $diary->artist->name }}</span>
                @if(auth()->id() == $diary->user_id)
                <span class="{{ $diary->is_public ? 'bg-brand' : 'bg-gray-400' }} px-3 py-3 rounded-3xl font-semibold text-center">{{ $diary->is_public ? '公　開' : '非公開' }}</span>
                @endif
            </div>
            <div class="flex flex-col md:flex-row">
                <x-primary-button>PDFにする</x-primary-button>
                <x-secondary-button><a href="{{ route('diaries.index') }}">一覧に戻る</a></x-secondary-button>
            </div>
        </div>
        <div class="flex mt-3 gap-4">
            <p class="flex-1 bg-brand-light p-2 rounded-lg m-2">{{ $diary->body }}</p>
            @if(auth()->id() == $diary->user_id)
            <div class="flex flex-col w-10 gap-2 mt-2">
                <button class="bg-brand-light text-xs w-auto p-2 rounded text-blue-600 shadow-sm drop-shadow "><a href="{{ route('diaries.edit', $diary) }}">編集</a></button>
                <button type="button"
                    x-data
                    x-on:click="
                        window.dispatchEvent(new CustomEvent('confirm-delete', {
                        detail: {
                            name: 'confirm-delete',
                            title: '日記の削除',
                            action: '{{ route('diaries.destroy', $diary) }}',
                            message: 'この日記を削除します。よろしいですか？'
                        }
                    }))"
                    class="bg-orange-200 text-xs w-full p-2 rounded text-red-500 shadow-sm drop-shadow">
                    削除</button>
            </div>
            @endif
        </div>
        <p class="text-sm ml-2">更新日時：{{ $diary->updated_at->format('Y-m-d H:i') }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-3">
            @forelse($diary->images as $image)
            <img src="{{ Storage::url($image->path) }}" alt="日記写真">


            @empty
            <p class="text-gray-500">写真はありません</p>

            @endforelse
        </div>

        @if($diary->is_public)
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
                <x-secondary-button type="button"
                    x-data
                    x-on:click="
                        window.dispatchEvent(new CustomEvent('confirm-delete', {
                        detail: {
                            name: 'confirm-delete',
                            title: 'コメント削除',
                            action: '{{ route('comments.destroy', $comment) }}',
                            message: 'このコメントを削除します。よろしいですか？'
                        }
                    }))">削除</x-secondary-button>
                @endif
            </li>
            @empty
            <li class="text-sm text-gray-500">まだコメントはありません</li>
            @endforelse
        </ul>
        @endif

        {{-- 日記、コメント削除確認モーダル --}}
        <x-confirm-modal name="confirm-delete" title="確認" message="本当に削除しますか？" maxWidth="md" />

    </div>

</x-app-layout>