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
        <h2 class="text-lg sm:text-2xl font-semibold">{{ auth()->user()->name }}さんの日記詳細</h2>
    </x-slot>

    <div class="dark:bg-gray-900 dark:text-gray-100 pt-3 sm:pt-5">


        {{-- パンくず --}}
        <nav class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center text-xs text-gray-600 dark:text-gray-300 sm:text-base no-print">
            <a href="{{ route('diaries.index') }}" class="underline">マイページ</a>
            <span class="mx-1">/</span>
            <span>{{ $diary->happened_on->format('Y年n月j日') }}の日記</span>
        </nav>

        <div class="motion-safe:animate-fade-up">
            <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="bg-brand dark:bg-brand-dark p-2 rounded-lg font-semibold text-xs sm:text-base lg:text-lg text-center shadow">{{ $diary->happened_on->format('Y年n月j日') }}</span>
                        <span class="bg-brand dark:bg-brand-dark p-2 rounded-lg font-semibold text-xs sm:text-base lg:text-lg text-center shadow">{{ $diary->artist->name }}</span>
                        @if(auth()->id() == $diary->user_id)
                        <span class="{{ $diary->is_public ? 'bg-green-500' : 'bg-gray-400' }} px-2 py-2 rounded-lg font-semibold text-xs sm:text-base lg:text-lg text-white text-center shadow">{{ $diary->is_public ? '公　開' : '非公開' }}</span>
                        @endif
                    </div>
                    <x-secondary-button class="no-print text-gray-600" onclick="window.print()">印刷/PDF</x-secondary-button>
                </div>

                <div class="flex mt-3">
                    <p class="flex-1 bg-brand-light dark:bg-brand-dark p-4 my-2 rounded-lg shadow lg:text-lg">{{ $diary->body }}</p>
                </div>
                <div class="flex justify-between">
                    <div class="flex items-center gap-1">
                        <p class="text-sm ml-2">更新日時：{{ $diary->updated_at->format('Y-m-d H:i') }}</p>
                        {{-- いいねボタン --}}
                        <x-like-button :diary="$diary" :liked="$diary->liked_by_me" :count="$diary->likes_count" />
                    </div>
                    @if(auth()->id() == $diary->user_id)
                    <div class="flex items-center gap-2 mr-2 no-print">
                        <a href="{{ route('diaries.edit', $diary) }}" title="編集">
                            <x-icons.pencil-square size="size-4" class="text-brand-dark" /> {{-- 編集アイコン --}}
                        </a>
                        <button type="button"
                            x-data
                            @click="$dispatch('confirm-delete', {
                                name: 'confirm-delete',
                                title: '日記の削除',
                                action: '{{ route('diaries.destroy', $diary) }}',
                                message: 'この日記を削除します。よろしいですか？'
                            })"
                            title="削除">
                            <x-icons.trash size="size-4" class="text-brand-dark" /> {{-- 削除アイコン --}}
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
            <section class="bg-slate-100 dark:bg-slate-300 dark:text-gray-800 py-6 no-print">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{-- コメント部分 --}}
                    <h3 class="text-lg font-semibold my-2 dark:text-gray-800">⭐️コメント({{ $diary->comments_count }})</h3>

                    {{-- コメント入力ボタン --}}
                    <x-primary-button type="button" class="mb-2" x-data x-on:click="window.dispatchEvent(new CustomEvent('open-modal', { detail: '{{ 'commentModal-'.$diary->id }}' }))">コメントする</x-primary-button>

                    {{-- コメントモーダル本体 --}}
                    <x-comment-modal :diary="$diary" :name="'commentModal-'.$diary->id" maxWidth="md" />

                    {{-- コメント一覧 --}}
                    <x-comments :diary="$diary" />

                </div>
            </section>
            @endif

            {{-- 日記、コメント削除確認モーダル --}}
            <x-confirm-modal name="confirm-delete" title="確認" message="本当に削除しますか？" maxWidth="md" />
        </div>
    </div>
</x-app-layout>