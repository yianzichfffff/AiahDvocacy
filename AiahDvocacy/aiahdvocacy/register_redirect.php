<?php
if (isset($_GET['role'])) {
    $role = $_GET['role'];
    switch ($role) {
        case 'Beneficiary': header('Location: register_beneficiary.php'); break;
        case 'Volunteer': header('Location: register_volunteer.php'); break;
        case 'Sponsor': header('Location: register_sponsor.php'); break;
        case 'Admin': header('Location: register_admin.php'); break;
        default: header('Location: register.php'); break;
    }
    exit;
} else {
    header('Location: register.php');
    exit;
}
?>
