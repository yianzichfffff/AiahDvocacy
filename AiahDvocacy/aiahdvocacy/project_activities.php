<!DOCTYPE HTML>
<html class="no-js" lang="en">
<?php include '../inc/head.php'; ?>
<?php include 'dbconn.php'; ?>
<body>
<?php include '../inc/adminheader.php'; ?>

<?php
if (!isset($_GET['project_id'])) {
    echo "<script>alert('No project selected.'); window.location='adminproject.php';</script>";
    exit;
}

$project_id = $_GET['project_id'];

$projectStmt = $conn->prepare("SELECT * FROM projects WHERE Project_ID = ?");
$projectStmt->execute([$project_id]);
$project = $projectStmt->fetch(PDO::FETCH_ASSOC);

$actStmt = $conn->prepare("
    SELECT * 
    FROM activities 
    WHERE Project_ID = ? 
      AND (End_Date IS NULL OR End_Date >= CURDATE())
");
$actStmt->execute([$project_id]);
$activities = $actStmt->fetchAll(PDO::FETCH_ASSOC);
?>


<section id="inner-banner">
  <div class="overlay">
    <div class="container">
      <div class="row"> 
        <div class="col-sm-6"><h1>ACTIVITIES</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="adminproject.php">Projects</a> / Activities</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container text-center">
    <h2><?php echo htmlspecialchars($project['Project_Name']); ?> - Activities</h2>
    <p><?php echo htmlspecialchars($project['Description']); ?></p>
    <br>

    <?php if (count($activities) > 0): ?>
      <?php foreach ($activities as $act): ?>
    <?php
    $today = date('Y-m-d');
    $start_date = $act['Start_Date'];
    $end_date = $act['End_Date'] ?? $act['Date'];

    if ($today < $start_date) {
        $status = "<span style='color:blue; font-weight:bold;'>Not Started</span>";
    } elseif ($today >= $start_date && $today <= $end_date) {
        $status = "<span style='color:orange; font-weight:bold;'>Ongoing</span>";
    }
    ?>


        <div class="sponser-box" style="margin-bottom:20px;">
          <h4 style="color:red;"><?php echo htmlspecialchars($act['Activity_Name']); ?></h4>
          <p><?php echo htmlspecialchars($act['Description']); ?></p>
          <p><strong>Start Date:</strong> <?php echo htmlspecialchars($act['Start_Date']); ?></p>
          <p><strong>Location:</strong> <?php echo htmlspecialchars($act['Location']); ?></p>
          <p><strong>Status:</strong> <?php echo $status; ?></p>
          <a href="activityedit.php?id=<?php echo $act['Activity_ID']; ?>" class="btn1">Edit Activity</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="color:gray;"><em>No activities found for this project.</em></p>
    <?php endif; ?>
  </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
