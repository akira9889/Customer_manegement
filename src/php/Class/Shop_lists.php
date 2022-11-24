<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';

final class Shop_lists {

    private readonly int $company_id;

    public function __construct(int $company_id) {
        $this->company_id = $company_id;
    }

    public function listShops() {
        $sql = "SELECT *
                FROM shops
                WHERE company_id = :company_id";

        $options = [
            'company_id' => $this->company_id
        ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute();
    }
}
