<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['subject_name'], $data['sem_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $connection->prepare("INSERT INTO subjects (subject_name, sem_id) VALUES (?, ?)");
    $stmt->execute([$data['subject_name'], $data['sem_id']]);

    echo json_encode(['success' => true, 'message' => 'Subject added successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
