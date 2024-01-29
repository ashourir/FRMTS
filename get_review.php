<?php

include_once('connect.php');
include_once("CLASSES/Review.php");
session_start();

$starFilter = $_POST['starFilter'];
$pageLimit = $_POST['pageLimit'];

if ($starFilter !== 'All') {
    $reviews = getFilteredReviews($starFilter, $pageLimit);
} else {
    $reviews = getAllReviews($pageLimit);
}

if ($reviews != null) {
    echo json_encode($reviews);
}else {
   // echo json_encode("failed to upload review")
    echo "failed to upload review";
    
}

function getFilteredReviews($stars, $limit) {
    $reviews = array();

    $results = Review::GetReviewByStars($stars, $limit);
    //$reviews = [];
    while ($row = mysqli_fetch_assoc($results)) {
        $reviews[] = $row;
    }
    return $reviews;
}

function getAllReviews($limit) {
    $reviews = array();
    //echo "$order";

    $results = Review::GetAllReviews($limit);
    //$reviews = [];
    while ($row = mysqli_fetch_assoc($results)) {
        $reviews[] = $row;
    }

    return $reviews;
}