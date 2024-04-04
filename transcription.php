<?php

include ("./connect.php");
include ("./CLASSES/Document.php");
include ("./CLASSES/Volunteer.php");
include ("./CLASSES/Employee.php");

session_start();
if (!isset ($_SESSION['volunteer'])) {
  if (!isset ($_SESSION['employee'])) {
    if (!isset ($_POST['viewTranscribedDocId'])) {
      header('location:index.php');
    }
  }
}

if (isset ($_GET['msg'])) {
  $msg = $_GET['msg'];
  echo "<script>alert($msg)</script>";
}

if (isset ($_SESSION['volunteer'])) {
  $volunteer = $_SESSION['volunteer'];
  $document = Document::getDocumentById($volunteer->activeDocId);
  $currentWorkHistoryId = Volunteer::GetActiveDocumentId($volunteer);
  $daysRemaining = Document::GetTimeRemaining($currentWorkHistoryId);
  $btnHome = '<button title="Go to Volunteer Dashboard" type="button" class="btn btn-primary" id="btnVolunteerHome"> <i class="material-icons">house</i> </button>';
  $strDaysRemaining = '
              <label class="form-label form-control-sm" id="daysRemaining">
              DAYS REMAINING: ' . $daysRemaining . '
            </label>
';
  $blockTranscription = '';
  $blockNotes = '';


}

if (isset ($_SESSION['employee'])) {

  $empId = $_SESSION['employee'];
  $document = Document::getDocumentByEmpId($empId);
  $btnHome = '<button title="Go to Employee page" type="button" class="btn btn-primary" id="btnEmployeeHome"> <i class="material-icons">house</i> </button>';
  $strDaysRemaining = '';
  $blockTranscription = '';
  $blockNotes = '';
}

if (isset ($_POST['viewTranscribedDocId'])) {
  $docId = $_POST['viewTranscribedDocId'];
  $document = Document::getDocumentById($docId);
  $btnHome = '<button title="Go to View Transcribed Documents page" type="button" class="btn btn-primary" id="btnViewTranscDocHome"> <i class="material-icons">house</i> </button>';
  $strDaysRemaining = '';
  $blockTranscription = '<script>$(\'#txtTranscription\').attr(\'readonly\', true);</script>';
  $blockNotes = '<script>$(\'#txtNotes\').attr(\'readonly\', true);</script>';


}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include ("./head.php"); ?>

  <link rel="stylesheet" href="./CSS/transcription.css" rel="stylesheet">
  <link rel="stylesheet" href="./CSS/bookView.css" rel="stylesheet">
</head>


<body>

  <?php include ("./header.php"); ?>

  <!-- ################################################## MAIN CONTENT ################################################## -->
  <main>

    <!-- This JS code will generate the canvas with zoomable image -->
    <div class="container">
      <div class="row align-items-center">
        <div class="col-8 text-start align-items-center d-flex">
          <p class="display-4" id="txtTitle">
            <?php echo $document->docName; ?>
          </p>
        </div>
        <div class="col-4 text-end p-0" id="toolbarDiv">
          <div class="btn-group" role="group" aria-label="Basic example">
            <button title="Previous page" type="button" class="btn btn-primary" id="btnPrev"> <i
                class="material-icons">chevron_left</i> </button>
            <?php echo $btnHome; ?>
            </button>
            <button title="Next Page" type="button" class="btn btn-primary" id="btnNext"> <i
                class="material-icons">chevron_right</i> </button>
          </div>
          <div class="btn-group" role="group" aria-label="Basic example">
            <button title="Download this collection as a PDF file" type="button" class="btn btn-primary" id="print_pdf">
              <i class="material-icons">picture_as_pdf</i> </button>
            </button>
            <button title="View as a book" type="button" class="btn btn-primary" id="btnBookView">
              <i class="material-icons">book</i>
            </button>
          </div>
        </div>
      </div>
      <!-- This is the Code for the Book Viewer Popup-->

      <dialog id="bookViewDialog">
        <h2>Book View</h2>
        <div id="book"></div>
        <button title="Close Viewer" type="button" class="btn btn-primary" id="closeDialog"> <i
                class="material-icons">close</i> </button>
        <button title="Next page" type="button" class="btn btn-primary" id="prevPage"> <i
                class="material-icons">chevron_left</i> </button>
        <button title="Next page" type="button" class="btn btn-primary" id="nextPage"> <i
                class="material-icons">chevron_right</i> </button>
      </dialog>
      <!-------------------------------------------------->
      <div class="row mt-2 mb-2 border">
        <div class="col col-md-8 col-xl-8 p-2">
          <div id="openseadragon1" style="width: auto; height: 100%;"></div>
        </div>
        <div class="col">
          <div class="row">
            <?php Document::GetDocumentsStatusByStatusId($document->statusId); ?>
          </div>
          <div class="row">
            <?php echo $strDaysRemaining; ?>
          </div>
          <div class="row">
            <div class="mb-3">
              <label for="txtTranscription" class="form-label">Transcription</label>
              <textarea class="form-control" name="txtTranscription" id="txtTranscription" rows="10"></textarea>
            </div>
          </div>
          <div class="row">
            <div class="mb-3">
              <label for="txtNotes" class="form-label">Notes (optional)</label>
              <textarea class="form-control" name="txtNotes" id="txtNotes" rows="3"></textarea>
            </div>

            <div class="row">
              <?php Document::GetButtonsByDocumentStatusId($document->statusId) ?>

            </div>


          </div>
        </div>
      </div>
    </div>
    <!-- rendered content to be used in the jsPDF file generator -->
    <div class="d-none" id="createPDFCanvas">

    </div>

    <!-- modal dialog boxes -->
    <!-- Confirm modal will appear when the transcriber volunteer clicks on the complete button -->
    <?php include_once ('./modal_complete_project.php'); ?>

    <!-- Confirm modal will appear when the transcriber volunteer clicks on the drop button -->
    <?php include_once ('./modal_drop_project.php'); ?>

    <!-- Confirm modal will appear when the employee clicks on the drop button -->
    <?php include_once ('./modal_employee_drop_project.php'); ?>

    <!-- Confirm modal will appear when the employee clicks on the reject button -->
    <?php include_once ('./modal_employee_reject_project.php'); ?>

    <!-- Confirm modal will appear when the proofread volunteer clicks on the complete button -->
    <?php include_once ('./modal_complete_proofread.php'); ?>

    <!-- Confirm modal will appear when the proofread volunteer clicks on the complete button -->
    <?php include_once ('./modal_complete_approve.php'); ?>

  </main>

  <?php include ("./footer.php"); ?>

  <!-- To use php variables in javascript code in separated files, I need to put the value inside variables in json. -->
  <!-- https://stackoverflow.com/questions/3352576/how-do-i-embed-php-code-in-javascript -->
  <script>
    var folderName = <?php echo json_encode($document->folderName); ?>;
  </script>
  <script>
    var docId = <?php echo json_encode($document->docId); ?>;
  </script>


  <script src="./JS/openseadragon/openseadragon.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
  <script src="./JS/jsPDF-1.3.2/jspdf.js" crossorigin="anonymous"></script>
  <script src="./JS/jsPDF-1.3.2/plugins/addimage.js"></script>
  <script src="./JS/jsPDF-1.3.2/plugins/split_text_to_size.js"></script>
  <script src="./JS/turn.js-master/turn.min.js"></script>
  <script>
    
  function initializeTurnJsBook() {
    // Check the pdfarray
    if (!window.pdfArray || !window.pdfArray.images || window.pdfArray.images.length === 0) {
        console.log('No images available for Turn.js book.');
        return;
    }

    console.log('PDF Array:', window.pdfArray);

    // Empty the container element
      $('#book').empty();

    // Create and append image elements to the book container
    window.pdfArray.images.forEach(imageUrl => {
        console.log('Adding page with image URL:', imageUrl);
        const pageElement = $('<div class="hard"></div>'); // Use "hard" for hardcover effect
        const imgElement = $('<img>').attr('src', imageUrl).css({width: '100%', height: '100%'});
        pageElement.append(imgElement);
        $('#book').append(pageElement); // Append page to the book container directly
    });

    // Initialize Turn.js on the book element
    $('#book').turn({
        width: 800,
        height: 500,
        autoCenter: true
    });

    $('#prevPage').click(function() {
        $('#book').turn('previous'); // Turn to the previous page
    });

    // Event listener for the "Next Page" button
    $('#nextPage').click(function() {
        $('#book').turn('next'); // Turn to the next page
    });
}

</script>
<style>
</style>
<script src="./JS/transcription.js"></script>
  <?php
  echo $blockTranscription;
  echo $blockNotes
    ?>

</body>

</html>