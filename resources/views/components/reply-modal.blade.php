@props([])

@php
$modalName = $name ?: 'reply-modal';
@endphp

{{-- 返信投稿モーダル本体 --}}
<x-modal name="{{ $modalName }}" :maxWidth="$maxWidth" focusable>
    <div class="p-6"
    x-data="{
        showErrors: @js($errors->reply->has($errorField)),
        parentId: @js(old('parent_id'))
        }"
        @open-reply.window="
            parentId = $event.detail.parentId;
            showErrors = false;
            $dispatch('open-modal', '{{  $modalName }}');
        "
    >
        <div class="flex items-start justify-between mb-3">
            <h2 class="text-lg font-semibold dark:text-gray-200">返信を書く</h2>
            <button type="button" class="text-xl leading-none px-2" @click="showErrors = false; $dispatch('close-modal', '{{ $modalName }}')" aria-label="閉じる">&times;</button>
        </div>
        <form method="post" action="{{ route('comments.reply', $diary) }}">
            @csrf
            <input type="hidden" name="parent_id" :value="parentId">
            <x-textarea name="body" placeholder="返信を書く・・・" />
            <div x-show="showErrors" x-transition>
                <x-input-error :messages="$errors->reply->get('body')" class="mt-1" />
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <x-secondary-button type="button" @click="showErrors = false; $dispatch('close-modal', '{{ $modalName }}')">キャンセル</x-secondary-button>
                <x-primary-button>返信</x-primary-button>
            </div>
        </form>
    </div>
</x-modal>

{{-- バリデーションエラーがあればモーダルを自動で再オープン --}}
@if($errors->reply->has($errorField))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: '{{ $modalName }}'
}));
});
</script>
@endif