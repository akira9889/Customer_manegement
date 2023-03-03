<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';

class Customer {

    public function __construct(private int $customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function fetchCustomerData(): ?array
    {
        $sql = "SELECT *
                FROM `customers`
                WHERE `id` = :id";

        $options = ['id' => $this->customer_id];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute()[0] ?? null;
    }

    public static function fetchAge(string $birthday): int
    {
        $now = date('Ymd');

        $birthday = str_replace("-", "", $birthday);

        $age = floor(($now - $birthday) / 10000);
        return $age;
    }

    public function fetchCustomerHistoriesData(): array
    {
        $sql = "SELECT *
                FROM `visit_histories`
                WHERE `customer_id` = :customer_id
                ORDER BY `date` DESC
                LIMIT 10";

        $options = ['customer_id' => $this->customer_id];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute();
    }
}
