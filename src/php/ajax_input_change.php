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

$birthday = '';
if ($birthday_year && $birthday_month && $birthday_date) {
    $birthday = $birthday_year . '-' . $birthday_month . '-' . $birthday_date;
}

$validation = new Validation();

$data = [
    'first_name' => $first_name,
    'last_name' => $last_name,
    'first_kana' => $first_kana,
    'last_kana' => $last_kana,
    'birthday' => $birthday,
    'email' => $email,
    'tel' => $tel
    ];

$rules = [
    'first_name' => 'required|maxlength:20|japanese',
    'last_name' => 'required|maxlength:20|japanese',
    'first_kana' => 'required|maxlength:20|kana',
    'last_kana' => 'required|maxlength:20|kana',
    'email' => 'required|email',
    'birthday' => 'required|date',
    'tel' => 'required|tel'
];

$validation->validate($data, $rules);

header("Content-type: application/json; charset=UTF-8");
//JSONデータを出力
echo json_encode($validation->getErrors());
