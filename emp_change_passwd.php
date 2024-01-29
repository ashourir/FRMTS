<?php
require_once('connect.php');
session_start();
if (!isset($_SESSION['employee'])) {
  header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("e_head.php"); ?>
</head>

<body>
  <?php include("empHeader.php"); ?>
  <main>
    <?php
    if (isset($_GET["reply"])) {
      $reply = $_GET["reply"];
      echo "<h2 class='d-flex justify-content-center'>$reply</h2>";
    }
    ?>
    <div class="d-flex justify-content-left" style="padding-top: 15px; padding-left: 20px;">
      <form method="post" action="emp_change_pass_proc.php">
        <table>
          <tr>
            <td>Enter your current password: </td>
            <td><input type="password" id="txtCurrent" name="txtCurrent">
              <span id="curPassErr"></span>
            </td>
          </tr>
          <tr>
            <td>Enter your new password: </td>
            <td><input type="password" id="newPass1" name="newPass1"></td>
          </tr>
          <tr>
            <td>Confirm your new password: </td>
            <td><input type="password" id="newPass2" name="newPass2">
              <span id="passMatchErr"></span>
            </td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" id="btnSubmitChangePass" value="Change Password"></td>
          </tr>
        </table>
      </form>
    </div>
    <BR /><BR />
    <div class="d-flex justify-content-center">
      Please remember all passwords must:
    </div>
    <div class="d-flex justify-content-center">
      <ul>
        <li id="liLength">be at least 8 characters in length;</li>
        <li id="liNumber">Include at least 1 number;</li>
        <li id="liUpper">Include at least 1 uppercase letter;</li>
        <li id="liLower">Include at least 1 lowercase letter;</li>
        <li id="liSpecial">Include at least 1 special character (!, @, #, $, %, ^, &amp;, *);</li>
      </ul>
    </div>
  </main>
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

  <script type="text/javascript" src="./JS/elogout.js"></script>
  <script src="./JS/emp_change_pass.js"></script>
  
</body>

</html>
