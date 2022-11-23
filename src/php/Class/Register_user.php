<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';

final class RegisterUser
{
    //ユーザー登録
    public function RegisterUser(string $name, string $password, string $confirm_password)
    {
        $sql = "SELECT name FROM company
                WHERE name = :name
                LIMIT 1";

        $options = [
            'name' => $name
        ];

        $mysql = new ExecuteMySql($sql, $options);
        $company = $mysql->execute();

        if (!$company[0] && $password === $confirm_password) {
            $sql = "INSERT INTO company (name, password)
                    VALUES(:name, :password)";

            $options = [
                'name' => $name,
                'password' => $password
            ];

            $mysql = new ExecuteMySql($sql, $options);

            return $mysql->execute();
        } else {
            throw new Exception('ユーザー登録に失敗しました。');
        }
    }
}
