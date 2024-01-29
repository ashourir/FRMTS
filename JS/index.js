$(document).ready(() => {

  $('#intro_one').css('transform', 'translateX(0)');
  $('#intro_two').css('transform', 'translateX(0)');
  
  $("#startHeader").click(() => {
    $("#startContent").slideToggle();
    var arrow = $("#startArrow");
    var state = arrow.html();
    if (state == "▼"){
      arrow.html("&#x25B2;");
    }
    else{
      arrow.html("&#x25BC;");
    }
  });
  $("#outsideHeader").click(() => {
    $("#outsideContent").slideToggle();
    var arrow = $("#outsideArrow");
    var state = arrow.html();
    if (state == "▼"){
      arrow.html("&#x25B2;");
    }
    else{
      arrow.html("&#x25BC;");
    }
  });
});

