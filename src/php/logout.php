<?php
require_once('functions.php');

session_start();

$_SESSION = array();

session_destroy();

redirect('/login.php');
