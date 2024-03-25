window.onload = function() {

  document.getElementById("txtPassword").addEventListener("keyup", removePlaceholder);
  document.getElementById("txtUsername").addEventListener("keyup", removePlaceholder);
  document.getElementById("btnAddUser").addEventListener("click", validateAddUserCheck);
  document.getElementById("btnRemoveUser").addEventListener("click", validateRemoveUserCheck);
  document.getElementById("btnRemoveStaff").addEventListener("click", validateRemoveStaffCheck);
  goToTranscriptionPage();

  $('#btnUpload').on('click', function(e) {
    e.preventDefault();
    if ($('#docDesc').val() == "") {
      alert('document description required');
    } else if ($('#docType').val() == "") {
      alert('Please choose a document type');
    } else if ($('#collection').val() == "") {
      alert('Please choose a collection');
    } else if ($('#docName').val() == "") {
      alert('Please specify a name');
    } else {

      let order = [];
      $('.ui-state-default').each(function() {
        order.push(this.id);
      });

      $('#pageOrder').val(order);
      $('#upload_doc_form').submit();
    }
  });
}//end onload


$('#viewPass').on('mousedown', function() {
  toggleVis($('#txtPassword'), $('#hide_staff_passwd'), $('#show_staff_passwd'));
})
$('#viewPass').on('mouseup', function() {
  toggleVis($('#txtPassword'), $('#hide_staff_passwd'), $('#show_staff_passwd'));
})

function removePlaceholder(evt) {
  evt.target.setAttribute("placeholder", "");
};

function handleUpload(e) {
  $('#btnUpload').on('click', function(e) {
    e.preventDefault();
  })
}

function validateAddUserCheck(evt) {
      let username = document.getElementById("txtUsername");
      let password = document.getElementById("txtPassword");
      let approver = document.getElementById("chkApprover");
      let uploader = document.getElementById("chkUploader");
      let admin = document.getElementById("chkAdmin");
      let error = document.getElementById("spnAddError");
      let errorString = "Please correct the following errors:<ul>";

      //username validation
      if (username.value === "") {
        evt.preventDefault();
        username.setAttribute("placeholder", "Enter a username");
      }
      if (username.value.length < 4) {
        evt.preventDefault();
        errorString += "<li>Username must be at least 4 characters</li>";
      }

      //password validation
      if (password.value === "") {
        evt.preventDefault();
        password.setAttribute("placeholder", "Enter password");
      }
      if (password.value.length < 8) {
        evt.preventDefault();
        errorString += "<li>Password must be at least 8 characters</li>";
      }
      let isNumber = false;
      let isUppercase = false;
      let isLowercase = false;
      let isSpecialChar = false;
      let specialChars = ['!', '@', '#', '$', '%', '^', '&', '*'];
      for (var i = 0; i < password.value.length; i++) {
        if (!isNaN(password.value.charAt(i))) {
          isNumber = true;
        }
        if (password.value.charAt(i).toUpperCase() === password.value.charAt(i)) {
          isUppercase = true;
        }
        if (password.value.charAt(i).toLowerCase() === password.value.charAt(i)) {
          isLowercase = true;
        }
        if (specialChars.includes(password.value.charAt(i))) {
          isSpecialChar = true;
        }
        if (isNumber && isUppercase && isLowercase && isSpecialChar) {
          break;
        }
      }
      if (!isNumber) {
        evt.preventDefault();
        errorString += "<li>Include at least 1 number</li>";
      }
      if (!isUppercase) {
        evt.preventDefault();
        errorString += "<li>Include at least 1 uppercase letter</li>";
      }
      if (!isLowercase) {
        evt.preventDefault();
        errorString += "<li>Include at least 1 lowercase letter</li>";
      }
      if (!isSpecialChar) {
        evt.preventDefault();
        errorString += "<li>Include at least 1 special character (!, @, #, $, %, ^, &, *)</li>";
      }

      //uploader/approver validation
      if (!approver.checked && !uploader.checked && !admin.checked) {
        evt.preventDefault();
        errorString += "<li>Select at least one role</li>";
      }

      //output error to screen
      errorString += "</ul>";
      if (errorString !== "Please correct the following errors:<ul></ul>") {
        error.innerHTML = errorString;
      }
    };

function validateRemoveUserCheck(evt) {
    let email = document.getElementById("userList");
    let error = document.getElementById("spnDelUserError");
    let entries = email.children;
    if (email.value === "Select a User") {
      evt.preventDefault();
      error.innerHTML = "Please select an option";
    }
    let inList = false;
    for (var i = 0; i < entries.length; i++) {
      if (email.value === entries[i].getAttribute("value")) {
        inList = true;
        break;
      }
    }
    if (!inList) {
      evt.preventDefault();
      error.innerHTML = "Please ensure your typed selection matches an email from the list.";
    }
  }

  function validateRemoveStaffCheck(evt) {
    let email = document.getElementById("staffList");
    let error = document.getElementById("spnDelStaffError");
    let entries = email.children;
    if (email.value === "Select a Staff Member") {
      evt.preventDefault();
      error.innerHTML = "Please select an option";
    }
    let inList = false;
    for (var i = 0; i < entries.length; i++) {
      if (email.value === entries[i].getAttribute("value")) {
        inList = true;
        break;
      }
    }
    if (!inList) {
      evt.preventDefault();
      error.innerHTML = "Please ensure your typed selection matches an email from the list.";
    }
  } 

  function goToTranscriptionPage() {
    document.querySelector('#modal_choose_doc').addEventListener('click', ((e)=>{
      e.preventDefault()
    }))
  
    $('#modal_choose_doc').on('click', () => {
      let docId = $('#hidden_id').val();
      // $.redirect('transcription.php', {employeeDocId: docId});
      

      $.ajax({
        type: "POST",
        url: "approver_transcription_proc.php",
        data: {
          employeeDocId: docId,
        },
        success: function(response) {

          (response == 1) ? $.redirect("transcription.php") : $.redirect("employee.php");
          

        }
      });

    })
  }

  function noEdit() {
    console.log("no edit");
  }


