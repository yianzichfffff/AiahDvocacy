<?php  
session_start();
include 'dbconn.php'; 
if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['Project_Name'];
    $desc = $_POST['Description'];
    $std = $_POST['Start_Date'];
    $end = $_POST['End_Date'];

    $sql = "INSERT INTO projects (Project_Name,Description, Start_Date, End_Date)
                VALUES (:fnme, :des, :start, :nd)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':fnme' => $name,
            ':des' => $desc,
            ':start' => $std,
            ':nd' => $end,
        ]);

    echo "<script>alert('Project added successfully!'); window.location='adminproj.php';</script>";
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
        <div class="col-sm-6"><h1>ADD PROJECT</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / Add Project</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">
    <div class="row text-center">
      <h2>Add New Project</h2>
      <form action="" method="POST" enctype="multipart/form-data" class="text-left" style="max-width:600px; margin:auto;">
        <label>Project Name</label>
        <input type="text" name="Project_Name" class="form-control" required>

        <label>Description</label>
        <input type="text" name="Description" class="form-control">

        <label>Start Date</label>
        <input type="date" name="Start_Date" class="form-control" required>

        <label>End Date</label>
        <input type="date" name="End_Date" class="form-control" required>

        <br>
        <div class="sponser-box">
        <button type="submit" class="btn2">Save Project</button>
        <a class="sponsor-button" href="activityadd.php">Activity</a>
        <a class="sponsor-button" href="addbeneficiary.php">Beneficiary</a>
</div>
      </form>
    </div>
  </div>
</section>  
<?php include '../inc/footer.php'; ?>
</body>
</html>
