<?php
session_start();
include 'dbconn.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $message = 'Please enter username and password.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
  
            $_SESSION['User_ID']  = $user['User_ID'];
            $_SESSION['Username'] = $user['Username'];
            $_SESSION['Role']     = $user['Role'];

            switch ($user['Role']) {
                case 'admin': header('Location: admin.php'); break;
                case 'volunteer': header('Location: volunteer.php'); break;
                case 'sponsor': header('Location: sponsor.php'); break;
                case 'beneficiary': header('Location: beneficiary.php'); break;
              
            }
            exit;
        } else {
            $message = 'Invalid username or password.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>

<section style="padding:40px 0; text-align:center;">
  <h1 style="color:#d81b2a; font-size:40px; margin:0 0 20px;">Welcome to AiahDvocacy</h1>
  <h3 style="margin:0 0 20px; font-weight:700; color:#333;">Log In</h3>

  <div style="max-width:480px; margin:20px auto 0; text-align:left;">
    <form method="post" action="" style="background:#fff; padding:25px 30px; border-radius:4px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">

      <label style="display:block; font-weight:600; margin-bottom:6px;">Username</label>
      <input name="username" type="text" class="form-control" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:3px; margin-bottom:12px;" required>

      <label style="display:block; font-weight:600; margin-bottom:6px;">Password</label>
      <input name="password" type="password" class="form-control" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:3px; margin-bottom:18px;" required>

      <?php if ($message): ?>
        <div style="color:#c00; text-align:center; margin-bottom:12px;"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <button type="submit" style="width:100%; background:#d81b2a; color:#fff; padding:12px; border:none; border-radius:4px; font-weight:700; box-shadow: inset 0 -2px rgba(0,0,0,0.1);">LOGIN</button>

      <div style="text-align:center; margin-top:12px; font-size:14px; color:#666;">
        Don't have an account? <a href="register.php" style="color:#d81b2a; font-weight:700;">Register here</a>
      </div>
    </form>
  </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
