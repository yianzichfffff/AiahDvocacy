<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'beneficiary') {
    echo "<script>alert('Please log in as admin.'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['User_ID'];

$beneficiaryStmt = $conn->prepare("SELECT First_Name FROM beneficiary WHERE User_ID = ?");
$beneficiaryStmt->execute([$user_id]);
$beneficiary = $beneficiaryStmt->fetch(PDO::FETCH_ASSOC);

$today = date('Y-m-d');
$actStmt = $conn->prepare("
    SELECT a.Activity_ID, a.Activity_Name, a.Start_Date, a.End_Date, a.Location, p.Project_Name
    FROM activities a
    LEFT JOIN projects p ON a.Project_ID = p.Project_ID
    WHERE a.Start_Date >= ?
    ORDER BY a.Start_Date ASC
");
$actStmt->execute([$today]);
$upcomingActivities = $actStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body class="bg-light">
<?php include '../inc/beneficiaryheader.php'; ?>

<section id="inner-banner">
  <div class="overlay">
    <div class="container">
      <div class="row"> 
        <div class="col-sm-6"><h1>DASHBOARD</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="beneficiary.php">Home</a> / Announcements</h6>
        </div>
      </div>
    </div>
  </div>
</section>
<br>
<section class="container my-5">
    <h2 class="text-center mb-4">Hey <?= htmlspecialchars($beneficiary['First_Name']) ?>! <br> Here’s what’s coming up for you:</h2>
<br>
    <?php if(count($upcomingActivities) > 0): ?>
        <?php foreach($upcomingActivities as $act): ?>
            <div class="card shadow mb-3 p-4" style="border-radius:20px; background-color:#fff9e6;">
                <h4 style="color:#d35400;">📢 <?= htmlspecialchars($act['Activity_Name']) ?></h4>
                <p><strong>Project:</strong> <?= htmlspecialchars($act['Project_Name'] ?? 'N/A') ?></p>
                <p><strong>When:</strong> <?= htmlspecialchars($act['Start_Date']) ?> to <?= htmlspecialchars($act['End_Date']) ?></p>
                <p><strong>Where:</strong> <?= htmlspecialchars($act['Location']) ?></p>
                <p>We think you might enjoy this activity! Don’t miss out!!</p>
                <a href="activity_details.php?id=<?= $act['Activity_ID'] ?>" class="btn1" style="margin-top:10px;">View</a>
            </div>
            <br>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center" style="font-weight:bold; font-size:1.1rem;">
            Hi <?= htmlspecialchars($beneficiary['First_Name']) ?>, there are no upcoming activities right now. Stay tuned for announcements!
        </div>
    <?php endif; ?>
</section>
<br>
<?php include '../inc/help.php'; ?>
<?php include '../inc/beneficiaryfooter.php'; ?>
</body>
</html>
