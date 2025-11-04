<?php
session_start();
include 'dbconn.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fname = trim($_POST['First_Name']);
    $mname = trim($_POST['Middle_Name']);
    $lname = trim($_POST['Last_Name']);
    $gender = $_POST['Gender'];
    $birthdate = $_POST['Birthdate'];
    $address = trim($_POST['Address']);
    $contact = trim($_POST['Contact_No']);

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO users (Username, Password, Role) VALUES (?, ?, 'Beneficiary')");
        $stmt->execute([$username, $password]);
        $user_id = $conn->lastInsertId();

        $stmt2 = $conn->prepare("INSERT INTO beneficiary (User_ID, First_Name, Middle_Name, Last_Name, Gender, Birthdate, Address, Contact_No)
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->execute([$user_id, $fname, $mname, $lname, $gender, $birthdate, $address, $contact]);

        $conn->commit();
        $message = "Registration successful! You can now log in.";
    } catch (Exception $e) {
        $conn->rollBack();
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>
<section style="padding:40px 0; text-align:center;">
  <h1 style="color:#d81b2a;">Beneficiary Registration</h1>
  <div style="max-width:600px; margin:auto;">
    <form method="post" style="background:#fff; padding:25px 30px; border-radius:4px; box-shadow:0 2px 6px rgba(0,0,0,0.08); text-align:left;">
      
      <h3 style="margin-bottom:15px;">Account Information</h3>
      <label>Username</label>
      <input name="username" type="text" required style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:3px;">
      <label>Password</label>
      <input name="password" type="password" required style="width:100%;padding:10px;margin-bottom:20px;border:1px solid #ddd;border-radius:3px;">

      <h3 style="margin-bottom:15px;">Personal Information</h3>
      <input name="First_Name" placeholder="First Name" required style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:3px;">
      <input name="Middle_Name" placeholder="Middle Name" style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:3px;">
      <input name="Last_Name" placeholder="Last Name" required style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:3px;">
      
      <label>Gender</label>
      <select name="Gender" required style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:3px;">
        <option value="">Gender</option>
        <option>Male</option>
        <option>Female</option>
      </select>

      <label>Birthdate</label>
      <input type="date" name="Birthdate" required style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:3px;">

      <input name="Address" placeholder="Address" required style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:3px;">
      <input name="Contact_No" placeholder="Contact Number" required style="width:100%;padding:10px;margin-bottom:20px;border:1px solid #ddd;border-radius:3px;">

      <?php if ($message): ?>
        <div style="color:#d81b2a; text-align:center; margin-bottom:15px;"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <button type="submit" style="width:100%; background:#d81b2a; color:#fff; padding:12px; border:none; border-radius:4px; font-weight:700;">REGISTER</button>
      <div style="text-align:center; margin-top:12px;">
        Already registered? <a href="login.php" style="color:#d81b2a;">Log in here</a>
      </div>
    </form>
  </div>
</section>
<?php include '../inc/footer.php'; ?>
</body>
</html>
