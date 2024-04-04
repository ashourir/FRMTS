<?php
//include("CLASSES/Document.php");

(array) $docIds = $_POST["docCheck"];


foreach ($docIds as $docId) {
    // Construct the directory path
    $dirName = "./UPLOADS/" . "doc".$docId;

    // Check if the directory exists
    if (is_dir($dirName)) {
        // Attempt to remove the directory
        if (rmdir($dirName)) {
            $msg = "Scanned document removed successfully.";
        } else {
            $msg = "Failed to remove Scanned document.";
        }
    } else {
        $msg = "Directory not found.";
    }
}
header("location:employee.php?msg=$msg");