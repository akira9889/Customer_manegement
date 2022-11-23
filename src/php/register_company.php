<?php

require_once(__DIR__ . '/Class/Register_user.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $company = new RegisterUser();

    $company->RegisterUser($name, $password, $confirm_password);

    header('Location: /shop_list.php' . '?' . $name);
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
    <header>
        <div class="header">
            <h1>Manegee</h1>
        </div>
    </header>

    <div class="register_company-inner">
        <div class="inner">
            <h2 class="main-title">会社登録</h2>

            <form class="register-form" method="post">
                <ul class="register-list">
                    <li class="register-item">
                        <label for="last-name">会社名</label>
                        <div class="register-input">
                            <input type="text" name="name" placeholder="会社名">
                        </div>
                    </li>
                    <li class="register-item">
                        <label for="last-name">パスワード</label>
                        <div class="register-input">
                            <input type="text" name="password" placeholder="パスワード">
                        </div>
                    </li>
                    <li class="register-item">
                        <label for="last-name">パスワード確認</label>
                        <div class="register-input">
                            <input type="text" name="confirm_password" placeholder="パスワード確認">
                        </div>
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
