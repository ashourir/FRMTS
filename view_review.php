<?php
require_once('connect.php');
//add functionality to make sure they are logged in and have the proper authority for these tabs!!
session_start();
if (!isset($_SESSION['employee'])) {
  header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("e_head.php"); ?>
  <link rel="stylesheet" type="text/css" href="./CSS/review.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./CSS/transcription.css" rel="stylesheet">

</head>

<body>

  <?php include("empHeader.php"); ?>

  <!-- ################################################## MAIN CONTENT ################################################## -->
  <main>
    <div class="container">
      <?php //echo displayReviews(10) 
      ?>
      <div class="bg-white rounded shadow-sm p-4 mb-4 detailed-ratings-and-reviews">
        <h1 class="mb-1">View Ratings and Reviews</h1>
        <hr>

        <form>
          <label for="starDropdown">Filter by Stars:</label>
          <select id="starDropdown" name="starDropdown">
            <option selected>--Select Number of Stars--</option>
            <option value="All">All</option>
            <option value="1">Stars 1</option>
            <option value="2">Stars 2</option>
            <option value="3">Stars 3</option>
            <option value="4">Stars 4</option>
            <option value="5">Stars 5</option>
          </select>
          <label for="sort">Sort(optional):</label>
          <select name="sort" id="sort">
            <option value="0" selected>--Order By--</option>
            <option value="1">Newest</option>
            <option value="0">Oldest</option>
          </select>
          <hr>
          <label for="pageDisplayLimit">Select Display Amount:</label>
          <select id="pageDisplayLimit" name="pageDisplayLimit">
            <option value="5" selected></option>
          </select>
          <br>
          <dir id="navLinks"></dir>

        </form>
        <hr>

        <div id="reviewsContainer"> </div>

      </div>
      
      <button id="myBtn" title="Go to top"> <svg xmlns="http://www.w3.org/2000/svg" class="back-to-top-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
        </svg>
      </button>

    </div>

  </main>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script type="text/javascript" src="./JS/view_review_nav.js"></script>
  <script type="text/javascript" src="JS/elogout.js"></script>

</body>

</html>
