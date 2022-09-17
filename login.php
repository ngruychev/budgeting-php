<?php
  if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./util/rb_config.php";
    $username = $_POST["username"];
    $password = $_POST["password"];
    $user = R::findOne("user", "username = ?", [$username]);
    if ($user) {
      if (password_verify($password, $user->password)) {
        session_start();
        $_SESSION["user_id"] = $user->id;
        header("Location: index.php");
        exit;
      }
    } else {
      ?>
      <p class="msg msg--error msg--float">Incorrect username/password</p>
      <?php
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log in</title>
  <?php require_once "component/head.php"; ?>
  <link rel="stylesheet" href="style/login.css">
</head>
<body>
  <form action="/login.php" method="post" id="login">
    <h1 id="h1">Log in</h1>
    <label for="username" id="username-label">
      Username:
      <input type="text" name="username" id="username">
    </label>
    <label for="password" id="password_label">
      Password:
      <input type="password" name="password" id="password">
    </label>
    <input id="login_button" type="submit" name="submit" value="Log in">
  </form>
</body>
</html>