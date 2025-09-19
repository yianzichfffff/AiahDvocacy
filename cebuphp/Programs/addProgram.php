<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['program_name']) || !isset($data['institute'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $connection->prepare("INSERT INTO programs (program_name, institute) VALUES (?, ?)");
    $stmt->execute([$data['program_name'], $data['institute']]);

    echo json_encode(['success' => true, 'message' => 'Program added successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

