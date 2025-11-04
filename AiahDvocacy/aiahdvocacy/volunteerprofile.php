<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'volunteer') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['User_ID'];

$stmt = $conn->prepare("
    SELECT u.Username, u.Role, v.First_Name, v.Last_Name, v.Email, v.Phone, v.Volunteer_ID
    FROM users u
    LEFT JOIN volunteer v ON u.User_ID = v.User_ID
    WHERE u.User_ID = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/volunteerheader.php'; ?>
<br>
<br>
<br>
<br>
<br>
<section style="padding:40px 0; background:#f0f2f5;">
<div class="container" style="max-width:700px; background:#fff; padding:30px; border-radius:10px;">
    <h1>My Profile</h1><br>

    <h2><?= htmlspecialchars($user['Username']); ?> (Volunteer)</h2>
    <p><strong>Full Name:</strong> <?= htmlspecialchars(($user['First_Name'] ?? '') . ' ' . ($user['Last_Name'] ?? '')); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['Email'] ?? ''); ?></p>
    <p><strong>Contact No.:</strong> <?= htmlspecialchars($user['Phone'] ?? ''); ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['Role']); ?></p>

    <a href="volunteeredit.php?id=<?= $user['Volunteer_ID']; ?>" class="btn btn-primary">Edit Profile</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
