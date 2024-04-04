<?php
require('./CLASSES/Volunteer.php');
include('./connect.php');

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);


// Check if volunteerId is set and not empty
if (isset($data['volunteerId']) && !empty($data['volunteerId'])) {
    $volunteerId = $data['volunteerId'];
    $history = Volunteer::GetHistoryByAdmin($volunteerId);
    echo json_encode($history);
} else {
    echo json_encode(array('error' => 'Volunteer ID not provided'));
}
?>
