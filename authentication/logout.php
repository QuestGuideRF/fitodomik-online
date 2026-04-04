<?php
require_once '../config/session.php';
require_once '../config/headers.php';
$_SESSION = array();
session_destroy();
header("Location: authentication/login.php");
exit();