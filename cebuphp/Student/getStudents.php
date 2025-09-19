<?php
require '../dbcon.php';

try {
    $stmt = $connection->prepare(
        "SELECT s.stud_id, s.name, s.allowance, p.program_id, p.program_name
         FROM student_tbl s
         JOIN program_tbl p ON s.program_id = p.program_id
         ORDER BY s.stud_id ASC"
    );
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $students]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
