<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['year_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing year_id']);
    exit;
}

try {
    $stmt = $connection->prepare("DELETE FROM year_tbl WHERE year_id = ?");
    $stmt->execute([$data['year_id']]);

    echo json_encode(['success' => true, 'message' => 'Year deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
