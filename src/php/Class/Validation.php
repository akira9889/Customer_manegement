<?php

final class Validation
{
    public ?array $err;

    public function __construct()
    {
        $this->err = [];
    }

    public static function isJapaneseString($input)
    {
        return preg_match('/^[ぁ-んァ-ヶｱ-ﾝﾞﾟ一-龠]*$/', $input);
    }

    public static function isKanaString($input)
    {
        return preg_match('/\A[ァ-ヴー]+\z/u', $input);
    }

    public function checkNameFormat($first_name, $last_name)
    {
        if (isset($this->getErrors()['']))
        if (!$first_name || !$last_name) {
            $this->err['name'] = '名前を入力してください。';
        } elseif (!$this->isJapaneseString($first_name) || !$this->isJapaneseString($last_name)) {
            $this->err['name'] = '名前は日本語で入力してください。';
        } else {
            $this->err['name'] = NULL;
        }
    }

    public function getErrors()
    {
        return $this->err;
    }

    public function checkKanaFormat($first_kana, $last_kana)
    {
        if (!$first_kana || !$last_kana) {
            $this->err['kana'] = 'フリガナを入力してください。';
        } elseif (!$this->isKanaString($first_kana) || !$this->isKanaString($last_kana)) {
            $this->err['kana'] = 'カタカナで入力してください。';
        } else {
            $this->err['kana'] = NULL;
        }
    }

    public function checkDateFormat($year, $month, $date)
    {
        if (!$year || !$month || !$date) {
            $this->err['birthday'] = '日付を入力してください。';
        } elseif (!preg_match('/\A19[0-9]{2}|[2-9][0-9]{3}\z/u', $year) || !preg_match('/\A(0[1-9]{1}|1[0-2]{1})\z/u', $month) || !preg_match('/\A(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})\z/u', $date)) {
            $this->err['birthday'] = '日付が正しくありません。';
        } else {
            $this->err['birthday'] = NULL;
        }
    }

    public function checkMailFormat($input) {
        if (!$input) {
            $this->err['email'] = 'メールアドレスを入力してください。';
        } elseif (!preg_match('/\A[a-zA-Z0-9_.+-]+@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}\z/u', $input)) {
            $this->err['email'] = 'メールアドレスが不正です。';
        } else {
            $this->err['email'] = NULL;
        }
    }

    public function checkTelFormat($input)
    {
        if (!$input) {
            $this->err['tel'] = '電話番号を入力してください。';
        } elseif (!preg_match('/\A0\d{9,10}\z/u', $input)) {
            $this->err['tel'] = '電話番号が正しくありません。';
        } else {
            $this->err['tel'] = NULL;
        }
    }
}
