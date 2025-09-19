<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['sem_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing semester_id']);
    exit;
}

try {
    $stmt = $connection->prepare("DELETE FROM semester_tbl WHERE sem_id = ?");
    $stmt->execute([$data['sem_id']]);

    echo json_encode(['success' => true, 'message' => 'Semester deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
