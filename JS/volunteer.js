function hideOption() {
  $('.option').each(function() {
    $(this).hide();
  })
}

function updateProjectStatus(status) {
  let msg = "";
  if (status == "complete") {
    msg = "Project successfully completed";
  } else {
    msg = "Project successfully returned";
  }
  let xml = new XMLHttpRequest();
  xml.open('POST', 'volunteer_proc.php');
  xml.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "success") {
        window.location.replace(`volunteer.php?msg=${msg}`);
      } else {
        let msg = "An error has occurred";
        window.location.replace(`volunteer.php?msg=${msg}`);
      }
    }
  }
  xml.send(`updateProjectStatus=${status}`);
}


$(document).ready(function() {

  $('#home').show();
  $('#tab_home').addClass('tab_active');

  /*****************************  Close How To dropdown when clicked outside ****************************************/
  $('.sidebar_item').on('click', function() {
    if (this.id != "tab_howto") {
      $('.drop_menu').hide();
    }
  })


  ///////////////////////////////SIDEBAR - HIGHLIGHT CURRENT SELECTED//////////////////////////////
  $('.sidebar_item').on('click', function() {
    $('.sidebar_item').each(function() {
      $(this).removeClass('tab_active');
      hideOption();
    })
    $(this).addClass('tab_active');
  })

  /////////////////////////////////    HOME     ///////////////////////////////////////////////////
  $('#tab_home').on('click', function() {
    $('#home').show();
  })



  ////////////////////////////////   CURRENT WORK    ///////////////////////////////////////////
  $('#tab_cwork').on('click', function() {
    $('#current_work').show();
  })


  $('#cw_continue').on('click', function() {
    window.location.replace('transcription.php');
  })


  $('#btnDropConfirm').on('click', function() {
    updateProjectStatus('quit');
  });


  $('#btnCompleteConfirm').on('click', function() {
    updateProjectStatus('complete');
  });




  //////////////////////////////       PRVIOUS WORK         ///////////////////////////////////
  $('#tab_pwork').on('click', function() {
    $('#previous_work').show();
  })



  //////////////////////////////       HOW TO         ///////////////////////////////////
  $('#tab_howto').on('click', function() {
    $('.drop_menu').toggle(); //show / hides the dropdown menu
    $('#section_howto').toggle(); //shows / hides the instructions
  })



  ////////////////////////////////HANDLE PASSWORD RESET/////////////////////////////////////////
  $('#tab_chpass').on('click', function() {
    $('.success_msg').hide();
    $('#form__change_passwd').show();
    $('#change_passwd').show();
  })


  $('.toggleVis').on('click', function(e) {
    let id = e.target.id.split('_')[1];
    let input = $(`#${id}`);
    toggleVis(input, $(`#hide_${id}`), $(`#show_${id}`));
  })


  $('#submit__change_passwd').on('click', function(e) {
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

      let xml = new XMLHttpRequest();
      xml.open('POST', '../volunteer_proc.php');
      xml.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
      xml.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          if (this.responseText) {
            $('#form__change_passwd').hide();
            $('.success_msg').show();
            pass.val('');
            conf.val('');
          }
        }
      }
      xml.send(`updatePassword=${pass.val()}`)
    }
  })

/////////////////////Make Review///////////////////////////
$('#tab_review').on('click', function() {
    $('#make_review').show();
  })

  //////////////////////////////// LOGGING OUT  ///////////////////////////////////////
  $('#tab_logout').on('click', function() {
    $('#logout').show();
  })

  $('#btn_logout').on('click', function() {

    let xml = new XMLHttpRequest();
    xml.open('POST', '../logout_proc.php');
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if (this.responseText === 'destroyed') {
          window.location.replace('index.php');
        }
      }
    }
    xml.send('logout');
  })



  //TODO: JEREMY
  //Handle volunteer choosing a document
  $('.modal_choose_doc').on('click', function(e) {
    e.preventDefault();
    let docId = $(this).attr('doc_id');
    let statusId = $(this).attr('status_id');

    let xml = new XMLHttpRequest();
    xml.open('post', '../volunteer_proc.php');
    xml.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
    xml.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let result = this.responseText;

        if (result == "first") {
          $('.doc_content').hide();
          $('.modal_choose_doc').hide();
          $('.btn_continue').show();
          if (statusId == 1) {
            $('.transcriber_welcome').show();
          } else {
            $('.proofreader_welcome').show();
          }

          $('.btn_continue').on('click', function() {
            window.location.replace('transcription.php');
          })
        }
        else if (result == "success") {
          window.location.replace('transcription.php');
        }
        else if (result == "failure") {
          let msg = "An unexpected error occurred.  Please try again."
          window.location.href = `location.href&msg=${msg}`
        } else if (result == "currentWork") {
          $('.modal_document_details').hide();
          $('.modal_document_has_work').show();
        }
        else {
          let msg = this.responseText;
          window.location.href += `?msg=${msg}`;
        }
      }
    };
    xml.send(`selectDocument=${docId}&statusId=${statusId}`);
  })


  $('.modal').on('hidden.bs.modal', function(e) {
    $('.modal_document_details').show();
    $('.modal_document_has_work').hide();
  })

}) //end document ready






