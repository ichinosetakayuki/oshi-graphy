@props([
'user',
'initialBlocking' => false,
])

@php
$blockUrl = route('users.block.store', $user);
$unblockUrl = route('users.block.destroy', $user);
@endphp

<div
    x-data="{
        blocking: @js((bool)$initialBlocking),
        busy: false,
        async toggle() {
            if(this.busy) return;
            this.busy = true;

            const url = this.blocking ? @js($unblockUrl) : @js($blockUrl);
            const method = this.blocking ? 'DELETE' : 'POST';

            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });
                if(!res.ok) throw new Error('Request failed');
                const json = await res.json();
                this.blocking = !!json.blocking;
                $dispatch('toast', {
                    message: json.message,
                    type: json.status_type
                });
            } catch(e) {
                console.error(e);
                $dispatch('toast', {
                    message:'ネットワークエラーです。時間をおいて再試行してください。',
                    type: 'error'
                });
            } finally {
                this.busy = false;
            }
        }
    }"
    x-show="open" @click="open=false" class="absolute top-0 right-6 bg-gray-50 shadow p-4 w-48">
    <button type="button" :disabled="busy" @click.stop="toggle" @click="open=false">
        <template x-if="!blocking"><span class="text-red-400 font-semibold">ブロックする</span></template>
        <template x-if="blocking"><span class="text-blue-400 font-semibold">ブロック解除</span></template>
    </button>
</div>