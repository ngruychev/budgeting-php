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
  <p>Here you can track your spending and earnings (under Transactions), and see statistics (under Stats)</p>
  <p>Click on the menu to get started</p>
  <p>Or, use these shortcuts:</p>
  <ul>
    <li><a href="transactions.php" class="btn btn-sm btn-a">Transactions</a></li>
    <li><a href="stats.php" class="btn btn-sm btn-a">Stats</a></li>
    <li><a href="edit_transaction?new" class="btn btn-sm btn-b">Add transaction</a></li>
  </ul>
  <p>
    On Chrome for Android, you can click the three dots in the top right corner,
    and then "Add to homescreen" to add an icon to your homescreen that will open this app.
    On Android 7.1 and higher, you can long-press the icon to see a list of shortcuts directly from your homescreen.
  </p>
</body>
</html>
