<x-app-layout>
    <x-slot name="title">Oshi Graphy | 日記作成</x-slot>

    <x-slot name="header">
        <h2 class="text-lg sm:text-2xl font-semibold">📝 日記作成</h2>
    </x-slot>

    <div class="max-w-5xl w-full mx-auto px-4 py-4 sm:py-6">
        <div class=" max-w-3xl mx-auto border rounded-2xl p-3 md:p-4 lg:p-8 lg:shadow bg-white dark:bg-gray-800">

            <form method="post" action="{{ route('diaries.store') }}" id="diary-form" enctype="multipart/form-data" class="flex-col flex-wrap items-center gap-3 mb-5 w-full">
                @csrf
                <div class="flex flex-col gap-3 md:flex-row md:gap-6">
                    {{-- 日付 --}}
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col md:flex-row gap-2">
                            <x-form-label for="happened_on" value="日付" />
                            <x-text-input type="date" name="happened_on" id="happened_on" :value="old('happened_on')" />
                        </div>
                        <x-input-error :messages="$errors->get('happened_on')" class="md:text-center" />
                    </div>

                    {{-- アーティスト --}}
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col md:flex-row gap-2">
                            <x-form-label for="artist_id" value="アーティスト" width="w-32" class="shrink-0" />
                            <div class="w-full md:w-64">
                                <select
                                    name="artist_id"
                                    id="artist_id"
                                    class="js-artist-select focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    data-search-url="{{ route('artists.search') }}"
                                    data-placeholder="アーティストを選択..."
                                    data-min-input-length="1"
                                    data-artist-name-target="#artist_name_old"
                                    data-old-id="{{ old('artist_id') }}"
                                    data-old-name="{{ old('artist_name') }}">
                                    <option value="">-- アーティストを選択 --</option>
                                </select>
                            </div>
                        </div>
                        {{-- old('artist_name')を保存するための隠しフィールド（再描画用） --}}
                        <input type="hidden" id="artist_name_old" name="artist_name" value="{{ old('artist_name') }}">
                        <x-input-error :messages=" $errors->get('artist_id')" class="md:text-center" />
                    </div>


                </div>

                {{-- 本文 --}}
                <div class="flex flex-col gap-2 mt-3">
                    <div class="flex flex-col md:flex-row gap-2 mt-3">
                        <x-form-label for="body" class="shrink-0" value="本文" />
                        <x-textarea name="body" id="body" rows="6" />
                    </div>
                    <x-input-error :messages="$errors->get('body')" class="md:text-center" />
                </div>


                {{-- AIアシスト下書きゾーン --}}
                <div class="flex flex-col gap-2 mt-3">
                    <x-form-label value="AIアシスト" />
                    <div class="flex flex-col md:flex-row gap-2 mt-3">
                        <label for="ai_prompt" class="text-sm sm:text-right pl-2 sm:pr-2 w-28 shrink-0">AIへの相談</label>
                        <x-textarea id="ai_prompt" name="ai_prompt" rows="6" placeholder="文案作成に必要な情報（日時、場所、アーティスト、セトリ、感想など）を入力してください。" />
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="ai_send" class="px-3 py-2 rounded-md bg-brand text-black text-sm hover:bg-brand-dark">AIに相談</button>
                        <button type="button" id="ai_reset" class="px-3 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-sm hover:bg-gray-400">会話リセット</button>
                    </div>
                    <div class="flex flex-col md:flex-row gap-2 mt-3">
                        <div class="text-sm sm:text-right pl-2 sm:pr-2 w-28 shrink-0">AIの回答欄</div>
                        <div id="ai_answers" class="h-32 w-full overflow-y-auto rounded-md border border-gray-300 dark:border-gray-700 p-3 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm shadow-sm">
                            {{-- AI回答がここに --}}
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="ai_copy_latest" class="px-3 py-1 rounded-md bg-gray-200 dark:bg-gray-700 text-sm hover:bg-gray-400">本文にコピー</button>
                    </div>

                </div>

                {{-- 写真の登録 --}}
                <div class="flex flex-col gap-2 mt-3">
                    <div class="flex flex-col md:flex-row gap-2 mt-3">
                        <x-form-label for="images" value="写真" />
                        <input type="file" name="images[]" accept="image/*" id="images" multiple>
                    </div>
                    <x-input-error :messages="$errors->get('images')" />
                    {{-- 写真のプレビュー --}}
                    <div id="preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2"></div>
                </div>

                {{-- 公開設定 --}}
                <div class="mb-6 mt-3">
                    <input type="hidden" name="is_public" value="0">
                    <div class="flex gap-2">
                        <x-form-label>公開設定</x-form-label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_public" value="1" class="rounded border-gray-300 text-indigo-600" @checked(old('is_public'))>
                            <span class="ml-2 font-bold text-lg">公開する</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center gap-3">
                    <x-primary-button type="submit">保存</x-primary-button>
                    <x-secondary-button type="button" id="form-clear-btn">クリア</x-secondary-button>
                </div>

            </form>
        </div>
    </div>
    {{-- アーティスト検索のスクリプト読み込み --}}
    <x-artist-select2-script />
    {{-- AI会話リセット用削除確認モーダル --}}
    <x-confirm-modal name="confirm-delete" confirmText="リセットする" maxWidth="sm" />

    @push('scripts')
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

            $input.on('change', function() {
                renderPreviews(this.files);
            });
        });
    </script>
    {{-- アラート表示関数 --}}
    <script>
        /**
         * アラートに表示するメッセージ、タイトル、モーダルに渡す名前を入力し、
         * モーダル画面を開いて表示する関数。
         */
        window.showAlert = (message, title = 'お知らせ', name = 'alert') => {
            window.dispatchEvent(new CustomEvent('alert-populate', {
                detail: {
                    name,
                    title,
                    message
                }
            }));
        };
    </script>

    {{-- AIアシスト機能のscript --}}
    <script>
        const $prompt = $("#ai_prompt");
        const $answers = $("#ai_answers");
        const $send = $("#ai_send");
        const $reset = $("#ai_reset");
        const $copy = $("#ai_copy_latest");
        const $body = $("#body");

        function escapeHtml(str) {
            return $('<div>').text(str).html();
        }

        function appendAnswer(text) {
            const html = `
            <div class="bg-gray-100 dark:bg-gray-700 rounded-md mt-2 p-2 shadow-sm">
            <pre class="whitespace-pre-wrap break-words text-[13px]">${escapeHtml(text)}</pre>
            </div>`;
            $answers.append(html);
            $answers.scrollTop($answers[0].scrollHeight);
        }

        $send.on('click', function() {
            const text = $prompt.val().trim();
            if (!text) {
                showAlert('キーワードなどを入力してください', 'AIアシストエラー');
                return;
            }

            $send.prop('disabled', true).text('生成中…');

            $.ajax({
                    url: "{{ route('ai.diary.suggest') }}",
                    method: "POST",
                    data: {
                        prompt: text,
                        _token: "{{ csrf_token() }}"
                    }
                })
                .done(function(res) {
                    if (res.ok) {
                        appendAnswer(res.reply);
                        $prompt.val('');
                    } else {
                        showAlert(res?.message || '生成に失敗しました。', 'AIアシストエラー');
                    }
                })
                .fail(function(xhr) {
                    const msg = xhr.responseJSON?.message || '通信エラー';
                    showAlert(msg, 'AIアシストエラー');
                })
                .always(function() {
                    $send.prop('disabled', false).text('AIに相談');
                });
        });

        // AIとの会話リセット：ボタン押下時→確認モーダルを開く
        $reset.on('click', function() {
            window.dispatchEvent(new CustomEvent('confirm-delete', {
                detail: {
                    name: 'confirm-delete',
                    title: '会話履歴のリセット',
                    message: 'AIとの会話履歴をリセットしてよろしいですか？',
                    action: 'reset-ai-history'
                }
            }));
        });

        // AIとの会話リセット：モーダルでOKが押下時→実処理が走る
        window.addEventListener('confirmed', function(e) {
            if (e.detail.action === 'reset-ai-history') {
                $.post("{{ route('ai.diary.reset') }}", {
                        _token: "{{ csrf_token() }}"
                    })
                    .done(function() {
                        $answers.empty();
                    })
                    .fail(function() {
                        showAlert('会話履歴のリセットに失敗しました', 'AIアシストエラー');
                    });
            }
        });

        // AI回答を本文にコピーする処理
        $copy.on('click', function() {
            const $last = $answers.children('.bg-gray-100').last();
            if (!$last.length) {
                showAlert('まだAIの回答がありません', 'AIアシストエラー');
            }
            const text = $last.text().trim();
            $body.val(text).trigger('input');
            // inputイベントで値が変わったことを検知する。
        });
    </script>
    {{-- キャンセルボタン --}}
    <script>
        $("#form-clear-btn").on('click', function() {
            $("#happened_on, #body, #ai_prompt, #images").val('');
            $("#ai_answers, #preview").empty();
            $("input[type=checkbox]").prop('checked', false);
            $("#artist_id").val('').trigger('change');
        })
    </script>
    @endpush
</x-app-layout>