<!DOCTYPE HTML>
<html class="no-js" lang="en">
<?php include '../inc/head.php'; ?>
<?php include 'dbconn.php'; ?>
<body>
<?php include '../inc/sponsorheader.php'; ?>

<section id="inner-banner">
  <div class="overlay">
    <div class="container">
      <div class="row"> 
        <div class="col-sm-6"><h1>ACTIVITIES</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="sponsor.php">Home</a> / Activities</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3" style="display:flex; justify-content:space-between; align-items:center;">
      <h2 style="margin:0;">All Activities</h2>
      <a href="completedactivities.php" 
         class="btn1" 
         style="background-color:#ff4c4c; color:white; padding:8px 15px; border-radius:20px; font-weight:bold; text-decoration:none;">
         View Completed Activities
      </a>
    </div>
    <hr style="border-top: 2px solid #ccc;">

    <div class="row text-center">
      <div id="owl-demo" class="owl-carousel owl-theme">

      <?php

      $stmt = $conn->query("
        SELECT a.*, p.Project_Name 
        FROM activities a
        LEFT JOIN projects p ON a.Project_ID = p.Project_ID
      ");

      $today = new DateTime();
      $hasOngoing = false;

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

          $status = "No Date Info";
          if (!empty($row['Start_Date']) && !empty($row['End_Date'])) {
              $start = new DateTime($row['Start_Date']);
              $end   = new DateTime($row['End_Date']);

              if ($today < $start) {
                  $status = "Not yet started";
              } elseif ($today >= $start && $today <= $end) {
                  $status = "Ongoing";
              } else {
                  $status = "Completed";
              }
          }


          if ($status === "Completed") continue;

          $hasOngoing = true;
      ?>
        <div class="item">
          <div class="sponser-box">
            <h4 style="color:red;"><?php echo htmlspecialchars($row['Activity_Name']); ?></h4>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Project</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Project_Name'] ?? 'No Project'); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Description</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Description']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Location</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Location']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Start Date</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Start_Date']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Status</div>
              <div class="col-xs-6">
                <?php 
                  if ($status == "Ongoing") echo "<span style='color:orange;font-weight:bold;'>Ongoing</span>";
                  elseif ($status == "Not yet started") echo "<span style='color:blue;font-weight:bold;'>Not yet started</span>";
                  else echo "<span style='color:red;font-weight:bold;'>No date info</span>";
                ?>
              </div>
            </div>

            <a href="donationform.php?id=<?php echo $row['Activity_ID']; ?>" class="btn1">Donate Here</a>
          </div>
        </div>
      <?php } ?>

      <?php if (!$hasOngoing): ?>
        <p style="color:gray; text-align:center; width:100%;"><em>No ongoing or upcoming activities found.</em></p>
      <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
