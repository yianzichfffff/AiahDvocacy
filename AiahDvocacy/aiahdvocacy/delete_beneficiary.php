<?php
include 'dbconn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE beneficiary SET is_deleted = 1 WHERE Beneficiary_ID = ?");
    $stmt->execute([$id]);

    header("Location: adminbeneficiary.php?deleted=1");
    exit;
} else {
    echo "No beneficiary ID provided.";
}
?>
