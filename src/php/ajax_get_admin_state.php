<?php
require_once __DIR__ . '/Class/RegisterUser.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$sql = "SELECT `admin_state`
        FROM `users`
        WHERE `id` = :id";

$options = ['id' => $id];

$mysql = new ExecuteMySql($sql, $options);

if (isset($mysql->execute()[0]['admin_state'])) {
    $is_admin = $mysql->execute()[0]['admin_state'] === RegisterUser::STORE_MANEGER;
} else {
    $is_admin = FALSE;
}

header("Content-type: application/json; charset=UTF-8");

echo $is_admin;
