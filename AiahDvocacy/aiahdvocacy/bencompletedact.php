<?php

include 'dbconn.php';

$today = date('Y-m-d');

$stmt = $conn->prepare("SELECT * FROM activities WHERE End_Date < ?");
$stmt->execute([$today]);
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/beneficiaryheader.php'; ?>
<section id="inner-banner">
<div class="overlay">
<div class="container">
<div class="row"> 
<div class="col-sm-6"><h1>COMPLETED ACTIVITIES</h1></div>
<div class="col-sm-6">
  <h6 class="breadcrumb"><a href="beneficiary.php">Home</a> / Completed Activities</h6></div>
</div>
</div>
</div>
</section>
<section class="container" style="margin-top:50px;">
  <h2 style="text-align:center;">Completed Activities</h2>
  <div class="d-flex justify-content-between align-items-center mb-3" style="display:flex; justify-content:space-between; align-items:center;">
      <h2 style="margin:0;">List</h2>
      <a href="projactben.php" 
         class="btn1" 
         style="background-color:#ff4c4c; color:white; padding:8px 15px; border-radius:20px; font-weight:bold; text-decoration:none;">
         Back
      </a>
    </div>

  <?php if (count($activities) > 0): ?>
    <?php foreach ($activities as $act): ?>
      <div class="sponser-box" style="margin-bottom:20px;">
        <h4 style="color:green;"><?php echo htmlspecialchars($act['Activity_Name']); ?></h4>
        <p><?php echo htmlspecialchars($act['Description']); ?></p>
        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($act['Start_Date']); ?></p>
        <p><strong>End Date:</strong> <?php echo htmlspecialchars($act['End_Date']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($act['Location']); ?></p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="color:gray; text-align:center;"><em>No completed activities yet.</em></p>
  <?php endif; ?>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
