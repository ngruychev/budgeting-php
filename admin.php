<?php
require_once "util/require_admin.php";
require_once "./util/rb_config.php";
if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Create") {
  $new_user = R::dispense("user");
  if (R::findOne("user", "username = ?", [$_POST["username"]])) { ?>
    <p class="msg msg--error msg--float">Username already exists</p>
  <?php } else  if (strlen($_POST["password"]) < 8) { ?>
    <p class="msg msg--error msg--float">Password must be at least 8 characters long</p>
  <?php } else if (strlen($_POST["username"]) < 3) { ?>
    <p class="msg msg--error msg--float">Username must be at least 3 characters long</p>
  <?php } else {
    $new_user->username = $_POST["username"];
    $new_user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $new_user->roles = "";
    R::store($new_user);
  }
} else if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Switch") {
  $su_user = R::findOne("user", "username = ?", [$_POST["username"]]);
  if ($su_user) {
    $_SESSION["user_id"] = $su_user->id;
    header("Location: index.php");
  } else { ?>
    <p class="msg msg--error msg--float">User not found</p>
  <?php }
} else if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Add category") {
  $cat_name = $_POST["cat_name"];
  if (strlen($cat_name) < 3) { ?>
    <p class="msg msg--error msg--float">Category name should be at least 3 characters long</p>
  <?php } else {
    $category = R::dispense("category");
    $category->name = $cat_name;
    R::store($category);
    ?>
    <p class="msg msg--success msg--float">Category <?php echo htmlspecialchars($cat_name); ?> created</p>
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
      <legend>Create user</legend>
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
      <input type="submit" name="submit" value="Create">
    </fieldset>
  </form>
  <form action="admin.php" method="post">
    <fieldset>
      <legend>Switch to user</legend>
      <label for="username">
        Username:
        <input type="text" name="username" id="username">
      </label>
      <br>
      <input type="submit" name="submit" value="Switch">
    </fieldset>
  </form>
  <details>
    <summary>Category list</summary>
    <ul>
        <?php foreach (R::findAll("category") as $category) { ?>
          <li><?php echo htmlspecialchars($category->name); ?></li>
        <?php } ?>
    </ul>
  </details>
  <form action="admin.php" method="post">
    <fieldset>
      <legend>Add category</legend>
      <label for="cat_name">
        Category name:
        <input type="text" name="cat_name" id="cat_name">
      </label>
      <br>
      <input type="submit" value="Add category" name="submit">
    </fieldset>
  </form>
</body>
</html>