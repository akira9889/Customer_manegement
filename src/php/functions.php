<?php
function redirect($path): void
{
header('Location:'. $path);
exit;
}

function sessionDestroy(): void
{
    $_SESSION = array();

    session_destroy();
}

function group_by(array $table, string $key): array
{
    $groups = [];
    foreach ($table as $row) {
        $groups[$row[$key]][] = $row;
    }
    return $groups;
}

function h($str): ?string
{
    if ($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    } else {
        return null;
    }
}

function getPrevDate($target_date = NULL, $term = 1): string
{
    if (empty($target_date)) $target_date = date('Y-m-d');
    // $term月前末日を取得...(1)
    $last_date = date('Y-m-d', strtotime($target_date . " last day of -{$term} month"));
    // 対象日の前月日を取得...(2)
    $prev_date = date('Y-m-d', strtotime($target_date . " -{$term} month"));
    // (1)と(2)を比較し、(2)の方が未来日の時とみ(1)を出力する
    if ($prev_date > $last_date) {
        return $last_date;
    } else {
        return $prev_date;
    }
}

function null_trim($string): ?string
{
    if ($string === null) {
        return null;
    } else {
        return trim($string);
    }
}

function extractNumber(string $num): string
{
    // 半角数字に変換
    $num_half_width = mb_convert_kana( $num, 'anr' );

    // 区切りカンマを削除
    $num_plain = preg_replace( '/,/', '', $num_half_width );

    // 文字列で返す
    return (string) $num_plain;
}
