<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $company_login_data = filter_input_array(INPUT_POST, [
        'name' =>  FILTER_DEFAULT,
        'password' =>  FILTER_DEFAULT,
        'confirm_password' =>  FILTER_DEFAULT
    ]);

    $company = new RegisterCompany($company_login_data);

    $company->registerUser();

    $errors = $company->getErrors();
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

    <title>会社登録</title>
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
                            <input type="text" name="name" placeholder="会社名" value="<?= $company_login_data['name'] ?? null ?>">
                        </div>
                        <p class="invalid"><?= $errors['company_name'] ?? null?></p>
                    </li>
                    <li class="register-item">
                        <label for="last-name">パスワード</label>
                        <div class="register-input">
                            <input type="text" name="password" placeholder="パスワード">
                        </div>
                        <p class="invalid"><?= $errors['password']  ?? null?></p>
                    </li>
                    <li class="register-item">
                        <label for="last-name">パスワード確認</label>
                        <div class="register-input">
                            <input type="text" name="confirm_password" placeholder="パスワード確認">
                        </div>
                        <p class="invalid"><?= $errors['confirm_password']  ?? null?></p>
                    </li>
                    <div class="register-btn">
                        <button type="submit">登録</button>
                    </div>
            </form>
            <a class="return-login" href="login.php">ログイン画面へ戻る<span>→</span></a>
        </div>
    </div>

</body>

</html>
