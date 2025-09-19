<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);
$stud_id = $data['stud_id'] ?? null;
$subject_id = $data['subject_id'] ?? null;

if(!$stud_id || !$subject_id){
    echo json_encode(['success'=>false, 'message'=>'Missing student or subject']);
    exit;
}

try {
    $stmt = $connection->prepare("SELECT subject_name FROM student_tbl WHERE stud_id = ?");
    $stmt->execute([$stud_id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC)['subject_name'];

    $subjects = $current ? explode(',', $current) : [];

    if(in_array($subject_id, $subjects)){
        echo json_encode(['success'=>false, 'message'=>'Student already enrolled in this subject']);
        exit;
    }

    $subjects[] = $subject_id;
    $new_csv = implode(',', $subjects);

    $update = $connection->prepare("UPDATE student_tbl SET subject_name = ? WHERE stud_id = ?");
    $update->execute([$new_csv, $stud_id]);

    echo json_encode(['success'=>true, 'message'=>'Enrollment added successfully']);
} catch(PDOException $e){
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
