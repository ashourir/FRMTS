<?php
include_once("connect.php");
include_once("CLASSES/Volunteer.php");
$searchText = $_REQUEST["q"];
$emails = Volunteer::SearchVolunteerEmails($searchText);
$datalist = "";
if ($emails === ""){
    echo "No matching emails";
}
else{
    foreach ($emails as $email){
        $datalist .= "<option value='" . $email . "'>$email</option>";
    }
    echo $datalist;
}