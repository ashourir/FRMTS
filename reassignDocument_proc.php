<?php
require_once './CLASSES/Document.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $prevId = $data['prevId'];
    $actualEmpId = $data['currentId'];
    $documentId = $data['documentId'];
    $mode = $data['mode'];
    $targetRole = $data['targetRole'];
    $statusId = $data['statusId'];

    $result = Document::ReassignDocument($prevId, $actualEmpId, $documentId, $mode, $targetRole, $statusId);
    echo $result;
    echo json_encode(["message" => "Document reassigned successfully"]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
}
