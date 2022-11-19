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
  <header>
    <div class="header">
      <h1>Sample shop</h1>
    </div>
  </header>

  <div class="content">
    <div class="sidebar">
      <ul class="sidebar-list">
        <li class="sidebar-item">
          <a href="customer_list.php" class="sidebar-link active">顧客情報一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="visit-history.php" class="sidebar-link active2">来店履歴一覧</a>
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
                <rt>スズキ</rt>
                <rb>鈴木</rb>
              </ruby>
            </div>
            <div class="customer-first-name">
              <ruby>
                <rt>タロウ</rt>
                <rb>太郎</rb>
              </ruby>
            </div>
          </div>

          <p><span>男性</span><span>24歳</span></p>
        </div>

        <div class="customer-sub-detail">
          <dl>
            <dt>生年月日</dt>
            <dd>1998年09月09日</dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>メールアドレス</dt>
            <dd>test@gmail.com</dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>電話番号</dt>
            <dd>000-0000-0000</dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>キープボトル銘柄</dt>
            <dd>ジャックダニエルブラック<span>（1本）</span></dd>
            <dd>いいちこ<span>（1本）</span></dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>未払い料</dt>
            <dd>100,000円</dd>
          </dl>
        </div>
        <div class="customer-sub-detail">
          <dl>
            <dt>メモ</dt>
            <dd>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</dd>
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
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
            <tr>
              <th>1990-09-09</th>
              <td>10,000円</td>
              <td class="memo">テキストテキストテキストテキスト</td>
              <td><button type="button" class="modal-open"><i class="fa-solid fa-book-open"></i></button></td>
            </tr>
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
