@props([
'name' => 'alert',
'title' => 'お知らせ',
'message' => '',
'okText' => 'OK',
'maxWidth' => 'sm',
'variant' => 'default',
])

@php
$headingClass = $variant === 'danger'
? 'text-red-600 dark:text-red-400'
: 'text-gray-900 dark:text-gray-200';
$textClass = $variant === 'danger'
? 'text-red-700 dark:text-red-300'
: 'text-gray-700 dark:text-gray-300';
$btnClass = $variant === 'danger'
? 'bg-red-600 hover:bg-red-700'
: 'bg-brand hover:bg-brand-dark';
@endphp

<div
    x-data="{ ttl: @js($title), msg: @js($message) }"
    x-on:alert-populate.window="
        const d = $event.detail || {};
        if(d.name === '{{ $name }}') {
            ttl = d.title ?? @js($title);
            msg = d.message ?? @js($message);
            window.dispatchEvent(new CustomEvent('open-modal', { detail: '{{ $name }}' }));
        }
    "
>
    <x-modal name="{{ $name }}" :show="false" :maxWidth="$maxWidth" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold {{ $headingClass }}" x-text="ttl"></h2>
            <div class="mt-3 text-sm whitespace-pre-line {{ $textClass }}" x-text="msg"></div>
            <div class="mt-6 flex justify-end">
                <button
                    x-ref="ok"
                    type="button"
                    class="inline-flex items-center px-4 py-2 rounded-xl text-gray-900 font-semibold shadow focus:outline-none focus:ring-2 focus:ring-amber-500 {{ $btnClass }}"
                    x-init="$nextTick(() => $refs.ok?.focus())"
                    x-on:click="$dispatch('close')">
                    {{ $okText }}
                </button>
            </div>
        </div>
    </x-modal>
</div>