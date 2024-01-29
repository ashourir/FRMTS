<?php
include("./CLASSES/Volunteer.php");
include("./CLASSES/Document.php");
session_start();


if (isset($_POST["updateDocument"])) {
  $status = $_POST["updateDocument"];
  $volunteer = $_SESSION['volunteer'];
  $docId = $volunteer->activeDocId;
  $result = Document::UpdateVolunteerDocument($docId, $status);
  if ($result) {
    $volunteer->activeDocId == -1;
    echo "success";
  } else {
    echo "error";
  }
}



//this code will receite the text content from transcription and notes text area 
//and store each in separate txt files
if (isset($_POST["txtTransc"])) {
  $transcText = $_POST["txtTransc"];
  $notesText = $_POST["txtNotes"];
  $fileName = $_POST["fileName"];
  saveFileContents($transcText, $fileName, "t"); //"t" for transcription text 
  saveFileContents($notesText, $fileName, "n"); //"n" for notes text
}


//this code will receice the document folder path and return an array with all the images in it
//to load the openSeaDragon viewer
if (isset($_POST["createOSDCanva"])) {
  $folderName = $_POST["createOSDCanva"]; // returning ---> UPLOADS/test
  $images = glob("./UPLOADS/" . $folderName . "/" . '*.{jpg,png,jpeg}', GLOB_BRACE);
  $transcFileName = array_values(array_filter(glob("./UPLOADS/"  . $folderName . "/" . '*.{txt}', GLOB_BRACE), function ($v) { return false === strpos($v, '_NOTES.txt');
  }));
  $transcText = array();
  $notesFileName = glob("./UPLOADS/" . $folderName . "/" . '*_NOTES.{txt}', GLOB_BRACE);
  $notesText = array();

  foreach ($transcFileName as $file) {
    $content = "";
    $fileSize = filesize($file);
    $handle = fopen($file, "r");
    if ($fileSize) {
      $content = fread($handle, filesize($file));
    }
    array_push($transcText, $content);
  }

  foreach ($notesFileName as $file) {
    $content = "";
    $fileSize = filesize($file);
    $handle = fopen($file, "r");
    if ($fileSize) {
      $content = fread($handle, filesize($file));
    }
    array_push($notesText, $content);
  }





  $response = array(
    "images" => $images,
    "transcFileName" => $transcFileName,
    "transcText" => $transcText,
    "notesFileName" => $notesFileName,
    "notesText" => $notesText,
  );
  echo json_encode($response);
}

//this code will do: 
//1. Get the actual file path and name opened at openSeaDragon viewer
//2. return the text file content as response.
if (isset($_POST["getTxtDataByFilePath"])) {
  $imageFilePath = $_POST["getTxtDataByFilePath"]; // ./UPLOADS/test/gog.jpg
  $textFileName = getTextFilePath($imageFilePath, "t");
  $contents = getFileContent($textFileName);
  echo $contents;
}

//this code will do: 
//1. Get the actual file path and name opened at openSeaDragon viewer
//2. return the text file content as response.
if (isset($_POST["getTxtNotesDataByFilePath"])) {
  $fileNameNotes = $_POST["getTxtNotesDataByFilePath"];
  $textFileName = getTextFilePath($fileNameNotes, "n");
  $contents = getFileContent($textFileName);
  echo $contents;
}


/**
 * This code will create a file name with relative path for the 
 * Transcription and Notes txt files.
 * @param string $filePath To create the text file name.
 * @param string $option "t" for transcription and "n" for notes
 * @return string containing the text file name with relative path
 */
function getTextFilePath($filePath, $option)
{
  $imageName = pathinfo($filePath, PATHINFO_FILENAME);
  $folderName = pathinfo($filePath, PATHINFO_DIRNAME);
  $textFileName =
    ($option == "t")
    ? $folderName . "/" . $imageName . ".txt" // this line will create the path and name for the txt file ---> ./UPLOADS/test/gog.txt
    : $folderName . "/" . $imageName . "_NOTES" . ".txt"; // this line will create the path and name for the txt file ---> ./UPLOADS/test/gog_NOTES.txt

  return $textFileName;
}


/**
 * This function is verifying if the text file isn't exist yet.
 * If it doesn't, create the file with some start text and return its
 * content.
 * @param string $filePath
 * @return string containing what's inside the text file
 * 
 */
function getFileContent($filePath)
{
  if (!file_exists($filePath)) {
    $file = fopen($filePath, "w");
    fwrite($file, "\n");
    fclose($file);
  }
  $contents = "";

  $fileSize = filesize($filePath);

  $handle = fopen($filePath, "r");

  if ($fileSize) {
    $contents = fread($handle, filesize($filePath));
  }

  return $contents;
}
/**
 * This function will take the text, the file name and the option
 * to save a text in a transcription or notes txt file.
 * @param string $text A string containing the text to be stored
 * @param string $fileName A string containing the name of the file
 * @param string $option A string containing the "t" for tranascription or "n" for notes
 */
function saveFileContents($text, $fileName, $option)
{
  $transcFile = getTextFilePath($fileName, "t");
  $notesFile = getTextFilePath($fileName, "n");

  ($option == "t")
    ? $file = fopen($transcFile, "w")
    : $file = fopen($notesFile, "w");
  fwrite($file, $text);
  fclose($file);
}
