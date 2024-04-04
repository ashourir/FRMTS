<?php
include("CLASSES/Document.php");


    if (isset( $_POST["docId"], $_POST["newDocumentName"])) {
        $docId = $_POST["docId"];
        $newDocName = $_POST["newDocumentName"];
        echo "$docId, $newDocName";
        
        $result = Document::UpdateDocName($docId, $newDocName);
        if ($result) {
            
            $msg = "Document Name changed successfully.";
        } else {
            $msg = "Failed to change Document Name changed.";
        }
    }
    else {
        $msg = "Error: No data received. Please make sure all required fields are filled.";
    }
    header("location:employee.php?msg=$msg");