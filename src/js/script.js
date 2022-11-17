$(() => {
  // 変数に要素を入れる
  const open = $('.modal-open');
  const close = $('.modal-close');
  const container = $('.modal-container');

  // 開くボタンをクリックしたらモーダルを表示する
  open.on('click', () => {
    container.addClass('active');
    return false;
  });

  // 閉じるボタンをクリックしたらモーダルを閉じる
  close.on('click', () => {
    container.removeClass('active');
  });

  // モーダルの外側をクリックしたらモーダルを閉じる
  $(document).on('click', (e) => {
    if (!$(e.target).closest('.modal-body').length) {
      container.removeClass('active');
    }
  });
});
