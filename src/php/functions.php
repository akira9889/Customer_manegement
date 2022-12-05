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
