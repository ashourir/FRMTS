<?php

require_once('connect.php');
require_once('./CLASSES/Volunteer.php');
require_once('./CLASSES/Employee.php');
require_once('./email.php');

session_start();

if (isset($_POST['validateCredentials'])) {
  if (isset($_SESSION['employee'])) {
    unset($_SESSION['employee']);
  }
  $email = $_POST['validateCredentials'];
  $pass = $_POST['pass'];
  $result = Volunteer::ValidateCredentials($email, $pass);
  if ($result instanceof Volunteer) {
    $_SESSION['volunteer'] = $result;
    echo "valid";
  } else {
    echo "$result";
  }
}


if (isset($_POST['verifyEmployeeCredentials'])) {
  $username = $_POST['verifyEmployeeCredentials'];
  $password = $_POST['ePass'];
  $result = Employee::ValidateCredentials($username, $password);
  if ($result instanceof Employee) {
    $_SESSION['employee'] = $result->getEmpId();
  } else {
    echo "$result";
  }
}



if (isset($_POST['recoverPassword'])) {
  $email = $_POST['recoverPassword'];
  $dob = $_POST['dob'];
  $token = Volunteer::ResetPassword($email, $dob);
  if ($token) {
    $subject = 'Password Recovery - Transcribe.Fredericton';
    $msg = '
          <!DOCTYPE html>
            <html lang="en">
              <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Fredericton Region Museum</title>
                <style type="text/css">
                  .icon {
                    width:20px;
                    margin-right:.5em;
                  }
                  .contact {
                    display:flex;
                    align-items:center;
                  }
                </style>
              </head>
              <body>
                <div align="center" style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                  <img src="https://transcribe.frederictonregionmuseum.com/IMAGES/logo.jpg" width="100" alt="Transcribe Fredericton Region Museum"/>
                  <h1>Reset Password</h1>
                  <div><h3><a href="https://transcribe.frederictonregionmuseum.com/login_recovery.php?token=' . $token . '">Reset Password</a></h3></div>
                  <br><br><br>
                  <div>If link does not work please copy and paste the url below in your browser</div>
                  <br>
                  <div style="color:blue">https://transcribe.frederictonregionmuseum.com/login_recover.php?token=' . $token . '</div> 
                  <br>
                  <br>
                  <br>
                  <div class="contact">
                    <h4>Contact Us</h4>
                    By Email: <a href="mailto:frmtranscribe@gmail.com">frmtranscribe@gmail.com</a></br>
                    By Phone: 506-455-6041<br>
                    By Facebook at: <a href="https://www.facebook.com/FrederictionRegionMuseum">facebook.com/FrederictonRegionMuseum</a><br></br>
                    Or you can always contact us the old fashioned way by mail at:
                    <br><br>
                    <div class="mail">
                      Fredericton Region Museum<br>
                      571 Queen Street, P.O. Box 1312, Station A<br>
                      Fredericton, NB E3A 5C8<br>
                      Canada
                    </div>
                  </div>
                </div>
              </body>
            </html>';
    $result = sendMail($email, $subject, $msg, $token);
    if ($result == 'success') {
      echo "success";
    } else {
      echo "mail_error";
    }
  } else {
    echo "error";
  }
}

if (isset($_POST['updatePassword'])) {
  echo "in updatePassword";
  $psswd = $_POST['updatePassword'];
  $token = $_POST['token'];
  $result = Volunteer::UpdateVolunteer($psswd, $token);
  echo "$result";
}
