<?php
require '../dbcon.php';


try {
    $stmt = $connection->prepare(
        "SELECT p.program_id, p.program_name, i.ins_id
         FROM program_tbl p
         join institute_tbl i on p.ins_id=i.ins_id
         ORDER BY program_id DESC"
    );
    $stmt->execute();
    $program_tbl = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $program_tbl
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
