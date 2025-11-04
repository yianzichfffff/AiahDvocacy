<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'beneficiary') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['User_ID'];

$stmt = $conn->prepare("
    SELECT b.*, a.Activity_Name AS current_activity, p.Project_Name
    FROM beneficiary b
    LEFT JOIN activities a ON b.Activity_ID = a.Activity_ID
    LEFT JOIN projects p ON a.Project_ID = p.Project_ID
    WHERE b.User_ID = ? AND b.is_deleted = 0
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$birthdate = new DateTime($user['Birthdate']);
$age = (new DateTime())->diff($birthdate)->y;

$pastStmt = $conn->prepare("
    SELECT a.Activity_Name, a.Start_Date, p.Project_Name
    FROM activity_participation ap
    JOIN activities a ON ap.Activity_ID = a.Activity_ID
    LEFT JOIN projects p ON a.Project_ID = p.Project_ID
    WHERE ap.Beneficiary_ID=?
    AND a.Start_Date < CURDATE()
    ORDER BY a.Start_Date DESC
");
$pastStmt->execute([$user['Beneficiary_ID']]);
$past_activities = $pastStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/beneficiaryheader.php'; ?>
<br>
<br>
<br>
<br>
<br>
<br>
<section style="padding:40px 0; background:#f0f2f5;">
<div class="container" style="max-width:700px; background:#fff; padding:30px; border-radius:10px;">
    <h1>Account</h1>
    <br>
    <h2><?= htmlspecialchars($user['First_Name'] . ' ' . $user['Last_Name']); ?> (Beneficiary)</h2>
    <p><strong>Age:</strong> <?= $age; ?></p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($user['Gender']); ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($user['Address']); ?></p>
    <p><strong>Contact:</strong> <?= htmlspecialchars($user['Contact_No']); ?></p>

    <h3>Past Activities</h3>
    <?php if($past_activities): ?>
        <ul>
        <?php foreach($past_activities as $act): ?>
            <li><?= htmlspecialchars($act['Activity_Name']); ?> (<?= htmlspecialchars($act['Project_Name'] ?? ''); ?>) - <?= $act['Start_Date']; ?></li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No past activities.</p>
    <?php endif; ?>

    <a href="logout.php" style="padding:8px 15px; background:#d81b2a; color:white; border-radius:5px;">Logout</a>
</div>
</section>
<?php include '../inc/footer.php'; ?>
</body>
</html>
