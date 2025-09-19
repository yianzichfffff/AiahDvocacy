<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['year_id'], $data['year_from'], $data['year_to'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $connection->prepare("UPDATE year_tbl SET year_from = ?, year_to = ? WHERE year_id = ?");
    $stmt->execute([$data['year_from'], $data['year_to'], $data['year_id']]);

    echo json_encode(['success' => true, 'message' => 'Year updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
