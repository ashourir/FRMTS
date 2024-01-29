<?php

/**
 * Description of review
 *
 * @author justin
 */
class Review {

    private $revID;
    private $stars;
    private $comment;
    private $dateCreated;
    private $reviewVolunteeID;

    //methods go here:
    
    //insert review into the database 
    public static function AddReview(int $starsTotal, string $userComment, int $volID): bool {
        global $con;
        $stmt = $con->prepare("CALL AddNewReview(?,?,?)");
        $stmt->bind_param(
                'sss',
                $starsTotal,
                $volID,
                $userComment
        );
        $stmt->execute();
        return $stmt->affected_rows == 1;
    }//end addReview

    //get the review from the database to allow employee to see it:
    public static function GetAllReviews(int $limit) {
    //public static function GetAllReviews(int $limitMax, int $limitMin) {
        //print_r("Class Order: " .$order);
        global $con;
        $stmt = $con->prepare("CALL GetAllReviews(?)");
        //$stmt->bind_param('ss', $limitMax, $limitMin);
        $stmt->bind_param('s', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            return "invalid review";
        } else {
            return $result;
            //$revResult = mysqli_fetch_array($result);
//            return $review = new Review(
//                    $revResult['revID'],
//                    $revResult['starTotal'],
//                    $revResult['comments'],
//                    $revResult['datecreated']
//            );
        }//end else if 
    }//end getall
    
    
    public static function GetReviewByStars(int $stars, int $limit) {
        global $con;
        $stmt = $con->prepare("CALL GetReviewByStars(?,?)");
        $stmt->bind_param(
                'ss',
                $limit,
                $stars
        );
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return "invalid review";
        } else {
            return $result;
//            $revResult = mysqli_fetch_array($result);
//            return $review = new Review(
//                    $revResult['revID'],
//                    $revResult['starTotal'],
//                    $revResult['comments'],
//                    $revResult['datecreated']
//            );
        }//end else if 
    }//end get by starts

    public static function GetReviewCount() {
        global $con;
        $stmt = $con->prepare("CALL reviewCountTotal()");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_array($result);
        return $row["TotalCount"];

    }//end getCount
    
    public static function GetReviewCountByStars($starNum) {
        global $con;
        $stmt = $con->prepare("CALL reviewCountByStars(?)");
        $stmt->bind_param('s', $starNum);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_array($result);
        return $row["TotalCount"];

    }//end getCount
    
    //getters and setters:
    public function getRevID() {
        return $this->revID;
    }

    public function getStars() {
        return $this->stars;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getReviewVolunteeID() {
        return $this->reviewVolunteeID;
    }

    public function setRevID($revID): void {
        $this->revID = $revID;
    }

    public function setStars($stars): void {
        $this->stars = $stars;
    }

    public function setComment($comment): void {
        $this->comment = $comment;
    }

    public function setDateCreated($dateCreated): void {
        $this->dateCreated = $dateCreated;
    }

    public function setReviewVolunteeID($reviewVolunteeID): void {
        $this->reviewVolunteeID = $reviewVolunteeID;
    }

    public function __construct($revID, $stars, $comment, $dateCreated, $reviewVolunteeID) {
        $this->revID = $revID;
        $this->stars = $stars;
        $this->comment = $comment;
        $this->dateCreated = $dateCreated;
        $this->reviewVolunteeID = $reviewVolunteeID;
    }


}//end class review

