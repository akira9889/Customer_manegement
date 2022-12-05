<?php
require_once(__DIR__ . '/Class/RegisterShop.php');


$prefectures = '';
$area = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prefectures_id = (int) $_POST['prefecture_id'];
    $area = $_POST['area'];


    $company = new RegisterShop($prefectures_id, $area);

    $company->register_shop();
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
                            <label for="last-name">店舗エリア</label>
                            <div class="register-area">
                                <div class="register-input">
                                    <div class="register-input-select">
                                        <div class="select-container">
                                            <select name="prefecture_id" class="select_date">
                                                <option value="" selected>都道府県</option>
                                                <option value="1">北海道</option>
                                                <option value="2">青森県</option>
                                                <option value="3">岩手県</option>
                                                <option value="4">宮城県</option>
                                                <option value="5">秋田県</option>
                                                <option value="6">山形県</option>
                                                <option value="7">福島県</option>
                                                <option value="8">茨城県</option>
                                                <option value="9">栃木県</option>
                                                <option value="10">群馬県</option>
                                                <option value="11">埼玉県</option>
                                                <option value="12">千葉県</option>
                                                <option value="13">東京都</option>
                                                <option value="14">神奈川県</option>
                                                <option value="15">新潟県</option>
                                                <option value="16">富山県</option>
                                                <option value="17">石川県</option>
                                                <option value="18">福井県</option>
                                                <option value="19">山梨県</option>
                                                <option value="20">長野県</option>
                                                <option value="21">岐阜県</option>
                                                <option value="22">静岡県</option>
                                                <option value="23">愛知県</option>
                                                <option value="24">三重県</option>
                                                <option value="25">滋賀県</option>
                                                <option value="26">京都府</option>
                                                <option value="27">大阪府</option>
                                                <option value="28">兵庫県</option>
                                                <option value="29">奈良県</option>
                                                <option value="30">和歌山県</option>
                                                <option value="31">鳥取県</option>
                                                <option value="32">島根県</option>
                                                <option value="33">岡山県</option>
                                                <option value="34">広島県</option>
                                                <option value="35">山口県</option>
                                                <option value="36">徳島県</option>
                                                <option value="37">香川県</option>
                                                <option value="38">愛媛県</option>
                                                <option value="39">高知県</option>
                                                <option value="40">福岡県</option>
                                                <option value="41">佐賀県</option>
                                                <option value="42">長崎県</option>
                                                <option value="43">熊本県</option>
                                                <option value="44">大分県</option>
                                                <option value="45">宮崎県</option>
                                                <option value="46">鹿児島県</option>
                                                <option value="47">沖縄県</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="register-input register-input__shop">
                                    <input id="area" type="text" name="area" placeholder="品川">
                                    <label for="area">店</label>
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
