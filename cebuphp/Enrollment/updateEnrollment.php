<?php
require '../dbcon.php';

$data = json_decode(file_get_contents("php://input"), true);
$stud_id = $data['stud_id'] ?? null;
$old_subject = $data['old_subject_id'] ?? null;
$new_subject = $data['new_subject_id'] ?? null;

if(!$stud_id || !$old_subject || !$new_subject){
    echo json_encode(['success'=>false, 'message'=>'Missing data']);
    exit;
}

try {
    $stmt = $connection->prepare("SELECT subject_name FROM student_tbl WHERE stud_id = ?");
    $stmt->execute([$stud_id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC)['subject_name'];
    $subjects = $current ? explode(',', $current) : [];

    $subjects = array_diff($subjects, [$old_subject]);
    if(!in_array($new_subject, $subjects)){
        $subjects[] = $new_subject;
    }

    $new_csv = implode(',', $subjects);
    $update = $connection->prepare("UPDATE student_tbl SET subject_name = ? WHERE stud_id = ?");
    $update->execute([$new_csv, $stud_id]);

    echo json_encode(['success'=>true, 'message'=>'Enrollment updated']);
} catch(PDOException $e){
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
