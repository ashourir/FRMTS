<?php
include("connect.php");
include("CLASSES/Document.php");
include("CLASSES/Collection.php");
include("INCLUDES/page_check.php");

$offset = ($currentPage - 1) * $limit;
$totalDocs = Document::getCountUserDocuments();
$totalPages = ceil($totalDocs / $limit);

//Gets available documents to display based on the users page settings, collection request
if ($coll != "all") {
  $availableDocuments = Document::getUserDocuments($offset, $limit, $coll);
} else {
  $availableDocuments = Document::getUserDocuments($offset, $limit);
}
$collectionList = Collection::GetCollectionsDropDown();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("head.php"); ?>
  <link rel="stylesheet" href="./CSS/styles.css">
  <link rel="stylesheet" href="./CSS/volunteer.css">
  <link rel="stylesheet" href="./CSS/view_transcribed.css">
</head>

<body>

  <?php
  include("header.php");
  ?>


  <!--TOP SEARCH BAR & PAGE CONTROLS -->
  <?php include_once('INCLUDES/page_controls.php'); ?>

  <main>
    <section class="container_completed_documents">
      <?php
      if ($availableDocuments) {
        foreach ($availableDocuments as $doc) {

          $docId = $doc['id'];
          $statusId = $doc['statusId'];
          $statusName = $doc['statusName'];
          $documentName = $doc['documentName'];
          $folderName = $doc['folderName'];
          $typeId = $doc['typeId'];
          $numPages = $doc['numPages'];
          $documentDescription = $doc['documentDescription'];
          $textFilePath = $doc['textFilePath'];
          $category = $doc['category'];
          $typeDesc = $doc['typeDesc'];
          $collectionName = $doc['collectionName'];
          $timePeriod = $doc['timePeriod'];
          $thumbnail_img = "";
          $carousel_img = "";
          $data = array($documentName, $category, $typeDesc, $collectionName, $timePeriod, $statusName);
          $searchable = json_encode($data);


          //GETS ALL THE IMAGES RELATED TO THE DOCUMENT
          $images = glob("./UPLOADS/$folderName/*.{jpg,png,gif,jpeg}", GLOB_BRACE);
          for ($i = 0; $i < count($images); $i++) {
            if (isset($images[$i]) && file_exists($images[$i])) {
              if ($i == 0) {
                $thumbnail_img = '<img class="thumbnail_img" src="' . $images[0] . '"/>';
                $carousel_img .= '<div class="carousel-item active"><img class="carousel_image" src="' . $images[$i] . '" /></div>';
              } else {
                $carousel_img .= '<div class="carousel-item"><img class="carousel_image" src="' . $images[$i] . '"/></div>';
              }
            }
          }


          //Creates the html thumbnails on the page
          echo <<<_THUMBNAIL
             <div class="doc_thumbnail" docId="$docId" data-search='$searchable' id="vtd_$docId" collection="$collectionName">
                <div class="doc_thumbnail_img"> 
                  $thumbnail_img 
                </div>
                <h8><b>$documentName</b></h8>
                <h9><i>$collectionName Collection</i></h9>
              </div>
            _THUMBNAIL;
        } //end foreach
      } else {
        echo "No Documents Available";
      }
      ?>
    </section>

  </main>

  <?php include("footer.php"); ?>
  <script type="text/javascript" src="./JS/page_control.js"></script>
  <script type="text/javascript" src="./JS/view_transcribed.js"></script>
</body>

</html>
