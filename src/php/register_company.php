<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');

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
<?php
$title = '会社登録';
include("./templates/header.php");
?>
<div class="login-inner">
    <div class="inner">
        <h2 class="main-title">会社登録</h2>

        <form class="register-form" method="post">
            <ul class="register-list">
                <li class="register-item">
                    <label for="last_name">会社名</label>
                    <div class="register-input">
                        <input type="text" name="name" placeholder="会社名" value="<?= $company_login_data['name'] ?? null ?>">
                    </div>
                    <p class="invalid"><?= $errors['company_name'] ?? null ?></p>
                </li>
                <li class="register-item">
                    <label for="last_name">パスワード</label>
                    <div class="register-input">
                        <input type="text" name="password" placeholder="パスワード">
                    </div>
                    <p class="invalid"><?= $errors['password']  ?? null ?></p>
                </li>
                <li class="register-item">
                    <label for="last_name">パスワード確認</label>
                    <div class="register-input">
                        <input type="text" name="confirm_password" placeholder="パスワード確認">
                    </div>
                    <p class="invalid"><?= $errors['confirm_password']  ?? null ?></p>
                </li>
                <div class="register-btn">
                    <button type="submit">登録</button>
                </div>
        </form>
        <div class="return-btn">
            <a class="return-login" href="/login">ログイン画面へ戻る<span>→</span></a>
        </div>
    </div>
</div>

</body>

</html>
