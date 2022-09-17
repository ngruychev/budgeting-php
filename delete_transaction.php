<?php
require_once "util/require_login.php";
require_once "util/rb_config.php";
if ($_SERVER["REQUEST_METHOD"] != "POST") die("Invalid request method");
if (!isset($_POST["id"]) || $_POST["id"] == "") die("Invalid transaction id");
$transaction = R::load("transaction", $_POST["id"]);
if (!$transaction) die("Invalid transaction id");
if ($CURRENT_USER->id != $transaction->user->id) die("Invalid transaction id");
R::trash($transaction);
header("Location: transactions.php");
?>