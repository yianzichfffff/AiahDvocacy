<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['stud_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing stud_id']);
    exit;
}

try {
    $stmt = $connection->prepare("DELETE FROM students WHERE stud_id = ?");
    $stmt->execute([$data['stud_id']]);

    echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
