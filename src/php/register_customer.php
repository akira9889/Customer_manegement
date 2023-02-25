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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $new_customer_data = filter_input_array(INPUT_POST, [
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

  $new_customer = new RegisterCustomer($new_customer_data);

  $new_customer->registerCustomer();

  $errors = $new_customer->getErrors();
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

<body class="register_customer">
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
      <div class="main-inner">
        <h2 class="main-title">お客様登録</h2>
        <p class="invalid"><?= $errors['customer'] ?? null ?></p>
        <form class="register-form" method="post">
          <ul class="register-list">
            <li class="register-item">
              <label for="last_name">氏名</label>
              <div class="register-input">
                <div class="register-name">
                  <input type="text" name="last_name" placeholder="姓" value="<?= $new_customer_data['last_name'] ?? null ?>">
                  <p class="invalid"><?= $errors['last_name'] ?? null ?></p>
                </div>
                <div class="register-name">
                  <input type="text" name="first_name" placeholder="名" value="<?= $new_customer_data['first_name'] ?? null ?>">
                  <p class="invalid"><?= $errors['first_name'] ?? null ?></p>
                </div>
              </div>
            </li>
            <li class="register-item">
              <label for="last_kana">フリガナ</label>
              <div class="register-input">
                <div class="register-name">
                  <input type="text" name="last_kana" placeholder="セイ" value="<?= $new_customer_data['last_kana'] ?? null ?>">
                  <p class="invalid"><?= $errors['last_kana'] ?? null ?></p>
                </div>
                <div class="register-name">
                  <input type="text" name="first_kana" placeholder="メイ" value="<?= $new_customer_data['first_kana'] ?? null ?>">
                  <p class="invalid"><?= $errors['first_kana'] ?? null ?></p>
                </div>
              </div>
            </li>

            <li class="register-item">
              <label for="last_name">性別</label>
              <div class="register-input-radio">
                <label for="male">
                  <input type="radio" name="gender" value="男性" id="male" <?php if (isset($new_customer_data['gender']) && $new_customer_data['gender'] === '男性') echo 'checked' ?>>男性
                  <span></span>
                </label>
                <label for="female">
                  <input type="radio" name="gender" value="女性" id="female" <?php if (isset($new_customer_data['gender']) && $new_customer_data['gender'] === '女性') echo 'checked' ?>>女性
                  <span></span>
                </label>
              </div>
              <p class="invalid"><?= $errors['gender'] ?? null ?></p>
            </li>

            <li class="register-item">
              <label for="email">メールアドレス</label>
              <div class="register-input">
                <div class="register-email">
                  <input type="text" name="email" placeholder="メールアドレス" value="<?= $new_customer_data['email'] ?? null ?>">
                </div>
              </div>
              <p class="invalid"><?= $errors['email'] ?? null ?></p>
            </li>

            <li class="register-item">
              <label for="birthday">生年月日</label>
              <p class="birthday-example">例: <strong>1960</strong><span>年</span><strong>09</strong><span>月</span><strong>03</strong><span>日</span></p>
              <div class="register-input register-input-birthday">
                <div class="register-birthday">
                  <input type="text" name="birthday_year" placeholder="1960" value="<?= $new_customer_data['birthday_year'] ?? null ?>">
                  <label for="">年</label>
                </div>

                <div class="register-birthday">
                  <input type="text" name="birthday_month" placeholder="09" value="<?= $new_customer_data['birthday_month'] ?? null ?>">
                  <label for="">月</label>
                </div>

                <div class="register-birthday">
                  <input type="text" name="birthday_date" placeholder="03" value="<?= $new_customer_data['birthday_date'] ?? null ?>">
                  <label for="">日</label>
                </div>
              </div>
              <p class="invalid"><?= $errors['birthday'] ?? null ?></p>
            </li>

            <li class="register-item">
              <label for="email">電話番号</label>
              <div class="register-input">
                <div class="register-email">
                  <input type="tel" name="tel" placeholder="0123456789(ハイフン等なし)" value="<?= $new_customer_data['tel'] ?? null ?>">
                </div>
              </div>
            </li>
            <p class="invalid"><?= $errors['tel'] ?? null ?></p>
          </ul>

          <div class="register-btn">
            <button type="submit">登録</button>
          </div>
          <input name="shop_id" type="hidden" value="<?= $shop_id ?>">
        </form>
      </div>
    </div>
  </div>

</body>

</html>
