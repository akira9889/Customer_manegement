<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');
require_once(__DIR__ . '/Class/RegisterUser.php');
require_once(__DIR__ . '/Class/CustomerList.php');

session_start();

$shop_id = filter_input(INPUT_GET, 'shop_id', FILTER_VALIDATE_INT);

if (!$shop_id) {
  throw new Exception('店舗IDが取得出来ない', 404);
}

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

//未ログインの時はログイン画面へ遷移
if (
  !(isset($_SESSION['USER']) && (isset($_SESSION['USER']['shop_id']) && $_SESSION['USER']['shop_id'] === $shop_id)) &&
  !(isset($_SESSION['USER']['admin_state']) && $_SESSION['USER']['admin_state'] === RegisterCompany::OWNER && isset($shop['company_id']) && $shop['company_id'] === $_SESSION['USER']['id'])
) {
  redirect('/shop_login/?shop_id=' . $shop_id);
}

$left = filter_input(INPUT_GET, 'left', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1],
]);

$right = filter_input(INPUT_GET, 'right', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1],
]);

$count = filter_input(INPUT_GET, 'count', FILTER_VALIDATE_INT, [
  'options' => [
    'default' => 10,
    'min_range' => 1,
    'max_range' => CustomerList::PAGE_COUNT
  ],
]);

$customer_data = new CustomerList($shop_id, $count);

if (is_int($left)) {
  $customer_list = $customer_data->fetchNextCustomerList($left);
} elseif (is_int($right)) {
  $customer_list = $customer_data->fetchPrevCustomerList($right);
} else {
  $customer_list = $customer_data->fetchCustomerList();
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
          <a href="/customer_list/?shop_id=<?= $shop_id ?>" class="sidebar-link active">顧客情報一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="/visit_history/?shop_id=<?= $shop_id ?>" class="sidebar-link">来店履歴一覧</a>
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
        <div class="adding-btn">
          <a href="/register_customer/?shop_id=<?= $shop_id ?>">顧客新規作成<span>＋</span></a>
        </div>
      </div>

      <p class="clusterize-counter customer-table-counter">（<?= count($customer_list) ?>件）</p>
      <div id="scrollArea" class="table-wrap clusterize-scroll">
        <table class="customer-table">
          <thead>
            <tr>
              <th>顧客名</th>
              <th>電話番号</th>
              <th>メールアドレス</th>
              <th>生年月日</th>
            </tr>
          </thead>
          <tbody id="contentArea" class="clusterize-content">
            <?php foreach ($customer_list as $customer) : ?>
              <tr class="clusterize-no-data">
                <th class="name"><?= $customer['last_name'] . '&nbsp;' . $customer['first_name'] ?></th>
                <td><?= $customer['tel'] ?></td>
                <td><?= $customer['email'] ?></td>
                <td><?= $customer['birthday'] ?></td>
                <td class="row-link" data-id="id=<?= $customer['id'] ?>"><a href="/customer_detail/?id=<?= $customer['id'] ?>"></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($customer_list) : ?>
        <div class="pagenation-inner">
          <ul class="pagenation-list">
            <?php if ($customer_data->prev_right !== null) : ?>
              <li class="pagenation-item">
                <a href="<?= h("?shop_id=$shop_id&right=$customer_data->prev_right&count=$count") ?>" class="pagenation-link prev">&lt;&lt;</a>
              </li>
            <?php endif; ?>
            <?php if ($customer_data->next_left !== null) : ?>
              <li class="pagenation-item">
                <a href="<?= h("?shop_id=$shop_id&left=$customer_data->next_left&count=$count") ?>" class="pagenation-link next">&gt;&gt;</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clusterize.js/0.19.0/clusterize.min.js"></script>
  <script src="/js/script.js"></script>
  <script>
    $(function() {
      $('input').keydown(function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
          return false;
        } else {
          return true;
        }
      });
    });

    let url = new URL(window.location.href);
    // URLSearchParamsオブジェクトを取得
    let params = url.searchParams;

    // getメソッド
    const shop_id = params.get('shop_id');

    const count = (params.get('count')) ? params.get('count') : 100;

    $(document).on('keyup', 'input', function() {
      let left = null;
      let right = null;
      if (search.value === '') {
        let left = null;
        let right = null;
      }
      $.ajax({
        url: '/ajax_search/', //データベースを繋げるファイル
        type: "POST",
        data: {
          search_word: $('input[name=search_word]').val(),
          shop_id: shop_id,
          left: left,
          right: right,
          count: count
        },
        dataType: "json"
      }).done(function(customers) {
        var last_name = ''
        var first_name = ''
        var name = ''
        var last_kana = ''
        var first_kana = ''
        var kana = ''
        var tel = ''
        var email = ''
        var birthday = ''
        var information = ''

        var rows = [],
          search = document.getElementById('search');

        for (let customer of customers) {
          rows.push({
            values: [
              name, customer['last_name'] + customer['first_name'],
              kana, customer['last_kana'] + customer['first_kana'],
              tel, customer['tel'],
              email, customer['email'],
              birthday, customer['birthday'],
              information, customer['information']
            ],
            markup: '<tr>' +
              '<th class="name">' + customer['last_name'] + '&nbsp;' + customer['first_name'] + '</th>' +
              '<td>' + customer['tel'] + '</td>' +
              '<td>' + customer['email'] + '</td>' +
              '<td>' + customer['birthday'] + '</td>' +
              '<td class="row-link"}"><a href="/customer_detail/?id=' + customer['id'] + '"></a></td>' +
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
