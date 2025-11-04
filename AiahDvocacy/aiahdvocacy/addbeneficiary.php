<?php
session_start();
include 'dbconn.php';

$beneficiaryStmt = $conn->query("
    SELECT Beneficiary_ID, First_Name, Last_Name 
    FROM beneficiary 
    WHERE Activity_ID IS NULL AND is_deleted = 0
");
$beneficiaries = $beneficiaryStmt->fetchAll(PDO::FETCH_ASSOC);

$projectStmt = $conn->query("SELECT Project_ID, Project_Name FROM projects");
$projects = $projectStmt->fetchAll(PDO::FETCH_ASSOC);

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
    $activity_id = $_POST['Activity_ID'];

    $stmt = $conn->prepare("UPDATE beneficiary SET Activity_ID = ? WHERE Beneficiary_ID = ?");
    $stmt->execute([$activity_id, $beneficiary_id]);

    echo "<script>alert('Beneficiary assigned to activity successfully!'); window.location='adminbeneficiary.php';</script>";
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
        <div class="col-sm-6"><h1>Assign Beneficiary to Activity</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / Assign Beneficiary</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">
    <div class="row text-center">
      <h2>Assign Beneficiary</h2>
      <form action="" method="POST" class="text-left" style="max-width:600px; margin:auto;">
        
        <label>Beneficiary</label>
        <select name="Beneficiary_ID" id="Beneficiary_ID" class="form-control" required>
            <option value="">Select Beneficiary</option>
            <?php foreach($beneficiaries as $b): ?>
                <option value="<?= $b['Beneficiary_ID']; ?>">
                    <?= htmlspecialchars($b['First_Name'] . ' ' . $b['Last_Name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Project</label>
        <select name="Project_ID" id="Project_ID" class="form-control" required>
          <option value="">Select Project</option>
          <?php foreach ($projects as $proj): ?>
            <option value="<?= $proj['Project_ID']; ?>">
              <?= htmlspecialchars($proj['Project_Name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <label>Activity</label>
        <select name="Activity_ID" id="Activity_ID" class="form-control" required>
          <option value="">Select Activity</option>
        </select>

        <br>
        <a class="sponsor-button" href="projectadd.php">Project</a>
        <a class="sponsor-button" href="activityadd.php">Activity</a>
        <button type="submit" class="btn2">Save Beneficiary</button>
      </form>
    </div>
  </div>
</section>

<?php include '../inc/footer.php'; ?>

<script>
document.getElementById('Project_ID').addEventListener('change', function() {
    var projectId = this.value;
    var activityDropdown = document.getElementById('Activity_ID');
    activityDropdown.innerHTML = '<option>Loading...</option>';

    if (projectId) {
        fetch('beneficiary_add.php?getActivities=1&project_id=' + projectId)
        .then(res => res.json())
        .then(data => {
            activityDropdown.innerHTML = '<option value="">Select Activity</option>';
            data.forEach(act => {
                var opt = document.createElement('option');
                opt.value = act.Activity_ID;
                opt.textContent = act.Activity_Name;
                activityDropdown.appendChild(opt);
            });
        })
        .catch(err => {
            console.error(err);
            activityDropdown.innerHTML = '<option value="">Error loading activities</option>';
        });
    } else {
        activityDropdown.innerHTML = '<option value="">Select Activity</option>';
    }
});
</script>

</body>
</html>
