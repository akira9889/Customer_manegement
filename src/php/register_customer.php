<?php
require_once(__DIR__ . '/Class/RegisterCustomer.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');
require_once(__DIR__ . '/Class/RegisterUser.php');
require_once(__DIR__ . '/functions.php');

session_start();

$shop_id = filter_input(INPUT_GET, 'shop_id', FILTER_VALIDATE_INT);

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

// $last_name = '';
// $first_name = '';
// $last_name_kana = '';
// $first_name_kana = '';
// $email = '';
// $birthday_year = '';
// $birthday_month = '';
// $birthday_date = '';
// $tel = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $last_name = filter_input(INPUT_POST, 'last-name');
  $first_name = filter_input(INPUT_POST, 'first-name');
  $last_name_kana = filter_input(INPUT_POST, 'last-name-kana');
  $first_name_kana = filter_input(INPUT_POST, 'first-name-kana');
  $gender = filter_input(INPUT_POST, 'gender');
  $email = filter_input(INPUT_POST, 'email');
  $birthday_year = filter_input(INPUT_POST, 'birthday_year');
  $birthday_month = filter_input(INPUT_POST, 'birthday_month');
  $birthday_date = filter_input(INPUT_POST, 'birthday_date');
  $birthday = $birthday_year . '-' . $birthday_month . '-' . $birthday_date;
  $tel = filter_input(INPUT_POST, 'tel');
  $information = filter_input(INPUT_POST, 'information');

  $new_customer = new RegisterCustomer($last_name, $first_name, $last_name_kana, $first_name_kana, $email, $birthday_year, $birthday_month, $birthday_date, $tel, $gender, $information);

  $new_customer->registerCustomer();

  if ($new_customer->registered_state) {
    redirect('customer_detail.php?id=' . $new_customer->last_isert_id);
  }
}
$admin_state = $_SESSION['USER']['admin_state'] ?? null;
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

<body>
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
          <a href="visit-history.php?shop_id=<?= $shop_id ?>" class="sidebar-link active2">来店履歴一覧</a>
        </li>
        <?php if ($admin_state === RegisterCompany::OWNER || $admin_state === RegisterUser::STORE_MANEGER) : ?>
          <li class="sidebar-item">
            <a href="register_user.php?shop_id=<?= $shop_id ?>" class="sidebar-link">設定</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="main-content">
      <div class="main-inner">
        <h2 class="main-title">お客様登録</h2>

        <form class="register-form" method="post">
          <ul class="register-list">
            <li class="register-item">
              <label for="last-name">氏名</label>
              <div class="register-input">
                <input type="text" name="last-name" placeholder="姓" value="<?= $last_name ?? null ?>">
                <input type="text" name="first-name" placeholder="名" value="<?= $first_name ?? null ?>">
              </div>
              <p class="invalid"><?= $new_customer->err['name'] ?? null ?></p>
            </li>
            <li class="register-item">
              <label for="last-name">フリガナ</label>
              <div class="register-input">
                <input type="text" name="last-name-kana" placeholder="セイ">
                <input type="text" name="first-name-kana" placeholder="メイ">
              </div>
              <p class="invalid"><?= $new_customer->err['kana'] ?? null ?></p>
            </li>

            <li class="register-item">
              <label for="last-name">性別</label>
              <div class="register-input-radio">
                <label for="male">
                  <input type="radio" name="gender" value="男性" id="male" <?php if (isset($gender) && $gender === '男性') echo 'checked' ?>>男性
                  <span></span>
                </label>
                <label for="female">
                  <input type="radio" name="gender" value="女性" id="female" <?php if (isset($gender) && $gender === '女性') echo 'checked' ?>>女性
                  <span></span>
                </label>
              </div>
              <p class="invalid"><?= $new_customer->err['gender'] ?? null ?></p>
            </li>

            <li class="register-item">
              <label for="email">メールアドレス</label>
              <div class="register-input">
                <input type="text" name="email" placeholder="メールアドレス">
              </div>
              <p class="invalid"><?= $new_customer->err['email'] ?? null ?></p>
            </li>

            <li class="register-item">
              <label for="birthday">生年月日</label>
              <p class="birthday-example">例: <strong>1960</strong><span>年</span><strong>09</strong><span>月</span><strong>03</strong><span>日</span></p>
              <div class="register-input">
                <div class="register-input-birthday">
                  <input type="text" name="birthday_year" placeholder="1960" value="<?= $birthday_year ?? null ?>">
                  <label for="">年</label>
                </div>

                <div class="register-input-birthday">
                  <input type="text" name="birthday_month" placeholder="09" value="<?= $birthday_month ?? null ?>">
                  <label for="">月</label>
                </div>

                <div class="register-input-birthday">
                  <input type="text" name="birthday_date" placeholder="03" value="<?= $birthday_date ?? null ?>">
                  <label for="">日</label>
                </div>
              </div>
              <p class="invalid"><?= $new_customer->err['birthday'] ?? null ?></p>
            </li>

            <li class="register-item">
              <label for="email">電話番号</label>
              <div class="register-input">
                <input type="tel" name="tel" placeholder="0123456789(ハイフン等なし)" value="<?= $tel ?? null ?>">
              </div>
              <p class="invalid"><?= $new_customer->err['tel'] ?? null ?></p>
            </li>
          </ul>

          <div class="register-btn">
            <button type="submit">登録</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>

</html>
