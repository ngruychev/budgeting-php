<?php
require_once "rb-sqlite.php";
define('ROOT_DIR', realpath(dirname(__FILE__)) . '/');
R::setup("sqlite:" . ROOT_DIR . "../data/data.db");
// seed the database
if (R::count("user") == 0) {
  $user = R::dispense("user");
  $user->username = "admin";
  $user->password = password_hash("admin", PASSWORD_DEFAULT);
  $user->roles = "admin";
  R::store($user);
}
if (R::count("category") == 0) {
  $category = R::dispense("category");
  $category->name = "Food";
  R::store($category);
  $category = R::dispense("category");
  $category->name = "Transport";
  R::store($category);
  $category = R::dispense("category");
  $category->name = "Entertainment";
  R::store($category);
  $category = R::dispense("category");
  $category->name = "Other";
  R::store($category);
  $category = R::dispense("category");
  $category->name = "Education";
  R::store($category);
  $category = R::dispense("category");
  $category->name = "Housing";
  R::store($category);
  $category = R::dispense("category");
  $category->name = "Work";
  R::store($category);
  $category = R::dispense("category");
  $category->name = "Benefits";
  R::store($category);
}
?>
