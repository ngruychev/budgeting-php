<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
  header("Location: login.php");
  exit;
}
require_once "util/rb_config.php";
global $CURRENT_USER_ID;
global $CURRENT_USER;
global $CURRENT_USER_ROLES;
global $CURRENT_USER_IS_ADMIN;
$CURRENT_USER_ID = $_SESSION["user_id"];
$CURRENT_USER = R::load("user", $CURRENT_USER_ID);
if (!$CURRENT_USER) {
  header("Location: login.php");
  exit;
}
$CURRENT_USER_ROLES = explode(",", $CURRENT_USER->roles);
$CURRENT_USER_IS_ADMIN = in_array("admin", $CURRENT_USER_ROLES);
?>