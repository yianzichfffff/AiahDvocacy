<?php
include 'dbconn.php';

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
    $first = $_POST['First_Name'];
    $middle = $_POST['Middle_Name'];
    $last = $_POST['Last_Name'];
    $gender = $_POST['Gender'];
    $birthdate = $_POST['Birthdate'];
    $address = $_POST['Address'];
    $contact = $_POST['Contact_No'];
    $activity_id = $_POST['Activity_ID'];

    $sql = "INSERT INTO beneficiary 
            (First_Name, Middle_Name, Last_Name, Gender, Birthdate, Address, Contact_No, Activity_ID)
            VALUES 
            (:fname, :mname, :lname, :gender, :birthdate, :address, :cont, :activity)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':fname' => $first,
        ':mname' => $middle,
        ':lname' => $last,
        ':gender' => $gender,
        ':birthdate' => $birthdate,
        ':address' => $address,
        ':cont' => $contact,
        ':activity' => $activity_id
    ]);

    echo "<script>alert('Beneficiary added successfully!'); window.location='adminbeneficiary.php';</script>";
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
        <div class="col-sm-6"><h1>ADD BENEFICIARY</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / Add Beneficiary</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">
    <div class="row text-center">
      <h2>Add New Beneficiary</h2>
      <form action="" method="POST" enctype="multipart/form-data" class="text-left" style="max-width:600px; margin:auto;">
        
        <label>First Name</label>
        <input type="text" name="First_Name" class="form-control" required>

        <label>Middle Name</label>
        <input type="text" name="Middle_Name" class="form-control">

        <label>Last Name</label>
        <input type="text" name="Last_Name" class="form-control" required>

        <label>Gender</label>
        <select name="Gender" class="form-control" required>
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
          <option>Other</option>
        </select>

        <label>Birthdate</label>
        <input type="date" name="Birthdate" class="form-control" required>

        <label>Address</label>
        <input type="text" name="Address" class="form-control" required>

        <label>Contact No.</label>
        <input type="text" name="Contact_No" class="form-control">

        <label>Project</label>
        <select name="Project_ID" id="Project_ID" class="form-control" required>
          <option value="">Select Project</option>
          <?php foreach ($projects as $proj): ?>
            <option value="<?php echo $proj['Project_ID']; ?>">
              <?php echo htmlspecialchars($proj['Project_Name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <label>Activity</label>
        <select name="Activity_ID" id="Activity_ID" class="form-control" required>
          <option value="">Select Activity</option>
        </select>

        <br>
        <div class="sponser-box">
          <button type="submit" class="btn2">Save Beneficiary</button>
          <a class="sponsor-button" href="adminbeneficiary.php">Back</a>
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
        fetch('beneficiary_add.php?getActivities=1&project_id=' + projectId)
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
