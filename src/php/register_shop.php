<?php
require_once(__DIR__ . '/Class/RegisterShop.php');

session_start();

$company_id = filter_input(INPUT_GET, 'company_id', FILTER_VALIDATE_INT);

//ログインされていない場合はログイン画面へ
if (!isset($_SESSION['USER']['admin_state']) || $_SESSION['USER']['admin_state'] !== 1 || $_SESSION['USER']['id'] !== $company_id) {
    redirect('/login.php?company_id=' . $company_id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_data = filter_input_array(INPUT_POST, [
        'prefecture_id' => FILTER_VALIDATE_INT,
        'area' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ]);
    $shop_data['company_id'] = $company_id;

    $shop = new RegisterShop($shop_data);
    $shop->register_shop();
}



$sql = "SELECT `id`, `name` FROM prefectures";

$mysql = new ExecuteMySql($sql);

$prefectures = $mysql->execute();
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
                    <a href="shop_list.php?company_id=<?= $_GET['company_id'] ?>" class="sidebar-link">店舗一覧</a>
                </li>
                <li class="sidebar-item">
                    <a href="register_shop.php?company_id=<?= $_GET['company_id'] ?>" class="sidebar-link">店舗追加　＋</a>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="main-inner">
                <h2 class="main-title">店舗登録</h2>

                <form class="register-form" method="post">
                    <ul class="register-list">
                        <li class="register-item register-item__shop">
                            <p>店舗エリア</p>
                            <p class="invalid"><?php if (isset($shop) && isset($shop->getErrors()['shop'])) echo $shop->getErrors()['shop'] ?></p>
                            <div class="register-area">
                                <div class="register-input">
                                    <div class="register-input-select">
                                        <div class="select-container">
                                            <select name="prefecture_id" class="select_date">
                                                <option selected>都道府県</option>
                                                <?php foreach ($prefectures as $prefecture) : ?>
                                                    <option value="<?= $prefecture['id'] ?>" <?php
                                                                                                if (isset($shop_data['prefecture_id']) && $shop_data['prefecture_id'] === $prefecture['id']) {
                                                                                                    echo 'selected';
                                                                                                }
                                                                                                ?>><?= $prefecture['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="invalid"><?php if (isset($shop) && isset($shop->getErrors()['prefecture_id'])) echo $shop->getErrors()['prefecture_id']; ?></p>
                                </div>

                                <div class="register-input register-input__shop">
                                    <input id="area" type="text" name="area" placeholder="品川" value="<?php if (isset($shop_data['area'])) echo $shop_data['area'] ?>">
                                    <label for="area">店</label>
                                    <p class="invalid"><?php if (isset($shop) && isset($shop->getErrors()['area'])) echo $shop->getErrors()['area']; ?></p>
                                </div>
                            </div>

                        </li>
                        <div class="register-btn">
                            <button type="submit">登録</button>
                        </div>
                </form>
            </div>
        </div>

</body>

</html>
