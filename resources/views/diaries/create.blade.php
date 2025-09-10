<x-app-layout>
    <x-slot name="title">Oshi Graphy | 日記作成</x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-semibold">日記作成</h2>
    </x-slot>


    <div class="max-w-3xl mx-auto">

        <form method="post" action="{{ route('diaries.store') }}" enctype="multipart/form-data" class="flex-col flex-wrap items-center gap-3 mb-5 w-full">
            @csrf
            <div class="flex flex-col gap-3 md:flex-row md:gap-6">
                {{-- 日付 --}}
                <div class="flex flex-col md:flex-row gap-2">
                    <x-input-label for="happened_on" value="日付" class="w-28" />
                    <x-text-input type="date" name="happened_on" id="happened_on" :value="old('happened_on')" />
                    <x-input-error :messages="$errors->get('happened_on')" />
                </div>
                {{-- アーティスト --}}
                <div class="flex flex-col md:flex-row gap-2">
                    <x-input-label for="artist_id" value="アーティスト" class="w-40" />
                    <select name="artist_id" id="artist_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        {{-- old() があれば初期optionを1つだけ指す (JSで選択状態に) --}}
                        @if(old('artist_id') && old('artist_name'))
                        <option value="{{ old('artist_id') }}" selected>{{ old('artist_name') }}</option>
                        @endif
                    </select>
                    <x-input-error :messages="$errors->get('artist_id')" class="mt-2" />
                </div>

            </div>

            {{-- 本文 --}}
            <div class="flex flex-col md:flex-row gap-2 mt-3">
                <x-input-label for="body" value="本文" class="w-28" />
                <x-textarea name="body" id="body" rows="6" />
                <x-input-error :messages="$errors->get('body')" />
            </div>

            {{-- AIアシスト下書きゾーン --}}
            <div class="flex flex-col md:flex-row gap-2 mt-3">
                <x-input-label for=" ai_assist" value="AIアシスト" class="w-28" />
                <x-textarea id="ai_assist" name="ai_assist" rows="6" />
                <x-input-error :messages="$errors->get('ai_assist')" />
            </div>

            {{-- 写真の登録 --}}
            <div class="flex flex-col gap-2 mt-3">
                <div class="flex flex-col md:flex-row gap-2 mt-3">
                    <x-input-label for="images" value="写真" class="w-28" />
                    <input type="file" name="images[]" accept="image/*" id="images" multiple>
                </div>
                <x-input-error :messages="$errors->get('images')" />
                <div id="preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2"></div>
            </div>

            {{-- 公開設定 --}}
            <div class="mb-6 mt-3">
                <input type="hidden" name="is_public" value="0">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_public" value="1" class="rounded border-gray-300 text-indigo-600" @checked(old('is_public'))>
                    <span class="ml-2">公開する</span>
                </label>
                <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
            </div>

            <div class="flex items-center">
                <x-primary-button type="submit">保存</x-primary-button>
            </div>

            {{-- old('artist_name')を保存するための隠しフィールド（再描画用） --}}
            <input type="hidden" name="artist_name" id="artist_name_old" value="{{ old('artist_name') }}">

        </form>

    </div>


    {{-- Select2のCSS/JSをこのページだけに読み込む --}}
    @push('vendor-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    @endpush



    @push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- select2でアーティストを呼び出すためのscript --}}
    <script>
        $(function() {
            const ARTIST_SEARCH_URL = @json(route('artists.search'));

            const $sel = $("#artist_id").select2({
                width: '100%',
                placeholder: 'アーティストを検索...',
                allowClear: true,
                ajax: {
                    url: ARTIST_SEARCH_URL,
                    dataType: 'json',
                    delay: 200,
                    data: params => ({
                        q: params.term || ''
                    }),
                    processResults: data => ({
                        results: (data.items || []).map(it => ({
                            id: it.id,
                            text: it.name
                        })),
                    }),
                },
                minimumInputLength: 1,
                language: {
                    inputTooShort: () => '1文字以上入力してください',
                    searching: () => '検索中...',
                    noResults: () => '該当するアーティストが見つかりません'
                },
                containerCssClass: 'og-select2-tall',
                selectionCssClass: 'og-select2-tall',
            });
            const oldId = @json(old('artist_id'));
            const oldName = @json(old('artist_name'));
            if (oldId && oldName) {
                const opt = new Option(oldName, oldId, true, true);
                $sel.append(opt).trigger('change');
            }

            // 選択変更時に hidden の artist_name へラベル名を入れる（バリデーション戻りで使える）
            $sel.on('select2:select', (e) => {
                $("#artist_name_old").val(e.params.data.text || '');
            });
            $sel.on('select2:clear', () => {
                $("#artist_name_old").val('');
            });
        });
    </script>
    {{-- 写真をプレビュー表示するscript --}}
    <script>
        $(function() {
            const $input = $("#images");
            const $preview = $("#preview");

            function renderPreviews(files) {
                $preview.empty(); // 以前のプレビューをクリア

                Array.from(files).forEach(function(file) {
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const $img = $('<img>', {
                            src: e.target.result,
                            alt: file.name,
                            class: 'w-full h-24 object-cover rounded border'
                        });
                        $preview.append($img);
                    }
                    reader.readAsDataURL(file);
                });
            }
            // $input.on('click', function()  {
            //     this.value = null;
            // })

            $input.on('change', function() {
                renderPreviews(this.files);
            });
        });
    </script>
    @endpush
</x-app-layout>