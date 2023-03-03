<?php
require_once(dirname(__FILE__) . '/mapping.php');
require_once('functions.php');
try {
// var_dump($_REQUEST['path']);
// exit;
$request_path = isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI']: '';

$param = strstr($request_path, '?');
if ($param) {
    $request_path = str_replace($param, '', $request_path);
}

// 末尾にスラッシュが付いていない場合は強制的に付ける
if (!str_ends_with($request_path, '/')) {
$request_path .= '/';
}

// mapping.phpに従って対象PHPに処理を移譲
if (isset($url_list[$request_path])) {
// アクセスされたURLのプログラムに処理を移譲
include(dirname(__FILE__) . $url_list[$request_path]);
} else {
// 存在しないパスへのアクセスはエラーページへ
throw new Exception('存在しないパスへのアクセス:' . $request_path, 700);
}

} catch (Exception $e) {
header('Location: /error.php');
exit;
}
