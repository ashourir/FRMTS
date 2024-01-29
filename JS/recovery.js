$(document).ready(function() {

  //GRAB the token from the url
  const query = window.location.search;
  const urlParams = new URLSearchParams(query);
  const token = urlParams.get('token');

  $('.toggleVis').on('click', function(e) {
    let id = e.target.id.split('_')[1];
    let input = $(`#${id}`);
    toggleVis(input, $(`#hide_${id}`), $(`#show_${id}`));
  })

  //HANDLES THE ACTUAL PASSWORD SETTING FROM login_recovery.php
  $('#reset_password').on('click', function(e) {
    e.preventDefault();
    let rpasswd = $('#rpasswd');
    let lblRPasswd = $('[for=rpasswd]');
    let rconf = $('#rconf');
    let lblRConf = $('[for=rconf]');

    if (validatePassword(rpasswd, lblRPasswd) && validateMatchingPasswords(rpasswd, rconf, lblRConf)) {
      let xml = new XMLHttpRequest();
      xml.open('POST', '../login_proc.php');
      xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xml.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          if (this.responseText) {//password has been changed successfully
            $('#form__change_password').hide();
            $('.success_msg').show();
          }
        }
      }
      xml.send(`updatePassword=${rpasswd.val()}&token=${token}`);
    }
  })
});//end document ready
