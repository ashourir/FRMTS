window.onload = function() {
    
  document.getElementById("btnSubmitChangePass").addEventListener("click", checkPasswords);
  document.getElementById("txtCurrent").addEventListener("blur", verifyCurrentPass);

  function verifyCurrentPass(evt) {
    let xmlhttp = new XMLHttpRequest();
    let password = evt.target.value;
    let replySpan = document.getElementById("curPassErr");
    xmlhttp.onreadystatechange = function() {
      replySpan.innerHTML = this.responseText;
      if (this.responseText.includes("not")) {
        localStorage.setItem("validPass", "false");
        replySpan.style.backgroundColor = "#e62b25";
      }
      else {
        localStorage.setItem("validPass", "true");
        replySpan.style.backgroundColor = "#35cf06";
      }
    };
    xmlhttp.open("GET", "../check_curr_pass.php?q=" + password);
    xmlhttp.send();
  }
  
  function checkPasswords(evt) {
    let newPass1 = document.getElementById("newPass1").value;
    let newPass2 = document.getElementById("newPass2").value;
    let matchError = document.getElementById("passMatchErr");
    let replySpan = document.getElementById("curPassErr");
    let lengthErr = document.getElementById("liLength");
    let numberErr = document.getElementById("liNumber");
    let upperErr = document.getElementById("liUpper");
    let lowerErr = document.getElementById("liLower");
    let specialErr = document.getElementById("liSpecial");
    let currentPass = localStorage.getItem("validPass");
    if (currentPass === "false") {
      evt.preventDefault();
      replySpan.innerHTML = "Your current password does not match our records.  Please ensure your passwords match and try again."
    }
    if (newPass1 !== newPass2) {
      evt.preventDefault();
      matchError.style.backgroundColor = "#e62b25";
      matchError.innerHTML = "Passwords do not match";
    }

    //password validation
    if (newPass1 === "") {
      evt.preventDefault();
      document.getElementById("newPass1").setAttribute("placeholder", "Enter password");
    }
    if (newPass1.length < 8) {
      evt.preventDefault();
      lengthErr.style.backgroundColor = "#e62b25";
    }
    let isNumber = false;
    let isUppercase = false;
    let isLowercase = false;
    let isSpecialChar = false;
    let specialChars = ['!', '@', '#', '$', '%', '^', '&', '*'];
    for (var i = 0; i < newPass1.length; i++) {
      if (!isNaN(newPass1.charAt(i))) {
        isNumber = true;
      }
      if (newPass1.charAt(i).toUpperCase() === newPass1.charAt(i)) {
        isUppercase = true;
      }
      if (newPass1.charAt(i).toLowerCase() === newPass1.charAt(i)) {
        isLowercase = true;
      }
      if (specialChars.includes(newPass1.charAt(i))) {
        isSpecialChar = true;
      }
      if (isNumber && isUppercase && isLowercase && isSpecialChar) {
        break;
      }
    }
    if (!isNumber) {
      evt.preventDefault();
      numberErr.style.backgroundColor = "#e62b25";
    }
    if (!isUppercase) {
      evt.preventDefault();
      upperErr.style.backgroundColor = "#e62b25";
    }
    if (!isLowercase) {
      evt.preventDefault();
      lowerErr.style.backgroundColor = "#e62b25";
    }
    if (!isSpecialChar) {
      evt.preventDefault();
      specialErr.style.backgroundColor = "#e62b25";
    }
  }
};
