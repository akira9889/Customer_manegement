<?php

require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/Login.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');

session_start();

//ログイン済みの場合、店舗一覧ページに遷移
if (isset($_SESSION['USER']['admin_state']) && $_SESSION['USER']['admin_state'] === RegisterCompany::OWNER) {
    redirect('/shop_list/' . '?company_id=' . $_SESSION['USER']['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_data = filter_input_array(INPUT_POST, [
        'name' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT
    ]);

    $company = new Login($login_data);
    $company->login();

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

    <title>顧客情報一覧</title>
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-content">
                <h1 class="header-logo">Sample shop</h1>
            </div>
        </div>
    </header>

    <div class="login-inner">
        <div class="inner">
            <form class="register-form" method="post">
                <ul class="register-list">
                    <li class="register-item">
                        <label for="name">会社名</label>
                        <div class="register-input">
                            <input id="name" type="text" name="name" placeholder="ユーザー" value="<?= $login_data['user_name'] ?? null ?>">
                        </div>
                        <p class="invalid"><?= $errors['user_name'] ?? null ?></p>
                    </li>
                    <li class="register-item">
                        <label for="password">パスワード</label>
                        <div class="register-input">
                            <input id="password" type="text" name="password" placeholder="パスワード">
                        </div>
                        <p class="invalid"><?= $errors['password'] ?? null ?></p>
                    </li>
                    <div class=" register-btn">
                        <button type="submit">ログイン</button>
                    </div>
                </ul>
            </form>
            <p class="register-guid">ユーザー登録をしてない方は<a href="/register_company/">こちら</a>から登録をしてください。</p>
        </div>
    </div>

    </div>

</body>

</html>
