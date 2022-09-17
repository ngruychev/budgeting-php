<?php
require_once "util/require_login.php";
require_once "util/rb_config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stats</title>
  <?php require_once "component/head.php"; ?>
</head>
<body>
  <?php require_once "component/header.php"; ?>
  <?php
    $overall_spent = array_reduce($CURRENT_USER->withCondition("type = 'expense' OR type = 'expense_return'")->ownTransactionList, function ($acc, $x) {
      if ($x->type == "expense") return $acc + $x->amount;
      else return $acc - $x->amount;
    }, 0) / 100;
    $overall_earned = array_reduce($CURRENT_USER->withCondition("type = 'income'")->ownTransactionList, fn($acc, $x) => $acc + $x->amount, 0) / 100;
    $overall_balance = $overall_earned - $overall_spent;
    $spending_by_category = array_reduce(
      $CURRENT_USER->withCondition("type = 'expense' OR type = 'expense_return'")->ownTransactionList,
      function ($acc, $x) {
        if (!isset($acc[$x->category->name])) $acc[$x->category->name] = 0;
        if ($x->type == "expense") $acc[$x->category->name] += $x->amount;
        else $acc[$x->category->name] -= $x->amount;
        return $acc;
      },
      []
    );
    arsort($spending_by_category);
    $earning_by_categoory = array_reduce(
      $CURRENT_USER->withCondition("type = 'income'")->ownTransactionList,
      function ($acc, $x) {
        if (!isset($acc[$x->category->name])) $acc[$x->category->name] = 0;
        $acc[$x->category->name] += $x->amount;
        return $acc;
      },
      []
    );
    arsort($earning_by_categoory);
  ?>
  <h1>Stats</h1>
  <div class="container">
    <dl>
      <dt>Overall spent</dt>
      <dd><?php echo htmlspecialchars($overall_spent); ?></dd>
      <dt>Overall earned</dt>
      <dd><?php echo htmlspecialchars($overall_earned); ?></dd>
      <dt>Overall balance</dt>
      <dd><?php echo htmlspecialchars($overall_balance); ?></dd>
      <dt>Spending by category</dt>
      <dd>
        <table class="table">
          <thead>
            <tr>
              <th>Category</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($spending_by_category as $cat => $amount) { ?>
            <tr>
              <td><?php echo htmlspecialchars($cat); ?></td>
              <td><?php echo htmlspecialchars($amount / 100); ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </dd>
      <dt>Earning by category</dt>
      <dd>
        <table class="table">
          <thead>
            <tr>
              <th>Category</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($earning_by_categoory as $cat => $amount) { ?>
            <tr>
              <td><?php echo htmlspecialchars($cat); ?></td>
              <td><?php echo htmlspecialchars($amount / 100); ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </dd>
    </dl>
  </div>
</body>
</html>