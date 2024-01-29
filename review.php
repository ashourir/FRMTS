<?php
session_start();
if (!isset($_SESSION['volunteer'])) {
  header('location:login.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("head.php"); ?>
  <link rel="stylesheet" type="text/css" href="./CSS/review.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

  <?php include("header.php"); ?>

  <!-- ################################################## MAIN CONTENT ################################################## -->
  <main>
    <div class="review-cont">

      <div class="review-form">

        <h1>Leave a Review</h1>
        <div class="rating-section">
          <h3>Select your rating:</h3>
          <div id="rating-star-container">
            <span class="fa fa-star" data-rating="1"></span>
            <span class="fa fa-star" data-rating="2"></span>
            <span class="fa fa-star" data-rating="3"></span>
            <span class="fa fa-star" data-rating="4"></span>
            <span class="fa fa-star" data-rating="5"></span>
          </div>
          <div id='star-error'>
            <span id='star-error-message'></span>
          </div>

          <input type="hidden" id="rating-value" name="rating-value" value="0">
        </div>
        <div class="comment-section">
          <h3>Leave a comment:</h3>
          <textarea id="comment-text" rows="5"></textarea>
          <div id="character-count">0/255</div>
        </div>
        <span id='comment-error-message'></span>
        <span id='success-message'></span>
        <button id="submit-review-btn">Submit</button>
      </div>


    </div>

  </main>


  <?php include("footer.php"); ?>
  <script type="module" src="./JS/review.js"></script>

</body>

</html>
