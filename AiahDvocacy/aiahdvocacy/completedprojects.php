<?php
include 'dbconn.php';
$today = date('Y-m-d');

$stmt = $conn->prepare("SELECT * FROM projects WHERE End_Date < ?");
$stmt->execute([$today]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/adminheader.php'; ?>
<section id="inner-banner">
<div class="overlay">
<div class="container">
<div class="row"> 
<div class="col-sm-6"><h1>COMPLETED PROJECTS</h1></div>
<div class="col-sm-6">
  <h6 class="breadcrumb"><a href="admin.php">Home</a> / About us</h6></div>
</div>
</div>
</div>
</section>
<h1> Completed Projects</h2>
<section class="container" style="margin-top:50px;">
  <h2 style="text-align:center;">Completed Projects</h2>
  <?php if (count($projects) > 0): ?>
    <?php foreach ($projects as $proj): ?>
      <div class="sponser-box" style="margin-bottom:20px;">
        <h4 style="color:green;"><?php echo htmlspecialchars($proj['Project_Name']); ?></h4>
        <p><?php echo htmlspecialchars($proj['Description']); ?></p>
        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($proj['Start_Date']); ?></p>
        <p><strong>End Date:</strong> <?php echo htmlspecialchars($proj['End_Date']); ?></p>
        <a href="completedactivities.php?project_id=<?php echo $proj['Project_ID']; ?>" class="btn1">View Activities</a>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="color:gray;"><em>No completed projects yet.</em></p>
  <?php endif; ?>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
