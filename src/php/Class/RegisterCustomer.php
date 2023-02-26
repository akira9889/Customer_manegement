<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/ExecuteMySql.php';
require_once __DIR__ . '/../functions.php';

final class RegisterCustomer
{
    private string $last_name;
    private string $first_name;
    private string $last_kana;
    private string $first_kana;
    private ?string $gender;
    private string $email;
    private string $birthday_year;
    private string $birthday_month;
    private string $birthday_date;
    private string $birthday = '';
    private string $tel;
    private ?string $information;
    private ?int $customer_id = null;
    private ?int $shop_id = null;

    private Validation $validation;

    public function __construct(array $customer_data)
    {
        foreach ($customer_data as $key => $value) {
            if ($key === 'birthday_month' || $key === 'birthday_date') {
                if (ctype_digit($value)) {
                    $this->{$key} = sprintf('%02d', $value);
                } else {
                    $this->{$key} = null_trim($value);
                }
            } elseif ($key === 'shop_id' || $key === 'customer_id') {
                $this->{$key} = (int) null_trim($value);
            } else {
                $this->{$key} = null_trim($value);
            }
        }

        if ($this->birthday_year && $this->birthday_month && $this->birthday_date) {
            $this->birthday = $this->birthday_year.'-'.$this->birthday_month.'-'.$this->birthday_date;
        }

        $this->validation = new Validation();
    }

    public function registerCustomer(): void
    {
        $this->validate();

        if (empty($this->getErrors())) {

            if ($this->customer_id) {
                $sql = "UPDATE `customers`
                        SET `first_name` = :first_name, `last_name` = :last_name, `first_kana` = :first_kana, `last_kana` = :last_kana, `email` = :email, `tel` = :tel, birthday = :birthday, `information` = :information
                        WHERE `id` = :id";

                $options = [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'first_kana' => $this->first_kana,
                    'last_kana' => $this->last_kana,
                    'email' => $this->email,
                    'tel' => $this->tel,
                    'birthday' => $this->birthday,
                    'information' => $this->information,
                    'id' => $this->customer_id
                ];
            } else {
                $sql = "INSERT INTO `customers` (`shop_id`, `first_name`, `last_name`, `first_kana`, `last_kana`, `email`, `tel`, `birthday`, `gender`)
                        VALUES(:shop_id, :first_name, :last_name, :first_kana, :last_kana, :email, :tel, :birthday, :gender)";

                $options = [
                    'shop_id' => $this->shop_id,
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'first_kana' => $this->first_kana,
                    'last_kana' => $this->last_kana,
                    'email' => $this->email,
                    'tel' => $this->tel,
                    'birthday' => $this->birthday,
                    'gender' => $this->gender,
                ];
            }

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();

            $customer_id = $this->fetchCustomerId();

            redirect('customer_detail.php?id=' . $customer_id);
            exit;
        }
    }


    private function validate(): void
    {
        $data = [
            'first_name' =>  $this->first_name,
            'last_name' =>  $this->last_name,
            'first_kana' =>  $this->first_kana,
            'last_kana' =>  $this->last_kana,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'tel' => $this->tel
        ];

        $rules = [
            'first_name' => 'required|maxlength:20|japanese',
            'last_name' => 'required|maxlength:20|japanese',
            'first_kana' => 'required|maxlength:20|kana',
            'last_kana' => 'required|maxlength:20|kana',
            'email' => 'required|email',
            'birthday' => 'required|date',
            'tel' => 'required|tel'
        ];

        if (!$this->customer_id) {
            $data['gender'] = $this->gender;
            $rules['gender'] = 'gender';

            if ($this->fetchCustomerId()) {
                $this->validation->addErros('customer', 'こちらの「' . $this->last_name.' '.$this->first_name . '」様は登録されています。');
            }
        }

        $this->validation->validate($data, $rules);
    }

    public function getErrors(): array
    {
        return $this->validation->getErrors();
    }

    private function fetchCustomerId(): ?int
    {
        $sql = "SELECT `id`
                FROM `customers`
                WHERE CONCAT(`last_name`, `first_name`) = :name
                AND `birthday` = :birthday
                AND `shop_id` = :shop_id
                LIMIT 1";

        $options = [
            'name' => $this->last_name.$this->first_name,
            'birthday' => $this->birthday,
            'shop_id' => $this->shop_id
        ];

        $mysql = new ExecuteMySql($sql, $options);

        return $mysql->execute()[0]['id'] ?? null;
    }
}
