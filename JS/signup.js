
$(document).ready(function() {

  //Show / hide "Why do you need my DOB question / answer"
  $('.question').on('click', function() {
    $('.answer').toggle();
  })

  //Possibly look at this as a PHP variable so ADMIN can set min age
  const minimumAge = 0;
  let signup = $('#signup');

  let confDisplay = $('#signup_conf');
  confDisplay.hide();

  let email, lblEmail, dob, lblDOB;

  signup.on('click', function(e) {
    e.preventDefault();

    email = $('#email');
    lblEmail = $('[for=email]');
    dob = $('#dob');
    lblDOB = $('[for=dob]');
    let validEmail = false;
    let validDOB = false;


    validEmail = validateEmail(email, lblEmail);
    validDOB = validateDOB(dob, lblDOB, minimumAge);
    if (validEmail && validDOB) {
      checkEmailExists(email.val(), function(result) {
        if (result) {
          createVolunteer(email.val(), dob.val(), function(result) {
            if (result) {
              //display confirm message reminding them to check their inbox
              confDisplay.show();
              $('#form__signup').hide();
              //disable form to stop them from click click clicking away
              $('#form__signup :input').prop('disabled', true);
            }
          })
        } else {
          email.addClass('is-invalid');
          lblEmail.text('Email already in use');
          email.val('');
        }
      })
    }
  })//end signup click handler


})//end document.ready
