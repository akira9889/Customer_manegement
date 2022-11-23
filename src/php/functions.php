<?php
function redirect($path) {
header('Location:'. $path . '.php');
exit;
}
