<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'volunteer') {
    echo "<script>alert('Access denied. Please log in as a volunteer.'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['User_ID'];

$volunteerStmt = $conn->prepare("SELECT * FROM volunteer WHERE User_ID = ? LIMIT 1");
$volunteerStmt->execute([$user_id]);
$volunteer = $volunteerStmt->fetch(PDO::FETCH_ASSOC);

$totalProjectsStmt = $conn->prepare("
    SELECT COUNT(DISTINCT p.Project_ID) AS total
    FROM projects p
    JOIN activities a ON a.Project_ID = p.Project_ID
    JOIN volunteer v ON v.Activity_ID = a.Activity_ID
    WHERE v.User_ID = ?
");
$totalProjectsStmt->execute([$user_id]);
$totalProjects = $totalProjectsStmt->fetch(PDO::FETCH_ASSOC)['total'];

$totalActivitiesStmt = $conn->prepare("
    SELECT COUNT(DISTINCT v.Activity_ID) AS total
    FROM volunteer v
    WHERE v.User_ID = ?
");
$totalActivitiesStmt->execute([$user_id]);
$totalActivities = $totalActivitiesStmt->fetch(PDO::FETCH_ASSOC)['total'];

$totalCompletedStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM volunteer v
    WHERE v.User_ID = ? AND v.Status = 'Completed'
");
$totalCompletedStmt->execute([$user_id]);
$totalCompleted = $totalCompletedStmt->fetch(PDO::FETCH_ASSOC)['total'];

$activityDataStmt = $conn->prepare("
    SELECT p.Project_Name, COUNT(v.Activity_ID) AS total
    FROM volunteer v
    JOIN activities a ON v.Activity_ID = a.Activity_ID
    JOIN projects p ON a.Project_ID = p.Project_ID
    WHERE v.User_ID = ?
    GROUP BY p.Project_Name
");
$activityDataStmt->execute([$user_id]);
$activityData = $activityDataStmt->fetchAll(PDO::FETCH_ASSOC);

$statusDataStmt = $conn->prepare("
    SELECT v.Status, COUNT(*) AS total
    FROM volunteer v
    WHERE v.User_ID = ?
    GROUP BY v.Status
");
$statusDataStmt->execute([$user_id]);
$statusData = $statusDataStmt->fetchAll(PDO::FETCH_ASSOC);

$recentActivitiesStmt = $conn->prepare("
    SELECT a.Activity_Name, p.Project_Name, v.Status, v.Date_Joined
    FROM volunteer v
    JOIN activities a ON v.Activity_ID = a.Activity_ID
    JOIN projects p ON a.Project_ID = p.Project_ID
    WHERE v.User_ID = ?
    ORDER BY v.Date_Joined DESC
    LIMIT 5
");
$recentActivitiesStmt->execute([$user_id]);
$recentActivities = $recentActivitiesStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE HTML>
<html class="no-js" lang="en">
<?php include '../inc/head.php'; ?>
<body class="bg-light">
<?php include '../inc/volunteerheader.php'; ?>

<section id="inner-banner">
    <div class="overlay">
        <div class="container">
            <div class="row"> 
                <div class="col-sm-6"><h1>VOLUNTEER DASHBOARD</h1></div>
                <div class="col-sm-6">
                    <h6 class="breadcrumb"><a href="volunteer.php">Home</a> / Dashboard</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="dashboard-overview" style="padding: 60px 0;">
    <div class="container">

        <h2 class="text-center mb-5">Welcome, <?= htmlspecialchars($volunteer['First_Name'] ?? 'Volunteer') ?>!</h2>
<br>
        <div class="row text-center mb-5">
            <div class="col-md-4 mb-3">
                <div class="card shadow border-0 p-4 rounded-4">
                    <h5>Projects Joined</h5>
                    <h3 class="text-primary"><?= $totalProjects ?></h3>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow border-0 p-4 rounded-4">
                    <h5>Activities</h5>
                    <h3 class="text-success"><?= $totalActivities ?></h3>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow border-0 p-4 rounded-4">
                    <h5>Completed Activities</h5>
                    <h3 class="text-warning"><?= $totalCompleted ?></h3>
                </div>
            </div>
        </div>
        <br>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow p-4 rounded-4">
                    <h5 class="text-center mb-3">Recently Joined Activity</h5>
                    <?php if (count($recentActivities) > 0): ?>
                    <table class="table table-striped text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>Date Joined</th>
                                <th>Activity Name</th>
                                <th>Project</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentActivities as $act): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('M d, Y', strtotime($act['Date_Joined'] ?? ''))) ?></td>
                                    <td><?= htmlspecialchars($act['Activity_Name']) ?></td>
                                    <td><?= htmlspecialchars($act['Project_Name']) ?></td>
                                    <td><?= htmlspecialchars($act['Status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p class="text-center text-muted">You haven't joined any activities yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<?php include '../inc/help.php'; ?>
<?php include '../inc/volunteerfooter.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    const activityLabels = <?= json_encode(array_column($activityData, 'Project_Name') ?: ['No Activities']) ?>;
    const activityCounts = <?= json_encode(array_column($activityData, 'total') ?: [0]) ?>;

    new Chart(document.getElementById('activitiesChart'), {
        type: 'bar',
        data: {
            labels: activityLabels,
            datasets: [{
                label: 'Activities',
                data: activityCounts,
                backgroundColor: '#0d6efd',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Activities per Project' }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    const statusLabels = <?= json_encode(array_column($statusData, 'Status') ?: ['No Activities']) ?>;
    const statusCounts = <?= json_encode(array_column($statusData, 'total') ?: [0]) ?>;
    const statusColors = ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#20c997'];

    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: statusColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Activity Status Distribution' }
            }
        }
    });
</script>
</body>
</html>
