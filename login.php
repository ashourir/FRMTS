<?php
    session_start();
    if (isset($_SESSION['volunteer'])) {
        header("location:volunteer.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("head.php");
  session_start();
  ?>
  <link rel="stylesheet" href="./CSS/login.css">
</head>

<body>

  <?php
  include("header.php");
  include_once("./modal_recovery.php");
  if (isset($_GET["trans"])) {
    $_SESSION["get_started"] = 1;
  }
  if (isset($_GET["proof"])) {
    $_SESSION["get_started"] = 3;
  }
  ?>

  <main>
    <div class="container text-center">
      <main class="form-signin w-50 m-auto">
        <form id="login_form">
          <h3>Archival Transcription Project</h3>
          <h1>Volunteer Login</h1>

          <div class="form-floating w-100 p-2">
            <input type="text" class="form-control" id="email">
            <label for="email">Email</label>
          </div>

          <div class="form-floating w-100 p-2 pword">
            <input type="password" class="form-control" id="login_password">
            <label for="login_password">Password</label>
            <span class="input-group-addon toggleVis" id="toggleVPass">
              <i class="bi bi-eye" id="show_password" style="display:none"></i>
              <i class="bi bi-eye-slash" id="hide_password"></i>
            </span>
          </div>

          <button id="login" class="w-50 btn btn-lg btn-primary" type="submit">Login</button>
          <a id="forgot_password" href="#recovery_modal" data-bs-toggle="modal">Did you forget your password? <img src="./IMAGES/go_arrow.svg" alt="reset password" /></a>

        </form>
    </div>
  </main>


  <?php
  include("footer.php");
  ?>
  <script type="text/javascript" src="./JS/login.js"> </script>

</body>

</html>
