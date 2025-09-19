<?php
require '../dbcon.php';

header('Content-Type: application/json');

try {

    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        $input = $_POST;
    }

    $stud_id = $input['stud_id'] ?? null;
    $name = $input['name'] ?? null;
    $program_id = $input['program_id'] ?? null;
    $allowance = $input['allowance'] ?? null;

    if (!$stud_id || !$name || !$program_id || !$allowance) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    $stmt = $connection->prepare(
        "UPDATE students 
         SET name = :name, program_id = :program_id, allowance = :allowance 
         WHERE stud_id = :stud_id"
    );

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':program_id', $program_id, PDO::PARAM_INT);
    $stmt->bindParam(':allowance', $allowance);
    $stmt->bindParam(':stud_id', $stud_id, PDO::PARAM_INT);

    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Student updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
