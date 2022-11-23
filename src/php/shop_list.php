<?php
require_once(__DIR__ . '/functions.php');

session_start();

if (!isset($_SESSION['USER'])) {
    //ログインされていない場合はログイン画面へ
    redirect('/login');
}

//ログインユーザーの情報をセッションから取得
$session_user = $_SESSION['USER'];
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

    <title>顧客予約一覧</title>
</head>

<body class="register_shop">
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
                    <a href="shop_list.php" class="sidebar-link">店舗一覧</a>
                </li>
                <li class="sidebar-item">
                    <a href="register_shop.php" class="sidebar-link">店舗追加　＋</a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="main-inner">
                <h3>千葉県</h3>
                <ul class="shop-list">
                    <li class="shop-item"><a class="shop-link" href="/customer_list.php?<?= 'company_id=' . $session_user['id']?>&shop_id=1">行徳</a></li>
                    <li class="shop-item"><a class="shop-link" href="/customer_list.php">原木中山</a></li>
                </ul>
            </div>
        </div>

</body>

</html>
