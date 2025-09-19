<?php
require '../dbcon.php';

try {
    $stmt = $connection->prepare(
        "SELECT sub.subject_id, sub.subject_name, sem.sem_id
         FROM subject_tbl sub
         JOIN semester_tbl sem ON sub.sem_id = sem.sem_id
         ORDER BY sub.subject_id ASC"
    );
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $subjects]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
