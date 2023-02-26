<?php
require_once(__DIR__ . '/Class/RegisterCompany.php');
require_once(__DIR__ . '/Class/RegisterUser.php');
require_once(__DIR__ . '/Class/RegisterCustomer.php');
require_once(__DIR__ . '/Class/Customer.php');
require_once(__DIR__ . '/Class/VisitHistory.php');
require_once(__DIR__ . '/functions.php');

session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$customer = new Customer($id);
$customer_data = $customer->fetchCustomerData();

$shop_id = $customer_data['shop_id'];

$sql = "SELECT s.`company_id`, c.`name`, s.`area`
        FROM shops s
        INNER JOIN companies c
        ON s.`company_id` = c.`id`
        WHERE s.`id` = :shop_id
        LIMIT 1";

$options = [
  'shop_id' => $shop_id
];

$mysql = new ExecuteMySql($sql, $options);

$shop = $mysql->execute()[0] ?? null;

//ログインされていない場合はログイン画面へ
if (
  !(isset($_SESSION['USER']) && (isset($_SESSION['USER']['shop_id']) && $_SESSION['USER']['shop_id'] === $shop_id)) &&
  !(isset($_SESSION['USER']['admin_state']) && $_SESSION['USER']['admin_state'] === RegisterCompany::OWNER && isset($shop['company_id']) && $shop['company_id'] === $_SESSION['USER']['id'])
) {
  redirect('/shop_login.php?shop_id=' . $shop_id);
}

$modal_view_flug = FALSE;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['customer_information'])) {
    $update_customer_data = filter_input_array(INPUT_POST, [
      'last_name' => FILTER_DEFAULT,
      'first_name' => FILTER_DEFAULT,
      'last_kana' => FILTER_DEFAULT,
      'first_kana' => FILTER_DEFAULT,
      'gender' => FILTER_DEFAULT,
      'email' => FILTER_DEFAULT,
      'birthday_year' => FILTER_DEFAULT,
      'birthday_month' => FILTER_DEFAULT,
      'birthday_date' => FILTER_DEFAULT,
      'tel' => FILTER_DEFAULT,
      'information' => FILTER_DEFAULT,
      'shop_id' => FILTER_VALIDATE_INT
    ]);

    $update_customer = new RegisterCustomer($update_customer_data);

    $update_customer->registerCustomer();
  } elseif (isset($_POST['visit_history'])) {
    $history_data = filter_input_array(INPUT_POST, [
      'year' => FILTER_DEFAULT,
      'month' => FILTER_DEFAULT,
      'day' => FILTER_DEFAULT,
      'shop_id' => FILTER_VALIDATE_INT,
      'customer_id' => FILTER_VALIDATE_INT,
      'user_id' => FILTER_VALIDATE_INT,
      'memo' => FILTER_DEFAULT,
    ]);

    $price = filter_input(INPUT_POST, 'price', FILTER_DEFAULT);

    $history_data['price'] = $price;

    $target_date = $history_data['year'] . '-' . $history_data['month'] . '-' . $history_data['day'];

    $visit_history = new VisitHistory($history_data);

    $visit_history->registerVisitHistory();

    if (!empty($visit_history->getErrors())) {
      $modal_view_flug = TRUE;
    }
  }
}

$user_id = (isset($_SESSION['USER']) && ($_SESSION['USER']['admin_state'] === RegisterUser::STORE_MANEGER || $_SESSION['USER']['admin_state'] === null)) ? $_SESSION['USER']['id'] : null;
$admin_state = $_SESSION['USER']['admin_state'] ?? null;
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
        <h1 class="header-logo"><?= $shop['name'] . '  ' . $shop['area'] ?></h1>
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
        <?php if ($admin_state === RegisterCompany::OWNER || $admin_state === RegisterUser::STORE_MANEGER) : ?>
          <li class="sidebar-item">
            <a href="register_user.php?shop_id=<?= $shop_id ?>" class="sidebar-link">設定</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="main-content">
      <div class="adding-btn">
        <button class="modal-open" type="button">来店履歴追加<span>＋</span></button>
      </div>
      <div class="main-inner customer-form">
        <h2 class="main-title">お客様情報</h2>

        <div class="customer-main-detail">
          <div class="customer-name">
            <div class="customer-last_name">
              <ruby>
                <rt class="input" data-name="last_kana" data-type="text"><?= $customer_data['last_kana'] ?></rt>
                <rb class="input" data-name="last_name" data-type="text"><?= $customer_data['last_name'] ?></rb>
              </ruby>
            </div>
            <div class=" customer-first_name">
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
            <p class="invalid"></p>
            <dd id="birthday" class="input"><?php echo date('Y年m月d日', strtotime($customer_data['birthday'])) ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>メールアドレス</dt>
            <p class="invalid"></p>
            <dd class="input" data-name="email" data-type="email"><?= $customer_data['email'] ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>電話番号</dt>
            <p class="invalid"></p>
            <dd class="input" data-name="tel" data-type="tel"><?= $customer_data['tel'] ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>メモ</dt>
            <dd class="input" data-name="information" data-type="textarea"><?= h($customer_data['information']) ?></dd>
          </dl>
        </div>

        <div class="edit-btn">
          <input name="customer_information" type="button" value="編集">
        </div>
        <input name="shop_id" type="hidden" value="<?= $shop_id ?>">
      </div>

      <div class="table-wrap">
        <p>（最新10件）</p>
        <table class="customer-table customer-detail-table">
          <thead>
            <tr>
              <th>来店日</th>
              <th>総額</th>
              <th>当日メモ</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($visit_history_data as $data) : ?>
              <tr>
                <th><?= $data['date'] ?></th>
                <td><?= number_format($data['price']) ?>円</td>
                <td class="memo"><?= $data['memo'] ?></td>
                <td><button type="button" class="modal-open" data-date="<?= $data['date'] ?>"><i class="fa-solid fa-pencil"></i></button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal-container">
    <div class="modal-body">
      <div class="modal-close"></div>
      <form class="modal-content" method="post">
        <p class="modal-title">日付</p>


        <div class="modal-date">
          <input type="text" name="year" value="<?php isset($history_data) ? print $history_data['year'] : print date('Y'); ?>">
          <label>年</label>
          <input type="text" name="month" value="<?php isset($history_data) ? print $history_data['month'] : print date('m'); ?>">
          <label>月</label>
          <input type="text" name="day" value="<?php isset($history_data) ? print $history_data['day'] : print date('d'); ?>">
          <label>日</label>
        </div>
        <p class="invalid"><?php if (isset($history_data) && isset($visit_history->getErrors()['date'])) echo $visit_history->getErrors()['date'] ?></p>
        <div class="modal-price">
          <label>総額</label>
          <input type="text" name="price" value="<?php if (isset($history_data['price'])) echo $history_data['price'] ?>">
          <span>円</span>
        </div>
        <p class="invalid"><?php if (isset($history_data) && isset($visit_history->getErrors()['price'])) echo $visit_history->getErrors()['price'] ?></p>
        <div class="modal-memo">
          <label for="memo">メモ</label>
          <p class="invalid"><?php if (isset($history_data) && isset($visit_history->getErrors()['memo'])) echo $visit_history->getErrors()['memo'] ?></p>
          <textarea name="memo" rows="10"><?php if (isset($history_data['memo'])) echo $history_data['memo'] ?></textarea>
        </div>
        <input type="hidden" name="customer_id" value="<?= $id ?>">
        <input type="hidden" name="shop_id" value="<?= $shop_id ?>">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <div class="modal-btn">
          <input type="submit" name="visit_history" value="変更">
        </div>
      </form>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="/js/script.js"></script>
  <script>
    <?php if ($modal_view_flug) : ?>
      const container = $('.modal-container');
      container.addClass('active');
    <?php endif; ?>

    $(document).on('focusout', '.input input', function() {
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
            if (key === 'first_name' || key === 'last_name' || key === 'first_kana' || key === 'last_kana') {
              let input = $('input[name=' + key + ']');
              input.next(`p[data-err="${key}"]`).remove();
              input.after(`<p class="invalid" data-err="${key}">${err[key]}</p>`);

            } else if (key === 'birthday') {
              $('#birthday').prev('p').text('')
              $('#birthday').prev('p').text(err[key])
              $('#birthday').prev('p').css('padding', '0')
            } else {
              let input = $('input[name=' + key + ']');
              input.parent(`dd[data-name=${key}]`).prev('p').text('')
              input.parent(`dd[data-name=${key}]`).prev('p').text(err[key]).css('padding', '0')
            }
          }
          //バリデーションエラーがない時
          else {
            if (key === 'name' || key === 'kana') {
              $('.customer-main-detail').find(`p[data-err="${key}"]`).remove();
            } else if (key === 'birthday') {
              $('#birthday').prev('p').text('')
              $('#birthday').prev('p').css('padding', '13px 0')
            } else {
              let input = $('input[name=' + key + ']');
              input.parent(`dd[data-name=${key}]`).prev('p').text('')
              input.parent(`dd[data-name=${key}]`).prev('p').css('padding', '13px 0')
            }
          }
        });
      }).fail(function() {
        alert("error");
      });
    });

    $('.modal-open').click(function() {
      $('.invalid').text('');
      let target_date = $(this).data('date')

      let year
      let month
      let day

      if (target_date) {
        year = target_date.slice(0, 4)
        month = target_date.slice(5, 7)
        day = target_date.slice(8, 10)
        price = parseFloat($(this).closest('tr').children('td')[0].innerText.replace(/,/g, ""))
        memo = $(this).closest('tr').children('td')[1].innerText
      } else {
        date = new Date();
        year = date.getFullYear()
        month = ("00" + (date.getMonth() + 1)).slice(-2)
        day = ("00" + (date.getDate())).slice(-2);
        price = ''
        memo = ''
      }

      $('input[name="year"]').val(year)
      $('input[name="month"]').val(month)
      $('input[name="day"]').val(day)
      $('input[name="price"]').val(price)
      $('textarea[name="memo"]').val(memo)
    })
  </script>
</body>

</html>
