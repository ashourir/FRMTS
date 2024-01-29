<?php
include("CLASSES/Document.php");
include("CLASSES/Employee.php");

session_start();

//get the collection id from the AJAX request:
if (isset($_REQUEST["q"])) {
    $collId = $_REQUEST["q"];
    $radiobuttons = Document::GetDocumentsRadioBtnSingleCollection($collId);
    echo $radiobuttons;
}

if (isset($_POST["getDocumentDetails"])) {
    $docId = $_POST["getDocumentDetails"];
    $document = Document::getDocumentById($docId);
    $folderName = $document->folderName;

    $imagesPaths = glob("./UPLOADS/$folderName/" . '*.{jpg,png,jpeg}', GLOB_BRACE);
    $txtFiles = glob("./UPLOADS/$folderName/" . '*.{txt}', GLOB_BRACE);
    $responseArray = array();
    foreach ($imagesPaths as $imagePath) {
        $imageName = pathinfo($imagePath, PATHINFO_FILENAME);
        $txtFilePath = "./UPLOADS/$folderName/$imageName.txt";
        $content = "";

        if (file_exists($txtFilePath)) {
            $fileSize = filesize($txtFilePath);
            $handle = fopen($txtFilePath, "r");
            if ($fileSize) {
                $content = fread($handle, filesize($txtFilePath));
            }
        }

        $newArray = array(
            "image" => '<td><img src="' . $imagePath . '" width="180" height="180" alt=""/></td>',
            "textarea" => '<td><textarea id="' . $docId . '"  class="transcriptionText" rows="4" cols="50" name="' . $imageName . '" placeholder="Please enter the transcription text here ">' . $content . '</textarea>',
        );
        array_push($responseArray, $newArray);
    }

    echo json_encode($responseArray);
}

if (isset($_POST["txtFileName"])) {
    $empId = $_SESSION["employee"];
    $txtFileName = $_POST["txtFileName"];
    $transcriptionText = $_POST["transcribedText"];
    $docId = $_POST["docId"];

    $docFolderPath = Document::getDocumentFolderPathByDocId($docId);
    $completeTxtFileNameWithPath = "./UPLOADS/$docFolderPath/$txtFileName.txt";

    $file = fopen($completeTxtFileNameWithPath, "w");
    $result = fwrite($file, $transcriptionText);
    fclose($file);

    //call method to set the document status as complete
    $success = Employee::SetDocumentAsCompleteAndEmployeeActiveId($empId, $docId);

    if ($success) {
        if ($result) echo $docId;
    }
}
