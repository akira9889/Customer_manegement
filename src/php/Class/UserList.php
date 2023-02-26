<?php
require_once __DIR__ . '/RegisterUser.php';

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

    public function deleteUser(int $id): void
    {
        $sql = "DELETE FROM `users`
                WHERE `shop_id` = :shop_id
                AND `id` = :id";

        $options = [
            'shop_id' => $this->shop_id,
            'id' => $id
        ];

        $mysql = new ExecuteMySql($sql, $options);

        $mysql->execute();
    }

    public function updateUser(int $id, ?string $admin_state): void
    {
        $sql = "UPDATE `users`
                SET `admin_state` = :admin_state
                WHERE `shop_id` = :shop_id
                AND `id` = :id";

        $admin_state = isset($admin_state) ? RegisterUser::STORE_MANEGER : null;

        $options = [
            'id' => $id,
            'shop_id' => $this->shop_id,
            'admin_state' => $admin_state
        ];

        $mysql = new ExecuteMySql($sql, $options);

        $mysql->execute();
    }
}
