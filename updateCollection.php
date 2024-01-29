<?php
include("connect.php");
include("CLASSES/Collection.php");
$message = "Make sure you submit the form.";

//make sure the user can't get here directly by typing it into their browser
if (isset($_POST["updateCollection"])) { 
    //grab data from the form
    $collId = $_POST["collectionUpdate"];
    $collName = $_POST["collNameUpdate"];
    $timePeriod = $_POST["timePeriodUpdate"];
    $collDesc = $_POST["collDescUpdate"];

    //set to a doctype object because the method needs it  
    $newColl = new Collection(null, null, null, null);
    $newColl->collectionId = $collId;
    $newColl->name = $collName;
    $newColl->timePeriod = $timePeriod;
    $newColl->description = $collDesc;
     
    //call update collection method from the Collection class
    $success = Collection::UpdateCollection($newColl);

    
    if ($success) $message = $newColl->name . " successfully updated!";
    else $message = "Something went wrong. Please try again.";
    
}//end if to make sure they got here after the form
header("location:employee.php?msg=$message");