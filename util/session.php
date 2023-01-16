<?php
if (!isset($_SESSION)) session_start([
  "cookie_lifetime" => 400 * 24 * 60 * 60, // 400 days
]);
?>
