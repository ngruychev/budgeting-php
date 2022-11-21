<?php
require_once "./util/require_login.php";
require_once "./util/rb_config.php";

if (!$_SERVER["REQUEST_METHOD"] == "POST") {
  header("HTTP/1.1 405 Method Not Allowed");
  exit;
}

$categories = [];
foreach ($CURRENT_USER->ownCategoryList as $cat) {
  $categories[] = $cat->export();
}

$transactions = [];
foreach ($CURRENT_USER->ownTransactionList as $trans) {
  $transactions[] = $trans->export();
}

$data = [
  "categories" => $categories,
  "transactions" => $transactions,
];

$json = json_encode($data, JSON_PRETTY_PRINT);

header("Content-Type: application/json");
$today = date("Y-m-d");
$filename = "budgeting-system-export-$today.json";
header("Content-Disposition: attachment; filename=$filename");
echo $json;
?>
