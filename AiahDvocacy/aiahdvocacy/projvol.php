<!DOCTYPE HTML>
<html class="no-js" lang="en">
<?php include '../inc/head.php'; ?>
<?php include 'dbconn.php'; ?>
<body>
<?php include '../inc/volunteerheader.php'; ?>

<section id="inner-banner">
  <div class="overlay">
    <div class="container">
      <div class="row"> 
        <div class="col-sm-6"><h1>PROJECTS</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="volunteer.php">Home</a> / Projects</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">
    <div class="row text-center">
      <div id="owl-demo" class="owl-carousel owl-theme">

      <?php
      $stmt = $conn->query("SELECT * FROM projects");
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

          $start_date = new DateTime($row['Start_Date']);
          $end_date   = new DateTime($row['End_Date']);
          $interval = $start_date->diff($end_date);

          $duration = '';
          if ($interval->y > 0) $duration .= $interval->y . ' year' . ($interval->y > 1 ? 's ' : ' ');
          if ($interval->m > 0) $duration .= $interval->m . ' month' . ($interval->m > 1 ? 's ' : ' ');
          if ($interval->d > 0) $duration .= $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
          if ($duration === '') $duration = '0 days';

          $checkAct = $conn->prepare("SELECT COUNT(*) FROM activities WHERE Project_ID = ?");
          $checkAct->execute([$row['Project_ID']]);
          $activityCount = $checkAct->fetchColumn();
      ?>
        <div class="item">
          <div class="sponser-box">
            <h4 style="color:red;"><?php echo htmlspecialchars($row['Project_Name']); ?></h4>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">About the Project</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Description']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Duration</div>
              <div class="col-xs-6"><?php echo $duration; ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Starting</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['Start_Date']); ?></div>
            </div>

            <div class="spon-bdr clearfix">
              <div class="col-xs-6">Until</div>
              <div class="col-xs-6"><?php echo htmlspecialchars($row['End_Date']); ?></div>
            </div>


            <?php if ($activityCount > 0): ?>
              <a href="sponsorprojacti.php?project_id=<?php echo $row['Project_ID']; ?>" class="btn1" style="margin-top:10px;">View Activities</a>
            <?php else: ?>
              <p style="color:gray; margin-top:10px;"><em>No activity yet.</em></p>
            <?php endif; ?>
          </div>
        </div>
      <?php } ?>

      </div>
    </div>
  </div>
</section>

<?php include '../inc/volunteerfooter.php'; ?>
</body>
</html>
