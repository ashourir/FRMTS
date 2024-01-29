<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("head.php"); ?>
  <link rel="stylesheet" href="./CSS/signup.css">
  <title>SIGN UP</title>
</head>

<body>

  <?php include("header.php"); ?>

  <!-- ################################################## MAIN CONTENT ################################################## -->

  <div class="container text-center">
    <main class="form-signin w-50 m-auto">
      <form id="form__signup">
        <h3>Archival Transcription Project</h3>
        <h1>Volunteer Sign Up</h1>

        <div class="form-floating p-2">
          <input type="email" class="form-control" id="email" maxlength=" 45">
          <label for="email">Email address</label>
        </div>
        <div class="form-floating p-2">
          <input type="date" class="form-control" id="dob" placeholder="Date of Birth">
          <label for="dob">Date of birth</label>
        </div>

        <div class="question">
          <p>Why do you need my birthdate? </p>
          <img class="expand" src="./IMAGES/ICONS/expand.svg" alt="expand" />
        </div>
        <div class="answer" style="display:none">
          <p><i>We will use it to help verify your account if you forget your password</i></p>
        </div>

        <button id="signup" class="w-50 btn btn-lg btn-primary" type="submit">Register</button>
      </form>

      <div class="confirmation" id="signup_conf" style="display:none">
        <h2>Verification Email Sent</h2>
        <p>Check your email and follow the link to complete your registration</p>
      </div>
    </main>

  </div>

  <?php include("footer.php"); ?>
  <script src="./JS/signup.js"></script>

</body>

</html>
