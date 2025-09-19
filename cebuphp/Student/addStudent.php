<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name'], $data['program_id'], $data['allowance'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $connection->prepare("INSERT INTO student_tbl (name, program_id, allowance) VALUES (?, ?, ?)");
    $stmt->execute([$data['name'], $data['program_id'], $data['allowance']]);

    echo json_encode(['success' => true, 'message' => 'Student added successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
