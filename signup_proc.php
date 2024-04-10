<?php

require_once('connect.php');
require_once('CLASSES/Volunteer.php');
require_once('email.php');


if (isset($_POST['checkEmail'])) {
  $email = $_POST['checkEmail'];
  $result = Volunteer::VerifyAvailableEmail($email);
  echo "$result";
}


if (isset($_POST['createVolunteer'])) {
  list($email, $dob) = explode('|', $_POST['createVolunteer']);
  $result = Volunteer::AddVolunteer($email, $dob);
  if ($result) {
    $token = Volunteer::GetToken($email);
    $subject = 'Complete Your Registration - Transcribe.Fredericton';
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
                  <h1>You\'re almost done!</h1>
                  <div><h3><a href="https://transcribe.frederictonregionmuseum.com/signup_cred.php?token=' . $token . '">Click here to complete registration</a></h3></div>
                  <br><br><br>
                  <div>If link does not work please copy and paste the url below in your browser</div>
                  <div>https://transcribe.frederictonregionmuseum.com/signup_cred.php?token=' . $token . '</div> 

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
    if ($result == "success") {
      echo "1";
    } else {
      return false; //handle mail errors here?
    }
  } else {
    echo "$result";
  }
}


if (isset($_POST['finalize'])) {
  list($password, $token) = explode("|", $_POST['finalize']);
  $result = Volunteer::UpdateVolunteer($password, $token);
  echo "$result";
}
