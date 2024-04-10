<?php
include_once("connect.php");
include_once("CLASSES/Volunteer.php");
$delUserEmail = $_REQUEST["volList"];
$reply;
$delUserId = Volunteer::GetUserIdByEmail($delUserEmail);
if ($delUserId != 0){
    if (Volunteer::SetVolunteerInactive($delUserId)){ 
        $reply = "User successfully deleted";
    }
    else{
        $reply = "Failed to delete user";
    }
}
else{
    $reply = "User was unable to be found";
}
header("location:employee.php?removeUserMessage=$reply");