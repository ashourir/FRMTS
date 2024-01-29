<?php

include_once('connect.php');
include_once("CLASSES/Review.php");
session_start();

$starFilter = $_POST['starFilter'];
//$starFilter = 'All';
if ($starFilter !== 'All') {
    $countTotal = countFilteredReviews($starFilter);

} else {
    $countTotal = countTotalReviews();

}
echo json_encode($countTotal);

function countTotalReviews(): string {

    $results = Review::GetReviewCount();

    return $results;
}

function countFilteredReviews($star): string {

    $results = Review::GetReviewCountByStars($star);
    return $results;
}



