<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';
require_once(__DIR__ . '/Login.php');

class ShopLogin extends Login {

    private int $shop_id;

    public function __construct(array $login_data)
    {
        foreach ($login_data as $key => $value) {
            $this->{$key} = null_trim($value);
        }

        $this->shop_id = filter_input(INPUT_GET, 'shop_id', FILTER_VALIDATE_INT);

        $this->validation = new Validation();
    }

    public function login(): void
    {
        $this->validate();

        if ($this->login_flag) {
            $user = $this->fetchUser();
            $_SESSION['USER'] = $user;
            redirect('/customer_list/?shop_id=' . $user['shop_id']);
            exit;
        }
    }

    public function fetchUser(): ?array
    {
        $sql = "SELECT *
                FROM `users`
                WHERE `name` = :name
                AND `shop_id` = :shop_id
                LIMIT 1";

        $options = [
            'name' => $this->name,
            'shop_id' => $this->shop_id
        ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute()[0] ?? null;
    }
}
