$('#e_login_modal').on('show.bs.modal', function(e) {


  function verifyEmployeeCredentials(username, password, callback) {
    let xml = new XMLHttpRequest();
    xml.open('POST', 'login_proc.php');
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        callback(this.responseText);
      }
    }
    xml.send(`verifyEmployeeCredentials=${username}&ePass=${password}`);
  }



  $('#toggleEPass').on('mousedown', function() {
    toggleVis($('#ePassword'), $('#hide_epassword'), $('#show_epassword'));
  })
  $('#toggleEPass').on('mouseup', function() {
    toggleVis($('#ePassword'), $('#hide_epassword'), $('#show_epassword'));
  })



  $('#elogin').on('click', function(e) {
    e.preventDefault();

    let isempty = false;
    let username = $('#eUsername');
    let password = $('#ePassword');
    let lblUsername = $('[for=eUsername]');
    let lblPassword = $('[for=ePassword]');

    username.removeClass('is-invalid');
    password.removeClass('is-invalid');
    lblUsername.text('Username');
    lblPassword.text('Password');

    if (username.val().length === 0) {
      username.addClass('is-invalid');
      isempty = true;
    }
    if (password.val().length === 0) {
      password.addClass('is-invalid');
      isempty = true;
    }


    if (!isempty) {
      verifyEmployeeCredentials(username.val(), password.val(), function(result) {
        if (result == 'invalid email') {
          username.addClass('is-invalid');
          lblUsername.text('Username does not exist');
        }
        else if (result == 'invalid password') {
          password.addClass('is-invalid');
          lblPassword.text('Invalid password');
        }
        else {
          window.location.replace('employee.php');
        }
      })
    }
  })//end login click


})//end show modal

$('#e_login_modal').on('hide.bs.modal', function(e) {

  $('#eUsername').val('');
  $('#ePassword').val('');
});
