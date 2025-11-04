<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'volunteer') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['User_ID'];
$username = $_SESSION['Username'] ?? '';

$stmt = $conn->prepare("SELECT Volunteer_ID, Status FROM volunteer WHERE User_ID = ?");
$stmt->execute([$user_id]);
$volunteer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$volunteer) die("Volunteer profile not found!");
$volunteer_id = $volunteer['Volunteer_ID'];

$message = '';

$activity_id = $_GET['activity_id'] ?? null;
if (!$activity_id) die("No activity selected!");

$stmt = $conn->prepare("SELECT Activity_Name FROM activities WHERE Activity_ID = ?");
$stmt->execute([$activity_id]);
$activity = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$activity) die("Activity not found!");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        UPDATE volunteer
        SET Activity_ID = ?, Status = 'Pending'
        WHERE Volunteer_ID = ?
    ");
    $stmt->execute([$activity_id, $volunteer_id]);

    $message = "Your volunteer request has been submitted and is pending admin approval.";
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/volunteerheader.php'; ?>
<br>
<br>
<br>
<br>

<section style="padding:40px 0; background:#f0f2f5;">
<div class="container" style="max-width:600px; background:#fff; padding:30px; border-radius:10px;">
    <h1>Join Us</h1>
    <hr>

    <?php if($message): ?>
        <p style="color:green; font-weight:bold;"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Volunteer Name</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($username); ?>" disabled>

        <label>Activity</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($activity['Activity_Name']); ?>" disabled>
        <input type="hidden" name="Activity_ID" value="<?= $activity_id ?>">

        <div style="margin-top:20px;">
            <button type="submit" class="btn" style="background:#d81b2a; color:white; padding:8px 15px; border-radius:5px;">Volunteer</button>
        </div>
    </form>
</div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
