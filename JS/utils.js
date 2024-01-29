//Check to validate an email
//Takes in an email, a label, and a callback
//Returns False if email isn't valid
function validateEmail(email, lbl) {
  if (email.val().trim() == '') {
    email.val('');
    email.addClass('is-invalid');
    lbl.text('Email Required');
    return false;
  } else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email.val().trim())) {
    email.val('');
    lbl.text('Invalid Email');
    email.addClass('is-invalid');
    return false;
  } else {
    lbl.text('Email Address');
    email.removeClass('is-invalid');
    return true;
  }
}





function validateLogin(email, lblEmail, pass, lblPass) {
  let haveEmail = true;
  let havePass = true;
  if (email.val().trim() == '') {
    email.addClass('is-invalid');
    lblEmail.text('Email Required');
    haveEmail = false;
  } else {
    email.removeClass('is-invalid');
    lblEmail.text('Email');
  }

  if (pass.val().trim() == '') {
    pass.addClass('is-invalid');
    lblPass.text('Password Required');
    havePass = false;
  } else {
    pass.removeClass('is-invalid');
    lblPass.text('Password');
  }

  if (haveEmail && havePass) {
    validateCredentials(email.val(), pass.val(), function(result) {
      if (result == 'invalid email') {
        email.addClass('is-invalid');
        pass.val('');
        email.focus();
        lblEmail.text('Email does not exist');
      } else if (result == 'invalid password') {
        pass.addClass('is-invalid');
        pass.focus();
        pass.val('');
        lblPass.text('Invalid Password');
      } else {
        $.redirect('volunteer.php', { coll: "all", page: 1, count: 25 });
      }
    });
  }
}



function validateCredentials(email, pass, callback) {
  let xml = new XMLHttpRequest();
  xml.open("POST", "../login_proc.php");
  xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseText);
    }
  }
  xml.send(`validateCredentials=${email}&pass=${pass}`);
}

//Executes AJAX to check if supplied email is not in DB
function checkEmailExists(email, callback) {
  let xml = new XMLHttpRequest();
  xml.open("POST", "../signup_proc.php");
  xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseText);
    }
  }
  xml.send(`checkEmail=${email}`);
}


//Check to validate a DOB/
//Takes in an DOB, a label, and a minimum age requirement
//Returns True if age is valid and older than minimum else FALSE
function validateDOB(dob, lbl, minAge) {

  let date = new Date(dob.val().split('-'));
  let age = (Date.now() - date) / 1000 / 60 / 60 / 24 / 365;

  if (date == 'Invalid Date') {
    lbl.text('Date Required');
    dob.addClass('is-invalid');
    return false;
  } else if (age < 0) {
    lbl.text('Invalid Date');
    dob.addClass('is-invalid');
    return false;
  } else if (age < minAge) {
    lbl.text(`Must be ${minAge} years old`);
    dob.addClass('is-invalid');
    return false;
  } else {
    lbl.text('Date of Birth');
    dob.removeClass('is-invalid');
    return true;
  }
}



//Returns True if the supplied password input has the following attributes
//At least 8 characters long
//At least 1 uppercase character
//At least 1 special character
//At least 1 number
function validatePassword(passwd, lbl) {
  if (passwd.val().trim() == '') {
    passwd.addClass('is-invalid');
    lbl.text('Password required');
    return false;
  }
  else if (!/^(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/.test(passwd.val())) {
    passwd.focus();
    passwd.addClass('is-invalid');
    lbl.text(`Min one number and special char required.  Length 8-16 chars.`);
    return false;
  } else {
    passwd.removeClass('is-invalid');
    lbl.text('Password');
    return true;
  }
}


function validateMatchingPasswords(passwd, conf, lbl) {
  if (passwd.val() != conf.val()) {
    conf.focus();
    conf.val('');
    conf.addClass('is-invalid');
    lbl.text("Passwords don't match. Please try again");
    return false;
  } else {
    conf.css("background-color", '');
    lbl.text('Confirm Password');
    conf.removeClass('is-invalid');
    return true;
  }
}



//uses the static AddVolunteer method of the Volunteer class
function createVolunteer(email, dob, callback) {
  let xml = new XMLHttpRequest();
  xml.open("POST", "signup_proc.php");
  xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseText);
    }
  }
  xml.send(`createVolunteer=${email}|${dob}`);
}


//uses the static VerifyToken of the Volunteer class
function verifyToken(token, callback) {
  let xml = new XMLHttpRequest();
  xml.open("POST", "signup_proc.php");
  xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseText);
    }
  }
  xml.send(`token=${token}`);
}


//uses the static FinalizeVolunteer method of the Volunteer class
function finalizeVolunteer(password, token, callback) {
  let xml = new XMLHttpRequest();
  xml.open("POST", "signup_proc.php");
  xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseText);
    }
  }
  xml.send(`finalize=${password}|${token}`);
}



function toggleVis(input, hideIcon, showIcon) {
  if (input.attr('type') === 'password') {
    input.attr('type', 'text');
    hideIcon.hide();
    showIcon.show();
  } else {
    input.attr('type', 'password');
    hideIcon.show();
    showIcon.hide();
  }
}


function AddNewReview(stars, comment, callback) {
  let xml = new XMLHttpRequest();
  xml.open("POST", "../review_proc.php");
  xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    }
  }
  xml.send(`AddNewReview=${stars}|${comment}`);
}


//handle navbar highlighting
$(document).ready(function() {
  $.each($('.nav-item'), function() {
    $(this).toggleClass('active', window.location.pathname.indexOf($(this).find('a').attr('href')) > -1);
  })
})

