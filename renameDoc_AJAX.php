<?php
include("CLASSES/Document.php");

//get the collection id from the AJAX request:
$collId = $_REQUEST["q"];
$radioButton = Document::GetDocumentsRadioBtnSingleCollection($collId);
echo $radioButton;