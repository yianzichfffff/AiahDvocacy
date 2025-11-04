<!DOCTYPE HTML>
<html class="no-js" lang="en">
<?php include '../inc/head.php'; ?>
<?php include 'dbconn.php'; ?>
<body>
<?php include '../inc/adminheader.php'; ?>

<section id="inner-banner">
  <div class="overlay">
    <div class="container">
      <div class="row"> 
        <div class="col-sm-6"><h1>BENEFICIARY</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / Beneficiary</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">
    <div class="row text-center">
      <div id="owl-demo" class="owl-carousel owl-theme">

<div class="d-flex justify-content-between align-items-center mb-3" style="display:flex; justify-content:space-between; align-items:center;">
      <h1 style="margin:0; color:black">BENEFICIARIES</h1>
      <a href="beneficiary_add.php" 
         class="btn1" 
         style="background-color:#ff4c4c; color:white; padding:8px 15px; border-radius:20px; font-weight:bold; text-decoration:none;">
         Add Beneficiary
      </a>
    </div>
    <br>
        <?php

        $stmt = $conn->query("
    SELECT b.*, 
           a.Activity_Name, 
           p.Project_Name
    FROM beneficiary b
    LEFT JOIN activities a ON b.Activity_ID = a.Activity_ID
    LEFT JOIN projects p ON a.Project_ID = p.Project_ID
    WHERE b.is_deleted = 0
");


        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $birthdate = new DateTime($row['Birthdate']);
            $today = new DateTime();
            $age = $today->diff($birthdate)->y;
        ?>
         <div class="d-flex justify-content-between align-items-center mb-3" style="display:flex; justify-content:space-between; align-items:center;">
      <h2 style="margin:0;"></h2>
      
    </div>
        <div class="item">
          <div class="sponser-box">
            <h4 style="color:red;">Hi, I’m <?php echo htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']); ?></h4>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Where I Live</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Address']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">My Age</div>
              <div class="col-xs-6"><?php echo $age; ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">My Birthday</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Birthdate']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Gender</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Gender']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Contact No.</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Contact_No']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Activity</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Activity_Name'] ?? 'N/A'); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Project</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Project_Name'] ?? 'N/A'); ?></div>
            </div>

            <a href="beneficiary_edit.php?id=<?php echo $row['Beneficiary_ID']; ?>" class="btn1">Edit Information</a>
            <a href="delete_beneficiary.php?id=<?php echo $row['Beneficiary_ID']; ?>" class="btn1" 
style="background-color:red; margin-left:10px;"
              onclick="return confirm('Are you sure you want to hide this beneficiary?');">
              Delete
            </a>

          </div>
        </div>

        <?php } ?>
        
      </div>
    </div>
  </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
