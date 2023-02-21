<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';

final class ShopList {

    private readonly int $company_id;

    public function __construct(int $company_id) {
        $this->company_id = $company_id;
    }

    public function listShops() {
        $sql = "SELECT shops.id AS shop_id, shops.company_id, prefectures.id AS prefecture_id, prefectures.name AS prefecture, shops.area
                FROM shops
                INNER JOIN prefectures
                ON shops.prefecture_id = prefectures.id
                WHERE company_id = :company_id
                ORDER BY prefecture_id ASC, shop_id ASC";

        $options = [
            'company_id' => $this->company_id
        ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute();
    }
}
