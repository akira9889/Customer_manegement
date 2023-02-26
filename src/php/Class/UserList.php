<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';

class UserList
{
    public function __construct(private int $shop_id)
    {
        $this->shop_id = $shop_id;
    }

    public function fetchAdminUserList(): array
    {
        $sql = "SELECT `id`, `name`
                FROM `users`
                WHERE `shop_id` = :shop_id
                AND `admin_state` = 2
                ORDER BY `id` ASC";

        $options = [
            'shop_id' => $this->shop_id
        ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute();
    }

    public function fetchCommonUserList(): array
    {
        $sql = "SELECT `id`, `name`
                FROM `users`
                WHERE `shop_id` = :shop_id
                AND `admin_state` IS NULL
                ORDER BY `id` ASC";

        $options = [
            'shop_id' => $this->shop_id
        ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute();
    }
}
