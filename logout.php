<?php
require_once "./util/session.php";
session_start();
session_destroy();
header("Location: login.php");
exit;
?>