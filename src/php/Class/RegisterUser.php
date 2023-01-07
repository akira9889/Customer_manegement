<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';

class RegisterUser
{
    private const STORE_MANEGER = 2;

    private readonly string $name;

    private readonly string $password;

    private readonly string $confirm_password;

    private bool $registered_state;

    public array $err;

    public function __construct(string $name, string $password, string $confirm_password, $admin_state = null)
    {
        $this->name = $name;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
        $this->admin_state = $admin_state;
        $this->complete_registered_statestate = FALSE;
    }

    //ユーザー登録
    public function registerUser()
    {
        $this->validateInputs();

        if (empty($this->err)) {
            $sql = "INSERT INTO users (shop_id, name, password, admin_state)
                    VALUES(:shop_id, :name, :password, :admin_state)";

            if (isset($this->admin_state)) {
                $this->admin_state = self::STORE_MANEGER;
            }

            $options = [
                'shop_id' => $_GET['shop_id'],
                'name' => $this->name,
                'password' => $this->password,
                'admin_state' => $this->admin_state
            ];

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();

            $_SESSION['USER'] = $this->fetchUser();
            $_SESSION['USER']['admin'] = self::STORE_MANEGER;
            $this->registered_state = TRUE;
        }
    }

    public function fetchUser() {
        $sql = "SELECT id, name
                FROM users
                WHERE name = :name
                AND shop_id = :shop_id
                LIMIT 1";

        $options = [
            'name' => $this->name,
            'shop_id' => $_GET['shop_id']
        ];

        $mysql = new ExecuteMySql($sql, $options);

        if (!empty($mysql->execute()[0])) {
            return $mysql->execute()[0];
        }
    }

    public function isExistUser()
    {
        $result = $this->fetchUser();
        return isset($result);
    }

    private function validateInputs()
    {
        if ($this->isExistUser()) {
            $this->err['name'] = $this->name . 'という名前は使われております。<br>他の名前をお使いください。';
        }

        if (!$this->name) {
            $this->err['name'] = '名前を入力してください。';
        }

        if (!$this->isExistUser() && !$this->password) {
            $this->err['password'] = 'パスワードを入力してください。';
        }

        if (!$this->isExistUser() && !$this->confirm_password) {
            $this->err['confirm_password'] = '確認用パスワードを入力してください。';
        }

        if (!$this->isExistUser() && ($this->name && $this->password !== $this->confirm_password)) {
            $this->err['confirm_password'] = 'パスワードが一致しませんでした。';
        }

        if (!$this->isExistUser() && ($this->name && $this->password && !$this->confirm_password)) {
            $this->err['confirm_password'] = '確認用パスワードを入力してください';
        }

        if (!$this->isExistUser() && !$this->password) $this->err['password'] = 'パスワードを入力してください';
    }

    public function getRegisterdState() {
        return $this->registered_state;
    }
}
