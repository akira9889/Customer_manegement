<?php

require_once __DIR__ . '/../lib/ExecuteMySql.php';

final class RegisterCustomer
{

    public array $err;

    private string $last_name;
    private string $first_name;
    private string $last_kana;
    private string $first_kana;
    private ?string $gender;
    private string $email;
    private string $birthday_year;
    private string $birthday_month;
    private string $birthday_date;
    private string $tel;

    public function __construct(string $last_name, string $first_name, string $last_kana, string $first_kana, ?string $gender, string $email, string $birthday_year, string $birthday_month, string $birthday_date, string $tel)
    {
        $this->last_name = $last_name;
        $this->first_name = $first_name;
        $this->last_kana = $last_kana;
        $this->first_kana = $first_kana;
        $this->gender = $gender;
        $this->email = $email;
        $this->birthday_year = $birthday_year;
        $this->birthday_month = $birthday_month;
        $this->birthday_date = $birthday_date;
        $this->tel = $tel;
    }

    public function registerCustomer()
    {
        $this->validateInputs();

        if (empty($this->err)) {

            $sql = "INSERT INTO customers (shop_id, first_name, last_name, first_kana, last_kana, email, tel, birthday, gender)
                        VALUES(:shop_id, :first_name, :last_name, :first_kana, :last_kana, :email, :tel, :birthday, :gender)";

            $birthday = $this->birthday_year.'-'.$this->birthday_month.'-'.$this->birthday_date;

            $options = [
                'shop_id' => (int) $_GET['shop_id'],
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'first_kana' => $this->first_kana,
                'last_kana' => $this->last_kana,
                'email' => $this->email,
                'tel' => $this->tel,
                'birthday' => $birthday,
                'gender' => $this->gender,
            ];

            $mysql = new ExecuteMySql($sql, $options);

            $mysql->execute();
        }
    }

    private function validateInputs()
    {
        if (!$this->first_name || !$this->last_name) {
            $this->err['name'] = '名前を入力してください。';
        } elseif (!preg_match('/^[ぁ-んァ-ヶｱ-ﾝﾞﾟ一-龠]*$/', $this->first_name) || !preg_match('/^[ぁ-んァ-ヶｱ-ﾝﾞﾟ一-龠]*$/', $this->last_name)) {
            $this->err['name'] = '日本語で入力してください。';
        }

        if (!$this->first_kana || !$this->last_kana) {
            $this->err['kana'] = 'フリガナを入力してください。';
        } elseif (!preg_match('/\A[ァ-ヴー]+\z/u', $this->first_kana) || !preg_match('/\A[ァ-ヴー]+\z/u', $this->last_kana)) {
            $this->err['kana'] = 'カタカナで入力してください。';
        }

        if (!$this->email) {
            $this->err['email'] = 'メールアドレスを入力してください。';
        } elseif (!preg_match('/\A[a-zA-Z0-9_.+-]+@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}\z/u', $this->email)) {
            $this->err['email'] = 'メールアドレスが不正です。';
        }

        if (!$this->gender || !($this->gender === '男性' || $this->gender === '女性')) {
            $this->err['gender'] = '性別を選択してください。';
        }

        if (!$this->birthday_year) {
            $this->err['birthday'] = '生年月日を入力してください。';
        } elseif (!preg_match('/\A19[0-9]{2}|[2-9][0-9]{3}\z/u', $this->birthday_year)) {
            $this->err['birthday'] = '生年月日が不正です。';
        }

        if (!$this->birthday_month) {
            $this->err['birthday'] = '生年月日を入力してください。';
        } elseif (!preg_match('/\A(0[1-9]{1}|1[0-2]{1})\z/u', $this->birthday_month)) {
            $this->err['birthday'] = '生年月日が不正です。';
        }

        if (!$this->birthday_date) {
            $this->err['birthday'] = '生年月日を入力してください。';
        } elseif (!preg_match('/\A(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})\z/u', $this->birthday_date)) {
            $this->err['birthday'] = '生年月日が不正です。';
        }

        if (!$this->tel) {
            $this->err['tel'] = '電話番号を入力してください。';
        } elseif (!preg_match('/\A0\d{9,10}\z/u', $this->tel)) {
            $this->err['tel'] = '電話番号が不正です。';
        }
    }
}
