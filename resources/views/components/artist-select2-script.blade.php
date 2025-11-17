@pushOnce('scripts')
<script>
    $(function() {
        const $artistSelects = $('.js-artist-select');
        if (!$artistSelects.length) return;

        $artistSelects.each(function() {
            const $sel = $(this);
            // 二重初期化防止
            if ($sel.data('select2-initialized')) return;
            $sel.data('select2-initialized', true);

            const searchUrl = $sel.data('search-url');
            if (!searchUrl) return;

            const placeholder = $sel.data('placeholder') || 'アーティストを検索...';
            const minInputLength = parseInt($sel.data('min-input-length') || 1, 10);
            const artistNameTarget = $sel.data('artist-name-target');
            const oldId = $sel.data('old-id');
            const oldName = $sel.data('old-name');

            $sel.select2({
                width: '100%',
                placeholder: placeholder,
                allowClear: true,
                ajax: {
                    url: searchUrl,
                    dataType: 'json',
                    delay: 200,
                    data: function(params) {
                        return {
                            q: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        const items = data.items || data.data || [];
                        return {
                            results: items.map(function(it) {
                                return {
                                    id: it.id,
                                    text: it.name
                                };
                            }),
                        };
                    },
                },
                minimumInputLength: minInputLength,
                language: {
                    inputTooShort: () => '1文字以上入力してください',
                    searching: () => '検索中...',
                    noResults: () => '該当するアーティストが見つかりません'
                },
                containerCssClass: 'og-select2-tall',
                selectionCssClass: 'og-select2-tall',
            });

            // old()からの復元（create/edit/index共通）
            if (oldId && oldName) {
                // すでに<option>があるか確認、ある場合は追加しない
                const exists = $sel.find('option[value="' + oldId + '"]').length > 0;
                if (!exists) {
                    // new Option(text, value, defaultSelected, selected)
                    const opt = new Option(oldName, oldId, true, true);
                    $sel.append(opt);
                }
                $sel.val(oldId).trigger('change');
            }

            // 選択変更時に hidden の artist_name へラベル名を入れる（バリデーション戻りで使える）
            if (artistNameTarget) {
                const $hidden = $(artistNameTarget);
                if ($hidden.length) {
                    $sel.on('select2:select', (e) => {
                        $hidden.val(e.params.data.text || '');
                    });
                    $sel.on('select2:clear', () => {
                        $hidden.val('');
                    });
                }
            }
        });
    });
</script>
@endpushOnce