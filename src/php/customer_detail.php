<?php

require_once(__DIR__ . '/Class/RegisterCustomer.php');
require_once(__DIR__ . '/Class/Customer.php');
require_once(__DIR__ . '/functions.php');
// id取得
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$customer = new Customer($id);
$customer_data = $customer->fetchCustomerData();

$shop_id = $customer_data['shop_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $last_name = filter_input(INPUT_POST, 'last_name');
  $first_name = filter_input(INPUT_POST, 'first_name');
  $last_kana = filter_input(INPUT_POST, 'last_kana');
  $first_kana = filter_input(INPUT_POST, 'first_kana');
  $email = filter_input(INPUT_POST, 'email');
  $birthday_year = filter_input(INPUT_POST, 'birthday_year');
  $birthday_month = filter_input(INPUT_POST, 'birthday_month');
  $birthday_date = filter_input(INPUT_POST, 'birthday_date');
  $tel = filter_input(INPUT_POST, 'tel');
  $gender = filter_input(INPUT_POST, 'gender');
  $information = filter_input(INPUT_POST, 'information');

  $new_customer = new RegisterCustomer($last_name, $first_name, $last_kana, $first_kana, $email, $birthday_year, $birthday_month, $birthday_date, $tel, $gender, $information);

  $new_customer->registerCustomer();

  $php_array = $new_customer->err;
  $json_array = json_encode($php_array);

  if ($new_customer->registered_state) {
    redirect('customer_detail.php?id=' . $id);
  }
}

$visit_history_data = $customer->fetchCustomerHistoriesData();

?>
<!doctype html>
<html lang="ja">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- fontawesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

  <!-- Original CSS -->
  <link href="/css/style.css" rel="stylesheet" type="text/css">

  <title>顧客情報一覧</title>
</head>

<body class="customer_detail">
  <header class="header">
    <div class="header-inner">
      <div class="header-content">
        <h1 class="header-logo">Sample shop</h1>
        <nav id="header-nav" class="header-nav">
          <ul id="header-list" class="header-list">
            <li class="header-item">
              <a class="header-item-link" href="/logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
            </li>
        </nav>
      </div>
    </div>
  </header>

  <div class="content">
    <div class="sidebar">
      <ul class="sidebar-list">
        <li class="sidebar-item">
          <a href="customer_list.php?shop_id=<?= $shop_id ?>" class="sidebar-link">顧客情報一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="visit-history.php?shop_id=<?= $shop_id ?>" class="sidebar-link">来店履歴一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="reserve_list.php?shop_id=<?= $shop_id ?>" class="sidebar-link">予約一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="register_user.php?shop_id=<?= $shop_id ?>" class="sidebar-link">設定</a>
        </li>
      </ul>
    </div>

    <div class="main-content">
      <div class="adding-btn">
        <button type="button" class="modal-open">来店履歴追加<span>＋</span></button>
      </div>
      <div class="main-inner customer-form">
        <h2 class="main-title">お客様情報</h2>

        <div class="customer-main-detail">
          <div class="customer-name">
            <div class="customer-last-name">
              <ruby>
                <rt class="input" data-name="last_kana" data-type="text"><?= $customer_data['last_kana'] ?></rt>
                <rb class="input" data-name="last_name" data-type="text"><?= $customer_data['last_name'] ?></rb>
              </ruby>
            </div>
            <div class=" customer-first-name">
              <ruby>
                <rt class="input" data-name="first_kana" data-type="text"><?= $customer_data['first_kana'] ?></rt>
                <rb class="input" data-name="first_name" data-type="text"><?= $customer_data['first_name'] ?></rb>
              </ruby>
            </div>
          </div>
          <div class="gender"><span><?= $customer_data['gender'] ?></span><span><?php echo Customer::fetchAge($customer_data['birthday']); ?>歳</span></div>
        </div>

        <div class="customer-sub-detail">
          <dl>
            <dt>生年月日</dt>
            <dd id="birthday" class="input"><?php echo date('Y年m月d日', strtotime($customer_data['birthday'])) ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>メールアドレス</dt>
            <dd class="input" data-name="email" data-type="email"><?= $customer_data['email'] ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>電話番号</dt>
            <dd class="input" data-name="tel" data-type="tel"><?= $customer_data['tel'] ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>メモ</dt>
            <dd class="input" data-name="information" data-type="textarea"><?= $customer_data['information'] ?></dd>
          </dl>
        </div>
        <div class="edit-btn">
          <button type="button">編集</button>
        </div>
      </div>

      <div class="table-wrap">
        <p>（最新10件）</p>
        <table class="customer-table customer-detail-table">
          <thead>
            <tr>
              <th>来店日</th>
              <th>総額</th>
              <th>メモ</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($visit_history_data as $data) : ?>
              <tr>
                <th><?= $data['date'] ?></th>
                <td><?= number_format($data['price']) ?>円</td>
                <td class="memo"><?= $data['memo'] ?></td>
                <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal-container">
    <div class="modal-body">
      <div class="modal-close">×</div>
      <form class="modal-content">
        <div class="modal-date">2022年10月7日</div>
        <div class="modal-price">
          <label>総額</label>
          <input type="text">
          <span>円</span>
        </div>
        <div class="modal-memo">
          <label for="memo">メモ</label>
          <textarea name="memo" rows="10"></textarea>
        </div>
        <div class="modal-btn">
          <button type="submit">変更</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="/js/script.js"></script>
  <script>
    $(document).on('focusout', '.customer-form input', function() {
      $.ajax({
        url: 'ajax_input_change.php',
        type: "POST",
        data: {
          first_name: $('input[name=first_name]').val(),
          last_name: $('input[name=last_name]').val(),
          first_kana: $('input[name=first_kana]').val(),
          last_kana: $('input[name=last_kana]').val(),
          email: $('input[name=email]').val(),
          birthday_year: $('input[name=birthday_year]').val(),
          birthday_month: $('input[name=birthday_month]').val(),
          birthday_date: $('input[name=birthday_date]').val(),
          tel: $('input[name=tel]').val()
        },
        dataType: "json"
      }).done(function(err) {
        Object.keys(err).forEach(key => {
          //バリデーションエラー時
          if (err[key]) {
            if (key === 'name' || key === 'kana') {
              $('.customer-main-detail').prepend(`<p class="invalid" data-err="${key}">${err[key]}</p>`)
            } else if (key === 'birthday') {
              $('#birthday').before(`<p class="invalid">${err[key]}</p>`)
            } else {
              let input = $('input[name=' + key + ']');
              input.parent(`dd[data-name=${key}]`).before(`<p class="invalid">${err[key]}</p>`);
            }
          }
          //バリデーションエラーがない時
          else {
            if (key === 'name' || key === 'kana') {
              $('.customer-main-detail').find(`p[data-err="${key}"]`).remove();
            } else if (key === 'birthday') {
              $('#birthday').prev('p').remove()
            } else {
              let input = $('input[name=' + key + ']');
              input.parent(`dd[data-name=${key}]`).prev('p').remove()
            }
          }
        });
      }).fail(function() {
        alert("error");
      });
    });
  </script>

</body>

</html>
