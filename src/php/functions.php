<?php
function redirect($path) {
header('Location:'. $path);
exit;
}

function sessionDestroy() {
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

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function getPrevDate($target_date = NULL, $term = 1)
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
