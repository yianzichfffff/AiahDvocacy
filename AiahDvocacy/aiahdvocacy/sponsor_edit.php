<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'sponsor') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['User_ID'];

$stmt = $conn->prepare("
    SELECT s.First_Name, s.Last_Name, s.Email, s.Phone, s.Address
    FROM sponsor s
    WHERE s.User_ID = ?
");
$stmt->execute([$user_id]);
$sponsor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sponsor) {
    echo "<script>alert('Sponsor profile not found'); window.location='sponsorprofile.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $contact    = $_POST['contact'];
    $address    = $_POST['address'];

    $update = $conn->prepare("
        UPDATE sponsor
        SET First_Name=?, Last_Name=?, Email=?, Phone=?, Address=?
        WHERE User_ID=?
    ");
    $update->execute([$first_name, $last_name, $email, $contact, $address, $user_id]);

    echo "<script>alert('Profile updated successfully!'); window.location='sponsorprofile.php';</script>";
    exit;
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
<br>
<br>
<section style="padding:40px 0; background:#f0f2f5;">
<div class="container" style="max-width:700px; background:#fff; padding:30px; border-radius:10px;">
    <h1>Edit Profile</h1>
    <hr>
    <form method="POST" style="text-align:left;">

        <label>First Name</label>
        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($sponsor['First_Name']); ?>" required>

        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($sponsor['Last_Name']); ?>" required>

        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($sponsor['Email']); ?>" required>

        <label>Contact No.</label>
        <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($sponsor['Phone']); ?>">

        <label>Address</label>
        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($sponsor['Address']); ?>">

        <div style="margin-top:20px;">
            <button type="submit" class="btn" style="padding:8px 15px; background:#d81b2a; color:white; border-radius:5px;">Update Profile</button>
            <a href="sponsorprofile.php" class="btn" style="padding:8px 15px; background:gray; color:white; border-radius:5px;">Cancel</a>
        </div>
    </form>
</div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
