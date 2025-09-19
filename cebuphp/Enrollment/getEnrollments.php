<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);
$student_id = $data['stud_id'] ?? null;

try {
    if(!$student_id){
        echo json_encode(['success'=>false, 'message'=>'Student ID missing']);
        exit;
    }


    $stmt = $connection->prepare("SELECT s.stud_id, s.name, sub.subject_id, sub.subject_name 
                                  FROM student_tbl s
                                  LEFT JOIN subject_tbl sub ON FIND_IN_SET(sub.subject_id, s.subject_name)
                                  WHERE s.stud_id = ?");
    $stmt->execute([$student_id]);
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success'=>true, 'data'=>$enrollments]);
} catch(PDOException $e){
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
