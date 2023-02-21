<?php
class Validation
{
  private $errors = [];

  public function validate($data, $rules)
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

  public function getErrors()
  {
    return $this->errors;
  }

  public function addErros($key, $error_message)
  {
    $this->errors[$key] = $error_message;
  }

  private function getErrorMessage($item, $rule, $option)
  {

    $item_to_label_map = [
      'name' => '名前',
      'company_name' => '会社名',
      'email' => 'メールアドレス',
      'password' => 'パスワード',
      'confirm_password' => '確認用パスワード',
      'date' => '日付',
      'price' => '総額',
      'memo' => 'メモ',
      'area' => '店舗名'
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
      case 'prefecture':
        $error = '都道府県を選んでください';
        break;
      default:
        $error = $item_to_label_map[$item] . 'を正しく入力してください。';
        break;
    }
    return $error;
  }

  private function required($value)
  {
    return !empty($value);
  }

  private function minlength($value, $minlength)
  {
    return mb_strlen($value) >= $minlength;
  }

  private function maxlength($value, $maxlength)
  {
    return mb_strlen($value) <= $maxlength;
  }

  private function email($value)
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }

  private function date($value)
  {
    return preg_match('/\A(19[0-9]{2}|[2-9][0-9]{3})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\z/u', $value);
  }

  private function price($value)
  {
    return preg_match('/\A[1-9][0-9]*(,[1-9][0-9]*)*\z/u', $value);
  }

  private function prefecture($value)
  {
    return is_int($value) && $value >= 1 && $value <= 47;
  }

  public function checkUnique($value, $table, ...$columns)
  {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE ";
    foreach ($columns as $column) {
      $sql .= "$column = :$column OR ";
    }
    $sql = rtrim($sql, ' OR ');

  }
}
