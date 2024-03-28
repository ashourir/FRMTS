<?php
require('./CLASSES/Document.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    if (isset($data['documentId'])) {
        $documentId = $data['documentId'];
        $document = new Document();
        $document = Document::getDocumentById($documentId);
        echo json_encode($document->docName);
         
        
    } else {
        echo json_encode(array('error' => 'Document ID not provided'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}
?>
