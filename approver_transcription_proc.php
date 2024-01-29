<?php
include("CLASSES/Employee.php");
include("connect.php");

session_start();


if (isset($_POST['employeeDocId'])) {
    $empId = $_SESSION["employee"];
    $docId = $_POST['employeeDocId'];
    $success = Employee::UpdateDocumentStatusAndEmployeeActiveDocId($empId, $docId);
    return $success;
}


if (isset($_POST['approveDocument'])) {
    $empId = $_SESSION["employee"];
    $docId = $_POST['approveDocument'];
    $success = Employee::SetDocumentAsCompleteAndEmployeeActiveId($empId, $docId);
    return $success;
}


if (isset($_POST["employeeDropDocument"])) {
  $docId = $_POST["employeeDropDocument"];
  $empId = $_SESSION["employee"];
  $success = Employee::employeeDropDocument($empId, $docId);
  return $success;
}

if (isset($_POST["rejectDocument"])) {
  $docId = $_POST["rejectDocument"];
  $empId = $_SESSION["employee"];
  $success = Employee::employeeRejectDocument($empId, $docId);
  return $success;
}

if (isset($_POST["deleteTxtFiles"])) {
  $folderName = $_POST["deleteTxtFiles"];
  $txtFiles = glob("./UPLOADS/" . $folderName . "/" . '*.{txt}', GLOB_BRACE);
  array_map('unlink', $txtFiles);
}

