<?php
require_once __DIR__ . '/lib/ExecuteMySql.php';
require_once(__DIR__ . '/Class/CustomerList.php');

$search_word = filter_input(INPUT_POST, 'search_word');

$shop_id = filter_input(INPUT_POST, 'shop_id', FILTER_VALIDATE_INT);

$yyyymm = filter_input(INPUT_POST, 'yyyymm');

$left = filter_input(INPUT_POST, 'left', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

$right = filter_input(INPUT_POST, 'right', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

$count = filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => CustomerList::PAGE_COUNT],
]) ?: CustomerList::PAGE_COUNT;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($search_word) {
        $sql = "SELECT v.`date`, c.*, CONCAT(c.`last_name`, '　', c.`first_name`) as `name`, v.`memo`
                FROM `customers` c
                INNER JOIN `visit_histories` v
                ON v.customer_id = c.id
                WHERE c.`shop_id` = :shop_id
                AND DATE_FORMAT(v.`date`, '%Y-%m') = :date
                AND (CONCAT(c.`last_name`, c.`first_name`) LIKE :full_name
                OR CONCAT(c.`last_kana`, c.`first_kana`) LIKE :full_kana
                OR c.`email` LIKE :email
                OR c.`tel` LIKE :tel
                OR c.`birthday` LIKE :birthday
                OR c.`information` LIKE :information
                OR v.`memo` LIKE :memo)
                ORDER BY v.`date` DESC
                ";

        $options = [
            'shop_id' => $shop_id,
            'date' => $yyyymm,
            'full_name' => "{$search_word}%",
            'full_kana' => "{$search_word}%",
            'email' => "{$search_word}%",
            'tel' => "{$search_word}%",
            'birthday' => "{$search_word}%",
            'information' => "%{$search_word}%",
            'memo' => "%{$search_word}%"
        ];

    } elseif (!$search_word) {
        $sql = "SELECT v.`date`, c.*, CONCAT(c.`last_name`, '　', c.`first_name`) as `name`, v.memo
            FROM `visit_histories` v
            INNER JOIN `customers` c
            ON v.`customer_id` = c.`id`
            WHERE v.`shop_id` = :shop_id
            AND DATE_FORMAT(v.`date`, '%Y-%m') = :date
            ORDER BY v.`date` DESC
            ";

        $options = [
            'shop_id' => $shop_id,
            'date' => $yyyymm
        ];
    }

    $customer_data = new ExecuteMySql($sql, $options);
    $customer_list = $customer_data->execute();
    // $customer_list['yyyymm'] = $yyyymm;


    header("Content-type: application/json; charset=UTF-8");

    //JSONデータを出力
    echo json_encode($customer_list);
}
