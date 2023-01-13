<?php
require_once(__DIR__ . '/Class/Customer.php');
// id取得
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// 顧客情報の取得
$customer = new Customer($id);

$customer_data = $customer->fetchCustomerData();

$visit_history_data = $customer->fetchCustomerHistoriesData();

$keep_bottles = $customer->fetchCustomerKeepBottle();

//顧客情報の表示
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

<body class="customer_detail">
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
          <a href="customer_list.php" class="sidebar-link active">顧客情報一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="visit-history.php" class="sidebar-link">来店履歴一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="reserve_list.php" class="sidebar-link">予約一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="register_user.php" class="sidebar-link">設定</a>
        </li>
      </ul>
    </div>

    <div class="main-content">
      <div class="adding-btn">
        <button type="button" class="modal-open">来店履歴追加<span>＋</span></button>
      </div>
      <div class="main-inner">
        <h2 class="main-title">お客様情報</h2>

        <div class="customer-main-detail">
          <div class="customer-name">
            <div class="customer-last-name">
              <ruby>
                <rt><?= $customer_data['last_kana'] ?></rt>
                <rb><?= $customer_data['last_name'] ?></rb>
              </ruby>
            </div>
            <div class="customer-first-name">
              <ruby>
                <rt><?= $customer_data['first_kana'] ?></rt>
                <rb><?= $customer_data['first_name'] ?></rb>
              </ruby>
            </div>
          </div>

          <p><span><?= $customer_data['gender'] ?></span><span><?php echo Customer::fetchAge($customer_data['birthday']); ?>歳</span></p>
        </div>

        <div class="customer-sub-detail">
          <dl>
            <dt>生年月日</dt>
            <dd><?php echo date('Y年m月d日', strtotime($customer_data['birthday'])) ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>メールアドレス</dt>
            <dd><?= $customer_data['email'] ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>電話番号</dt>
            <dd><?= $customer_data['tel'] ?></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>キープボトル銘柄</dt>
            <?php foreach ($keep_bottles as $keep_bottle): ?>
            <dd><?= $keep_bottle['name'] ?><span>（<?= $keep_bottle['bottle_num'] ?>本）</span></dd>
            <?php endforeach; ?>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>メモ</dt>
            <dd><?= $customer_data['information'] ?></dd>
          </dl>
        </div>
        <div class="edit-btn">
          <button type="button">編集</button>
        </div>
      </div>


      <div class="table-wrap">
        <p>（最新10件）</p>
        <table class="customer-table customer-detail-table">
          <thead>
            <tr>
              <th>来店日</th>
              <th>総額</th>
              <th>メモ</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($visit_history_data as $data) : ?>
              <tr>
                <th><?= $data['date'] ?></th>
                <td><?= number_format($data['price']) ?>円</td>
                <td class="memo"><?= $data['memo'] ?></td>
                <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal-container">
    <div class="modal-body">
      <div class="modal-close">×</div>
      <form class="modal-content">
        <div class="modal-date">2022年10月7日</div>
        <div class="modal-price">
          <label>総額</label>
          <input type="text">
          <span>円</span>
        </div>
        <div class="modal-memo">
          <label for="memo">メモ</label>
          <textarea name="memo" rows="10"></textarea>
        </div>
        <div class="modal-btn">
          <button type="submit">変更</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="/js/script.js"></script>
</body>

</html>
