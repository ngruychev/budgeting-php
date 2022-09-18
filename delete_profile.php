<?php
require_once "util/require_login.php";
require_once "util/rb_config.php";

if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_POST["confirm"]) || $_POST["confirm"] != "on") {
    ?>
    <p class="msg msg--error msg--float">You must confirm this action</p>
    <?php
  }

  $user = $CURRENT_USER;
  // delete all associated categories and transactions
  foreach ($user->ownCategoryList as $category) {
    R::trash($category);
  }
  foreach ($user->ownTransactionList as $transaction) {
    R::trash($transaction);
  }
  R::trash($user);
  session_destroy();
  header("Location: logout.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete profile</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <form action="delete_profile.php" method="post" class="center_of_page">
    <fieldset>
      <p class="msg msg--error">
        <b>Warning!</b> This action is irreversible.
      </p>
      <legend>Delete profile</legend>
      <label for="confirm" id="confirm_label">
        <input type="checkbox" name="confirm" id="confirm" required>
        <p>
          I confirm that I want to delete my profile.
          <br>
          I know that this action is irreversible.
          <br>
          I know that all transactions, categories, and other data will be deleted permanently.
          <br>
          I know that I will not be able to recover my data after this action.
        </p>
      </label>
      <br>
      <input type="submit" name="submit" value="Delete profile" class="btn btn-sm btn-b">
    </fieldset>
  </form>
</body>
</html>