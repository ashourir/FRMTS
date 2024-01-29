<?php
include("connect.php");
include("CLASSES/Collection.php");
$message = "";

//make sure the user can't get here directly by typing it into their browser
if (isset($_POST["createCollection"])) {
    
    $collectionName = $_POST["collectionName"];
    $timePeriod = $_POST["timePeriod"];
    $collectionDesc = $_POST["collDesc"];
    
    $collId = Collection::CreateCollection($collectionName, $timePeriod, $collectionDesc);
    echo "name: " . $collectionName . "<BR>";
    echo "collId in the proc page: " . $collId;
    print_r($_POST);
    
    
    $message = "Collection $collectionName created!";
    
}//end of making sure they came from the form

echo "message: " . $message . "<BR>";
header("location:employee.php?msg=$message");
// header("location:employee.php");
