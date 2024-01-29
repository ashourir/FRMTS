import * as Utils from "./utils.js";

//initialize variables:
const commentErrorMessage = document.getElementById('comment-error-message');
const starErrorMessage = document.getElementById('star-error-message');

const starElements = document.querySelectorAll('.fa-star');
const ratingValueInput = document.querySelector('#rating-value');
const commentText = document.querySelector('#comment-text');
const submitReviewBtn = document.querySelector('#submit-review-btn');

// Function to add new review
// Parameters:
// - stars: an number to messure the number of stars selected
// - comment: a string with the users comment input 
// Returns: 1 if successfully added review
function AddNewReviewJS(stars, comment) {
    let xml = new XMLHttpRequest();
    xml.open("POST", "review_proc.php");
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (JSON.parse(this.responseText)) {
                const response = JSON.parse(this.responseText);
                document.getElementById("success-message").innerHTML = "Your submission was successful! Thank you for taking the time to leave a review!";//-----------where do i want to send the user after successfull submit??
                submitReviewBtn.setAttribute("disabled", "true");
                submitReviewBtn.style.backgroundColor = 'green';
                //clear the texboxes and stars or send to new location:
                commentText.value = '';
                starElements.forEach(star => {
                    star.classList.remove('checked');
                });
            }
        }
    };
    xml.send(`addNewReview=${stars}|${comment}`);
}

//when page is ready:
$(document).ready(function () {

    const characterCount = document.querySelector('#character-count');

    commentText.addEventListener('input', (e) => {
        
        //Get the number of characters entered in the comment box
        const charactersEntered = e.target.value.length;
        
        //Update the character count display
        characterCount.innerHTML = `${charactersEntered}/255`;

        //if the number of characters entered is more than the limit show an error message and disable the submit button else enable the submit button and hide the error message
        if (charactersEntered > 255) {
            submitReviewBtn.setAttribute("disabled", "true");
            //commentText.setAttribute("disabled", "true");
            commentErrorMessage.style.display = 'inline-block';
            commentErrorMessage.innerHTML = 'You have gone over the Character limit for this review!';
            characterCount.style.color = 'red';
            commentText.style.borderWidth = '0.25em';
            commentText.style.borderColor = 'red';
        } else {
            submitReviewBtn.removeAttribute("disabled");
            //commentText.removeAttribute("disabled");
            commentErrorMessage.style.display = 'none';
            commentErrorMessage.innerHTML = '';
            characterCount.style.color = 'black';
            commentText.style.borderWidth = '0.05em';
            commentText.style.borderColor = '';
        }
    });

    //Add an event listener to each star element to display correct number of stars selected as orange
    starElements.forEach(starElement => {
        starElement.addEventListener('click', () => {
            
            //Get the rating value from the star element
            const ratingValue = starElement.getAttribute('data-rating');
            
            //Set the rating value input to the selected rating value
            ratingValueInput.value = ratingValue;
            
            const rating = ratingValueInput.value;
            //Check if a rating has been selected else show an error message and disable the submit button
            if (rating !== '0') {
                submitReviewBtn.removeAttribute("disabled");
                starErrorMessage.innerHTML = '';
                starErrorMessage.style.display = 'none';
                commentErrorMessage.innerHTML = '';
            } else {
                submitReviewBtn.setAttribute("disabled", "true");
                starErrorMessage.style.display = 'inline-block';
                starErrorMessage.innerHTML = 'Please select a star rating';
            }//end eles if 
            

            //Set the checked class on star elements up to the selected rating value, and remove it from others
            starElements.forEach(star => {
                if (star.getAttribute('data-rating') <= ratingValue) {
                    star.classList.add('checked');
                } else {
                    star.classList.remove('checked');
                }
            });
        });
    });

    //Add an event listener to the submit button that will run the addReviewJS() function 
    //as long as all inputs are good
    submitReviewBtn.addEventListener('click', () => {
        
        //Get the rating and comment values
        const rating = ratingValueInput.value;
        const comment = commentText.value;
        // Add logic here to send the rating and comment to the server
        //Check if the rating and comment values are not empty if not call the AddNewReviewJS function to submit the review
        if (rating !== '0') {
            starErrorMessage.style.display = 'none';
            if (comment !== '') {
                starErrorMessage.innerHTML = '';
                starErrorMessage.style.display = 'none';
                commentErrorMessage.innerHTML = '';
                AddNewReviewJS(rating, comment);
            } else {
                commentErrorMessage.style.display = 'inline-block';
                commentErrorMessage.innerHTML = 'Please enter a comment';
            }//end inner if 
        } else {
            starErrorMessage.style.display = 'inline-block';
            starErrorMessage.innerHTML = 'Please select a star rating';

        }//end eles if isNull

    });

});

