<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['subject_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing subject_id']);
    exit;
}

try {
    $stmt = $connection->prepare("DELETE FROM subjects WHERE subject_id = ?");
    $stmt->execute([$data['subject_id']]);

    echo json_encode(['success' => true, 'message' => 'Subject deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
