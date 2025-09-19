<?php
require '../dbcon.php';

try {
    $stmt = $connection->prepare("SELECT * FROM year_tbl ORDER BY year_id ASC");
    $stmt->execute();
    $year_tbl = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $year_tbl]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
