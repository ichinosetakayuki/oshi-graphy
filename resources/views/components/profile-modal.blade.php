{{-- <x-modal name="{{ $name }}" maxWidth="lg">
    <div class="p-6"
        x-data
        x-init="
        @if($editable && ($errors->profile->any()))
        window.dispatchEvent(new CustomEvent('open-modal', {detail: '{{ $name}}' }));
        @endif
        ">
        <h2 class="text-lg font-semibold mb-4 border-l-8 border-brand pl-3">
            {{ $editable ? 'プロフィール編集' : " $user->name さんのプロフィール" }}
        </h2>
        <!-- @if($editable && session('status') === 'profile-updated')
        <p class="text-green-600 text-sm mb-3">プロフィールを更新しました。</p>
        @endif -->

        @if($editable)
        <form method="post" action="{{ route('profile.update.info') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
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
                    <img :src="previewSrc" alt="アイコン" class="w-16 h-16 rounded-full object-cover border" :class="del ? 'opacity-40' : ''">
                    <!-- 実ファイル入力（見た目は隠す） -->
                    <input type="file" name="icon" id="icon" x-ref="icon" accept="image/*" class="hidden" x-on:change="const f = $event.target.files?.[0] ?? null; setPreview(f);" :disabled="del">
                    <!-- ラベルをボタン風にしてfileを開く -->
                    <label for="icon" class="inline-flex items-center px-3 py-2 rounded-lg border shadow-sm hover:bg-gray-50 cursor-pointer" :class="del ? 'pointer-events-none opacity-50' : ''">画像を選択</label>
                    <span class="text-sm text-gray-500" x-text="fileName ? fileName : '未選択' "></span>
                </div>
                @error('icon')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror

                <!-- 削除チェックで保存時に削除 -->
                <label class="inline-flex items-center gap-2 mt-1 select-none ml-20">
                    <input type="checkbox" name="delete_icon" value="1" x-model="del">
                    <span class="text-sm">この画像を削除する</span>
                </label>
            </div>

            <div>
                <label for="profile" class="block text-sm font-medium mb-1">プロフィール文</label>
                <textarea name="profile" id="profile" rows="5" placeholder="プロフィール文を入力してください。" class="w-full rounded border p-2">{{ old('profile', $user->profile) }}</textarea>
                @error('profile')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-sm text-gray-500">
                公開日記数：{{ $user->public_diaries_count }}
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <x-secondary-button x-on:click="$dispatch('close')">閉じる</x-secondary-button>
                <x-primary-button>保存</x-primary-button>
            </div>
        </form>
        @else
        {{-- 閲覧専用 --}}
        <div class="space-y-4">
            <div class="flex items-center gap-4">
                <img src="{{ $user->icon_url }}" alt="アイコン" class="w-16 h-16 rounded-full object-cover border">
                <div class="text-lg font-medium">{{ $user->name }}</div>
            </div>
            <div class="bg-brand-light min-h-16 rounded-lg p-2 whitespace-pre-line">{{ $user->profile ?: '(未設定)'}}</div>
            <div class="text-sm text-gray-500">
                公開日記数：{{ $user->public_diaries_count }}
            </div>
            <div class="flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">閉じる</x-secondary-button>
            </div>
        </div>
        @endif
    </div>
</x-modal> --}}