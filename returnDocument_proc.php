<?php
include('./CLASSES/Document.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    if (isset($data['documentId'])) {
        $documentId = $data['documentId'];
        $result = Document::UpdateVolunteerDocument($documentId, 'return');
        if ($result == 1) {
            echo json_encode(['success' => true, 'message' => 'Document returned to the pool']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Something went wrong']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing documentId in JSON data']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
