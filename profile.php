<?php
require_once "util/require_login.php";
if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Change password") {
  // password must be at least 8 characters long
  $user = $CURRENT_USER;
  if (strlen($_POST["password"]) < 8) { ?>
    <p class="msg msg--error msg--float">Password must be at least 8 characters long</p>
  <?php } else { ?>
    <p class="msg msg--success msg--float">Password changed successfully</p>
  <?php
    $user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    R::store($user);
  }
  $user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  R::store($user);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User profile</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <?php require_once "component/header.php"; ?>
  <h1>
    User profile for <?php echo htmlspecialchars($CURRENT_USER->username); ?>
    <?php if (in_array("admin", $CURRENT_USER_ROLES)) { ?>
      <br>
      <a href="admin.php" class="btn btn-sm">Admin panel</a>
    <?php } ?>
  </h1>
  <form action="/profile.php" method="post" id="profile">
    <fieldset>
      <legend>Change password</legend>
      <label for="password" id="password_label">
        Password:
        <input type="password" name="password" id="password">
      </label>
      <input id="login_button" type="submit" name="submit" value="Change password">
    </fieldset>
  </form>
</body>
</html>