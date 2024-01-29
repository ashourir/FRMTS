<?php

require_once('./connect.php');
require_once('./CLASSES/Volunteer.php');

if (isset($_GET['token'])) {
  if (!Volunteer::VerifyToken($_GET['token'])) {
    header('location:signup.php');
  }
} else {
  header('location:signup.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("head.php"); ?>
</head>

<body>

  <?php include("header.php"); ?>

  <div class="container text-center">
    <main class="form-signin w-50 m-auto">

      <div class='success_msg' style='display:none'>
        <h2>Your password has been changed
      </div>

      <form id="form__change_password">
        <h1 class="h3 mb-3 fw-normal">Reset your password</h1>

        <div class="form-floating p-2 pword">
          <input type="password" class="form-control" id="rpasswd" placeholder="Enter a new password" maxlength="45">
          <label for="rpasswd">Enter a new password</label>
          <span class="input-group-addon toggleVis">
            <i class="bi bi-eye" id="show_rpasswd" style="display:none"></i>
            <i class="bi bi-eye-slash" id="hide_rpasswd"></i>
          </span>
        </div>

        <div class="form-floating p-2 pword">
          <input type="password" class="form-control" id="rconf" placeholder="Confirm password" maxlength="45">
          <label for="rconf">Confirm new password</label>
          <span class="input-group-addontoggleVis toggleVis">
            <i class="bi bi-eye" id="show_rconf" style="display:none"></i>
            <i class="bi bi-eye-slash" id="hide_rconf"></i>
          </span>
        </div>
        <button id="reset_password" class="w-100 btn btn-lg btn-primary" type="submit">Reset Password</button>
      </form>
    </main>

  </div>

  <?php include("footer.php"); ?>
  <script type="text/javascript" <script type="text/javascript" src="./JS/recovery.js"></script>

</body>

</html>
