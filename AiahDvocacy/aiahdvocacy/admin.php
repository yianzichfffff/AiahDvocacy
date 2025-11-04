<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'admin') {
  echo "<script>alert('Access denied. Please log in as an admin.'); window.location='login.php';</script>";
  exit;
}

$totalDonation = $conn->query("
    SELECT IFNULL(SUM(Amount), 0) AS total 
    FROM donations 
    WHERE Status = 'approved'
")->fetch(PDO::FETCH_ASSOC)['total'];

$totalBeneficiary = $conn->query("SELECT COUNT(*) AS total FROM beneficiary")->fetch(PDO::FETCH_ASSOC)['total'];

$totalProject = $conn->query("SELECT COUNT(*) AS total FROM projects")->fetch(PDO::FETCH_ASSOC)['total'];

$totalActivity = $conn->query("SELECT COUNT(*) AS total FROM activities")->fetch(PDO::FETCH_ASSOC)['total'];

$donationsByProject = $conn->query("
  SELECT a.Activity_Name, IFNULL(SUM(d.Amount),0) AS TotalDonation
  FROM activities a
  LEFT JOIN donations d 
    ON d.Activity_ID = a.Activity_ID AND d.Status = 'approved'
  GROUP BY a.Activity_ID
")->fetchAll(PDO::FETCH_ASSOC);

$beneficiariesByProject = $conn->query("
  SELECT a.Activity_Name, COUNT(b.Beneficiary_ID) AS Beneficiaries
  FROM activities a
  LEFT JOIN beneficiary b ON b.Activity_ID = a.Activity_ID
  GROUP BY a.Activity_ID
")->fetchAll(PDO::FETCH_ASSOC);

$dailyDonations = $conn->query("
  SELECT DATE_FORMAT(d.Date_Donated, '%b %d, %Y') AS Day, IFNULL(SUM(d.Amount),0) AS Total
  FROM donations d
  WHERE d.Status = 'approved'
  GROUP BY DATE(d.Date_Donated)
  ORDER BY d.Date_Donated ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE HTML>
<html class="no-js" lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/adminheader.php'; ?>

<section id="inner-banner">
  <div class="overlay">
    <div class="container">
      <div class="row"> 
        <div class="col-sm-6"><h1>DASHBOARD</h1></div>
        <div class="col-sm-6">
          <h6 class="breadcrumb"><a href="admin.php">Home</a> / Dashboard</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about-sec">
  <div class="container py-5">
    <h2 class="text-center mb-4">Admin Dashboard Overview</h2>
    <br><br>

    <div class="row text-center mb-4">
      <div class="col-md-3">
        <div class="card shadow border-0 p-3">
          <h5>Total Donations</h5>
          <h3 class="text-primary">₱<?= number_format($totalDonation, 2) ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow border-0 p-3">
          <h5>Total Beneficiaries</h5>
          <h3 class="text-success"><?= $totalBeneficiary ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow border-0 p-3">
          <h5>Total Projects</h5>
          <h3 class="text-info"><?= $totalProject ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow border-0 p-3">
          <h5>Total Activities</h5>
          <h3 class="text-warning"><?= $totalActivity ?></h3>
        </div>
      </div>
    </div>
    <br><br>

    <div class="row mt-5">

      <div class="col-md-6 mb-4">
        <div class="card shadow p-4">
          <h5 class="text-center mb-3">Donations by Activities</h5>
          <canvas id="donationChart" height="250"></canvas>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card shadow p-4">
          <h5 class="text-center mb-3">Beneficiaries by Activities</h5>
          <canvas id="beneficiaryChart" height="250"></canvas>
        </div>
      </div>
    </div>

    <br><br>
    <div class="row mt-4">
      <div class="col-md-12 mb-4">
        <div class="card shadow p-4">
          <h5 class="text-center mb-3">Daily Donation Trend</h5>
          <canvas id="dailyChart" height="100"></canvas>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include '../inc/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

  new Chart(document.getElementById('donationChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($donationsByProject, 'Activity_Name') ?: ['No Approved Donations']) ?>,
      datasets: [{
        label: '₱ Donations',
        data: <?= json_encode(array_column($donationsByProject, 'TotalDonation') ?: [0]) ?>,
        backgroundColor: '#0d6efd',
        borderRadius: 5
      }]
    },
    options: { scales: { y: { beginAtZero: true } } }
  });

  new Chart(document.getElementById('beneficiaryChart'), {
    type: 'pie',
    data: {
      labels: <?= json_encode(array_column($beneficiariesByProject, 'Activity_Name') ?: ['No Data']) ?>,
      datasets: [{
        data: <?= json_encode(array_column($beneficiariesByProject, 'Beneficiaries') ?: [0]) ?>,
        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#20c997']
      }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
  });

  new Chart(document.getElementById('dailyChart'), {
    type: 'line',
    data: {
      labels: <?= json_encode(array_column($dailyDonations, 'Day') ?: ['No Approved Donations']) ?>,
      datasets: [{
        label: '₱ Donations per Day',
        data: <?= json_encode(array_column($dailyDonations, 'Total') ?: [0]) ?>,
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13,110,253,0.1)',
        fill: true,
        tension: 0.3
      }]
    },
    options: { scales: { y: { beginAtZero: true } } }
  });
</script>
</body>
</html>
