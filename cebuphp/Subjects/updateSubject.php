<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['subject_id'], $data['subject_name'], $data['sem_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $connection->prepare(
        "UPDATE subjects SET subject_name = ?, sem_id = ? WHERE subject_id = ?"
    );
    $stmt->execute([$data['subject_name'], $data['sem_id'], $data['subject_id']]);

    echo json_encode(['success' => true, 'message' => 'Subject updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
