<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';
require_once __DIR__ . '/RegisterCompany.php';

class Login
{
    protected string $name;

    protected string $password;

    protected Validation $validation;

    protected bool $login_flag = FALSE;

    public function __construct(array $login_data)
    {
        foreach ($login_data as $key => $value) {
            $this->{$key} = null_trim($value);
        }

        $this->validation = new Validation();
    }

    public function login(): void
    {
        $this->validate();

        if ($this->login_flag) {
            $company = $this->fetchUser();
            $_SESSION['USER'] = $company;
            $_SESSION['USER']['admin_state'] = RegisterCompany::OWNER;
            redirect('/shop_list/' . '?company_id=' . $company['id']);
            exit;
        }
    }

    protected function fetchUser(): ?array
    {
        $sql = "SELECT *
                FROM `companies`
                WHERE `name` = :name
                LIMIT 1";

        $options = ['name' => $this->name];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute()[0] ?? null;
    }

    protected function validate(): void
    {
        $data = [
            'user_name' => $this->name,
            'password' => $this->password
        ];

        $rules = [
            'user_name' => 'required',
            'password' => 'required'
        ];

        $this->validation->validate($data, $rules);

        if (empty($this->getErrors())) {
            $company = $this->fetchUser();

            if (!$company) {
                $this->validation->addErros('user_name', 'こちらの「' . $this->name . '」というユーザーは登録されていません。');
            }

            if ($company && $this->password !== $company['password']) {
                $this->validation->addErros('password', 'パスワードが違います。');
            }

            if (empty($this->getErrors()) && $company['password'] === $this->password) {
                $this->login_flag = TRUE;
            }
        }
    }

    public function getErrors(): array
    {
        return $this->validation->getErrors();
    }
}

?>
