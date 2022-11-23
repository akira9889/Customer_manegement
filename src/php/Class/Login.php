<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';

final class Login
{
    public readonly string $table_name;

    public array $err;

    public function __construct(string $table_name) {
        $this->table_name = $table_name;
    }
    //ユーザー取得
    public function fetchUser(string $name) {
        $sql = "SELECT *
                FROM {$this->table_name}
                WHERE name = :name
                LIMIT 1";

        $options = [
                    'name' => $name,
                    ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute()[0];
    }

    public function login(string $name, string $password, string $url) {
        $user = $this->fetchUser($name);

        if (!$user) $this->err['name'] = $name . 'というユーザー名は登録されておりません。';
        if (!$name) $this->err['name'] = '会社名を入力してください。';

        if ($user && $password === $user['password']) {
            $_SESSION['USER'] = $user;
            $_SESSION['USER']['admin'] = 1;
            $this->redirect($url);
        }

        if ($user && $password !== $user['password']) $this->err['password'] = 'パスワードが違います';
        if ($user && !$password) $this->err['password'] = 'パスワードを入力してください';
    }

    private function redirect(string $url) {
        header('Location:' . $url);
        exit;
    }

}

?>
