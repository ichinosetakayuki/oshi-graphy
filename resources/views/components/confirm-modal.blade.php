@props([
'confirmText' => '削除する',
'cancelText' => 'キャンセル',
])


<div
    x-data="{ action: '', msg: @js($message) }"
    x-on:confirm-delete.window="
        const d = $event.detail || {};
        if(d.name === '{{ $name }}') {
            action = d.action || '';
            msg = d.message ?? @js($message); 
            window.dispatchEvent(new CustomEvent('open-modal', {detail: '{{ $name }}' }));
        }
    ">

    <x-modal name="{{ $name }}" :show="false" :maxWidth="$maxWidth" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-2">{{ $title }}</h2>
            <p class="text-sm text-gray-600" x-text="msg"></p>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ $cancelText }}</x-secondary-button>
                <form method="post" x-bind:action="action">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>{{ $confirmText }}</x-danger-button>
                </form>
            </div>
        </div>
    </x-modal>
</div>
