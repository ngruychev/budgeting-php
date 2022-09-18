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
</head>
<body>
  <form action="login.php" method="post" class="center_of_page">
    <fieldset>
      <legend>Log in</legend>
      <label for="username">
        Username:
        <input type="text" name="username" id="username">
      </label>
      <br>
      <label for="password">
        Password:
        <input type="password" name="password" id="password">
      </label>
      <br>
      <input type="submit" name="submit" value="Log in">
      <br>
      No account? <a href="register.php">Register</a>
    </fieldset>
  </form>
</body>
</html>