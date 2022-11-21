<?php
require_once "util/require_login.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_POST["action"])) {
    die("Invalid action");
  }
  if ($_POST["action"] == "change_password") {
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
  } else if ($_POST["action"] == "add_category") {
    if (!isset($_POST["name"]) || $_POST["name"] == "") { ?>
      <p class="msg msg--error msg--float">Name must not be empty</p>
    <?php } else if (R::findOne("category", "name = ? AND user_id = ?", [$_POST["name"], $CURRENT_USER->id])) { ?>
      <p class="msg msg--error msg--float">Category with this name already exists</p>
    <?php } else {
      $category = R::dispense("category");
      $category->name = $_POST["name"];
      $category->user = $CURRENT_USER;
      R::store($category);
      ?>
      <p class="msg msg--success msg--float">Category added successfully</p>
      <?php
    }
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
  <title>User profile</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <?php require_once "component/header.php"; ?>
  <h1>
    User profile for <?php echo htmlspecialchars($CURRENT_USER->username); ?>
    <?php if ($CURRENT_USER_IS_ADMIN) { ?>
      <br>
      <a href="admin.php" class="btn btn-sm btn-c">Admin panel</a>
    <?php } ?>
  </h1>
  <form action="profile.php" method="post" id="profile">
    <fieldset>
      <legend>Change password</legend>
      <input type="hidden" name="action" value="change_password">
      <label for="password" id="password_label">
        Password:
        <input type="password" name="password" id="password">
      </label>
      <input id="login_button" type="submit" name="submit" value="Change password">
    </fieldset>
  </form>
  <form action="export_user_data.php" method="post">
    <fieldset>
      <legend>Export data</legend>
      <input type="submit" value="Export data" class="btn btn-sm btn-b">
    </fieldset>
  </form>
  <h2>Categories</h2>
  <div class="overflow-x-scroll">
    <table class="table">
      <thead>
        <tr>
          <th>Category</th>
          <th>Edit</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($CURRENT_USER->ownCategoryList as $category) { ?>
          <tr>
            <td><?php echo htmlspecialchars($category->name); ?></td>
            <td>
              <a href="edit_category.php?id=<?php echo $category->id; ?>" class="btn btn-sm btn-a">Edit</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <form action="profile.php" method="post">
    <fieldset>
      <legend>Add category</legend>
      <input type="hidden" name="action" value="add_category">
      <label for="name" id="name_label">
        Name:
        <input type="text" name="name" id="name" required>
      </label>
      <input type="submit" value="Add" class="btn btn-sm btn-b">
    </fieldset>
  </form>
  <h2>Delete profile</h2>
  <p>
    <a href="delete_profile.php" class="btn btn-sm btn-c">Delete profile</a>
  </p>
</body>
</html>