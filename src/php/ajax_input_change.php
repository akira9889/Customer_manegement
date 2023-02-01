<?php
require_once(__DIR__ . '/Class/Validation.php');

$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$first_kana = filter_input(INPUT_POST, 'first_kana');
$last_kana = filter_input(INPUT_POST, 'last_kana');
$email = filter_input(INPUT_POST, 'email');
$birthday_year = filter_input(INPUT_POST, 'birthday_year');
$birthday_month = filter_input(INPUT_POST, 'birthday_month');
$birthday_date = filter_input(INPUT_POST, 'birthday_date');
$tel = filter_input(INPUT_POST, 'tel');

$validation = new Validation;

$validation->checkNameFormat($first_name, $last_name);
$validation->checkKanaFormat($first_kana, $last_kana);
$validation->checkBirthdayFormat($birthday_year, $birthday_month, $birthday_date);
$validation->checkMailFormat($email);
$validation->checkTelFormat($tel);

header("Content-type: application/json; charset=UTF-8");
//JSONデータを出力
echo json_encode($validation->err);
