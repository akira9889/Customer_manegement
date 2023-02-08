<?php
require_once(__DIR__ . '/Class/RegisterUser.php');
require_once(__DIR__ . '/functions.php');

$name = '';
$shop_id = filter_input(INPUT_GET, 'shop_id', FILTER_VALIDATE_INT);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = filter_input(INPUT_POST, 'name');
  $password = filter_input(INPUT_POST, 'password');
  $confirm_password = filter_input(INPUT_POST, 'confirm_password');
  $admin_state = filter_input(INPUT_POST, 'admin_state', FILTER_VALIDATE_INT);

  $user = new RegisterUser($name, $password, $confirm_password, $admin_state);

  $user->registerUser();

  if ($user->getRegisterdState()) {
    redirect('/customer_list.php?shop_id=' . $shop_id);
  }
}
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
          <a href="register_user.php?shop_id=<?= $shop_id ?>" class="sidebar-link active">設定</a>
        </li>
      </ul>
    </div>

    <div class="main-content">
      <div class="main-inner">
        <h2 class="main-title">ユーザー登録</h2>

        <form class="register-form" method="post">
          <ul class="register-list">
            <li class="register-item">
              <label for="last-name">ユーザ名</label>
              <div class="register-input">
                <input type="text" name="name" placeholder="ユーザ名" value="<?= $name ?>">
              </div>
              <p class="invalid"><?php if (isset($user->err['name'])) echo $user->err['name'] ?></p>
            </li>
            <li class="register-item">
              <label for="last-name">パスワード</label>
              <div class="register-input">
                <input type="text" name="password" placeholder="パスワード">
              </div>
              <p class="invalid"><?php if (isset($user->err['password'])) echo $user->err['password'] ?></p>
            </li>
            <li class=" register-item">
              <label for="last-name">パスワード確認</label>
              <div class="register-input">
                <input type="text" name="confirm_password" placeholder="パスワード確認">
              </div>
              <p class="invalid"><?php if (isset($user->err['confirm_password'])) echo $user->err['confirm_password'] ?></p>
            </li>
            <li class=" register-item register-item__admin">
              <label for="admin">管理者機能<input id="admin" type="checkbox" name="admin_state"><span></span></label>
              <p>(ユーザーの追加や削除、顧客関連の情報を追加、編集することができます。)</p>
              <!-- <div class="register-input register-input__check">

              </div> -->

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
