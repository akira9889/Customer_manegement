<?php
// require_once(dirname(__FILE__) . '/mapping.php');
// require_once('functions.php');
// try {
// session_start();

// $request_path = isset($_REQUEST['path'])? $_REQUEST['path']: '';

// // 末尾にスラッシュが付いていない場合は強制的に付ける
// if (!str_ends_with($request_path, '/')) {
// $request_path .= '/';
// }

// // mapping.phpに従って対象PHPに処理を移譲
// if (isset($url_list[$request_path])) {
// // アクセスされたURLのプログラムに処理を移譲
// include(dirname(__FILE__) . $url_list[$request_path]);
// } else {
// // 存在しないパスへのアクセスはエラーページへ
// throw new Exception('存在しないパスへのアクセス:' . $request_path, 700);
// }

// } catch (Exception $e) {
// header('Location: /error-404.html');
// exit;
// }
