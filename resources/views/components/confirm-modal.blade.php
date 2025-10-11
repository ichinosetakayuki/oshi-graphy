@props([
'confirmText' => '削除する',
'cancelText' => 'キャンセル',
])


<div
    x-data="{ action: '', ttl: @js($title), msg: @js($message) }"
    x-on:confirm-delete.window="
        const d = $event.detail || {};
        if(d.name === '{{ $name }}') {
            action = d.action || '';
            ttl = d.title ?? @js($title);
            msg = d.message ?? @js($message); 
            window.dispatchEvent(new CustomEvent('open-modal', {detail: '{{ $name }}' }));
        }
    ">

    <x-modal name="{{ $name }}" :show="false" :maxWidth="$maxWidth" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-2" x-text="ttl"></h2>
            <p class="text-sm text-gray-600 dark:text-gray-300" x-text="msg"></p>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ $cancelText }}</x-secondary-button>
                {{-- AI会話リセットの時：イベント通知だけ --}}
                <template x-if="action === 'reset-ai-history'">
                    <x-danger-button
                        x-on:click="
                        window.dispatchEvent(new CustomEvent('confirmed', { detail: { action } }));
                        $dispatch('close');
                    ">
                        {{ $confirmText }}
                    </x-danger-button>
                </template>
                {{-- それ以外（フォーム送信：DELETE想定） --}}
                <template x-if="action !== 'reset-ai-history'">
                    <form method="post" x-bind:action="action">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>{{ $confirmText }}</x-danger-button>
                    </form>
                </template>
            </div>
        </div>
    </x-modal>
</div>