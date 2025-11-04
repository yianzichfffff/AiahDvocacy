<?php
session_start();
include 'dbconn.php';

$projectStmt = $conn->query("SELECT Project_ID, Project_Name FROM projects");
$projects = $projectStmt->fetchAll(PDO::FETCH_ASSOC);

$beneficiary = $conn->query("
    SELECT Beneficiary_ID, CONCAT(First_Name, ' ', Last_Name) AS FullName
    FROM beneficiary
    WHERE Project_ID IS NULL OR Activity_ID IS NULL
");
$beneficiaries = $beneficiary->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['getActivities']) && isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    $stmt = $conn->prepare("SELECT Activity_ID, Activity_Name FROM activities WHERE Project_ID = ?");
    $stmt->execute([$project_id]);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($activities);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $beneficiary_id = $_POST['Beneficiary_ID'];
    $project_id = $_POST['Project_ID'];
    $activity_id = $_POST['Activity_ID'];

    $sql = "UPDATE beneficiary 
            SET Project_ID = :project, Activity_ID = :activity
            WHERE Beneficiary_ID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':project' => $project_id,
        ':activity' => $activity_id,
        ':id' => $beneficiary_id
    ]);

    echo "<script>alert('Beneficiary assigned successfully!'); window.location='adminbeneficiary.php';</script>";
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
        <div class="col-sm-6"><h1>ASSIGN PROJECT & ACTIVITY</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / Assign Project & Activity</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">
    <div class="row text-center">
      <h2>Assign Beneficiary to Project & Activity</h2>
      <form action="" method="POST" class="text-left" style="max-width:600px; margin:auto;">
        
        <label>Select Beneficiary</label>
        <select name="Beneficiary_ID" class="form-control" required>
          <option value="">Select Beneficiary</option>
          <?php foreach ($beneficiaries as $ben): ?>
            <option value="<?php echo $ben['Beneficiary_ID']; ?>">
              <?php echo htmlspecialchars($ben['FullName']); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label>Select Project</label>
        <select name="Project_ID" id="Project_ID" class="form-control" required>
          <option value="">Select Project</option>
          <?php foreach ($projects as $proj): ?>
            <option value="<?php echo $proj['Project_ID']; ?>">
              <?php echo htmlspecialchars($proj['Project_Name']); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label>Select Activity</label>
        <select name="Activity_ID" id="Activity_ID" class="form-control" required>
          <option value="">Select Activity</option>
        </select>

        <br>
        <div class="sponser-box">
          <button type="submit" class="btn2">Assign Beneficiary</button>
          <a class="sponsor-button" href="projectadd.php">Add Project</a>
          <a class="sponsor-button" href="activityadd.php">Add Activity</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?php include '../inc/footer.php'; ?>

<script>
document.getElementById('Project_ID').addEventListener('change', function() {
    var projectId = this.value;
    var activityDropdown = document.getElementById('Activity_ID');
    activityDropdown.innerHTML = '<option value="">Loading...</option>';

    if (projectId) {
        fetch('addact.php?getActivities=1&project_id=' + projectId)
        .then(response => response.json())
        .then(data => {
            activityDropdown.innerHTML = '<option value="">Select Activity</option>';
            data.forEach(activity => {
                var opt = document.createElement('option');
                opt.value = activity.Activity_ID;
                opt.textContent = activity.Activity_Name;
                activityDropdown.appendChild(opt);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            activityDropdown.innerHTML = '<option value="">Error loading activities</option>';
        });
    } else {
        activityDropdown.innerHTML = '<option value="">Select Activity</option>';
    }
});
</script>

</body>
</html>
