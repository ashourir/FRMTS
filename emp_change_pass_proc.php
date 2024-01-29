<?php
include("connect.php");
include("./CLASSES/Employee.php");
session_start();
$reply = "";
$empId = $_SESSION["employee"];
$newPass = password_hash($_POST["newPass1"], PASSWORD_DEFAULT);
$currPass = $_POST["txtCurrent"];
$currentPassCorrect = Employee::CheckEmployeePassword($empId, $currPass);
if ($currentPassCorrect == "The password you entered matches your current password") {
  $isChanged = Employee::UpdateEmployeePassword($empId, $newPass);
  if ($isChanged == 1) {
    $reply = "Password successfully updated";
  } else {
    $reply = "Password could not be updated";
  }
} else {
  $reply = $currentPassCorrect;
}
echo $reply;
header("location:emp_change_passwd.php?reply=$reply");

