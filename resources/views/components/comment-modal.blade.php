@props([])

@php
$modalName = $name ?: ('commentModal-' . $diary->id);
@endphp

{{-- 起動ボタン（モーダルを開く） --}}
@if($showButton)
<x-primary-button type="button" class="mb-2" x-data x-on:click="window.dispatchEvent(new CustomEvent('open-modal', { detail: '{{ $modalName }}' }))">コメントする</x-primary-button>
@endif

{{-- コメント投稿モーダル本体 --}}
<x-modal name="{{ $modalName }}" :maxWidth="$maxWidth" focusable>
    <div class="p-6">
        <div class="flex items-start justify-between mb-3">
            <h2 class="text-lg font-semibold">コメントを書く</h2>
            <button type="button" class="text-xl leading-none px-2" x-on:click="window.dispatchEvent(new CustomEvent('close-modal', { detail: '{{ $modalName }}' }))" aria-label="閉じる">&times;</button>
        </div>
        <form method="post" action="{{ route('comments.store', $diary) }}">
            @csrf
            <x-textarea name="body" placeholder="コメントを書く・・・" />
            <x-input-error :messages="$errors->get('body')" class="mt-1" />
            <div class="mt-4 flex justify-end gap-2">
                <x-secondary-button type="button" x-on:click="window.dispatchEvent(new CustomEvent('close-modal', { detail: '{{ $modalName }}' }))">キャンセル</x-secondary-button>
                <x-primary-button>投稿</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>

{{-- バリデーションエラーがあればモーダルを自動で再オープン --}}
@if($errors->has($errorField))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: '{{ $modalName }}'
        }));
    });
</script>
@endif