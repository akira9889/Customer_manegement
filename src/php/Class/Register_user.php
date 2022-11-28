<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';

final class RegisterUser
{
    private readonly string $name;

    private readonly string $password;

    private readonly string $confirm_password;

    private readonly string $table_name;

    public array $err;

    public function __construct(string $name, string $password, string $confirm_password, string $table_name)
    {
        $this->name = $name;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
        $this->table_name = $table_name;
    }

    //ユーザー登録
    public function RegisterUser()
    {
        $this->validateInputs();

        if (empty($this->err)) {
            $sql = "INSERT INTO {$this->table_name} (name, password)
                    VALUES(:name, :password)";

            $options = [
                'name' => $this->name,
                'password' => $this->password
            ];

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();

            $_SESSION['USER'] = $this->fetchUser();
            $_SESSION['USER']['admin'] = 1;
            return TRUE;
        }
        return FALSE;
    }

    public function fetchUser() {
        $sql = "SELECT id, name
                FROM {$this->table_name}
                WHERE name = :name
                LIMIT 1";

        $options = [
            'name' => $this->name
        ];

        $mysql = new ExecuteMySql($sql, $options);

        if (!empty($mysql->execute()[0])) {
            return $mysql->execute()[0];
        }
    }

    private function isExistUser()
    {
        $result = $this->fetchUser();
        return isset($result);
    }

    private function validateInputs()
    {
        if ($this->isExistUser()) {
            $this->err['name'] = $this->name . 'というユーザー名は使われております。<br>他のユーザー名をお使いください。';
        }

        if (!$this->name) {
            $this->err['name'] = '会社名を入力してください。';
        }

        if (!$this->password) {
            $this->err['password'] = 'パスワードを入力してください。';
        }

        if (!$this->confirm_password) {
            $this->err['confirm_password'] = '確認用パスワードを入力してください。';
        }

        if ($this->name && $this->password !== $this->confirm_password) {
            $this->err['confirm_password'] = 'パスワードが一致しませんでした。';
        }

        if ($this->name && !$this->password) $this->err['password'] = 'パスワードを入力してください';
    }
}
