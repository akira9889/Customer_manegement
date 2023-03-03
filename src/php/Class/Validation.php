<?php
class Validation
{
  private $errors = [];

  public function validate(array $data, array $rules): void
  {
    $valid = true;
    foreach ($rules as $item => $ruleset) {
      $value = isset($data[$item]) ? $data[$item] : null;
      $ruleset = is_string($ruleset) ? explode('|', $ruleset) : $ruleset;
      foreach ($ruleset as $rule) {
        $option = null;
        if (strstr($rule, ':')) {
          list($rule, $option) = explode(':', $rule);
        }
        $valid = $this->{$rule}($value, $option);
        if (!$valid) {
          $error = $this->getErrorMessage($item, $rule, $option);
            $this->errors[$item] = $error;
          break;
        }
      }
    }
  }

  public function getErrors(): array
  {
    return $this->errors;
  }

  public function addErros($key, $error_message): void
  {
    $this->errors[$key] = $error_message;
  }

  private function getErrorMessage($item, $rule, $option): string
  {

    $item_to_label_map = [
      'user_name' => 'ユーザー名',
      'name' => '名前',
      'last_name' => '姓',
      'first_name' => '名',
      'last_kana' => 'セイ',
      'first_kana' => 'メイ',
      'company_name' => '会社名',
      'email' => 'メールアドレス',
      'password' => 'パスワード',
      'confirm_password' => '確認用パスワード',
      'tel' => '電話',
      'date' => '日付',
      'yyyymm' => '日付',
      'price' => '総額',
      'memo' => 'メモ',
      'area' => '店舗名',
      'birthday' => '生年月日',
    ];

    $rule_to_label_map = [
      'japanese' => '日本語',
      'kana' => 'カタカナ',
    ];

    switch ($rule) {
      case 'required':
        $error = $item_to_label_map[$item] . 'を入力してください。';
        break;
      case 'maxlength':
        $error = $item_to_label_map[$item].'は'.$option.'文字までです。';
        break;
      case 'minlength':
        $error = $item_to_label_map[$item].'は'.$option.'文字以上です。';
        break;
      case 'japanese':
      case 'kana':
        $error = $rule_to_label_map[$rule].'で入力してください。';
        break;
      case 'gender':
        $error = '性別を選んでください。';
        break;
      case 'prefecture':
        $error = '都道府県を選んでください';
        break;
      default:
        $error = $item_to_label_map[$item] . 'を正しく入力してください。';
        break;
    }
    return $error;
  }

  private function required($value): bool
  {
    return !empty($value);
  }

  private function minlength($value, $minlength): bool
  {
    return mb_strlen($value) >= $minlength;
  }

  private function maxlength($value, $maxlength): bool
  {
    return mb_strlen($value) <= $maxlength;
  }

  private function japanese($value): bool
  {
    return preg_match('/^[ぁ-んァ-ヶｱ-ﾝﾞﾟ一-龠]*$/', $value) || preg_match('/^[ぁ-んァ-ヶｱ-ﾝﾞﾟ一-龠]*$/', $value);
  }

  private function kana($value): bool
  {
    return preg_match('/\A[ァ-ヴー]+\z/u', $value) || preg_match('/\A[ァ-ヴー]+\z/u', $value);
  }

  private function email($value): bool
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }

  private function gender($value): bool
  {
    return $value === '男性' || $value === '女性';
  }

  private function tel($value): bool
  {
    return preg_match('/\A0\d{9,10}\z/u', $value);
  }

  private function date($value): bool
  {
    return preg_match('/\A(19[0-9]{2}|[2-9][0-9]{3})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\z/u', $value);
  }

  private function yyyymm($value): bool
  {
    return preg_match('/\A(19[0-9]{2}|[2-9][0-9]{3})-(0[1-9]|1[0-2])\z/u', $value);
  }

  private function prefecture($value): bool
  {
    return is_int($value) && $value >= 1 && $value <= 47;
  }

  private function price($value): bool
  {
    return preg_match('/\A[1-9][0-9]*(,[1-9][0-9]*)*\z/u', $value);
  }
}
