<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['User_ID']) || $_SESSION['Role'] !== 'volunteer') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['User_ID'];

$stmt = $conn->prepare("
    SELECT First_Name, Last_Name, Email, Phone
    FROM volunteer
    WHERE User_ID = ?
");
$stmt->execute([$user_id]);
$volunteer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$volunteer) {
    echo "<script>alert('Volunteer profile not found'); window.location='volunteerprofile.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $contact    = $_POST['contact'];


    $update = $conn->prepare("
        UPDATE volunteer
        SET First_Name=?, Last_Name=?, Email=?, Phone=?
        WHERE User_ID=?
    ");
    $update->execute([$first_name, $last_name, $email, $contact, $user_id]);

    echo "<script>alert('Profile updated successfully!'); window.location='volunteerprofile.php';</script>";
    exit;
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
<br>
<section style="padding:40px 0; background:#f0f2f5;">
<div class="container" style="max-width:700px; background:#fff; padding:30px; border-radius:10px;">
    <h1>Edit Profile</h1>
    <hr>
    <form method="POST" style="text-align:left;">

        <label>First Name</label>
        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($volunteer['First_Name']); ?>" required>

        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($volunteer['Last_Name']); ?>" required>

        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($volunteer['Email']); ?>" required>

        <label>Contact No.</label>
        <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($volunteer['Phone']); ?>">


        <div style="margin-top:20px;">
            <button type="submit" class="btn" style="padding:8px 15px; background:#d81b2a; color:white; border-radius:5px;">Update Profile</button>
            <a href="volunteerprofile.php" class="btn" style="padding:8px 15px; background:gray; color:white; border-radius:5px;">Cancel</a>
        </div>
    </form>
</div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
