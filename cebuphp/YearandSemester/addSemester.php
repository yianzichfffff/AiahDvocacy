<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['sem_name'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required Semester name']);
    exit;
}

try {
    $stmt = $connection->prepare("INSERT INTO semester_tbl (sem_name) VALUES (?)");
    $stmt->execute([$data['sem_name']]);

    echo json_encode(['success' => true, 'message' => 'Semester added successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
