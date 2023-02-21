<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';
require_once __DIR__ . '/RegisterCompany.php';

class Login
{
    private readonly string $table_name;

    private readonly string $user_name;

    private readonly string $password;

    public array $err;

    public function __construct(string $user_name, string $password, string $table_name)
    {
        $this->user_name = $user_name;
        $this->password = $password;
        $this->table_name = $table_name;
    }
    //ユーザー取得
    public function fetchUser()
    {
        $sql = "SELECT *
                FROM {$this->table_name}
                WHERE `name` = :name
                LIMIT 1";

        $options = [
                    'name' => $this->user_name
                    ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute()[0] ?? null;
    }

    public function check_login()
    {
        $user = $this->fetchUser();

        //バリデーションチェック
        if (!$user) $this->err['name'] = $this->user_name . 'というアカウントは登録されておりません。';
        if (!$this->user_name) $this->err['name'] = '会社名を入力してください。';
        if ($user && $this->password !== $user['password']) $this->err['password'] = 'パスワードが違います';
        if ($user && !$this->password) $this->err['password'] = 'パスワードを入力してください';

        if ($user && $this->password === $user['password']) {
            $_SESSION['USER'] = $user;
            $_SESSION['USER']['admin_state'] = RegisterCompany::OWNER;
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>
