<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'beneficiary') {
    echo "<script>alert('Please log in as beneficiary.'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['User_ID'];

if (!isset($_GET['id'])) {
    echo "<script>alert('No activity selected.'); window.location='beneficiary_dashboard.php';</script>";
    exit;
}

$activity_id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT a.*, p.Project_Name
    FROM activities a
    LEFT JOIN projects p ON a.Project_ID = p.Project_ID
    WHERE a.Activity_ID = ?
");
$stmt->execute([$activity_id]);
$activity = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activity) {
    echo "<script>alert('Activity not found.'); window.location='beneficiary_dashboard.php';</script>";
    exit;
}

$joinStmt = $conn->prepare("
    SELECT * FROM beneficiary WHERE User_ID = ? AND Activity_ID = ?
");
$joinStmt->execute([$user_id, $activity_id]);
$alreadyJoined = $joinStmt->rowCount() > 0;

if (isset($_POST['join']) && !$alreadyJoined) {
    $insertStmt = $conn->prepare("
        INSERT INTO beneficiary (User_ID, Activity_ID) VALUES (?, ?)
    ");
    $insertStmt->execute([$user_id, $activity_id]);
    $alreadyJoined = true;
    echo "<script>alert('You have successfully joined this activity!'); window.location='beneficiary_dashboard.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/beneficiaryheader.php'; ?>
<br>
<BR>
<BR>
<BR>
<br>

<section class="container my-5">
    <h2 class="text-center mb-4"><?= htmlspecialchars($activity['Activity_Name']) ?></h2>
    <div class="card shadow p-4">
        <p><strong>Project:</strong> <?= htmlspecialchars($activity['Project_Name'] ?? 'N/A') ?></p>
        <p><strong>When:</strong> <?= htmlspecialchars($activity['Start_Date']) ?> to <?= htmlspecialchars($activity['End_Date']) ?></p>
        <p><strong>Where:</strong> <?= htmlspecialchars($activity['Location']) ?></p>
        <p><strong>Description:</strong></p>
        <p><?= htmlspecialchars($activity['Description']) ?></p>

        <?php if ($alreadyJoined): ?>
            <div class="alert alert-success">You have already joined this activity ✅</div>
        <?php else: ?>
            <form method="POST">
                <a href="beneficiary.php" 
         class="btn1" 
         style="background-color:#ff4c4c; color:white; padding:8px 15px; border-radius:20px; font-weight:bold; text-decoration:none;">
         Back
      </a>
                
            </form>
            <br>
            <br>
        <?php endif; ?>
    </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
