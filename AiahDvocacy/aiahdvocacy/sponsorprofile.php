<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'sponsor') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['User_ID'];

$stmt = $conn->prepare("
    SELECT u.Username, u.Role, s.First_Name, s.Last_Name, s.Email, s.Phone, s.Address, s.Sponsor_ID
    FROM users u
    LEFT JOIN sponsor s ON u.User_ID = s.User_ID
    WHERE u.User_ID = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/sponsorheader.php'; ?>
<br>
<br>
<br>
<br>
<br>
<br>

<section style="padding:40px 0; background:#f0f2f5;">
<div class="container" style="max-width:700px; background:#fff; padding:30px; border-radius:10px;">
    <h1>My Profile</h1><br>

    <h2><?= htmlspecialchars($user['Username']); ?> (Sponsor)</h2>
    <p><strong>Full Name:</strong> <?= htmlspecialchars(($user['First_Name'] ?? '') . ' ' . ($user['Last_Name'] ?? '')); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['Email'] ?? ''); ?></p>
    <p><strong>Contact No.:</strong> <?= htmlspecialchars($user['Phone'] ?? ''); ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($user['Address'] ?? ''); ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['Role']); ?></p>

    <a href="sponsor_edit.php?id=<?= $user['Sponsor_ID']; ?>" class="btn btn-primary">Edit Profile</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
