<?php
function redirect($path) {
header('Location:'. $path);
exit;
}
