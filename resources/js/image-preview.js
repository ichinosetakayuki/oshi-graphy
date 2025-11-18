/**
 * 選択した画像ファイルのプレビューを表示するスクリプト
 */
$(function () {
  $('.js-image-input').on('change', function () {
    const input = this; // .js-image-inputのinputタグを変数に
    const $input = $(input); // inputをjQuery化

    const previewSelector = $input.data('preview'); // inputのdata-preview属性を取得
    const $preview = $(previewSelector); // それをjQuery化 プレビュー領域のdivタグを変数で保持

    $preview.empty(); // 以前のプレビューをクリア

    Array.from(input.files).forEach(function (file) {
      if (!file.type.startsWith('image/')) return; // 画像以外は無視

      // FileReader:WEB API ファイルを読み込む準備
      const reader = new FileReader();
      // ファイルの読み込みが成功したら実行される処理
      reader.onload = function (e) {
        // 画像タグを作る。jQueryの専用構文
        // 第2引数のオブジェクト{}にあるkey/valueを属性として自動的にセット
        const $img = $('<img>', {
          src: e.target.result,
          alt: file.name,
          class: 'w-full h-24 object-cover rounded border'
        });
        $preview.append($img);
      }
      // ファイルをBase64に変換開始する命令 読み込みが終わるとreader.onloadが実行
      reader.readAsDataURL(file);
    });

  });

});