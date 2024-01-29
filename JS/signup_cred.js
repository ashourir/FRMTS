$(document).ready(function() {

  const query = window.location.search;
  const urlParams = new URLSearchParams(query);
  const token = urlParams.get('token');


  $('.toggleVis').on('mousedown', function(e) {
    let id = e.target.id.split('_')[1];
    let input = $(`#${id}`);
    toggleVis(input, $(`#hide_${id}`), $(`#show_${id}`));
  })
  $('.toggleVis').on('mouseup', function(e) {
    let id = e.target.id.split('_')[1];
    let input = $(`#${id}`);
    toggleVis(input, $(`#hide_${id}`), $(`#show_${id}`));
  })


  $('#complete_signup').on('click', function(e) {
    e.preventDefault();

    let pass = $('#passwd');
    let conf = $('#conf');
    let lblPass = $('[for=passwd]');
    let lblConf = $('[for=conf]');

    let validPass = false;
    let validConf = false;

    validPass = validatePassword(pass, lblPass);
    validConf = validateMatchingPasswords(pass, conf, lblConf);
    if (validPass && validConf) {
      finalizeVolunteer(pass.val(), token, function(result) {
        if (result) { //if db update successful

          $('#signup_cred_conf').show();
          $('#signup_cred_form').hide();
        }
      })
    }
  })//end signup click


})//end document.ready
