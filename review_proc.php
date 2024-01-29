<?php

require_once('connect.php');
require_once('./CLASSES/Review.php');
require_once('./CLASSES/Volunteer.php');

session_start();

//Check if volunteer is logged in
if(!isset($_SESSION['volunteer'])) {
    header('location:login.php');
} else {
    $volunteer = $_SESSION['volunteer'];

    //Check if volunteer object is set
    if(isset($volunteer)) {
        // Get volunteer ID from session variable
        $volunteerID = $volunteer->volunteerId;

        //Check if form was submitted to add a new review
        if (isset($_POST['addNewReview'])) {
            //Get stars and comment from form input
            list($stars, $comment) = explode('|', $_POST['addNewReview']);

            //Add new review to the database
            $result = Review::AddReview($stars, $comment, $volunteerID);

            //Check if review was successfully added
            if ($result == 1) {
                echo "$result";
            } else {
                echo"Failed to add new review";
            }
        }//end if addNewReview
    } else {
        echo "2";
    }//end inner else if 
}//end outer else if