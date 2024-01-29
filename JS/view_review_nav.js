// Function to display navigation links or shrotcuts for reviews
// Parameters:
// - reviews: an array of reviews to be displayed
// - pageLimit: the number of reviews to be displayed per page
// Returns: a string containing HTML code for the navigation links
function displayNav(reviews, pageLimit) {
  let DisplayString = "";
  var count = reviews.length;
  let j = -1;
  const startIndex = 0;
  const endIndex = pageLimit;

  //Check if the page limit is less than or equal to the number of reviews
  if (pageLimit <= count) {
    for (let i = startIndex; i <= endIndex; i += 25) {
      if (i !== 0) {
        DisplayString += '<div id="navA"><a href="#result' + j + '" id="goTo">Review ' + i + ' </a></div> ';
        j += 25;
      } else {
        j += 25;
      }//end for loop if else
    }//end for loop

    //Display the last result if the number of reviews is not a multiple of 25
    if (count % 25) {
      DisplayString += '<div id="navA"><a href="#result' + count + '" id="goTo">Review ' + count + ' </a></div> ';//display the last result 
    }//end if 
  } else {
    //Return an error message if the page limit is greater than the number of reviews
    return "";
    // return "Nav Error";
  }//end outer if else

  return DisplayString;
}//end displayNav Function

// Function to display reviews
// Parameters:
// - reviews: an array of reviews to be displayed
// - pageLimit: the number of reviews to be displayed per page
// Returns: a string containing HTML code for the reviews
function displayReview(reviews, pageLimit) {
  let DisplayString = "";
  var count = reviews.length;
  let j = 1;
  const startIndex = 0;
  const endIndex = pageLimit - 1;
  //if the number of reviews to be displayed is less than or equal to the total number of reviews
  if (pageLimit <= count) {

    //loop through the reviews to be displayed
    for (let i = startIndex; i <= endIndex; i++) {

      //Retrieve the necessary review details
      let ID = reviews[i].revID;
      let star = reviews[i].starTotal;
      let comment = reviews[i].comments;
      let dateCreated = reviews[i].datecreated;
      let volunteerID = reviews[i].volunteerID;

      //Construct the HTML code for the review and append it to the DisplayString
      DisplayString += "<div class='reviews-members pt-4 pb-4'>" +
        "<div id='result" + j + "'class='media'>" +
        "<div class='media-body'>" +
        "<div class='reviews-members-header'>" +
        "<h2 class='mb-1'>" + j++ + ".<a class='text-black' href='#'>Review ID: " + ID + " - Volunteer ID: " + volunteerID + "</a> - Created Date: " + dateCreated + "</h2>" +
        "<div id='unclickable-rating-star-container'>";

      //Append the appropriate number of star icons based on the rating of the review
      for (let c = 1; c <= 5; c++) {
        DisplayString += c <= star ? "<span class='fa fa-star checked' onclick='return false;' data-rating='" + c + "'></span>" : "<span class='fa fa-star' data-rating='" + c + "'></span>";
      }//end inner for loop

      //Close the div tags and add comment text
      DisplayString += "</div>" +
        "</div>" +
        "<div class='reviews-members-body'>" +
        "<p>" + comment + "</p>" +
        "<hr>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>";
    }//end for loop

  } else {
    //If the number of reviews to be displayed is greater than the total number of reviews, return an error message
    return "Selected higher number than possible database entries ";
  }//end else if 

  return DisplayString;

}//end display review function

// This function generates HTML code for the star limit dropdown options
// Parameters:
// - starAmount: the maximum number of stars to be displayed in the dropdown
// Returns: a string containing HTML code for the dropdown options
function displaylimit(starAmount) {
  let DisplayString = "";
  const startIndex = 1;
  for (let i = startIndex; i <= starAmount; i++) {

    DisplayString += "<option value=" + i + ">" + i + "</option>";

  }//end for loop

  return DisplayString;

}//end displaylimit function


$(document).ready(function() {
  const starFilter = document.querySelector('#starDropdown');
  const pageLimit = document.querySelector('#pageDisplayLimit');
  const sortingOptions = document.querySelector('#sort');
  $('#pageDisplayLimit').hide();
  $('#sort').hide();
  console.log(pageLimit.value);

  //When the star filter dropdown changes, show the page limit and sorting dropdowns and update the available page limit options
  starFilter.addEventListener('change', function() {
    $('#pageDisplayLimit').show();
    $('#sort').show();

    //Reset the page limit to the default value of 5
    document.querySelector('#pageDisplayLimit').innerHTML = "<option value=" + 5 + " selected></option>"; // reset to the default

    // Get the selected star count filter and page limit value
    const starFilterValue = starFilter.value;
    const pageLimitValue = pageLimit.value;

    //-------------------------------------------------Order by options---------------------------------------------------------
    // When the sorting dropdown changes, retrieve the total number of reviews for the selected star count filter and update the available page limit options
    sortingOptions.addEventListener('change', function() {
      const sortingOptionsValue = sortingOptions.value;

      //if sorting by descending order
      if (sortingOptionsValue == 1) {

        //Send an AJAX request to retrieve the total number of reviews for the selected star count filter
        $.ajax({
          type: 'POST',
          url: 'get_review_count.php',
          data: { starFilter: starFilterValue },
          success: function(count) {
            let reviewCount = 0;
            let total = JSON.parse(count);
            reviewCount = total;

            //Update the available page limit options based on the total number of reviews for the selected star count filter
            document.querySelector('#pageDisplayLimit').innerHTML = displaylimit(reviewCount);
            // Display the reviews sorted by descending order
            displayReviewsByDescending(starFilterValue, pageLimitValue);
          }
        });

        //if sorting by ascending order
      } else if (sortingOptionsValue == 0) {
        //Send an AJAX request to retrieve the total number of reviews for the selected star count filter
        $.ajax({
          type: 'POST',
          url: 'get_review_count.php',
          data: { starFilter: starFilterValue },
          success: function(count) {
            let reviewCount = 0;
            let total = JSON.parse(count);
            reviewCount = total;

            //update the available page limit options based on the total number of reviews for the selected star count filter
            document.querySelector('#pageDisplayLimit').innerHTML = displaylimit(reviewCount);

            //display the reviews sorted by ascending order
            displayReviewsByAscending(starFilterValue, pageLimitValue);
          }
        });
      }//end else if 
    });

    //-------------------------------------------------End Order by---------------------------------------------------------

    // Sends an AJAX request to the server to retrieve the total number of reviews for a given star rating filter
    // Parameters:
    // - starFilterValue: the star rating filter value selected by the user
    // Returns: count of results in DB
    // On success sets the value of the page display limit dropdown to the number of reviews retrieved
    // and calls the displayReviewsByDescending or displayReviewsByAscending function based on the selected sorting option value
    $.ajax({
      type: 'POST',
      url: 'get_review_count.php',
      data: { starFilter: starFilterValue },
      success: function(count) {
        let reviewCount = 0;
        let total = JSON.parse(count);
        reviewCount = total;
        document.querySelector('#pageDisplayLimit').innerHTML = displaylimit(reviewCount);
        const sortingOptionsValue = sortingOptions.value;
        if (sortingOptionsValue == 1) {
          displayReviewsByDescending(starFilterValue, pageLimitValue);
        } else if (sortingOptionsValue == 0) {
          displayReviewsByAscending(starFilterValue, pageLimitValue);
        }
      }
    });
  });
  // End of star filter event listener block

  //calls the displayReviewsByDescending or displayReviewsByAscending function based on if the page limit is changed and the sortingOptionsValue value 
  pageLimit.addEventListener('click', function() {
    const starFilterValue = starFilter.value;
    const pageLimitValue = pageLimit.value;
    const sortingOptionsValue = sortingOptions.value;
    if (sortingOptionsValue == 1) {
      displayReviewsByDescending(starFilterValue, pageLimitValue);
    } else if (sortingOptionsValue == 0) {
      displayReviewsByAscending(starFilterValue, pageLimitValue);
    }
  });

  //displays the reviews after taking the array of results from get_review.php and reversing the array
  function displayReviewsByDescending(starFilterValue, pageLimitValue) {
    $.ajax({
      type: 'POST',
      url: 'get_review.php',
      data: { starFilter: starFilterValue, pageLimit: pageLimitValue },
      success: function(reviews) {
        let reviewData = JSON.parse(reviews);
        reviewData.reverse();
        document.querySelector('#reviewsContainer').innerHTML = displayReview(reviewData, pageLimitValue);
        document.querySelector('#navLinks').innerHTML = displayNav(reviewData, pageLimitValue);
      }
    });
  }

  //displays the reviews after taking the array of results from get_review.php
  function displayReviewsByAscending(starFilterValue, pageLimitValue) {
    $.ajax({
      type: 'POST',
      url: 'get_review.php',
      data: { starFilter: starFilterValue, pageLimit: pageLimitValue },
      success: function(reviews) {
        let reviewData = JSON.parse(reviews);
        document.querySelector('#reviewsContainer').innerHTML = displayReview(reviewData, pageLimitValue);
        document.querySelector('#navLinks').innerHTML = displayNav(reviewData, pageLimitValue);
      }
    });
  }
  //------------------------------------------------------------Back To Top Arrow-------------------------------------------------------------------------------

});



//################################## NAV LOGOUT BUTTON  END###############################


//The following code is for the return to top of the page arrow button that will appear if the user is scrolling
//and NOT currently at the top of the page
document.querySelector('#myBtn').addEventListener('click', function(event) {
  event.preventDefault();

  const targetElement = document.querySelector('body');
  const topOffset = targetElement.getBoundingClientRect().top + window.pageYOffset;
  window.scrollTo({
    top: topOffset,
    behavior: 'smooth'
  });
});
//end myBtn click event listener

// Get the button:
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {
  scrollFunction();
};
//end windows on scroll 

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }//end else if 
}//end scroll Function

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}//end top Function
