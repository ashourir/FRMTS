$(document).ready(function() {
  const starFilter = document.querySelector('#starDropdown');
  const pageLimit = document.querySelector('#pageDisplayLimit');
  const sortingOptions = document.querySelector('#sort');
  $('#pageDisplayLimit').hide();
  $('#sort').hide();




  starFilter.addEventListener('change', function() {

    $('#pageDisplayLimit').show();
    $('#sort').show();
    document.querySelector('#pageDisplayLimit').innerHTML = "<option value=" + 5 + " selected></option>";//reset to the default
    const starFilterValue = starFilter.value;
    const pageLimitValue = pageLimit.value;
    console.log("inside ready js");
    console.log(starFilterValue);
    console.log(pageLimitValue);

    //-------------------------------------------------Order by options---------------------------------------------------------

    sortingOptions.addEventListener('change', function() {
      const sortingOptionsValue = sortingOptions.value;
      //document.querySelector('#pageDisplayLimit').innerHTML = "<option value=" + 5 + " selected></option>";//reset to the default
      console.log("inside sorting options:");
      console.log(sortingOptionsValue);
      if (sortingOptionsValue == 1) {
        console.log("New DESC");

        $.ajax({
          type: 'POST',
          url: 'get_Review.php',
          data: { starFilter: starFilterValue, pageLimit: pageLimitValue },
          success: function(reviews) {
            let reviewData = JSON.parse(reviews);
            //console.log(reviews);
            //console.log(reviewData);
            reviewData.reverse();
            document.querySelector('#reviewsContainer').innerHTML = displayReview(reviewData, pageLimitValue);
          }
        });

      } else if (sortingOptionsValue == 0) {

        $.ajax({
          type: 'POST',
          url: 'get_Review.php',
          data: { starFilter: starFilterValue, pageLimit: pageLimitValue },
          success: function(reviews) {
            let reviewData = JSON.parse(reviews);
            //console.log(reviews);
            //console.log(reviewData);
            document.querySelector('#reviewsContainer').innerHTML = displayReview(reviewData, pageLimitValue);
          }
        });
      }
    });

    //-------------------------------------------------End Order by---------------------------------------------------------    



    //-------------------------------------------------Change the options for the Display limit depending on the stars Count---------------------------------------------------------
    $.ajax({
      type: 'POST',
      url: 'get_Review_Count.php',
      data: { starFilter: starFilterValue },
      success: function(count) {
        let reviewCount = 0;
        let total = JSON.parse(count);
        reviewCount = total;
        document.querySelector('#pageDisplayLimit').innerHTML += displaylimit(reviewCount);

        $.ajax({
          type: 'POST',
          url: 'get_Review.php',
          data: { starFilter: starFilterValue, pageLimit: pageLimitValue },
          success: function(reviews) {
            let reviewData = JSON.parse(reviews);
            document.querySelector('#reviewsContainer').innerHTML = displayReview(reviewData, pageLimitValue);
          }
        });
      }
    });
    //----------------------------------------------------------------------------------------------------------

  });
  pageLimit.addEventListener('click', function() {
    const starFilterValue = starFilter.value;
    const pageLimitValue = pageLimit.value;

    const sortingOptionsValue = sortingOptions.value;
    if (sortingOptionsValue == 1) {
      $.ajax({
        type: 'POST',
        url: 'get_Review.php',
        data: { starFilter: starFilterValue, pageLimit: pageLimitValue },
        success: function(reviews) {
          let reviewData = JSON.parse(reviews);
          reviewData.reverse();
          document.querySelector('#reviewsContainer').innerHTML = displayReview(reviewData, pageLimitValue);
        }
      });

    } else if (sortingOptionsValue == 0) {

      $.ajax({
        type: 'POST',
        url: 'get_Review.php',
        data: { starFilter: starFilterValue, pageLimit: pageLimitValue },
        success: function(reviews) {
          let reviewData = JSON.parse(reviews);
          document.querySelector('#reviewsContainer').innerHTML = displayReview(reviewData, pageLimitValue);
        }
      });
    }
  });
});



function displayReview(reviews, pageLimit) {
  let DisplayString = "";
  var count = reviews.length;
  const startIndex = 0;
  const endIndex = pageLimit - 1;
  if (pageLimit <= count) {
    for (let i = startIndex; i <= endIndex; i++) {
      let ID = reviews[i].revID;
      let star = reviews[i].starTotal;
      let comment = reviews[i].comments;
      let dateCreated = reviews[i].datecreated;

      DisplayString += "<div class='reviews-members pt-4 pb-4'>" +
        "<div class='media'>" +
        "<div class='media-body'>" +
        "<div class='reviews-members-header'>" +
        "<h2 class='mb-1'><a class='text-black' href='#'>Review ID: " + ID + "</a> - Created Date: " + dateCreated + "</h2>" +
        "<div id='unclickable-rating-star-container'>";
      for (let c = 1; c <= 5; c++) {
        DisplayString += c <= star ? "<span class='fa fa-star checked' onclick='return false;' data-rating='" + c + "'></span>" : "<span class='fa fa-star' data-rating='" + c + "'></span>";
      }

      DisplayString += "</div>" +
        "</div>" +
        "<div class='reviews-members-body'>" +
        "<p>" + comment + "</p>" +
        "<hr>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>";
    }

  } else {

    return "Selected higher number than possible database entries ";
  }

  return DisplayString;
}
function displaylimit(starAmount) {
  let DisplayString = "";
  const startIndex = 5;
  for (let i = startIndex; i <= starAmount; i++) {
    DisplayString += "<option value=" + i + ">" + i + "</option>";
  }


  return DisplayString;
}
