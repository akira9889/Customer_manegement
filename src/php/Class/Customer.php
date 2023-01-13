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

    public function fetchCustomerKeepBottle()
    {
        $sql = "SELECT k.customer_id as customer_id, b.name, k.bottle_num
                FROM keep_bottle AS k
                INNER JOIN bottle AS b
                ON b.shop_id = k.shop_id
                AND k.bottle_id = b.id
                WHERE customer_id = :customer_id";

        $options = ['customer_id' => $this->customer_id];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute();
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
