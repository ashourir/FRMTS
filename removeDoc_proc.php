<?php

include("CLASSES/Document.php");

// Retrieve the document IDs from the POST request
$docIds = isset($_POST["docCheck"]) ? $_POST["docCheck"] : [];


// Function to remove a directory and its contents
function removeDirectory($dir) {
    if (!is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            removeDirectory($path);
        } else {
            unlink($path);
        }
    }

    return rmdir($dir);
}


foreach ($docIds as $docId) {
    
    $dirName = "./UPLOADS/" . "doc" . $docId;

    // Attempt to remove the directory and its contents
    if (removeDirectory($dirName)) {
        $msg = "Scanned document removed successfully.";
    } else {
        $msg = "Failed to remove Scanned document.";
    }

    // Attempt to make the document unavailable by ID
    $success = Document::MakeUnavailableById($docId);
    if (!$success) {
        $msg = "Something went wrong. Please try again.";
    } else {
        if (count($docIds) == 1) {
            $msg .= "Document removed.";
        } else {
            $msg .= "Documents removed.";
        }
    }
}


header("location: employee.php?msg=$msg");
?>

