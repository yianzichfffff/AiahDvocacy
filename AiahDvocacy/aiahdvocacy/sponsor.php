<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'sponsor') {
    echo "<script>alert('Access denied. Please log in as a sponsor.'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['User_ID'];

$sponsorStmt = $conn->prepare("SELECT * FROM sponsor WHERE User_ID = ?");
$sponsorStmt->execute([$user_id]);
$sponsor = $sponsorStmt->fetch(PDO::FETCH_ASSOC);

$totalDonationStmt = $conn->prepare("
    SELECT IFNULL(SUM(Amount),0) AS total 
    FROM donations 
    WHERE Sponsor_ID = ? AND Status = 'approved'
");
$totalDonationStmt->execute([$sponsor['Sponsor_ID']]);
$totalDonation = $totalDonationStmt->fetch(PDO::FETCH_ASSOC)['total'];

$totalProjectsStmt = $conn->prepare("
    SELECT COUNT(DISTINCT Activity_ID) AS total 
    FROM donations 
    WHERE Sponsor_ID = ? AND Status = 'approved'
");
$totalProjectsStmt->execute([$sponsor['Sponsor_ID']]);
$totalProjects = $totalProjectsStmt->fetch(PDO::FETCH_ASSOC)['total'];

$projectDonationsStmt = $conn->prepare("
    SELECT a.Activity_Name AS Project_Name, IFNULL(SUM(d.Amount),0) AS total
    FROM activities a
    JOIN donations d ON d.Activity_ID = a.Activity_ID
    WHERE d.Sponsor_ID = ? AND d.Status = 'approved'
    GROUP BY a.Activity_ID
");
$projectDonationsStmt->execute([$sponsor['Sponsor_ID']]);
$projectDonations = $projectDonationsStmt->fetchAll(PDO::FETCH_ASSOC);

$dailyStmt = $conn->prepare("
    SELECT DATE_FORMAT(Date_Donated, '%b %d, %Y') AS Day, IFNULL(SUM(Amount),0) AS Total
    FROM donations
    WHERE Sponsor_ID = ? AND Status = 'approved'
    GROUP BY DATE(Date_Donated)
    ORDER BY Date_Donated ASC
");
$dailyStmt->execute([$sponsor['Sponsor_ID']]);
$dailyDonations = $dailyStmt->fetchAll(PDO::FETCH_ASSOC);

$recentDonationsStmt = $conn->prepare("
    SELECT d.Amount, d.Date_Donated, a.Activity_Name
    FROM donations d
    LEFT JOIN activities a ON d.Activity_ID = a.Activity_ID
    WHERE d.Sponsor_ID = ? AND d.Status = 'approved'
    ORDER BY d.Date_Donated DESC
    LIMIT 5
");
$recentDonationsStmt->execute([$sponsor['Sponsor_ID']]);
$recentDonations = $recentDonationsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE HTML>
<html class="no-js" lang="en">

<?php include '../inc/head.php'; ?>

<body class="bg-light">  
    <?php include '../inc/sponsorheader.php'; ?>

    <section id="inner-banner">
        <div class="overlay">
            <div class="container">
                <div class="row"> 
                    <div class="col-sm-6"><h1>SPONSOR DASHBOARD</h1></div>
                    <div class="col-sm-6">
                        <h6 class="breadcrumb"><a href="sponsor.php">Home</a> / Dashboard</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="dashboard-overview" style="padding: 60px 0;">
        <div class="container">

            <h2 class="text-center mb-5">Welcome, <?= htmlspecialchars($sponsor['First_Name']) ?>!</h2>
<br>
<br>
           
            <div class="row text-center mb-5">
                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0 p-4">
                        <h5>Total Donations</h5>
                        <h3 class="text-primary">₱<?= number_format($totalDonation, 2) ?></h3>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card shadow border-0 p-4">
                        <h5>Activities Supported</h5>
                        <h3 class="text-success"><?= $totalProjects ?></h3>
                    </div>
                </div>
            </div>
<br>
<br>
        
            <div class="row">
                
                <div class="col-md-6 mb-4">
                    <div class="card shadow p-4">
                        <h5 class="text-center mb-3">Donations by Activity</h5>
                        <canvas id="donationByProjectChart" height="250"></canvas>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow p-4">
                        <h5 class="text-center mb-3">Daily Donation Trend</h5>
                        <canvas id="dailyDonationChart" height="250"></canvas>
                    </div>
                </div>
            </div>
<br>
<br>
 
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow p-4">
                        <h5 class="text-center mb-3">Recent Donations</h5>
                        <table class="table table-striped text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentDonations as $donation): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($donation['Date_Donated']))) ?></td>
                                        <td><?= htmlspecialchars($donation['Activity_Name'] ?? 'N/A') ?></td>
                                        <td>₱<?= number_format($donation['Amount'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <?php include '../inc/help.php'; ?>
    <?php include '../inc/sponsorfooter.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const projectLabels = <?= json_encode(array_column($projectDonations, 'Project_Name') ?: ['No Approved Donations']) ?>;
        const projectData = <?= json_encode(array_column($projectDonations, 'total') ?: [0]) ?>;

        const dailyLabels = <?= json_encode(array_column($dailyDonations, 'Day') ?: ['No Approved Donations']) ?>;
        const dailyData = <?= json_encode(array_column($dailyDonations, 'Total') ?: [0]) ?>;

        new Chart(document.getElementById('donationByProjectChart'), {
            type: 'pie',
            data: {
                labels: projectLabels,
                datasets: [{
                    label: '₱ Donations',
                    data: projectData,
                    backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#20c997']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Your Donation Distribution' }
                }
            }
        });

        new Chart(document.getElementById('dailyDonationChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: '₱ Daily Donations',
                    data: dailyData,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Your Daily Contribution Trend' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: value => '₱' + value } }
                }
            }
        });
    </script>
</body>
</html>
