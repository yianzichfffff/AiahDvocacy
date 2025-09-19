<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['sem_id'], $data['sem_name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $connection->prepare("UPDATE semester_tbl SET sem_name = ? WHERE sem_id = ?");
    $stmt->execute([$data['sem_name'], $data['sem_id']]);

    echo json_encode(['success' => true, 'message' => 'Semester updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
