$(document).ready(function() {

  let email = $('#email');
  let lblEmail = $('[for=email]');
  let passwd = $('#login_password');
  let lblPasswd = $('[for=login_password]');
  let login = $('#login');


  /******************** Handle volunteer loggin in ********************************/
  login.on('click', function(e) {
    e.preventDefault();
    validateLogin(email, lblEmail, passwd, lblPasswd);
  })

  $('#toggleVPass').on('mousedown', function() {
    toggleVis(passwd, $('#hide_password'), $('#show_password'));
  })
  $('#toggleVPass').on('mouseup', function() {
    toggleVis(passwd, $('#hide_password'), $('#show_password'));
  })

})//end document ready



/***************************** Handle someone who forgot password ************************************************/
$('#recovery_modal').on('show.bs.modal', function() {

  $('.recovery_success').hide();

  $('#recovery_submit').on('click', function(e) {
    e.preventDefault();
    let rEmail = $('#recovery_email');
    let lblREmail = $('[for=recovery_email]');
    let rDOB = $('#recovery_dob');
    let lblRDOB = $('[for=recovery_dob]');

    if (validateEmail(rEmail, lblREmail) && validateDOB(rDOB, lblRDOB, 0)) {
      let xml = new XMLHttpRequest();
      xml.open('POST', 'login_proc.php');
      xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xml.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          if (this.responseText == "success") {
            $('.recovery_modal_title').hide();
            $('.recovery_body').hide();
            $('.modal-footer').hide();
            $('#reset_email').text(rEmail.val());
            $('.recovery_success').show();

          } else if (this.responseText == "mail_error") { //handle mail errors 

          } else {
            //either email or DOB do not exist or do not match
            $('.error').show();

          }
        }
      }
      xml.send(`recoverPassword=${rEmail.val()}&dob=${rDOB.val()}`);
    }
  })
})//end show modal


$('#recovery_modal').on('hidden.bs.modal', function() {
  $('.recovery_body').show();
  $('.modal-footer').show();
  $('.recovery_modal_title').show();
  $('#recovery_email').val('');
})

