<?php
require_once __DIR__ . '/../lib/ExecuteMySql.php';

class RegisterUser
{
    use Traits\EncryptTrait;

    public const STORE_MANEGER = 2;

    private readonly string $name;

    private readonly string $password;

    private readonly string $confirm_password;

    private ?int $admin_state;

    private int $shop_id;

    private Validation $validation;

    public function __construct(array $new_user_data)
    {
        foreach ($new_user_data as $key => $value) {
            $this->{$key} = $value;
        }

        if (isset($this->admin_state)) {
            $this->admin_state = self::STORE_MANEGER;
        }

        $this->validation = new Validation();
    }

    public function registerUser(): void
    {
        $this->validate();

        if (empty($this->getErrors())) {
            $sql = "INSERT INTO `users` (`shop_id`, `name`, `password`, `admin_state`)
                    VALUES(:shop_id, :name, :password, :admin_state)";

            $options = [
                'shop_id' => $this->shop_id,
                'name' => $this->name,
                'password' => $this->encrypt($this->password),
                'admin_state' => $this->admin_state
            ];

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();

            redirect('/user_list/?shop_id=' . $this->shop_id);
            exit;
        }
    }

    public function fetchUser(): ?array
    {
        $sql = "SELECT `id`, `name`
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

    private function validate(): void
    {
        $data = [
            'user_name' =>  $this->name,
            'password' =>  $this->password,
            'confirm_password' => $this->confirm_password
        ];

        $rules = [
            'user_name' => 'required|maxlength:20',
            'password' => 'required|minlength:6|maxlength:20',
            'confirm_password' => 'required'
        ];

        $this->validation->validate($data, $rules);

        $user = $this->fetchUser();

        if ($user) {
            $this->validation->addErros('user_name', 'こちらの「' . $this->name . '」という名前は使われています。');
        }

        if (!$user && ($this->name && $this->password !== $this->confirm_password)) {
            $this->validation->addErros('password', 'パスワードが一致しませんでした。');
        }
    }

    public function getErrors(): array
    {
        return $this->validation->getErrors();
    }
}
