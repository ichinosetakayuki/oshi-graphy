<x-app-layout>
    <x-slot name="title">Oshi Graphy | プロフィール編集</x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-semibold">プロフィール編集</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="post" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')
            <div x-data="{
                    fileName : '',
                    del: {{ old('delete_icon') ? 'true' : 'false' }},
                    previewSrc: '{{ $user->icon_url }}',
                    _objUrl: null,
                    setPreview(file) {
                        if(this._objUrl) { URL.revokeObjectURL(file); this._objUrl = null; }
                        if(file) {
                            this._objUrl = URL.createObjectURL(file);
                            this.previewSrc = this._objUrl;
                            this.fileName = file.name;
                            } else {
                            this.previewSrc = '{{ $user->icon_url }}';
                            this.fileName = '';
                        }
                    }
                }"
                x-init="$watch('del', v => { if(v) { $refs.icon.value = ''; setPreview(null); } });" class="space-y-2">
                <!-- ↑delを監視：削除チェック時はプレビューを保存済みに戻し、選択状態も空に -->
                <div class="flex items-center gap-4">
                    <!-- プレビューはpreviewSrcを表示 -->
                    <img :src="previewSrc" alt="アイコン" class="w-24 h-24 rounded-full object-cover border shadow" :class="del ? 'opacity-40' : ''">
                    <!-- 実ファイル入力（見た目は隠す） -->
                    <input type="file" name="icon" id="icon" x-ref="icon" accept="image/*" class="hidden" x-on:change="const f = $event.target.files?.[0] ?? null; setPreview(f);" :disabled="del">
                    <!-- ラベルをボタン風にしてfileを開く -->
                    <label for="icon" class="inline-flex items-center px-3 py-2 rounded-lg border shadow-sm bg-brand-light hover:bg-gray-50 cursor-pointer" :class="del ? 'pointer-events-none opacity-50' : ''">画像を選択</label>
                    <span class="text-base text-gray-500" x-text="fileName ? fileName : '未選択' "></span>
                </div>
                @error('icon')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror

                <!-- 削除チェックで保存時に削除 -->
                <label class="inline-flex items-center gap-2 select-none ml-28">
                    <input type="checkbox" name="delete_icon" value="1" x-model="del">
                    <span class="text-base">この画像を削除する</span>
                </label>
            </div>

            <div>
                <label for="profile" class="block text-base font-medium mb-1">自己紹介</label>
                <x-textarea name="profile" id="profile" rows="5" placeholder="自己紹介を入力してください。" class="w-full rounded border p-2">{{ old('profile', $user->profile) }}</x-textarea>
                @error('profile')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-sm text-gray-500">
                公開日記数：{{ $user->public_diaries_count }}
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <!-- <x-secondary-button x-on:click="$dispatch('close')">閉じる</x-secondary-button> -->
                <x-primary-button>保存</x-primary-button>
            </div>
        </form>

    </div>

</x-app-layout>