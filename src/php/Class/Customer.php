<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';

class Customer {

    private $customer_id;

    public function __construct(int $customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function fetchCustomerData()
    {
        $sql = "SELECT *
                FROM customers
                WHERE id = :id";

        $options = ['id' => $this->customer_id];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute()[0];
    }

    public static function fetchAge($birthday)
    {
        $now = date('Ymd');

        $birthday = str_replace("-", "", $birthday);

        // 年齢
        $age = floor(($now - $birthday) / 10000);
        return $age;
    }

    public function fetchCustomerHistoriesData()
    {
        $sql = "SELECT *
                FROM visit_histories
                WHERE customer_id = :customer_id
                ORDER BY date DESC
                LIMIT 10";

        $options = ['customer_id' => $this->customer_id];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute();
    }
}
