<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['program_id'], $data['program_name'], $data['institute'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $connection->prepare("UPDATE programs SET program_name = ?, institute = ? WHERE program_id = ?");
    $stmt->execute([$data['program_name'], $data['institute'], $data['program_id']]);

    echo json_encode(['success' => true, 'message' => 'Program updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
