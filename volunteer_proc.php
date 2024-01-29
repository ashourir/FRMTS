<?php

require_once('connect.php');
require_once('./CLASSES/Volunteer.php');
require_once('./CLASSES/Collection.php');
require_once('./CLASSES/Document.php');

session_start();
$volunteer = $_SESSION['volunteer'];

if (isset($_POST['updatePassword'])) {

  $id = $volunteer->volunteerId;
  $passwd = $_POST['updatePassword'];
  $result = Volunteer::UpdatePassword($id, $passwd);
  echo "$result";
}



if (isset($_POST['selectDocument'])) {
  $docId = (int)$_POST['selectDocument'];
  $statusId = (int)$_POST['statusId'];

  //check if they volunteer already has an active document
  if ($volunteer->activeDocId == -1) { //they dont have an active document so proceed
    $result = Volunteer::StartNewProject($volunteer->volunteerId, $docId, $statusId);

    if ($result) {
      $volunteer->activeDocId = $docId;
      if (Volunteer::IsFirstJobType($volunteer->volunteerId, $statusId) == 1) {
        echo "first";
      } else {
        echo "success";
      }
    } else { //stored proc didn't fully execute and rolled back
      echo "failure";
    }
  } else {
    echo "currentWork";
  }
}


if (isset($_POST['checkHasWork'])) {
  if (!$volunteer->activeDocId == -1) {
    return true;
  } else {
    return false;
  }
}


if (isset($_POST['updateProjectStatus'])) {
  $docId = $volunteer->activeDocId;
  $status = $_POST['updateProjectStatus'];
  $result = Document::UpdateVolunteerDocument($docId, $status);
  if ($result) {
    $volunteer->activeDocId == -1;
    echo "success";
  } else {
    echo "error";
  }
}
