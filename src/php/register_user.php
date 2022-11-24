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

  <title>管理者登録</title>
</head>

<body class="register_user">
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
          <a href="customer_list.php" class="sidebar-link">顧客情報一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="visit-history.php" class="sidebar-link active2">来店履歴一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="reserve_list.php" class="sidebar-link">予約一覧</a>
        </li>
        <li class="sidebar-item">
          <a href="register_user.php" class="sidebar-link active">設定</a>
        </li>
      </ul>
    </div>

    <div class="main-content">
      <div class="main-inner">
        <h2 class="main-title">ユーザー登録</h2>

        <form action="" class="register-form">
          <ul class="register-list">
            <li class="register-item">
              <label for="last-name">ユーザ名</label>
              <div class="register-input">
                <input type="text" name="name" placeholder="ユーザ名">
              </div>
            </li>
            <li class="register-item">
              <label for="last-name">パスワード</label>
              <div class="register-input">
                <input type="text" name="password" placeholder="パスワード">
              </div>
            </li>
            <li class="register-item">
              <label for="last-name">パスワード確認</label>
              <div class="register-input">
                <input type="text" name="confirm_password" placeholder="パスワード確認">
              </div>
            </li>
            <div class="register-btn">
              <button type="submit">登録</button>
            </div>
        </form>
      </div>
    </div>
  </div>

</body>

</html>
