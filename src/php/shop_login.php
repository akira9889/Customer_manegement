<?php

require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');
require_once(__DIR__ . '/Class/ShopLogin.php');

session_start();

$shop_id = filter_input(INPUT_GET, 'shop_id', FILTER_VALIDATE_INT);

$sql = "SELECT s.company_id, c.name
        FROM shops s
        INNER JOIN companies c
        ON s.company_id = c.id
        WHERE s.id = :shop_id
        LIMIT 1";

$options = [
    'shop_id' => $shop_id
];

$mysql = new ExecuteMySql($sql, $options);

if (!empty($mysql->execute()[0])) {
    $shop = $mysql->execute()[0];
} else {
    throw new Exception('店舗IDが取得出来ない', 404);
}

if ((isset($_SESSION['USER']['shop_id']) && $_SESSION['USER']['shop_id'] === $shop_id) || (isset($_SESSION['USER']['admin_state']) && $_SESSION['USER']['admin_state'] === RegisterCompany::OWNER && isset($shop['company_id']) && $shop['company_id'] === $_SESSION['USER']['id'])) {
    redirect('/customer_list.php?shop_id=' . $shop_id);
}

$name = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $user = new ShopLogin($name, $password, 'users');

    if ($user->checkLogin()) {
        redirect('/customer_list.php?shop_id=' . $user->fetchUser($name)['shop_id']);
    };
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

    <title>顧客情報一覧</title>
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-content">
                <h1 class="header-logo"><?php if(isset($shop['name'])) echo $shop['name'] ?></h1>
            </div>
        </div>
    </header>

    <div class="login-inner">
        <div class="inner">
            <form class="register-form" method="post">
                <ul class="register-list">
                    <li class="register-item">
                        <label for="last-name">氏名</label>
                        <div class="register-input">
                            <input type="text" name="name" placeholder="氏名" value="<?= $name ?>">
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
                    <div class="register-btn">
                        <button type="submit">ログイン</button>
                    </div>
                </ul>
            </form>
        </div>
    </div>

    </div>

</body>

</html>
