<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['program_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing program_id']);
    exit;
}

try {
    $stmt = $connection->prepare("DELETE FROM programs WHERE program_id = ?");
    $stmt->execute([$data['program_id']]);

    echo json_encode(['success' => true, 'message' => 'Program deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
