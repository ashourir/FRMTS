<?php
include("connect.php");
include("CLASSES/DocumentType.php");
$message = "Make sure you submit the form.";


//make sure the user can't get here directly by typing it into their browser
if (isset($_POST["addType"])) { 
    $docType = new DocumentType(null, null, null);
    $docType->setCategory($_POST["docCategory"]);
    $docType->setDescription($_POST["docTypeDesc"]);
    $success = DocumentType::InsertType($docType);
    if ($success) $message = $docType->getCategory() . " successfully added!";
    else $message = "Something went wrong. Please try again.";
    
}//end if to make sure they got here after the form
header("location:employee.php?msg=$message");

