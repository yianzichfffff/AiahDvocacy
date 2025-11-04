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
        <div class="col-sm-6"><h1>SPONSOR</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / Sponsor</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container">
    <div class="row text-center">
      <div id="owl-demo" class="owl-carousel owl-theme">
<h1 style= "color:black">SPONSORS</h1>
<br>
        <?php

        $stmt = $conn->query("
          SELECT 
            s.Sponsor_ID,
            s.First_Name,
            s.Last_Name,
            s.Address,
            s.Gender,
            s.Phone,
            s.Email,
            IFNULL(SUM(d.Amount), 0) AS Total_Approved_Donation
          FROM sponsor s
          LEFT JOIN donations d 
            ON s.Sponsor_ID = d.Sponsor_ID 
            AND d.Status = 'Approved'
          GROUP BY s.Sponsor_ID, s.First_Name, s.Last_Name, s.Address, s.Gender, s.Phone, s.Email
          ORDER BY s.First_Name
        ");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <div class="item">
            <div class="sponser-box">
              <h4 style="color:red;">
                Hi, I’m <?php echo htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']); ?>
              </h4>

              <div class="spon-bdr clearfix">
                <div class="col-xs-6">Where I Live</div>
                <div class="col-xs-6"><?php echo htmlspecialchars($row['Address']); ?></div>
              </div>

              <div class="spon-bdr clearfix">
                <div class="col-xs-6">Gender</div>
                <div class="col-xs-6"><?php echo htmlspecialchars($row['Gender']); ?></div>
              </div>

              <div class="spon-bdr clearfix">
                <div class="col-xs-6">Contact No.</div>
                <div class="col-xs-6"><?php echo htmlspecialchars($row['Phone']); ?></div>
              </div>

              <div class="spon-bdr clearfix">
                <div class="col-xs-6">Email</div>
                <div class="col-xs-6"><?php echo htmlspecialchars($row['Email']); ?></div>
              </div>

              <div class="spon-bdr clearfix">
                <div class="col-xs-6"><strong>Total Donations</strong></div>
                <div class="col-xs-6"><strong>₱<?php echo number_format($row['Total_Approved_Donation'], 2); ?></strong></div>
              </div>
            </div>
          </div>
        <?php
        } 
        ?>

      </div>
    </div>
  </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
