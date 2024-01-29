<?php

session_start();

if (isset($_POST['logout'])) {
    unset($_SESSION['employee']);
    unset($_SESSION['volunteer']);
  session_destroy();
  echo "destroyed";
}
