<?php
function redirect($path) {
header('Location:'. $path);
exit;
}

function sessionDestroy() {
    $_SESSION = array();

    session_destroy();
}
