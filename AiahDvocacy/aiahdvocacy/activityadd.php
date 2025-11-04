<?php
session_start();
include 'dbconn.php'; 

$projectStmt = $conn->query("SELECT Project_ID, Project_Name FROM projects");
$projects = $projectStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_name = $_POST['Activity_Name'];
    $desc = $_POST['Description'];
    $start_date = $_POST['Start_Date'];
    $end_date = $_POST['End_Date']; 
    $location = $_POST['Location'];
    $project_id = $_POST['Project_ID'];

    $sql = "INSERT INTO activities (Activity_Name, Description, Start_Date, End_Date, Location, Project_ID)
            VALUES (:acname, :des, :dt, :enddt, :loc, :proj)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':acname' => $activity_name,
        ':des' => $desc,
        ':dt' => $start_date,
        ':enddt' => $end_date,
        ':loc' => $location,
        ':proj' => $project_id
    ]);

    echo "<script>alert('Activity added successfully!'); window.location='adminactivities.php';</script>";
}
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
        <div class="col-sm-6"><h1>ADD ACTIVITY</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / Add Activity</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">
    <div class="row text-center">
      <h2>Add New Activity</h2>
      <form action="" method="POST" enctype="multipart/form-data" class="text-left" style="max-width:600px; margin:auto;">

        <label>Activity Name</label>
        <input type="text" name="Activity_Name" class="form-control" required>

        <label>Description</label>
        <input type="text" name="Description" class="form-control">

        <label>Start Date</label>
        <input type="date" name="Start_Date" class="form-control" required>

        <label>End Date</label>
        <input type="date" name="End_Date" class="form-control" required>

        <label>Location</label>
        <input type="text" name="Location" class="form-control" required>

        <label>Project</label>
        <select name="Project_ID" id="Project_ID" class="form-control" required>
          <option value="">Select Project</option>
          <?php foreach ($projects as $proj): ?>
            <option value="<?php echo $proj['Project_ID']; ?>">
              <?php echo htmlspecialchars($proj['Project_Name']); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <br>
        <div class="sponser-box">
          <a class="sponsor-button" href="projectadd.php">Project</a>
          <button type="submit" class="btn2">Save Activity</button>
          <a class="sponsor-button" href="addbeneficiary.php">Beneficiary</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
