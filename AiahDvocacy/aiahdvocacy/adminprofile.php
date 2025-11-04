<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['User_ID'];
$stmt = $conn->prepare("SELECT * FROM users WHERE User_ID=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/adminheader.php'; ?>
<br>
<br>
<br>
<br>
<section style="padding:40px 0; background:#f0f2f5;">
<div class="container" style="max-width:700px; background:#fff; padding:30px; border-radius:10px;">
    <h1>Account</h1>
    <br>
    <h2> <?= htmlspecialchars($user['Username']); ?> (Admin)</h2>

    <p><strong>Role:</strong> <?= htmlspecialchars($_SESSION['Role']); ?></p>
    <a href="logout.php" style="padding:8px 15px; background:#d81b2a; color:white; border-radius:5px;">Logout</a>
</div>
</section>
<?php include '../inc/footer.php'; ?>
</body>
</html>
