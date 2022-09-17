<?php
require_once "util/rb_config.php";
?>
<header>
  <nav class="nav" tabindex="-1" onclick="this.focus()">
    <div class="container">
      <a class="pagename current">Budgeting</a>
      <a href="index.php">Home</a>
      <a href="transactions.php">Transactions</a>
      <a href="stats.php">Stats</a>
      <span class="user_infobox">
        <?php if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != "") { ?>
        <a class="user_infobox__username" href="profile.php">
          <?php
          global $CURRENT_USER;
          global $CURRENT_USER_ROLES;
          $CURRENT_USER = R::load("user", $_SESSION["user_id"]);
          $CURRENT_USER_ROLES = explode(",", $CURRENT_USER->roles);
          echo htmlspecialchars($CURRENT_USER->username);
          ?>
        </a>
        <a class="user_infobox__logout" href="logout.php">Log out</a>
        <?php } else { ?>
        <a class="user_infobox__login" href="login.php">Log in</a>
        <?php } ?>
      </span>
    </div>
  </nav>
</header>