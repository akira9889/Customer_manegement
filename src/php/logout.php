<?php
require_once('functions.php');
require_once(__DIR__ . '/Class/RegisterCompany.php');


session_start();

if ($_SESSION['USER']['admin_state'] === RegisterCompany::OWNER) {
    $redirect_url = '/login/';
    sessionDestroy();
    redirect($redirect_url);
} elseif($_SESSION['USER']['admin_state'] !== RegisterCompany::OWNER) {
    $redirect_url = '/shop_login/?shop_id=' . $_SESSION['USER']['shop_id'];
    sessionDestroy();
    redirect($redirect_url);
} else {
    throw new Exception('未ログイン時のログアウト', '500');
}
