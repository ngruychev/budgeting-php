<?php
require_once "util/require_admin.php";
require_once "./util/rb_config.php";
if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_POST["action"])) {
    die("Invalid action");
  }
  if ($_POST["action"] == "su_user") {
    $su_user = R::findOne("user", "username = ?", [$_POST["username"]]);
    if ($su_user) {
      $_SESSION["user_id"] = $su_user->id;
      header("Location: index.php");
    } else { ?>
      <p class="msg msg--error msg--float">User not found</p>
    <?php }
  } else {
    die("Invalid action");
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin panel</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <?php require_once "component/header.php"; ?>
  <h1>Admin panel</h1>
  <details>
    <summary>User list:</summary>
    <ul>
      <?php foreach (R::findAll("user") as $user) { ?>
        <li><?php echo htmlspecialchars($user->username); ?></li>
        <?php } ?>
    </ul>
  </details>
  <form action="admin.php" method="post">
    <fieldset>
      <legend>Switch to user</legend>
      <input type="hidden" name="action" value="su_user">
      <label for="username">
        Username:
        <input type="text" name="username" id="username">
      </label>
      <br>
      <input type="submit" name="submit" value="Switch to user">
    </fieldset>
  </form>
</body>
</html>