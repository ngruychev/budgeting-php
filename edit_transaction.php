<?php
require_once "util/require_login.php";
require_once "util/rb_config.php";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["new"])) {
    $transaction = R::dispense("transaction");
    $transaction->amount = 0;
    $transaction->type = "expense";
    $transaction->category = reset($CURRENT_USER->ownCategoryList);
    $transaction->description = "";
    $transaction->comment = "";
    $transaction->date = date("Y-m-d");
    $transaction->user = $CURRENT_USER;
    R::store($transaction);
    header("Location: edit_transaction.php?id=" . $transaction->id);
    exit;
  }
  if (!isset($_GET["id"]) || $_GET["id"] == "") {
    header("Location: transactions.php");
    die("Invalid/missing transaction id");
  }
  $transaction = R::load("transaction", $_GET["id"]);
  if (!$transaction) {
    header("Location: transactions.php");
    exit;
  }
  if ($CURRENT_USER->id != $transaction->user->id) {
    header("Location: transactions.php");
    exit;
  }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (
    isset($_POST["id"])
    && isset($_POST["amount"])
    && isset($_POST["type"])
    && isset($_POST["category"])
    && isset($_POST["description"])
    && isset($_POST["comment"])
    && isset($_POST["date"])
    && isset($_POST["submit"])
    && $_SERVER["REQUEST_METHOD"] == "POST"
  ) {
    $id = $_POST["id"];
    $amount = $_POST["amount"];
    $type = $_POST["type"];
    $category = $_POST["category"];
    $description = $_POST["description"] or "";
    $comment = $_POST["comment"] or "";
    $date = $_POST["date"];
    if (!is_numeric($amount)) {
      ?>
      <p class="msg msg--error msg--float">Amount must be a number</p>
    <?php } else if ($amount < 0) { ?>
      <p class="msg msg--error msg--float">Amount must be positive</p>
    <?php } else if (!in_array($type, ["income", "expense", "expense_return"])) { ?>
      <p class="msg msg--error msg--float">Type must be either income or expense</p>
    <?php } else if (!in_array($category, array_map(fn($c) => $c->id, R::findAll("category")))) { ?>
      <p class="msg msg--error msg--float">Category must be a valid category</p>
    <?php } else if (R::load("category", $category)->user->id != $CURRENT_USER->id) { ?>
      <p class="msg msg--error msg--float">You don't own this category</p>
    <?php } else if (strlen($comment) > 255) { ?>
      <p class="msg msg--error msg--float">Comment must be at most 255 characters long</p>
    <?php } else if (strlen($description) > 255) { ?>
      <p class="msg msg--error msg--float">Comment must be at most 255 characters long</p>
    <?php } else if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) { ?>
      <p class="msg msg--error msg--float">Date must be in the format YYYY-MM-DD</p>
    <?php } else {
      $transaction = R::load("transaction", $id);
      if (!$transaction) {
        header("Location: transactions.php");
        exit;
      }
      if ($CURRENT_USER->id != $transaction->user->id) {
        header("Location: transactions.php");
        exit;
      }
      $transaction->amount = $amount * 100; // store in cents
      $transaction->type = $type;
      $category = R::load("category", $category);
      $transaction->category = $category;
      $transaction->description = $description;
      $transaction->comment = $comment;
      $transaction->date = $date;
      $transaction->user = $CURRENT_USER;
      R::store($transaction);
      header("Location: transactions.php");
      exit;
    }
  } else {
    header("Location: transactions.php");
    exit;
  }
} else {
  die("Invalid request method");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit transaction</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <?php require_once "component/header.php"; ?>
  <h1>Edit transaction</h1>
  <form action="edit_transaction.php" method="post">
    <fieldset>
      <legend>Edit transaction</legend>
      <input type="hidden" name="id" value="<?php echo $transaction->id; ?>">
      <label for="amount">
        Amount:
        <input type="number" name="amount" id="amount" step="0.01" value="<?php echo htmlspecialchars($transaction->amount / 100); ?>">
      </label>
      <br>
      <label for="type">
        Type:
        <select name="type" id="type">
          <option value="income" <?php if ($transaction->type == "income") echo "selected"; ?>>Income</option>
          <option value="expense" <?php if ($transaction->type == "expense") echo "selected"; ?>>Expense</option>
          <option value="expense_return" <?php if ($transaction->type == "expense_return") echo "selected"; ?>>Expense return</option>
        </select>
      </label>
      <br>
      <label for="description">
        Description:
        <textarea name="description" id="description" cols="30" rows="1"><?php echo htmlspecialchars($transaction->description); ?></textarea>
      </label>
      <br>
      <label for="comment">
        Comment:
        <textarea name="comment" id="comment" cols="30" rows="1"><?php echo htmlspecialchars($transaction->comment); ?></textarea>
      </label>
      <br>
      <label for="date">
        Date:
        <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($transaction->date); ?>">
      </label>
      <br>
      <label for="category">
        Category:
        <select name="category" id="category">
          <?php foreach ($CURRENT_USER->ownCategoryList as $category) { ?>
            <option value="<?php echo $category->id; ?>" <?php if ($category->id == $transaction->category->id) { echo "selected"; } ?>><?php echo htmlspecialchars($category->name); ?></option>
          <?php } ?>
        </select>
      </label>
      <br>
      <input type="submit" class="btn btn-sm btn-b" name="submit" value="Edit transaction">
    </fieldset>
  </form>
  <form action="delete_transaction.php" method="POST">
    <fieldset>
      <legend>Delete</legend>
      <input type="hidden" name="id" value="<?php echo $transaction->id; ?>">
      <input type="submit" name="submit" value="Delete" class="btn btn-sm btn-c">
    </fieldset>
  </form>
</body>
</html>