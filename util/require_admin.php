<?php
require_once "require_login.php";
if (!in_array("admin", $CURRENT_USER_ROLES)) {
  header("Location: /");
  exit();
}
global $CURRENT_USER_IS_ADMIN;
$CURRENT_USER_IS_ADMIN = true;
?>