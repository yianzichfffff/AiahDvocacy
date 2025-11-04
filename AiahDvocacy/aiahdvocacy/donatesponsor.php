<?php
session_start();
include 'dbconn.php';

$message = ''; 

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'sponsor') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['User_ID'];
$username = $_SESSION['Username'] ?? '';

$stmt = $conn->prepare("SELECT Sponsor_ID FROM sponsor WHERE User_ID = ?");
$stmt->execute([$user_id]);
$sponsor = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$sponsor) die("Sponsor profile not found!");
$sponsor_id = $sponsor['Sponsor_ID'];

$activity_id = $_GET['activity_id'] ?? null;
if (!$activity_id) die("No activity selected!");

$stmt = $conn->prepare("SELECT Activity_Name FROM activities WHERE Activity_ID = ?");
$stmt->execute([$activity_id]);
$activity = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$activity) die("Activity not found!");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? null;
    $payment_method = $_POST['payment_method'] ?? null;

    if (!$amount || !$payment_method) {
        $message = "Please fill all required fields.";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO donations 
            (Sponsor_ID, Activity_ID, Amount, Mode_of_Payment, Date_Donated, Status)
            VALUES (?, ?, ?, ?, NOW(), 'Pending')
        ");
        $stmt->execute([$sponsor_id, $activity_id, $amount, $payment_method]);

        $message = "Your donation has been submitted and is pending approval by the admin.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<?php include '../inc/sponsorheader.php'; ?>
<br>
<br>
<br>
<section style="padding:40px 0; background:#f0f2f5;">
<div class="container" style="max-width:600px; background:#fff; padding:30px; border-radius:10px;">
    <h1>Make a Donation</h1>
    <hr>

    <?php if($message): ?>
        <p style="color:red; font-weight:bold;"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Donor Name</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($username); ?>" disabled>

        <label>Activity</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($activity['Activity_Name']); ?>" disabled>
        <input type="hidden" name="Activity_ID" value="<?= $activity_id ?>">

        <label>Amount (PHP)</label>
        <input type="number" name="amount" class="form-control" required min="1">

        <label>Mode of Payment</label>
        <select name="payment_method" class="form-control" required>
            <option value="">Select Payment Method</option>
            <option value="GCash">GCash</option>
            <option value="PayMaya">PayMaya</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Cash">Cash</option>
        </select>

        <div style="margin-top:20px;">
            <button type="submit" class="btn" style="background:#d81b2a; color:white; padding:8px 15px; border-radius:5px;">Donate Now</button>
        </div>
    </form>
</div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
