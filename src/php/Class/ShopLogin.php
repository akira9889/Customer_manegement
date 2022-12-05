<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';
require_once(__DIR__ . '/Login.php');

final class ShopLogin extends Login {

    private readonly string $table_name;

    private readonly string $user_name;

    private readonly string $password;

    public function __construct(string $user_name, string $password, string $table_name)
    {
        $this->user_name = $user_name;
        $this->password = $password;
        $this->table_name = $table_name;
    }

    private function getShopId() {
        return $_GET['shop_id'];
    }

    //ユーザー取得
    public function fetchUser() {
        $sql = "SELECT *
                FROM {$this->table_name}
                WHERE name = :name
                AND shop_id = :shop_id
                LIMIT 1";

        $options = [
            'name' => $this->user_name,
            'shop_id' => $this->getShopId()
        ];

        $mysql = new ExecuteMySql($sql, $options);

        if (!empty($mysql->execute()[0])) {
            return $mysql->execute()[0];
        }
    }

    public function checkLogin()
    {
        $user = $this->fetchUser();

        //バリデーションチェック
        if (!$user) $this->err['name'] = $this->user_name . 'というアカウントは登録されておりません。';
        if (!$this->user_name) $this->err['name'] = 'ユーザー名を入力してください。';
        if ($user && $this->password !== $user['password']) $this->err['password'] = 'パスワードが違います';
        if ($user && !$this->password) $this->err['password'] = 'パスワードを入力してください';

        if ($user && $this->password === $user['password']) {
            $_SESSION['USER'] = $user;
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
