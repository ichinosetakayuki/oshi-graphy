@props([
'name' => 'images',
'id' => 'images',
'multiple' => 'true',
'previewId' => 'preview'
])

{{-- 写真の登録 --}}
<div class="flex flex-col gap-2 mt-3">
    <div class="flex flex-col md:flex-row gap-2 mt-3">
        <x-form-label for="{{ $id }}" value="写真" class="w-28" />
        <input
            type="file"
            name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            id="{{ $id }}"
            accept="image/*"
            {{ $multiple ? 'multiple' : '' }}
            class="js-image-input"
            data-preview="#{{ $previewId }}">
    </div>
    {{-- 配列自体のエラー --}}
    <x-input-error :messages="$errors->get($name)" />
    {{-- 各画像ファイルの個別エラー --}}
    <x-input-error :messages="$errors->get($name.'*')" />
    
    {{-- 写真のプレビュー --}}
    <div id="{{ $previewId }}" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2"></div>
</div>