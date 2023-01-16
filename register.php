<?php
if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
  require_once "./util/rb_config.php";
  $username = $_POST["username"];
  $password = $_POST["password"];
  if (R::findOne("user", "username = ?", [$username])) {
    ?>
    <p class="msg msg--error msg--float">Username already exists</p>
    <?php
  } else if (strlen($username) < 3) {
    ?>
    <p class="msg msg--error msg--float">Username must be at least 3 characters</p>
    <?php
  } else if (strlen($password) < 8) {
    ?>
    <p class="msg msg--error msg--float">Password must be at least 8 characters</p>
    <?php
  } else {
    $user = R::dispense("user");
    $user->username = $username;
    $user->password = password_hash($password, PASSWORD_DEFAULT);
    $user->roles = "";
    $user->ownCategoryList[] = R::dispense("category");
    end($user->ownCategoryList)->name = "Food";
    end($user->ownCategoryList)->user = $user;
    $user->ownCategoryList[] = R::dispense("category");
    end($user->ownCategoryList)->name = "Transport";
    end($user->ownCategoryList)->user = $user;
    $user->ownCategoryList[] = R::dispense("category");
    end($user->ownCategoryList)->name = "Entertainment";
    end($user->ownCategoryList)->user = $user;
    $user->ownCategoryList[] = R::dispense("category");
    end($user->ownCategoryList)->name = "Other";
    end($user->ownCategoryList)->user = $user;
    R::store($user);
    require_once "util/session.php";
    $_SESSION["user_id"] = $user->id;
    header("Location: index.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <form action="register.php" method="post" class="center_of_page">
    <fieldset>
      <legend>Register</legend>
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
      <input type="submit" name="submit" value="Register">
    </fieldset>
  </form>
</body>
</html>