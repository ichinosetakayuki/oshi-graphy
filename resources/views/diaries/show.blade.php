@push('styles')
<style>
    @media print {

        .no-print,
        header,
        button {
            display: none;
        }
    }

    /* A4縦に整える */
    @page {
        size: A4;
        margin: 16mm;
    }

    body {
        background: #fff !important;
        color: #000;
    }

    /* 画像を収める 途中で切れないようにする*/
    img {
        max-width: 100% !important;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    /* 改ページを入れたいところに<div class="page-break"></div>を置く */
    .page-break {
        page-break-before: always;
    }
</style>
@endpush

<x-app-layout>
    <x-slot name="title">Oshi Graphy | （マイページ）日記詳細</x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-semibold">{{ auth()->user()->name }}さんの日記詳細</h2>
    </x-slot>

    {{-- パンくず --}}
    <nav class="max-w-5xl mx-auto flex items-center text-xs text-gray-600 sm:text-base px-4 my-3 sm:my-5 no-print">
        <a href="{{ route('diaries.index') }}" class="underline">マイページ</a>
        <span class="mx-1">/</span>
        <span>{{ $diary->happened_on->format('Y年n月j日') }}の日記</span>
    </nav>

    <div class="motion-safe:animate-fade-up">
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="bg-brand p-2 rounded-lg font-semibold text-xs sm:text-base text-center shadow">{{ $diary->happened_on->format('Y年n月j日') }}</span>
                <span class="bg-brand p-2 rounded-lg font-semibold text-xs sm:text-base text-center shadow">{{ $diary->artist->name }}</span>
                @if(auth()->id() == $diary->user_id)
                <span class="{{ $diary->is_public ? 'bg-green-500' : 'bg-gray-400' }} px-2 py-2 rounded-lg font-semibold text-xs sm:text-base text-white text-center shadow">{{ $diary->is_public ? '公　開' : '非公開' }}</span>
                @endif
            </div>

            <div class="flex mt-3">
                <p class="flex-1 bg-brand-light p-2 my-2 rounded-lg shadow">{{ $diary->body }}</p>
            </div>
            <div class="flex justify-between">
                <p class="text-sm ml-2">更新日時：{{ $diary->updated_at->format('Y-m-d H:i') }}</p>
                @if(auth()->id() == $diary->user_id)
                <div class="flex items-center gap-2 mr-2">
                    <a href="{{ route('diaries.edit', $diary) }}" title="編集">
                        <x-icons.pencil-square size="w-4 h-4" class="text-brand-dark" /> {{-- 編集アイコン --}}
                    </a>
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
                    }))" title="削除">
                        <x-icons.trash size="w-4 h-4" class="text-brand-dark" /> {{-- 削除アイコン --}}
                    </button>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-3">
                @forelse($diary->images as $image)
                <img src="{{ Storage::url($image->path) }}" alt="日記写真">


                @empty
                <p class="text-gray-500">写真はありません</p>

                @endforelse
            </div>
        </section>

        @if($diary->is_public)
        <section class="bg-slate-100 py-6">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="no-print">
                    {{-- コメント部分 --}}
                    <h3 class="text-lg font-semibold my-2">⭐️コメント({{ $diary->comments->count() }})</h3>

                    {{-- コメント入力ボタン＆モーダル本体 --}}
                    <x-comment-modal :diary="$diary" :name="'commentModal-'.$diary->id" maxWidth="md" />

                    {{-- コメント一覧 --}}
                    <ul class="space-y-4">
                        @forelse($diary->comments as $comment)
                        <li>
                            <div>
                                <img src="{{ $comment->user->icon_url ?? asset('images/icon_placeholder.png') }}" alt="アイコン画像" class="inline-block size-5 rounded-full object-cover border">
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
                </div>
            </div>
        </section>
        @endif

        {{-- 日記、コメント削除確認モーダル --}}
        <x-confirm-modal name="confirm-delete" title="確認" message="本当に削除しますか？" maxWidth="md" />
    </div>

</x-app-layout>