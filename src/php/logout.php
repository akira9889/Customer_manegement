<?php
require_once('functions.php');

session_start();

if (isset($_SESSION['USER']['admin'])) {
    $redirect_url = '/login.php?company_id=' . $_SESSION['USER']['id'];
    sessionDestroy();
    redirect($redirect_url);
} elseif(isset($_SESSION['USER'])) {
    $redirect_url = '/shop_login.php?shop_id=' . $_SESSION['USER']['shop_id'];
    sessionDestroy();
    redirect($redirect_url);
} else {
    throw new Exception('未ログイン時のログアウト', '500');
}
