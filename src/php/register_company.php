<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');

session_start();

$name = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_input(INPUT_POST, 'name');
    $password = filter_input(INPUT_POST, 'password');
    $confirm_password = filter_input(INPUT_POST, 'confirm_password');

    $company = new RegisterCompany($name, $password, $confirm_password);

    if ($company->registerUser()) {
        redirect('/shop_list.php?company_id=' . $company->fetchUser()['id']);
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
                <h1 class="header-logo">Manegee</h1>
            </div>
        </div>
    </header>

    <div class="login-inner">
        <div class="inner">
            <h2 class="main-title">会社登録</h2>

            <form class="register-form" method="post">
                <ul class="register-list">
                    <li class="register-item">
                        <label for="last-name">会社名</label>
                        <div class="register-input">
                            <input type="text" name="name" placeholder="会社名" value="<?= $name ?>">
                        </div>
                        <p class="invalid"><?php if (isset($company->err['name'])) echo $company->err['name'] ?></p>
                    </li>
                    <li class="register-item">
                        <label for="last-name">パスワード</label>
                        <div class="register-input">
                            <input type="text" name="password" placeholder="パスワード">
                        </div>
                        <p class="invalid"><?php if (isset($company->err['password'])) echo $company->err['password'] ?></p>
                    </li>
                    <li class="register-item">
                        <label for="last-name">パスワード確認</label>
                        <div class="register-input">
                            <input type="text" name="confirm_password" placeholder="パスワード確認">
                        </div>
                        <p class="invalid"><?php if (isset($company->err['confirm_password'])) echo $company->err['confirm_password'] ?></p>
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
