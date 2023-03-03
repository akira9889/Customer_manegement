<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/CustomerList.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');
require_once(__DIR__ . '/Class/RegisterUser.php');

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

//ログインされていない場合はログイン画面へ
if (
  !(isset($_SESSION['USER']) && (isset($_SESSION['USER']['shop_id']) && $_SESSION['USER']['shop_id'] === $shop_id)) &&
  !(isset($_SESSION['USER']['admin_state']) && $_SESSION['USER']['admin_state'] === RegisterCompany::OWNER && isset($shop['company_id']) && $shop['company_id'] === $_SESSION['USER']['id'])
) {
  redirect('/shop_login.php?shop_id=' . $shop_id);
}

$yyyymm = filter_input(INPUT_GET, 'date', FILTER_DEFAULT) ?: date('Y-m');

$count = filter_input(INPUT_GET, 'count', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1, 'max_range' => CustomerList::PAGE_COUNT],
]) ?: CustomerList::PAGE_COUNT;

$customer_data = new CustomerList($shop_id, $count);
$visit_histories_data = $customer_data->fetchVisitHistoriesData($yyyymm);

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

<body>
  <header class="header">
    <div class="header-inner">
      <div class="header-content">
        <h1 class="header-logo"><?= $shop['name'] . '  ' . $shop['area'] ?></h1>
        <nav id="header-nav" class="header-nav">
          <ul id="header-list" class="header-list">
            <li class="header-item">
              <a class="header-item-link" href="/logout/"><i class="fa-solid fa-right-from-bracket"></i></a>
            </li>
        </nav>
      </div>
    </div>
  </header>

  <div class="content">
    <div class="sidebar">
      <ul class="sidebar-list">
        <li class="sidebar-item">
          <a href="/customer_list/?shop_id=<?= $shop_id ?>" class="sidebar-link">顧客情報一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="/visit_history/?shop_id=<?= $shop_id ?>" class="sidebar-link active">来店履歴一覧</a>
        </li>
        <?php if ($admin_state === RegisterCompany::OWNER || $admin_state === RegisterUser::STORE_MANEGER) : ?>
          <li class="sidebar-item has-sub-menu">
            <p class="sidebar-link">設定</p>
            <ul class="sub-menu">
              <li class="sub-item sidebar-item"><a class="sidebar-link" href="/register_user/?shop_id=<?= $shop_id ?>">スタッフ登録</a></li>
              <li class="sub-item sidebar-item"><a class="sidebar-link" href="/user_list/?shop_id=<?= $shop_id ?>">スタッフ一覧</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="main-content">
      <div class="main-head">
        <form class="search-container">
          <input id="search" name="search_word" type="text" placeholder="検索" class="search">
        </form>
        <form class="select-container" method="get">
          <input type="hidden" name="shop_id" value="<?= $shop_id ?>">
          <select name="date" id="select_date" class="select_date" onchange="submit(this.form)">
            <option value="<?= date('Y-m') ?>"><?= date('Y/m') ?></option>
            <?php for ($i = 1; $i < 12; $i++) : ?>
              <?php $target_yyyymm = getPrevDate(date('Y-m'), $i) ?>
              <option value="<?= date('Y-m', strtotime($target_yyyymm)) ?>" <?php if ($yyyymm == date('Y-m', strtotime($target_yyyymm))) echo 'selected' ?>><?= date('Y/m', strtotime($target_yyyymm)) ?></option>
            <?php endfor; ?>
          </select>
        </form>
      </div>

      <p class="clusterize-counter customer-table-counter">（<?= count($visit_histories_data) ?>件）</p>
      <div id="scrollArea" class="table-wrap clusterize-scroll">
        <table class="customer-table visit-history-table">
          <thead>
            <tr>
              <th>最終来店日</th>
              <th>顧客名</th>
              <th>当日メモ</th>
            </tr>
          </thead>
          <tbody id="contentArea" class="clusterize-content">
            <?php foreach ($visit_histories_data as $customer) : ?>
              <tr class="clusterize-no-data">
                <th><?= $customer['date'] ?></th>
                <td><?= $customer['name'] ?></td>
                <td class="memo"><?= $customer['memo'] ?></td>
                <td class="row-link"><a href="/customer_detail/?id=<?= $customer['id'] ?>"></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clusterize.js/0.19.0/clusterize.min.js"></script>
  <script src="/js/script.js"></script>
  <script>
    const search = document.getElementById('search');

    let url = new URL(window.location.href);
    // URLSearchParamsオブジェクトを取得
    let params = url.searchParams;

    // getメソッド
    const shop_id = params.get('shop_id');

    const count = (params.get('count')) ? params.get('count') : 100;

    $(document).on('keyup', 'input', function() {
      let yyyymm = $('#select_date').val();
      $.ajax({
        url: '/ajax_visit_history/', //データベースを繋げるファイル
        type: "POST",
        data: {
          search_word: $('#search').val(),
          shop_id: shop_id,
          yyyymm: yyyymm,
        },
        dataType: "json"
      }).done(function(customers) {
        var date = ''
        var name = ''
        var kana = ''
        var tel = ''
        var email = ''
        var birthday = ''
        var information = ''
        var memo = ''

        var rows = [];

        for (let customer of customers) {
          rows.push({
            values: [
              date, customer['date'],
              name, customer['last_name'] + customer['first_name'],
              kana, customer['last_kana'] + customer['first_kana'],
              tel, customer['tel'],
              email, customer['email'],
              birthday, customer['birthday'],
              information, customer['information'],
              memo, customer['memo']
            ],
            markup: '<tr>' +
              '<th>' + customer['date'] + '</th>' +
              '<td>' + customer['name'] + '</td>' +
              '<td class="memo">' + customer['memo'] + '</td>' +
              '<td class="row-link"}"><a href="customer_detail.php?id=' + customer['id'] + '"></a></td>' +
              '</tr>',
            active: true
          });
        }

        var filterRows = function(rows) {
          var results = [];
          for (var i = 0, ii = rows.length; i < ii; i++) {
            if (rows[i].active) results.push(rows[i].markup)
          }
          return results;
        }

        var clusterize = new Clusterize({
          rows: filterRows(rows),
          scrollId: 'scrollArea',
          contentId: 'contentArea',
        });

        var onSearch = function() {
          if (search.value !== '') {

            for (var i = 0, ii = rows.length; i < ii; i++) {
              var suitable = false;
              for (var j = 0, jj = rows[i].values.length; j < jj; j++) {
                if (rows[i].values[j].toString().indexOf(search.value) + 1)
                  suitable = true;
              }
              rows[i].active = suitable;
            }
          }
          clusterize.update(filterRows(rows));

          $('p.customer-table-counter').text('（' + clusterize.getRowsAmount() + '件）')

          if (search.value === '') {
            $('div.pagenation-inner').css({
              'opacity': '1',
              'pointer-events': 'auto'
            });
          } else {
            $('div.pagenation-inner').css({
              'opacity': '0',
              'pointer-events': 'none'
            });
          }
        }

        onSearch();

      }).fail(function() {
        alert("error"); //通信失敗時
      });
    });
  </script>
</body>

</html>
