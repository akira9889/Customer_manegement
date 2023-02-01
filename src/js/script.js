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

  const editBtn = $('.edit-btn');

  const setInput = () => {
    $('.customer-form').wrapInner('<form method="post"></form>')
    var bithdayYear = $('#birthday').text().split('年')[0]
    var bithdayMonth = $('#birthday').text().split('年')[1].split('月')[0]
    var bithdayDate = $('#birthday').text().split('年')[1].split('月')[1].split('日')[0]

    $('.input').map(function () {
      var value = $(this).text();
      var name = $(this).data('name');
      var type = $(this).data('type');
      if ($(this).attr('id') === 'birthday') {
        $(this).html(`<div><input id="birthday_year" type="text" name="birthday_year" value="${bithdayYear}"><span>年</span></div>
                        <div><input id="birthday_month" type="text" name="birthday_month" value="${bithdayMonth}"><span>月</span></div>
                        <div><input id="birthday_date" type="text" name="birthday_date" value="${bithdayDate}"><span>日</span></div>
                      `);
      } else {

        if (type === 'textarea') {
          $(this).html(`<textarea name="information" style="min-height:100px;resize: vertical;">${value}</textarea>`);
        } else {
          $(this).html('<input>');
          $(this).children('input').val(value).attr({ 'name': name, 'type': type });
        }

      }
    });
    editBtn.children('button').text('保存').attr({
      'type': 'submit'
    })
  }

  editBtn.on('click load', () => {
    if (editBtn.children('button').attr('type') == 'button') {
      setInput();
      return false;
    }
  });
});
