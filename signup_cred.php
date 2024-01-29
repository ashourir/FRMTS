<?php

require_once('connect.php');
require_once('CLASSES/Volunteer.php');

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
  <link rel="stylesheet" href="./CSS/signup.css">
  <title>SIGN UP</title>
</head>

<body>

  <?php include("header.php"); ?>

  <div class="container text-center">
    <main class="form-signin w-50 m-auto">
      <div class="confirmation" id="signup_cred_conf" style="display:none">
        <h2>You have successfully signed up</h2>
      </div>

      <form id='signup_cred_form'>
        <h1 class="h3 mb-3 fw-normal">Complete registration</h1>

        <div class="form-floating p-2 passwords">
          <input type="password" class="form-control" id="passwd" maxlength="45">
          <label for="passwd">Enter a password</label>
          <span class="input-group-addon toggleVis">
            <i class="bi bi-eye" id="show_passwd" style="display:none"></i>
            <i class="bi bi-eye-slash" id="hide_passwd"></i>
          </span>
        </div>
        <div class="form-floating p-2 passwords">
          <input type="password" class="form-control" id="conf" maxlength="45">
          <label for="conf">Confirm password</label>
          <span class="input-group-addon toggleVis">
            <i class="bi bi-eye" id="show_conf" style="display:none"></i>
            <i class="bi bi-eye-slash" id="hide_conf"></i>
          </span>
        </div>

        <button id="complete_signup" class="w-100 btn btn-lg btn-primary" type="submit">Complete Registration</button>
      </form>

    </main>

  </div>

  <?php include("footer.php"); ?>
  <script type="text/javascript" src="./JS/signup_cred.js"></script>

</body>

</html>
