<?php
require '../dbcon.php';

try {
    $stmt = $connection->prepare("SELECT s.sem_id, s.sem_name, y.year_id 
    FROM semester_tbl s
    join year_tbl y on s.year_id=y.year_id
    ORDER BY sem_id ASC");
    $stmt->execute();
    $semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $semesters]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
