<?php

include 'dbconn.php';
include '../inc/head.php';
include '../inc/adminheader.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('No beneficiary selected.'); window.location='adminbeneficiary.php';</script>";
    exit();
}

$beneficiary_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM beneficiary WHERE Beneficiary_ID = :id AND is_deleted = 0");
$stmt->execute(['id' => $beneficiary_id]);
$beneficiary = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$beneficiary) {
    echo "<script>alert('Beneficiary not found.'); window.location='adminbeneficiary.php';</script>";
    exit();
}
$activitiesStmt = $conn->query("SELECT Activity_ID, Activity_Name FROM activities ORDER BY Activity_Name ASC");
$activities = $activitiesStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['First_Name'];
    $last_name = $_POST['Last_Name'];
    $birthdate = $_POST['Birthdate'];
    $gender = $_POST['Gender'];
    $address = $_POST['Address'];
    $contact_no = $_POST['Contact_No'];
    $activity_id = $_POST['Activity_ID'];

    $updateStmt = $conn->prepare("UPDATE beneficiary SET 
        First_Name = :first_name,
        Last_Name = :last_name,
        Birthdate = :birthdate,
        Gender = :gender,
        Address = :address,
        Contact_No = :contact_no,
        Activity_ID = :activity_id
        WHERE Beneficiary_ID = :id
    ");

    $updateStmt->execute([
        'first_name' => $first_name,
        'last_name' => $last_name,
        'birthdate' => $birthdate,
        'gender' => $gender,
        'address' => $address,
        'contact_no' => $contact_no,
        'activity_id' => $activity_id,
        'id' => $beneficiary_id
    ]);

    echo "<script>alert('Beneficiary updated successfully!'); window.location='adminbeneficiary.php';</script>";
    exit();
}
?>

<section id="inner-banner">
  <div class="overlay">
    <div class="container">
      <div class="row"> 
        <div class="col-sm-6"><h1>Edit Beneficiary</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / <a href="adminbeneficiary.php">Beneficiary</a> / Edit</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container text-center">
    <h2>Edit Beneficiary Details</h2>
    <br>

    <form method="POST" style="max-width:700px; margin:auto; text-align:left;">

      <label>First Name:</label>
      <input type="text" name="First_Name" value="<?php echo htmlspecialchars($beneficiary['First_Name']); ?>" class="form-control" required><br>

      <label>Last Name:</label>
      <input type="text" name="Last_Name" value="<?php echo htmlspecialchars($beneficiary['Last_Name']); ?>" class="form-control" required><br>

      <label>Birthdate:</label>
      <input type="date" name="Birthdate" value="<?php echo htmlspecialchars($beneficiary['Birthdate']); ?>" class="form-control" required><br>

      <label>Gender:</label>
      <select name="Gender" class="form-control" required>
        <option value="Male" <?php if($beneficiary['Gender']=='Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if($beneficiary['Gender']=='Female') echo 'selected'; ?>>Female</option>
      </select><br>

      <label>Address:</label>
      <input type="text" name="Address" value="<?php echo htmlspecialchars($beneficiary['Address']); ?>" class="form-control" required><br>

      <label>Contact No.:</label>
      <input type="text" name="Contact_No" value="<?php echo htmlspecialchars($beneficiary['Contact_No']); ?>" class="form-control" required><br>

      <label>Activity:</label>
      <select name="Activity_ID" class="form-control" required>
        <option value="">Select Activity</option>
        <?php foreach($activities as $activity) { ?>
          <option value="<?php echo $activity['Activity_ID']; ?>" <?php if($activity['Activity_ID'] == $beneficiary['Activity_ID']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($activity['Activity_Name']); ?>
          </option>
        <?php } ?>
      </select><br><br>

      <div style="text-align:center;">
        <button type="submit" class="btn1">Update Beneficiary</button>
        <a href="adminbeneficiary.php" class="btn1" style="background-color: gray; margin-left:10px;">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?php include '../inc/footer.php'; ?>

</body>
</html>
