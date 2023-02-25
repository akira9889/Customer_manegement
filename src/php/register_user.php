<?php
require_once(__DIR__ . '/Class/RegisterUser.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');
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

//オーナーまたは管理ユーザー以外はログイン画面へ遷移
if (
  !(isset($_SESSION['USER']['admin_state']) && $_SESSION['USER']['admin_state'] === RegisterCompany::OWNER && isset($shop['company_id']) && $shop['company_id'] === $_SESSION['USER']['id']) &&
  !(isset($_SESSION['USER']['admin_state']) && $_SESSION['USER']['admin_state'] === RegisterUser::STORE_MANEGER && isset($shop_id) && $shop_id === $_SESSION['USER']['shop_id'])
) {
  redirect('/shop_login.php?shop_id=' . $shop_id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $new_user_data = filter_input_array(INPUT_POST, [
    'name' => FILTER_DEFAULT,
    'password' => FILTER_DEFAULT,
    'confirm_password' => FILTER_DEFAULT,
    'admin_state' => FILTER_VALIDATE_INT,
  ]);

  $new_user_data['shop_id'] = $shop_id;

  $user = new RegisterUser($new_user_data);

  $user->registerUser();

  $errors = $user->getErrors();
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

  <title>管理者登録</title>
</head>

<body class="register_user">
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
            <a href="register_user.php?shop_id=<?= $shop_id ?>" class="sidebar-link active">設定</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="main-content">
      <div class="main-inner">
        <h2 class="main-title">ユーザー登録</h2>

        <form class="register-form" method="post">
          <ul class="register-list">
            <li class="register-item">
              <label for="last_name">ユーザ名</label>
              <div class="register-input">
                <input type="text" name="name" placeholder="ユーザ名" value="<?= $new_user_data['name'] ?? null ?>">
              </div>
              <p class="invalid"><?= $errors['user_name'] ?? null?></p>
            </li>
            <li class="register-item">
              <label for="last_name">パスワード</label>
              <div class="register-input">
                <input type="text" name="password" placeholder="パスワード">
              </div>
              <p class="invalid"><?= $errors['password'] ?? null?></p>
            </li>
            <li class=" register-item">
              <label for="last_name">パスワード確認</label>
              <div class="register-input">
                <input type="text" name="confirm_password" placeholder="パスワード確認">
              </div>
              <p class="invalid"><?= $errors['confirm_password'] ?? null ?></p>
            </li>
            <li class=" register-item register-item__admin">
              <label for="admin">管理者機能<input id="admin" type="checkbox" name="admin_state" <?php if (isset($new_user_data['admin_state'])) echo 'checked' ?>><span></span></label>
              <p>(ユーザーの追加や削除、顧客関連の情報を追加、編集することができます。)</p>
            </li>
            <div class="register-btn">
              <button type="submit">登録</button>
            </div>
        </form>
      </div>
    </div>
  </div>

</body>

</html>
