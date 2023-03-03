<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/ExecuteMySql.php';
require_once __DIR__ . '/Validation.php';

final class RegisterCompany
{
    public const OWNER = 1;

    private string $name;

    private string $password;

    private string $confirm_password;

    private Validation $validation;

    public function __construct(array $company_login_data)
    {
        foreach ($company_login_data as $key => $value) {
            $this->{$key} = $value;
        }

        $this->validation = new Validation();
    }

    //ユーザー登録
    public function registerUser(): void
    {
        $this->validate();

        if (empty($this->getErrors())) {
            $sql = "INSERT INTO companies (`name`, `password`)
                    VALUES(:name, :password)";

            $options = [
                'name' => $this->name,
                'password' => $this->password,
            ];

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();

            $company = $this->fetchCompany();

            $_SESSION['USER'] = $company;
            $_SESSION['USER']['admin_state'] = self::OWNER;

            redirect('/shop_list/?company_id=' . $company['id']);
            exit;
        }
    }

    private function validate(): void
    {
        $data = [
            'company_name' =>  $this->name,
            'password' =>  $this->password,
            'confirm_password' => $this->confirm_password
        ];

        $rules = [
            'company_name' => 'required|maxlength:20',
            'password' => 'required|minlength:6|maxlength:20',
            'confirm_password' => 'required'
        ];

        $this->validation->validate($data, $rules);

        $company = $this->fetchCompany();

        if ($company) {
            $this->validation->addErros('company_name', 'こちらの「' . $this->name . '」という会社名は使われています。');
        }

        if (!$company && ($this->name && $this->password !== $this->confirm_password)) {
            $this->validation->addErros('password', 'パスワードが一致しませんでした。');
        }
    }

    public function fetchCompany(): ?array
    {
        $sql = "SELECT `id`, `name`
                FROM companies
                WHERE name = :name
                LIMIT 1";

        $options = [
            'name' => $this->name
        ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute()[0] ?? null;
    }

    public function getErrors(): array
    {
        return $this->validation->getErrors();
    }
}
