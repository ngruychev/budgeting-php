<?php
require_once "require_login.php";
if (!$CURRENT_USER_IS_ADMIN) {
  header("Location: /");
  exit();
}
?>