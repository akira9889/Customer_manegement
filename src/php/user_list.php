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

<body class="user_list">
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
                <h2 class="main-title">スタッフ一覧</h2>
                <div class="admin-user">
                    <h3>管理者</h3>
                    <ul class="user-list">
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                    </ul>
                </div>
                <div class="common-user">
                    <h3>メンバー</h3>
                    <ul class="user-list">
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                        <li class="user-item">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <p class="user-name">上野誠太郎</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-container">
        <div class="modal-body">
            <div class="modal-close"></div>
            <form class="modal-content">
                <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                <p class="user-name">上野誠太郎</p>
                <li class=" register-item register-item__admin">
                    <label for="admin">管理者機能<input id="admin" type="checkbox" name="admin_state" <?php if (isset($new_user_data['admin_state'])) echo 'checked' ?>><span></span></label>
                    <p>(ユーザーの追加や削除、編集することができます。)</p>
                </li>

                <div class="modal-btn-list">
                    <div id="cancel-btn">
                        <button type="button">キャンセル</button>
                    </div>
                    <div id="user-delete-btn">
                        <input type="button" value="削除">
                    </div>
                    <div id="user-update-btn">
                        <input type="button" value="更新">
                    </div>
                </div>
                <input type="hidden" name="id">
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/js/script.js"></script>
    <script>
        const close = $('.modal-close');

        $('#user-delete-btn').click(() => {
            $('#user-update-btn').css('display', 'none')
            $('#cancel-btn').css('display', 'inline-block')
            $('#user-delete-btn').css('margin-right', '0')
            $('.register-item__admin > label').css('display', 'none')
            $('.register-item__admin > p').text('こちらのユーザー削除しますか？');
        })

        $('#cancel-btn').click(() => {
            clearModal()
        })

        close.on('click', () => {
            setTimeout(() => {
                clearModal()
            }, 300);
        });

        $(document).on('click', (e) => {
            if (!$(e.target).closest('.modal-body').length) {
                setTimeout(() => {
                    clearModal()
                }, 300);
            }
        });

        const clearModal = () => {
            $('#user-update-btn').css('display', 'inline-block')
            $('#cancel-btn').css('display', 'none')
            $('#user-delete-btn').css('margin-right', '30px')
            $('.register-item__admin > label').css('display', 'inline-flex')
            $('.register-item__admin > p').text('ユーザーの追加や削除、編集することができます。');
        }
    </script>
</body>

</html>
