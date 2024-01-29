<?php
include("CLASSES/Document.php");

//get the collection id from the AJAX request:
$collId = $_REQUEST["q"];
$checkboxes = Document::GetDocumentsCheckBoxesSingleCollection($collId);
echo $checkboxes;