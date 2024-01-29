<?php
include("connect.php");
include("CLASSES/Document.php");
include("CLASSES/Collection.php");
$message = "";
session_start();

if (!isset($_POST["docType"])) {
  header("location:index.php");
} else {
  //attempt to upload the file
  if (empty($_FILES["document"])) {
    $message = "Please upload a document";
    header("location:employee.php?msg=$message");
  } //end if no file/is empty

  //grab data from form
  $docName = sanitizeSQL($_POST["docName"]);
  $docType = $_POST["docType"];
  $coll = $_POST["collection"];
  $desc = sanitizeSQL($_POST["docDesc"]);
  $numPages = $_POST["numPages"];
  $pagesOrder = explode(",", $_POST["pageOrder"]); //will have the order or 0 if only one page!


  //insert a record, grab the doc id
  $doc = new Document();
  $doc->docName = $docName;
  $doc->statusId = 1;
  $doc->typeId = $docType;
  $doc->folderName = "temp";
  $doc->numPages = $numPages;
  $doc->description = $desc;

  $docId = Document::InsertDocument($doc);
  echo "docID after insert: $docId <BR>";

  //insert record in collection-document map table
  $successColl = Collection::InsertCollDocMap($coll, $docId);
  echo "colldocmap success?: $successColl <BR>";


  //make directory with doc id only (to make sure the folder can be created so no user input can mess this up)
  $folderName = "doc" . $docId;
  $dirName = "./UPLOADS/" . $folderName;

  mkdir($dirName);
  echo "dirNAme: $dirName <BR>";

  //update folderName field in record
  $successUpdate = Document::UpdateFolderName($docId, $folderName);
  echo "successUpdate?: $successUpdate <BR>";

  //add the image files to the directory!
  if ($numPages == 1) {

    //only one page for the document:
    $destFile = $dirName . "/1_" . $_FILES["document"]["name"][0];
    echo "destFile:     " . $destFile . "<BR>";
    if (move_uploaded_file($_FILES["document"]["tmp_name"][0], $destFile)) {
      $message = "Document successfully uploaded!";
      header("location:employee.php?msg=$message");
    } else { //did not move to the UPLOADS folder properly
      $message = "Something went wrong. Please try again.";
      rmdir($dirName);
      //if the move doesn't work, 'delete' the record (make the doc unavailable - statusId of 0)
      Document::MakeUnavailableById($docId);
      header("location:employee.php?msg=$message"); //I don't want to finish the loop
    }
  } else {
    //more than one page in the document:
    //loop through pagesOrder:
    for ($i = 0; $i < $numPages; $i++) {
      $name = $pagesOrder[$i];
      echo "name in for loop of pagesOrder array: $name <BR>";
      
      //loop through files and move to folder
      for ($j = 0; $j < $numPages; $j++) {
        $pageNum = $i + 1;
        if ($name == $_FILES["document"]["name"][$j]) {
          $destFile = $dirName . "/" . $pageNum . "_" . $_FILES["document"]["name"][$j];
          echo "destFile:     " . $destFile . "<BR>";

          if (move_uploaded_file($_FILES["document"]["tmp_name"][$j], $destFile)) {
            $message = "Files successfully uploaded!";
            header("location:employee.php?msg=$message");
          } else { //did not move to the UPLOADS folder properly

            rmdir($dirName);
            //if the move doesn't work, 'delete' the record (make the doc unavailable - statusId of 0)
            Document::MakeUnavailableById($docId);
            header("location:employee.php?msg=Something went wrong. Please try again."); //I don't want to finish the loop
          } //end move didn't work
        } //end if name is the right file
      } //end looping through files
    } //end looping through the pagesOrder array
  }
} //end if to make sure they got here after the form
