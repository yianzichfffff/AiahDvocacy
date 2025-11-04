<?php
session_start();
include 'dbconn.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('No activity selected.'); window.location='adminproject.php';</script>";
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM activities WHERE Activity_ID = ?");
$stmt->execute([$id]);
$activity = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activity) {
    echo "<script>alert('Activity not found.'); window.location='adminproject.php';</script>";
    exit;
}


$projStmt = $conn->query("SELECT Project_ID, Project_Name FROM projects ORDER BY Project_Name ASC");
$projects = $projStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $name = $_POST['Activity_Name'];
    $sdate = $_POST['Start_Date'];
    $edate = $_POST['End_Date'];
    $desc = $_POST['Description'];
    $loc = $_POST['Location'];

    $update = "UPDATE activities 
               SET Project_ID=?, Activity_Name=?, Start_Date=?, End_Date=?, Description=?, Location=? 
               WHERE Activity_ID=?";
    $stmt = $conn->prepare($update);
    $stmt->execute([$project_id, $name, $sdate, $edate, $desc, $loc, $id]);

    echo "<script>alert('Activity updated successfully!'); window.location='adminactivities.php?project_id=$project_id';</script>";
    exit;
}
?>

<!DOCTYPE HTML>
<html class="no-js" lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/adminheader.php'; ?>

<section id="inner-banner">
  <div class="overlay">
    <div class="container">
      <div class="row"> 
        <div class="col-sm-6"><h1>Edit Activity</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="adminproject.php">Projects</a> / Edit Activity</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container text-center">
    <h2>Edit Activity Details</h2>
    <br>

    <form method="POST" style="max-width:700px; margin:auto; text-align:left;">
      <label>Project:</label>
      <select name="project_id" class="form-control" required>
        <?php foreach ($projects as $proj): ?>
          <option value="<?php echo $proj['Project_ID']; ?>" 
            <?php echo ($proj['Project_ID'] == $activity['Project_ID']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($proj['Project_Name']); ?>
          </option>
        <?php endforeach; ?>
      </select><br>

      <label>Activity Name:</label>
      <input type="text" name="Activity_Name" value="<?php echo htmlspecialchars($activity['Activity_Name']); ?>" class="form-control" required><br>

      <label>Start Date:</label>
      <input type="date" name="Start_Date" value="<?php echo htmlspecialchars($activity['Start_Date']); ?>" class="form-control" required><br>

      <label>End Date:</label>
      <input type="date" name="End_Date" value="<?php echo htmlspecialchars($activity['End_Date']); ?>" class="form-control"><br>

      <label>Description:</label>
      <textarea name="Description" class="form-control" rows="4"><?php echo htmlspecialchars($activity['Description']); ?></textarea><br>

      <label>Location:</label>
      <input type="text" name="Location" value="<?php echo htmlspecialchars($activity['Location']); ?>" class="form-control"><br>

      <div style="text-align:center;">
        <button type="submit" class="btn1">Update Activity</button>
        <a href="project_activities.php?project_id=<?php echo $activity['Project_ID']; ?>" class="btn1" style="background-color: gray;">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
