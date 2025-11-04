<?php
session_start();
include 'dbconn.php';

$volunteers = $conn->query("
    SELECT v.Volunteer_ID, v.First_Name, v.Last_Name, a.Activity_Name
    FROM volunteer v
    LEFT JOIN activities a ON v.Activity_ID = a.Activity_ID
    WHERE v.Status = 'Pending'
")->fetchAll(PDO::FETCH_ASSOC);

$donations = $conn->query("
    SELECT d.Donation_ID, d.Amount, d.Date_Donated, s.First_Name AS Sponsor_First, s.Last_Name AS Sponsor_Last, a.Activity_Name
    FROM donations d
    JOIN sponsor s ON d.Sponsor_ID = s.Sponsor_ID
    JOIN activities a ON d.Activity_ID = a.Activity_ID
    WHERE d.Status = 'Pending'
    ORDER BY d.Date_Donated DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/adminheader.php'; ?>
<br>
<br>
<br>
<br>
<br>
<section style="padding:40px 0;">
<div class="container" style="max-width:900px;">
    <h2>Pending Requests</h2>
    <hr>

    <h3>Volunteer</h3>
    <?php if(count($volunteers) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Volunteer ID</th>
                    <th>Full Name</th>
                    <th>Activity Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($volunteers as $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['Volunteer_ID']) ?></td>
                    <td><?= htmlspecialchars($v['First_Name'] . ' ' . $v['Last_Name']) ?></td>
                    <td><?= htmlspecialchars($v['Activity_Name'] ?? 'N/A') ?></td>
                    <td>
                        <a href="approve_volunteer.php?id=<?= $v['Volunteer_ID'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="reject_volunteer.php?id=<?= $v['Volunteer_ID'] ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending volunteer requests.</p>
    <?php endif; ?>

    <h3>Donation</h3>
    <?php if(count($donations) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Donor</th>
                    <th>Activity</th>
                    <th>Amount (PHP)</th>
                    <th>Date Donated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($donations as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['Sponsor_First'] . ' ' . $d['Sponsor_Last']) ?></td>
                    <td><?= htmlspecialchars($d['Activity_Name']) ?></td>
                    <td><?= htmlspecialchars(number_format($d['Amount'], 2)) ?></td>
                    <td><?= htmlspecialchars($d['Date_Donated']) ?></td>
                    <td>
                        <a href="approve_donation.php?id=<?= $d['Donation_ID'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="reject_donation.php?id=<?= $d['Donation_ID'] ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending donation requests.</p>
    <?php endif; ?>

</div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
