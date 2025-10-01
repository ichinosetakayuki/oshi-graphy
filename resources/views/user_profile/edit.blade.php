<x-app-layout>
    <x-slot name="title">Oshi Graphy | プロフィール編集</x-slot>

    <x-slot name="header">
        <h2 class="text-lg sm:text-2xl font-semibold">プロフィール編集</h2>
    </x-slot>

    <div class="max-w-5xl w-full mx-auto px-4 py-4 sm:py-6">
        <div class="max-w-3xl mx-auto border rounded-2xl px-4 sm:px-8 py-6 shadow bg-white">
            <form method="post" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <x-input-label for="name">名前</x-input-label>
                    <x-text-input name="name" id="name" class="mt-1 ml-4" :value="old('name', $user->name)" />
                    <x-input-error :messages="$errors->profile->get('name')" />
                </div>
                <div x-data="{
                    fileName : '',
                    del: {{ old('delete_icon') ? 'true' : 'false' }},
                    previewSrc: '{{ $user->icon_url }}',
                    _objUrl: null,
                    setPreview(file) {
                        if(this._objUrl) { URL.revokeObjectURL(this._objUrl); this._objUrl = null; }
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
                    <x-input-label>アイコン</x-input-label>
                    {{-- ↑delを監視：削除チェック時はプレビューを保存済みに戻し、選択状態も空に --}}
                    <div class="flex flex-col sm:flex-row items-center gap-4 sm:ml-10">
                        {{-- プレビューはpreviewSrcを表示 --}}
                        <img :src="previewSrc" alt="アイコン" class="w-24 h-24 rounded-full object-cover border shadow" :class="del ? 'opacity-40' : ''">
                        {{-- 実ファイル入力（見た目は隠す） --}}
                        <input type="file" name="icon" id="icon" x-ref="icon" accept="image/*" class="hidden" x-on:change="const f = $event.target.files?.[0] ?? null; setPreview(f);" :disabled="del">
                        {{-- ラベルをボタン風にしてfileを開く --}}
                        <label for="icon" class="inline-flex items-center px-3 py-2 rounded-lg border shadow-sm bg-brand-light hover:bg-gray-50 cursor-pointer" :class="del ? 'pointer-events-none opacity-50' : ''">画像を選択</label>
                        <span class="text-base text-gray-500" x-text="fileName ? fileName : '未選択' "></span>
                    </div>
                    <x-input-error :messages="$errors->profile->get('icon')" />

                    {{-- 削除チェックで保存時に削除 --}}
                    <div class="flex justify-center sm:block">
                        <label class="inline-flex items-center gap-2 select-none sm:ml-36">
                            <input type="checkbox" name="delete_icon" value="1" x-model="del">
                            <span class="text-base">この画像を削除する</span>
                        </label>
                    </div>

                </div>

                <div>
                    <x-input-label for="profile">自己紹介</x-input-label>
                    <x-textarea name="profile" id="profile" rows="5" placeholder="自己紹介を入力してください。" class="w-full rounded border p-2 ml-2 sm:ml-4">{{ old('profile', $user->profile) }}</x-textarea>
                    <x-input-error :messages="$errors->profile->get('profile')" />
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <x-secondary-button onclick="history.back()">前のページに戻る</x-secondary-button>
                    <x-primary-button>保存</x-primary-button>
                </div>
            </form>

        </div>
    </div>


</x-app-layout>