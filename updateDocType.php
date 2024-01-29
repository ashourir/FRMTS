<?php
include("connect.php");
include("CLASSES/DocumentType.php");
$message = "Make sure you submit the form.";

//make sure the user can't get here directly by typing it into their browser
if (isset($_POST["updateType"])) { 
    //grab data from the form  
    $typeId = $_POST["docTypeUpdate"];
    $typeCat = $_POST["docCategoryUpdate"];
    $typeDesc = $_POST["docTypeDescUpdate"];

    //set to a doctype object because the method needs it   
    $docType = new DocumentType(null, null, null);
    $docType->setTypeId($typeId);
    $docType->setCategory($typeCat);
    $docType->setDescription($typeDesc);
    
    //call update type method from the DocumentType class
    $success = DocumentType::UpdateType($docType);
    
    if ($success) $message = $docType->getCategory() . " successfully updated!";
    else $message = "Something went wrong. Please try again.";
    
}//end if to make sure they got here after the form
header("location:employee.php?msg=$message");