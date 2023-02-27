<?php
require_once(__DIR__ . '/Class/UserList.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');
require_once __DIR__ . '/functions.php';

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
    if (isset($_POST['delete'])) {

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $users = new UserList($shop_id);
        $users->deleteUser($id);
    }

    if (isset($_POST['update'])) {

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $admin_state = filter_input(INPUT_POST, 'admin_state');
        $users = new UserList($shop_id);

        $users->updateUser($id, $admin_state);
    }
}

$users = new UserList($shop_id);
$admin_users = $users->fetchAdminUserList();
$common_users = $users->fetchCommonUserList();

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
                    <li class="sidebar-item has-sub-menu">
                        <p class="sidebar-link">設定</p>

                        <ul class="sub-menu">
                            <li class="sub-item sidebar-item"><a class="sidebar-link" href="register_user.php?shop_id=<?= $shop_id ?>">スタッフ登録</a></li>
                            <li class="sub-item sidebar-item"><a class="sidebar-link" href="user_list.php?shop_id=<?= $shop_id ?>">スタッフ一覧</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="main-content">
            <div class="main-inner">
                <h2 class="main-title">スタッフ一覧</h2>
                <div class="admin-user">
                    <?php if (!empty($admin_users)) : ?>
                        <h3>管理者</h3>
                        <ul class="user-list">
                            <?php foreach ($admin_users as $user) : ?>
                                <li class="user-item" data-id="<?= $user['id'] ?>">
                                    <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                                    <p class="user-name"><?= $user['name'] ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="common-user">
                    <?php if (!empty($common_users)) : ?>
                        <h3>メンバー</h3>
                        <ul class="user-list">
                            <?php foreach ($common_users as $user) : ?>
                                <li class="user-item" data-id="<?= $user['id'] ?>">
                                    <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                                    <p class="user-name"><?= $user['name'] ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-container">
        <div class="modal-body">
            <div class="modal-close"></div>
            <form class="modal-content" method="post">
                <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                <p class="user-name"></p>
                <li class=" register-item register-item__admin">
                    <label for="admin">管理者機能<input id="admin" type="checkbox" name="admin_state"><span></span></label>
                    <p>(ユーザーの追加や削除、編集することができます。)</p>
                </li>

                <div class="modal-btn-list">
                    <div id="cancel-btn" class="hidden">
                        <button type="button">キャンセル</button>
                    </div>
                    <div id="user-delete-btn" class="user-delete-btn">
                        <button type="input">削除</button>
                    </div>
                    <div id="user-update-btn">
                        <input name="update" type="submit" value="更新">
                    </div>
                    <div id="confirm-delete-btn" class="user-delete-btn hidden">
                        <input name="delete" type="submit" value="削除">
                    </div>
                </div>
                <input id="user-id" type="hidden" name="id">
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/js/script.js"></script>
    <script>
        const user = $('.user-item');
        const container = $('.modal-container');
        const close = $('.modal-close');

        user.on('click', (e) => {
            container.addClass('active');

            let id = $(e.currentTarget).data('id')
            let name = $(e.currentTarget).find('.user-name').text()

            $('.modal-content .user-name').text(name)
            $('#user-id').val(id)

            $.ajax({
                url: 'ajax_get_admin_state.php',
                type: "POST",
                data: {
                    id: id
                },
                dataType: "text"
            }).done(function(admin) {
                if (admin) {
                    $('#admin').prop('checked', true)
                } else {
                    $('#admin').prop('checked', false)
                }
            }).fail(function() {
                alert('接続エラー');
            });

            return false;
        })

        $('#user-delete-btn').click(() => {
            $('#user-update-btn').addClass('hidden')
            $('#cancel-btn').removeClass('hidden')
            $('#confirm-delete-btn').removeClass('hidden')
            $('#user-delete-btn').addClass('hidden')
            $('.register-item__admin > label').css('display', 'none')

            $('.register-item__admin > p').text('こちらのユーザー削除しますか？');
            return false;

        })

        $('#cancel-btn').click(() => {
            clearModal()
        })

        $('#confirm-delete-btn').click(() => {
            $('.modal-content').submit();
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
            $('#user-update-btn').removeClass('hidden')
            $('#cancel-btn').addClass('hidden')
            $('#user-delete-btn').removeClass('hidden')
            $('#confirm-delete-btn').addClass('hidden')
            $('.register-item__admin > label').css('display', 'inline-flex')
            $('.register-item__admin > p').text('ユーザーの追加や削除、編集することができます。');
        }
    </script>
</body>

</html>
