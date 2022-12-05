<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/ExecuteMySql.php';
// require_once __DIR__ . '/RegisterUser.php';


// class RegisterCompany extends RegisterUser
// {
//     private const OWNER = 1;

//     public function __construct(string $name, string $password, string $confirm_password)
//     {
//         $this->name = $name;
//         $this->password = $password;
//         $this->confirm_password = $confirm_password;
//         $this->admin_state = self::OWNER;
//     }

//     public function registerUser()
//     {
//         $this->validateInputs();

//         if (empty($this->err)) {
//             $sql = "INSERT INTO company (shop_id, name, password, admin_state)
//                     VALUES(:shop_id, :name, :password, :admin_state)";

//             $options = [
//                 'name' => $this->name,
//                 'password' => $this->password,
//                 'admin_state' => $this->admin_state
//             ];

//             $mysql = new ExecuteMySql($sql, $options);

//             $mysql->execute();

//             $_SESSION['USER'] = $this->fetchUser();
//             $_SESSION['USER']['admin'] = self::OWNER;
//             return TRUE;
//         }
//         return FALSE;
//     }

//     private function validateInputs()
//     {
//         if ($this->isExistUser()) {
//             $this->err['name'] = $this->name . 'という会社名は使われております。<br>他のユーザー名をお使いください。';
//         }

//         if (!$this->name) {
//             $this->err['name'] = '名前を入力してください。';
//         }

//         if (!$this->password) {
//             $this->err['password'] = 'パスワードを入力してください。';
//         }

//         if (!$this->confirm_password) {
//             $this->err['confirm_password'] = '確認用パスワードを入力してください。';
//         }

//         if ($this->name && $this->password !== $this->confirm_password) {
//             $this->err['confirm_password'] = 'パスワードが一致しませんでした。';
//         }

//         if ($this->name && !$this->password) $this->err['password'] = 'パスワードを入力してください';
//     }
// }

class RegisterCompany
{
    private const OWNER = 1;

    private readonly string $name;

    private readonly string $password;

    private readonly string $confirm_password;

    public array $err;

    public function __construct(string $name, string $password, string $confirm_password)
    {
        $this->name = $name;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
        $this->admin_state = self::OWNER;
    }

    //ユーザー登録
    public function registerUser()
    {
        $this->validateInputs();

        if (empty($this->err)) {
            $sql = "INSERT INTO companies (shop_id, name, password, admin_state)
                    VALUES(:shop_id, :name, :password, :admin_state)";

            $options = [
                'name' => $this->name,
                'password' => $this->password,
                'admin_state' => $this->admin_state
            ];

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();

            $_SESSION['USER'] = $this->fetchUser();
            $_SESSION['USER']['admin'] = self::OWNER;
            return TRUE;
        }
        return FALSE;
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

    public function fetchUser()
    {
        $sql = "SELECT id, name
                FROM companies
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

    public function isExistUser()
    {
        $result = $this->fetchUser();
        return isset($result);
    }

}
