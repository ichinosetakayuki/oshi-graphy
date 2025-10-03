@props([
'message' => '',
'type' => 'info',
'timeout' => 5000,
'position' => 'top-center',
])

@php
$pos = match($position) {
'top-center' => 'top-4 left-1/2 -translate-x-1/2',
'top-left' => 'top-4 left-4',
'bottom-right' => 'bottom-4 right-4',
'bottom-left' => 'bottom-4 left-4',
default => 'top-4 right-4',
};
@endphp

<div
    x-data="{
        show: @js((bool)$message),
        msg: @js($message),
        type: @js($type),
        timeout: {{ (int)$timeout }},
        timer: null,
        open(payload) {
            this.msg = payload?.message ?? '';
            this.type = payload?.type ?? 'info';
            this.show = true;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.show = false, this.timeout);
        }
    }"
    x-init="
        if(show) { timer = setTimeout(() => show = false, timeout) }
        window.addEventListener('toast', e => open(e.detail || {}))
    "
    class="fixed z-50 {{ $pos }}"
    x-cloak>
    <template x-if="show">
        <div x-transition.opacity.duration.400ms
            class="w-80 max-w-[90vw] rounded-xl shadow-lg border bg-gray-100 overflow-hidden"
            :class="{
                'border-blue-200 bg-blue-100': type==='info',
                'border-green-200 bg-green-100': type==='success',
                'border-yellow-200 bg-yellow-100': type==='warning',
                'border-red-200 bg-red-100': type==='error'
            }"
            role="status"
            aria-live="polite">
            <div class="flex items-center gap-3 p-3">
                <span class="inline-block w-2 h-2 rounded-full self-center"
                    :class="{
                            'bg-blue-500': type==='info',
                            'bg-green-500': type==='success',
                            'bg-yellow-500': type==='warning',
                            'bg-red-500': type==='error'
                        }"></span>
                <div class="text-sm text-gray-800 leading-snug" x-text="msg"></div>
                <button type="button" class="ml-auto text-gray-400 hover:text-gray-600" @click="show=false" aria-label="閉じる">×</button>
            </div>
        </div>
    </template>
</div>