<?php

require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/Login.php');

session_start();

if (isset($_SESSION['USER']['admin']) && $_SESSION['USER']['admin'] === 1 && $_SESSION['USER']['id'] === (int) $_GET['company_id']) {
    redirect('/shop_list.php' . '?company_id=' . $_GET['id']);
}

$name = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $company = new Login($name, $password, 'company');

    if ($company->check_login()) {
        redirect('/shop_list.php' . '?company_id=' . $company->fetchUser()['id']);
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

    <title>顧客情報一覧</title>
</head>

<body>
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

    <div class="login-inner">
        <div class="inner">
            <form class="register-form" method="post">
                <ul class="register-list">
                    <li class="register-item">
                        <label for="last-name">会社名</label>
                        <div class="register-input">
                            <input type="text" name="name" placeholder="会社名" value="<?= $name ?>">
                        </div>
                        <p><?php if (isset($company->err['name'])) echo $company->err['name'] ?></p>
                    </li>
                    <li class="register-item">
                        <label for="last-name">パスワード</label>
                        <div class="register-input">
                            <input type="text" name="password" placeholder="パスワード">
                        </div>
                        <p><?php if (isset($company->err['password'])) echo $company->err['password'] ?></p>
                    </li>
                    <div class=" register-btn">
                        <button type="submit">ログイン</button>
                    </div>
                </ul>
            </form>

            <p class="register-guid">会社登録をしてない方は<a href="/register_company.php">こちら</a>で登録をしてください。</p>
        </div>
    </div>

    </div>

</body>

</html>
