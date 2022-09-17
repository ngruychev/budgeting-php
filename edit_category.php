<?php
require_once "util/require_login.php";
require_once "util/rb_config.php";

if (!isset($_GET["id"]) || $_GET["id"] == "") {
  if (!isset($_POST["id"]) || $_POST["id"] == "") {
    header("Location: profile.php");
    die("Invalid/missing category id");
  }
  $id = $_POST["id"];
} else {
  $id = $_GET["id"];
}

$category = R::load("category", $id);
if (!$category) {
  header("Location: profile.php");
  exit;
}

if ($CURRENT_USER->id != $category->user->id) {
  header("Location: profile.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_POST["action"])) {
    die("Invalid action");
  }
  if ($_POST["action"] == "delete") {
    if (count($CURRENT_USER->ownCategoryList) == 1) {
      ?>
      <p class="msg msg--error msg--float">You can't delete your last category</p>
      <?php
    } else if (!isset($_POST["replace_with"]) || $_POST["replace_with"] == "") {
      ?>
      <p class="msg msg--error msg--float">You must select a category to replace with</p>
      <?php
    } else if (!in_array($_POST["replace_with"], array_map(fn($c) => $c->id, R::findAll("category")))) {
      ?>
      <p class="msg msg--error msg--float">You must select a valid category to replace with</p>
      <?php
    } else if (R::load("category", $_POST["replace_with"])->user->id != $CURRENT_USER->id) {
      ?>
      <p class="msg msg--error msg--float">You don't own the replacement category</p>
      <?php
    } else {
      $replace_with = R::load("category", $_POST["replace_with"]);
      foreach ($category->ownTransactionList as $transaction) {
        $transaction->category = $replace_with;
        R::store($transaction);
      }
      R::trash($category);
      header("Location: profile.php");
      exit;
    }
  } else if ($_POST["action"] == "update") {
    if (!isset($_POST["name"]) || $_POST["name"] == "") {
      ?>
      <p class="msg msg--error msg--float">Name must not be empty</p>
    <?php } else if (R::findOne("category", "name = ? AND user_id = ?", [$_POST["name"], $CURRENT_USER->id])) { ?>
      <p class="msg msg--error msg--float">Category with this name already exists</p>
    <?php } else {
      $category->name = $_POST["name"];
      R::store($category);
      header("Location: profile.php");
      exit;
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit category</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <?php require_once "component/header.php"; ?>
  <h1>Edit category</h1>
  <form action="edit_category.php" method="post">
    <fieldset>
      <legend>Rename category</legend>
      <input type="hidden" name="id" value="<?php echo $category->id; ?>">
      <input type="hidden" name="action" value="rename">
      <label for="new_name">
        New name:
        <input type="text" name="new_name" id="new_name" value="<?php echo htmlspecialchars($category->name); ?>" required>
      </label>
      <input type="submit" class="btn btn-sm btn-b" value="Rename">
    </fieldset>
  </form>
  <?php if (count($CURRENT_USER->ownCategoryList) > 1) { ?>
    <form action="edit_category.php" method="post">
      <fieldset>
        <legend>Delete category</legend>
        <input type="hidden" name="id" value="<?php echo $category->id; ?>">
        <input type="hidden" name="action" value="delete">
        <label for="replace_with">
          Replace deleted category in transactions with:
          <select name="replace_with" id="replace_with" required>
            <?php foreach ($CURRENT_USER->ownCategoryList as $cat) { ?>
              <?php if ($cat->id != $category->id) { ?>
                <option value="<?php echo $cat->id; ?>"><?php echo htmlspecialchars($cat->name); ?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </label>
        <input type="submit" class="btn btn-sm btn-c" value="Delete">
      </fieldset>
    </form>
  <?php } ?>
</body>
</html>