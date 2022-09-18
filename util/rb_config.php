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
  $user->ownCategoryList[] = R::dispense("category");
  end($user->ownCategoryList)->name = "Food";
  end($user->ownCategoryList)->user = $user;
  $user->ownCategoryList[] = R::dispense("category");
  end($user->ownCategoryList)->name = "Transport";
  end($user->ownCategoryList)->user = $user;
  $user->ownCategoryList[] = R::dispense("category");
  end($user->ownCategoryList)->name = "Enterainment";
  end($user->ownCategoryList)->user = $user;
  $user->ownCategoryList[] = R::dispense("category");
  end($user->ownCategoryList)->name = "Other";
  end($user->ownCategoryList)->user = $user;
  R::store($user);
}
