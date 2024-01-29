<?php
include_once("connect.php");
include_once("CLASSES/Employee.php");
$searchText = $_REQUEST["q"];
$usernames = Employee::SearchStaffUsernames($searchText);
$datalist = "";
if ($usernames === ""){
    echo "No matching emails";
}
else{
    foreach ($usernames as $username){
        $datalist .= "<option value='$username'></option>";
    }
    echo $datalist;
}