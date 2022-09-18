<?php
require_once "util/require_login.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Budgeting system</title>
  <?php require_once "component/head.php"; ?>
  <link rel="manifest" href="manifest.json">
</head>
<body>
  <?php require_once "component/header.php"; ?>
  <h1>Welcome, <?php echo htmlspecialchars($CURRENT_USER->username) ?></h1>
</body>
</html>
