<?php
require('./CLASSES/Document.php');
global $con;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON data directly
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['documentId'])) {
        $documentId = $data['documentId'];
        $document = Document::getDocumentById($documentId);
        echo json_encode($document->folderName);
    } else {
        echo json_encode(array('error' => 'Document ID not provided'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}
?>
