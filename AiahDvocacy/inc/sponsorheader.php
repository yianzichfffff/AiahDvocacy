        
        <?php
session_start();
$loggedIn = isset($_SESSION['User_ID']);
$username = $_SESSION['Username'] ?? '';
$role = $_SESSION['Role'] ?? '';
?>
<div class="topbar">
        <div class="container">
        <div class="row"> 
        <div class="bar-phone">
        <i class="fa fa-phone"></i> <span>Call Us :</span> <strong>+63-944-431-9923</strong>
        </div>
        <div class="bar-mail">
        <i class="fa fa-envelope"></i> <span>Mail Us :</span> <strong>aiahdvocacy@gmail.com</strong>
        </div>
        <div class="header-social">
        <a class="facebook" href="#" title="facebook" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i>  </a>
        <a class="twitter" href="#" title="twitter" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i>  </a>
        <a class="linkedin" href="#" title="linkedin" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i>  </a>
        <a class="google" href="#" title="google-plus" target="_blank" rel="nofollow"><i class="fa fa-google-plus"></i>  </a>
        <a class="youtube" href="#" title="youtube-play" target="_blank" rel="nofollow"><i class="fa fa-youtube-play"></i>  </a>
        </div>
        </div>
        </div>
        </div>
        <nav class="navbar navbar-default navbar-sticky bootsnav">
        <div class="container">
        <div class="row"> 
        <div class="attr-nav">
        </div>           
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
        <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand logo" href="sponsor.php"><img src="../uploads/logo.png" class="img-responsive" /></a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
        <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
        <li><a href="sponsor.php">Home</a></li>
        <li><a href="sponsorabout.php">About Us</a></li>
        <li><a href="sponsorproject.php">Projects</a></li>
        <li><a href="sponsorcontact.php">Contact Us</a></li>
                <?php if($loggedIn): ?>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Profile (<?= htmlspecialchars($username); ?>) <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="sponsorprofile.php">View Profile</a></li>
                    </ul>
                    </li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
        </ul>
        </div>
        </div>
        </div>
        </nav>