<?php
session_start();
include 'dbconn.php';
?>

<!doctype html>
<html lang="en">
<?php include '../inc/head.php'; ?>
<body>

<section style="padding:40px 0; text-align:center;">
  <h1 style="color:#d81b2a; font-size:40px; margin-bottom:10px;">Join AiahDvocacy</h1>
  <h3 style="margin-bottom:20px; font-weight:700; color:#333;">Select Your Role</h3>

  <div style="max-width:480px; margin:auto;">
    <form method="get" action="register_redirect.php" style="background:#fff; padding:25px 30px; border-radius:4px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
      <label style="display:block; font-weight:600; margin-bottom:8px;">Register as:</label>
      <select name="role" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:3px; margin-bottom:18px;">
        <option value="">Select Role</option>
        <option value="Beneficiary">Beneficiary</option>
        <option value="Volunteer">Volunteer</option>
        <option value="Sponsor">Sponsor</option>
        <option value="Admin">Admin</option>
      </select>

      <button type="submit" style="width:100%; background:#d81b2a; color:#fff; padding:12px; border:none; border-radius:4px; font-weight:700;">
        CONTINUE
      </button>

      <div style="text-align:center; margin-top:12px; font-size:14px; color:#666;">
        Already have an account? <a href="login.php" style="color:#d81b2a; font-weight:700;">Log in here</a>
      </div>
    </form>
  </div>
</section>

<?php include '../inc/footer.php'; ?>
</body>
</html>
