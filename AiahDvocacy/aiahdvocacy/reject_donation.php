<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("No donation selected.");
}

$donation_id = $_GET['id'];

$stmt = $conn->prepare("UPDATE donations SET Status = 'Rejected' WHERE Donation_ID = ?");
$stmt->execute([$donation_id]);

header("Location: admin_requests.php");
exit;
?>
