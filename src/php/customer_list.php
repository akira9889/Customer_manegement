<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');
require_once(__DIR__ . '/Class/CustomerList.php');

session_start();

$sql = "SELECT company_id
        FROM shops
        WHERE id = :shop_id
        LIMIT 1";

$options = [
  'shop_id' => (int) $_GET['shop_id']
];

$mysql = new ExecuteMySql($sql, $options);

if (!empty($mysql->execute()[0])) {
  $company_id = $mysql->execute()[0];
}

if (!(isset($_SESSION['USER']) && (isset($_SESSION['USER']['shop_id']) && $_SESSION['USER']['shop_id'] === (int) $_GET['shop_id'])) && !(isset($_SESSION['USER']['admin']) && $_SESSION['USER']['admin'] === RegisterCompany::OWNER && isset($company_id['company_id']) && $company_id['company_id'] === $_SESSION['USER']['id'])) {
  //ログインされていない場合はログイン画面へ
  redirect('/shop_login.php?shop_id=' . $_GET['shop_id']);
}

$shop_id = (int) filter_input(INPUT_GET, 'shop_id', FILTER_VALIDATE_INT);

$left = (int) filter_input(INPUT_GET, 'left', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1],
]);

$right = (int) filter_input(INPUT_GET, 'right', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1],
]);

$count = (int) filter_input(INPUT_GET, 'count', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1, 'max_range' => CustomerList::PAGE_COUNT],
]) ?: 10;

$customer_data = new CustomerList( (int) $shop_id, $count);

if (is_int($left)) {
  $customer_list = $customer_data->fetchNextCustomerList($left);
} elseif (is_int($right)) {
  $customer_list = $customer_data->fetchPrevCustomerList($right);
} else {
  $customer_list = $customer_data->fetchCustomerList();
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
          <a href="customer_list.php?shop_id=<?= $_GET['shop_id'] ?>" class="sidebar-link active">顧客情報一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="visit-history.php?shop_id=<?= $_GET['shop_id'] ?>" class="sidebar-link">来店履歴一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="reserve_list.php?shop_id=<?= $_GET['shop_id'] ?>" class="sidebar-link">予約一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="register_user.php?shop_id=<?= $_GET['shop_id'] ?>" class="sidebar-link">設定</a>
        </li>
      </ul>
    </div>

    <div class="main-content">
      <div class="main-head">
        <form class="search-container">
          <input type="text" placeholder="検索" class="search">
        </form>
        <div class="adding-btn">
          <a href="register_customer.php">顧客新規作成<span>＋</span></a>
        </div>
      </div>

      <div class="table-wrap">
        <table class="customer-table">
          <thead>
            <tr>
              <th>顧客名</th>
              <th>電話番号</th>
              <th>メールアドレス</th>
              <th>生年月日</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($customer_list as $customer) : ?>
              <tr>
                <th class="name"><?= $customer['first_name'] . '&nbsp;' . $customer['id'] ?></th>
                <td><?= $customer['tel'] ?></td>
                <td><?= $customer['email'] ?></td>
                <td><?= $customer['birthday'] ?></td>
                <td class="row-link"><a href="customer_detail.php?id=<?= $customer['id'] ?>"></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($customer_list): ?>
      <div class="pagenation-inner">
        <ul class="pagenation-list">
          <?php if ($customer_data->prev_right !== null): ?>
          <li class="pagenation-item">
            <a href="<?= h("?shop_id=$shop_id&right=$customer_data->prev_right&count=$count") ?>" class="pagenation-link prev">&lt;&lt;</a>
          </li>
          <?php endif; ?>
          <?php if ($customer_data->next_left !== null): ?>
          <li class="pagenation-item">
            <a href="<?=h("?shop_id=$shop_id&left=$customer_data->next_left&count=$count")?>" class="pagenation-link next">&gt;&gt;</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
  </div>

</body>

</html>
