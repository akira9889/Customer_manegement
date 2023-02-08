<?php
require_once __DIR__ . '/lib/ExecuteMySql.php';
require_once(__DIR__ . '/Class/CustomerList.php');

$search_word = filter_input(INPUT_POST, 'search_word');

$shop_id = filter_input(INPUT_POST, 'shop_id', FILTER_VALIDATE_INT);

$yyyymm = filter_input(INPUT_POST, 'date', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9]{4}-[0-9]{2}$/'))) ?: date('Y-m');

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
            $sql = "SELECT *
            FROM `customers`
            WHERE `shop_id` = :shop_id
            AND concat(`last_name`, `first_name`) LIKE :full_name
            OR concat(`last_kana`, `first_kana`) LIKE :full_kana
            OR `email` LIKE :email
            OR `tel` LIKE :tel
            OR `birthday` LIKE :birthday
            OR `information` LIKE :information
            ";

            $options = [
                'shop_id' => $shop_id,
                'full_name' => "{$search_word}%",
                'full_kana' => "{$search_word}%",
                'email' => "{$search_word}%",
                'tel' => "{$search_word}%",
                'birthday' => "{$search_word}%",
                'information' => "%{$search_word}%"
            ];

        $customer_data = new ExecuteMySql($sql, $options);
        $customer_list = $customer_data->execute();
    } else {
        $customer_data = new CustomerList($shop_id, $count);

        if (is_int($left)) {
            $customer_list = $customer_data->fetchNextCustomerList($left);
        } elseif (is_int($right)) {
            $customer_list = $customer_data->fetchPrevCustomerList($right);
        } else {
            $customer_list = $customer_data->fetchCustomerList();
        }
    }

    header("Content-type: application/json; charset=UTF-8");

    //JSONデータを出力
    echo json_encode($customer_list);
}
