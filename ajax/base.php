<?php
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="styles/css/feedback.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<nav class="navtop">
        <div>
            <h1><a href="home.php"> <img src="static/images/logo.png" style="padding-left=-10" height="200" width="400" alt="PFH Logo"></a> </h1>
            <?php
                // If the user is not logged in redirect to the login page...
                if (isset($_SESSION['userrole']) && $_SESSION['userrole'] == 'hs2fw_a') {
                    echo '<a href="admin.php"><i></i>Admin</a>';
                }elseif(isset($_SESSION['userrole']) && $_SESSION['userrole'] == 'hs2fw_b'){
                    echo '<a href="feedbackform.php"><i></i>Feedback</a>';
                }
            ?>
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
</html>
