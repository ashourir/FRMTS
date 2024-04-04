<?php

include("./connect.php");
include("./CLASSES/Document.php");
include("./CLASSES/Page.php");
include("./CLASSES/Volunteer.php");
include("./CLASSES/Collection.php");

session_start();
if (!isset($_SESSION['volunteer'])) {
  header('location:index.php');
}

if (isset($_GET['msg'])) {
  $msg = $_GET['msg'];
  echo "<script>alert('$msg');</script>";
}

include("INCLUDES/page_check.php");

$offset = ($currentPage - 1) * $limit;
$volunteer = $_SESSION['volunteer'];
$currentWorkHistoryId = Volunteer::GetActiveDocumentId($volunteer);
$history = $volunteer->GetHistory();
$totalDocs = Document::getCountAvailableVolunteerDocuments($volunteer->volunteerId);
$totalPages = ceil($totalDocs / $limit);

if (isset($_SESSION['get_started'])) {
  $statusId = $_SESSION['get_started'];
  unset($_SESSION["get_started"]);
  $availableDocuments = Document::getAvailableVolunteerDocumentsByStatusId($volunteer->volunteerId, $statusId);
} else if ($coll == "all") {
  $availableDocuments = Document::getAvailableVolunteerDocuments($volunteer->volunteerId, $offset, $limit);
} else {
  $availableDocuments = Document::getAvailableVolunteerDocsByColl($volunteer->volunteerId, $coll, $offset, $limit);
}
$collectionList = Collection::GetCollectionsDropDown();

?>


<!DOCTYPE html>
<html lang="en">

<head>

  <?php include("head.php"); ?>
  <link rel="stylesheet" href="./CSS/styles.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="./CSS/review.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./CSS/volunteer.css" rel="stylesheet">

</head>

<body>



  <div class="sidebar">
    <img src="./IMAGES/logo.jpg" id="logo" />
    <div class="row sidebar_item" id="tab_home">Home</div>
    <div class="row sidebar_item" id="tab_cwork">Current Work</div>
    <div class="row sidebar_item" id="tab_pwork">Previous Work</div>
    <div class="row sidebar_item" id="tab_howto">How To<span class="bi bi-caret-down-fill" style="width:auto"></span> </div>
    <div class="drop_menu" style="display:none">
      <a href="#transcribe" class="row sub_item">- Transcribe</a>
      <a href="#postcard" class="row sub_item">- Postcard</a>
      <a href="#proofread" class="row sub_item">- Proofread</a>
      <a href="#faq" class="row sub_item">- FAQ</a>
      <a href="#tips" class="row sub_item">- Tips</a>
    </div>
    <div class="row sidebar_item" id="tab_chpass">Change Password</div>
    <div class="row sidebar_item" id="tab_review">Review</div>
    <div class="row sidebar_item" id="tab_logout">Log Out</div>
  </div>

  <div class="main_display">




    <!-- HOME -->
    <div class="option" id="home" style="display:none">
      <!--TOP SEARCH BAR & PAGE CONTROLS -->
      <?php include_once('INCLUDES/page_controls.php'); ?>


      <div class="available_documents">
        <div class="all_available_documents">
          <?php
          if ($availableDocuments) {
            include("modal_volunteer_document.php");
          } else {
            echo "No Documents of this collection available";
          }
          ?>
        </div>
        <div class="query_documents" style="display:none"> </div>
      </div>
      <!-- Div will hold result of search -->
      <div class="search_documents" style="display:none"> </div>
    </div>




    <!-- CURRENT WORK -->
    <div class="option" id="current_work" style="display:none">
      <?php
      if ($volunteer->activeDocId != -1) {

        include_once('./modal_complete_project.php');
        include_once('./modal_drop_project.php');

        $document = Document::getDocumentById($volunteer->activeDocId);
        $daysRemaining = Document::GetTimeRemaining($currentWorkHistoryId);
        $images = glob("./UPLOADS/$document->folderName/*.{jpg,png,gif}", GLOB_BRACE);
        if (isset($images[0]) && file_exists($images[0])) {
          $img = '<img class="thumbnail_img" src="' . $images[0] . '"/>';
        } else {
          $img = '<img class="default_img" src="./IMAGES/doc.svg" />';
        }



        echo <<<_DOC
          <div class="cw_thumbnail_img">
            $img
          </div>
          <div class="cw_details">
            <h8><b>$document->docName</b></h8>
            <h9><i>$document->description</i></h9>
            <h8>DAYS REMAINING: <b>$daysRemaining</b></h8>
            <div class="controls">
              <button id="cw_quit" data-bs-toggle="modal" data-bs-target="#modal_confirm_drop">Quit Project</button>
              <button id="cw_continue">Continue</button>
              <button id="cw_complete" data-bs-toggle="modal" data-bs-target="#modal_confirm_complete">Project Complete</button>
            </div>
          </div>

          <div class="cw_quit" style="display:none">
            <p>Are you sure you want to quit this project?</p>
            <div class="controls">
              <button id="cw_cancel_quit">Cancel</button>
              <button id="cw_confirm_quit">QUIT PROJECT</button>
            </div>
          </div>
          _DOC;
      } else {
        echo "No current work";
      }
      ?>
    </div>


    <!-- PREVIOUS WORK --->
    <div class="option" id="previous_work" style="display:none">
      <?php
      print($history);
      ?>
    </div>


    <!-- HOW TO -->
    <div class="option" id="section_howto" style="display:none">
      <div class="instructions">
        <?php include('./instructions.php'); ?>
      </div>
    </div>



    <!-- PASSWORD RESET FORM -->
    <div class="option" id="change_passwd" style="display:none">
      <form id="form__change_passwd">

        <label for="passwd">Enter a new password</label>
        <div class="form-floating p-2 passwords">
          <input type="password" id="passwd" class="form-control" />
          <span class="input-group-addon toggleVis">
            <i class="bi bi-eye" id="show_passwd"></i>
            <i class="bi bi-eye-slash" id="hide_passwd" style="display:none"></i>
          </span>
        </div>
        <br>

        <label for="conf">Confirm new password</label>
        <div class="form-floating p-2 passwords">
          <input type="password" id="conf" class="form-control" />
          <span class="input-group-addon toggleVis">
            <i class="bi bi-eye" id="show_conf"></i>
            <i class="bi bi-eye-slash" id="hide_conf" style="display:none"></i>
          </span>
        </div>
        <br>

        <button class="w-50 btn btn-lg btn-primary" id="submit__change_passwd">Change Password</button>

      </form>

      <div class="success_msg" style="display:none">
        <h2>Password changed</h2>
      </div>
    </div>

    <!-- REVIEW -->
    <div class="option" id="make_review" style="display:none">
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
    </div>

    <!-- LOG OUT -->
    <div class="option" id="logout" style="display:none">
      <p>Are you sure you want to log out?</p><br>
      <button class="w-50 btn btn-lg btn-primary" id="btn_logout">Yes</button>
    </div>

  </div>




  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://cdn.rawgit.com/mgalante/jquery.redirect/master/jquery.redirect.js"></script>
  <script type="text/javascript" src="./JS/utils.js"></script>
  <script type="text/javascript" src="./JS/page_control.js"></script>
  <script type="text/javascript" src="./JS/volunteer.js"> </script>
  <script type="module" src="./JS/review.js"></script>
</body>
</html>