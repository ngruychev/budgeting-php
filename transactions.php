<?php
require_once "util/require_login.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <?php require_once "component/header.php"; ?>
  <h1>Transactions for <?php echo htmlspecialchars($CURRENT_USER->username); ?></h1>
  <form action="transactions.php" method="get">
    <fieldset>
      <legend>Filter</legend>
      <?php
      $filter_date = "date";
      if (isset($_GET["filter_date"])) $filter_date = $_GET["filter_date"];
      ?>
      <label for="filter_date_any">
        <input type="radio" name="filter_date" value="none" id="filter_date_any" <?php if ($filter_date == "none") echo "checked"; ?>>
        Any date
      </label>
      <br>
      <label for="filter_date">
        <input type="radio" name="filter_date" value="date" id="filter_date" <?php if ($filter_date == "date") echo "checked"; ?>>
        By date
      </label>
      <div class="container">
        <label for="date">
          Date:
          <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($_GET["date"] ?? date("Y-m-d")); ?>">
        </label>
      </div>
      <label for="filter_date_range">
        <input type="radio" name="filter_date" value="date_range" id="filter_date_range" <?php if ($filter_date == "date_range") echo "checked"; ?>>
        By date range
      </label>
      <div class="container">
        <label for="date_from">
          From:
          <input type="date" name="date_from" id="date_from" value="<?php echo htmlspecialchars($_GET["date_from"] ?? date("Y-m-d")); ?>">
        </label>
        <br>
        <label for="date_to">
          To:
          <input type="date" name="date_to" id="date_to" value="<?php echo htmlspecialchars($_GET["date_to"] ?? date("Y-m-d")); ?>">
        </label>
      </div>
      <label for="category">
        Category:
        <select name="category" id="category">
          <option value="">All</option>
          <?php foreach ($CURRENT_USER->ownCategoryList as $category) { ?>
            <option value="<?php echo htmlspecialchars($category->id);?>"
            <?php if (isset($_GET["category"]) && $_GET["category"] == $category->id) {
              echo " selected";
            } ?>
            > <?php echo htmlspecialchars($category->name); ?> </option>
          <?php } ?>
        </select>
      </label>
      <br>
      <label for="type">
        Type:
        <select name="type" id="type">
          <option value="">All</option>
          <option value="expense" <?php if (isset($_GET["type"]) && $_GET["type"] == "expense") echo "selected"; ?>>Expense</option>
          <option value="expense_return" <?php if (isset($_GET["type"]) && $_GET["type"] == "expense") echo "selected"; ?>>Expense return</option>
          <option value="income" <?php if (isset($_GET["type"]) && $_GET["type"] == "income") echo "selected"; ?>>Income</option>
        </select>
      </label>
      <br>
      <input type="submit" class="btn btn-sm btn-a" value="Filter">
    </fieldset>
  </form>
  <a href="edit_transaction.php?new" class="btn btn-sm btn-b">Add transaction</a>
  <?php
  $category = isset($_GET["category"]) ? $_GET["category"] : "";
  $type = isset($_GET["type"]) ? $_GET["type"] : "";
  if ($filter_date == "date_range") {
    $date_from = $_GET["date_from"];
    $date_to = $_GET["date_to"];
    $transactions = $CURRENT_USER->withCondition(
      "REPLACE(`date`, '-', '') BETWEEN REPLACE(?, '-', '') AND REPLACE(?, '-', '') AND (category_id = ? OR ? = 1) AND (`type` = ? OR ? = 1) ORDER BY date ASC",
      [$date_from, $date_to, $category, $category == "", $type, $type == ""]
    )->ownTransactionList;
  } else if ($filter_date == "date") {
    $date = isset($_GET["date"]) ? $_GET["date"] : date("Y-m-d");
    $transactions = $CURRENT_USER->withCondition(
      "date = ? AND (category_id = ? OR ? = 1) AND (`type` = ? OR ? = 1) ORDER BY date ASC",
      [$date, $category, $category == "", $type, $type == ""]
    )->ownTransactionList;
  } else {
    $transactions = $CURRENT_USER->withCondition(
      "(category_id = ? OR ? = 1) AND (`type` = ? OR ? = 1) ORDER BY date ASC",
      [$category, $category == "", $type, $type == ""]
    )->ownTransactionList;
  }
  if (count($transactions) == 0) { ?>
    <p class="msg msg--info">You have no transactions yet</p>
  <?php } else { ?>
    <dl>
      <dt>Total spent</dt>
      <dd>
        <?php
        $total_spent = array_reduce($transactions, function ($acc, $transaction) {
          if ($transaction->type == "expense") return $acc + $transaction->amount;
          else if ($transaction->type == "expense_return") return $acc - $transaction->amount;
          else return $acc;
        }, 0);
        echo htmlspecialchars($total_spent / 100);
        ?>
      </dd>
    </dl>
    <div class="overflow-x-scroll">
      <table class="table">
        <thead>
          <tr>
            <th>Amount</th>
            <th>Type</th>
            <th>Category</th>
            <th>Description</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $transaction) { ?>
            <tr>
              <td><?php echo htmlspecialchars($transaction->amount / 100); ?></td>
              <td><?php echo htmlspecialchars($transaction->type); ?></td>
              <td><?php echo htmlspecialchars($transaction->category->name); ?></td>
              <td><?php echo htmlspecialchars($transaction->description); ?></td>
              <td><?php echo htmlspecialchars($transaction->comment); ?></td>
              <td><?php echo htmlspecialchars($transaction->date); ?></td>
              <td>
                <a href="edit_transaction.php?id=<?php echo htmlspecialchars($transaction->id); ?>" class="btn btn-sm btn-a">Edit</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } ?>
</body>
</html>