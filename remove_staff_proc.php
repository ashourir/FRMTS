<?php
include_once("connect.php");
include_once("CLASSES/Employee.php");
$delStaffUsername = $_REQUEST["staffList"];
$reply;
$delStaffId = Employee::GetStaffIdByUsername($delStaffUsername);
if ($delStaffId != 0){
    if (Employee::SetStaffInactive($delStaffId)){ 
        $reply = "User successfully deleted";
    }
    else{
        $reply = "Failed to delete user";
    }
}
else{
    $reply = "User was unable to be found";
}
header("location:employee.php?removeStaffMessage=$reply");