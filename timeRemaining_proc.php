<?php
 Include('./CLASSES/Document.php');
 Include('./CLASSES/Volunteer.php');
 $jsonData = file_get_contents('php://input');

 // Decode the JSON data into an associative array
 $data = json_decode($jsonData, true);
 
 // Extract the volunteerId as an integer
 $volunteerId = $data['volunteerId'];

 //$volunteer = Volunteer::GetVolunteerById($volunteerId);
 
$historyId = Volunteer::GetActiveHistoryId($volunteerId);


 $remain = Document::GetTimeRemaining($historyId);
 if($historyId){
    echo json_encode(['timeRemaining' => $remain]);
 }

