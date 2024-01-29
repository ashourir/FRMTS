$(document).ready(function() {
    
    $('#btnLogout').on('click', function(e) {
        e.preventDefault();
        let xml = new XMLHttpRequest();
  xml.open('POST', 'logout_proc.php');
  xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText === 'destroyed') {
        window.location.replace('index.php');
      }
    }
  };
  xml.send('logout');
    })
})