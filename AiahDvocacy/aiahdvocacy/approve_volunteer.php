<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("No volunteer selected.");
}

$volunteer_id = $_GET['id'];

$stmt = $conn->prepare("UPDATE volunteer SET Status = 'Approved' WHERE Volunteer_ID = ?");
$stmt->execute([$volunteer_id]);

header("Location: admin_requests.php");
exit;
?>
