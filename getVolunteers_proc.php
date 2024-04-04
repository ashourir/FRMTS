<?php
include('./CLASSES/Volunteer.php');
include('./connect.php');

try {
    $requestData = json_decode(file_get_contents('php://input'), true);

    if (isset($requestData['mode'])) {
        $mode = $requestData['mode'];

        $volunteers = Volunteer::GetVolunteers($mode);

        echo json_encode($volunteers);
    } else {
        http_response_code(400);
        echo json_encode(array('error' => 'Mode parameter is missing'));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array('error' => $e->getMessage()));
}
?>
