<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['year_from']) || !isset($data['year_to'])) {
    echo json_encode(['success' => false, 'message' => 'Missing year']);
    exit;
}

try {
    $stmt = $connection->prepare("INSERT INTO year_tbl (year_from, year_to) VALUES (?, ?)");
    $stmt->execute([$data['year_from'], $data['year_to']]);

    echo json_encode(['success' => true, 'message' => 'Year added successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
