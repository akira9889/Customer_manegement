$(() => {
  $('.has-sub-menu').hover(
    function () {
      $(this).addClass('active')
      $(this).children('.sub-menu').slideDown(100)
    },
    function () {
      $(this).removeClass('active')
      $(this).children('.sub-menu').slideUp(100)

    }
  )

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

  const setFormInput = () => {
    $('.customer-form').wrapInner('<form method="post"></form>')
    var birthdayYear = $('#birthday').text().split('年')[0]
    var birthdayMonth = $('#birthday').text().split('年')[1].split('月')[0]
    var birthdayDate = $('#birthday').text().split('年')[1].split('月')[1].split('日')[0]

    $('.input').map(function () {
      var value = $(this).text();
      var name = $(this).data('name');
      var type = $(this).data('type');
      if ($(this).attr('id') === 'birthday') {
        $(this).html(`<div><input id="birthday_year" type="text" name="birthday_year" value="${birthdayYear}"><span>年</span></div>
                        <div><input id="birthday_month" type="text" name="birthday_month" value="${birthdayMonth}"><span>月</span></div>
                        <div><input id="birthday_date" type="text" name="birthday_date" value="${birthdayDate}"><span>日</span></div>
                      `);
      } else {
        if (type === 'textarea') {
          $(this).html(`<textarea name="information" style="min-height:100px;resize: vertical;">${value}</textarea>`);
        } else {
          $(this).html('<input>');
          $(this).children('input').val(value).attr({ 'name': name, 'type': type });
        }
      }
      $('.invalid').css('padding', '13px 0')
    });
    editBtn.children('input').val('保存').attr({
      'type': 'submit'
    })
  }

  editBtn.click(() => {
    if (editBtn.children('input').attr('type') === 'button') {
      setFormInput();
      return false;
    }
  })

});
