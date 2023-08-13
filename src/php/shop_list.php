<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/ShopList.php');

$company_id = filter_input(INPUT_GET, 'company_id', FILTER_VALIDATE_INT);

//ログインされていない場合はログイン画面へ
if (!isset($_SESSION['USER']['admin_state']) || $_SESSION['USER']['admin_state'] !== 1 || $_SESSION['USER']['id'] !== $company_id) {
    redirect('/login/');
}

$shops = new ShopList($company_id);

?>

<?php
$title = '店舗一覧';
include("./templates/header.php");
?>

<div class="content">
    <div class="sidebar">
        <ul class="sidebar-list">
            <li class="sidebar-item">
                <a href="/shop_list/?company_id=<?= $company_id ?>" class="sidebar-link">店舗一覧</a>
            </li>
            <li class="sidebar-item">
                <a href="/register_shop/?company_id=<?= $company_id ?>" class="sidebar-link">店舗追加　＋</a>
            </li>
        </ul>
    </div>

    <?php if (count($shops->listShops())) : ?>
        <div class="main-content">
            <div class="main-inner">
                <?php
                $group_by_prefectures = group_by($shops->listShops(), 'prefecture');
                $prefectures = array_keys($group_by_prefectures);
                for ($i = 0; $i < count($group_by_prefectures); $i++) :
                ?>
                    <h3 class="shop_prefecture">
                        <?php
                        $prefecture = $prefectures[$i];
                        echo $prefecture;
                        ?>
                    </h3>
                    <ul class="shop-list">
                        <?php
                        foreach ($group_by_prefectures[$prefecture] as $shop) :
                            $url = '/shop_login/?shop_id=' . $shop['shop_id'];
                        ?>
                            <li class="shop-item">
                                <a class="shop-link" href="<?= $url ?>">
                                    <?= $shop['area'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
    </body>

    </html>
