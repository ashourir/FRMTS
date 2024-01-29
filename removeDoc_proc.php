<?php
include("CLASSES/Document.php");

(array) $docIds = $_POST["docCheck"];

for ($i=0; $i<count($docIds); $i++) {
    $success = Document::MakeUnavailableById($docIds[$i]);
    if (!$success) {
        $msg = "Something went wrong. Please try again.";
    } 
    else {
        if (count($docIds)==1) $msg = "Document removed.";
        else $msg = "Documents removed.";
    }
}
header("location:employee.php?msg=$msg");