<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
  header("Location: login.php");
  exit;
}
require_once "util/rb_config.php";
global $CURRENT_USER_ID;
global $CURRENT_USER;
global $CURRENT_USER_ROLES;
$CURRENT_USER_ID = $_SESSION["user_id"];
$CURRENT_USER = R::load("user", $CURRENT_USER_ID);
$CURRENT_USER_ROLES = explode(",", $CURRENT_USER->roles);
?>