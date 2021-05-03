<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
ini_set('display_errors', 1);
require_once('./config.php');

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'hs2fw_a') {
    echo "<script>alert('Unauthorized!')</script>";
	exit;
}

$sqlFeedback = "SELECT * FROM `Feedbacks` f LEFT OUTER JOIN `Users` u on u.id = f.user_id";
$feedbackResult = mysqli_query($con, $sqlFeedback);

$sqlCharityReq = "SELECT * FROM `Charity_Requests` cr LEFT OUTER JOIN `Users` u on u.id = cr.user_id";
$creqResult = mysqli_query($con, $sqlCharityReq);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Admin Page</title>
		<link href="styles/css/feedback.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php
		include("base.php");
		?>
		<div class="content">
			<h2>Manage Feedbacks</h2>
			<?php
				while($row = mysqli_fetch_array($feedbackResult)){  
					echo "<div><table>";
					echo "<tr><td>" . "Username:" . "</td><td>" . $row['username'] . "</td></tr>"; 
					echo "<tr><td>" . "Title:" . "</td><td>" . $row['title'] . "</td></tr>"; 
					echo "<tr><td>" . "Comment:" . "</td><td>" . $row['content'] . "</td></tr>"; 
					echo "<tr><td>" . "<a href='feedback.php?del=" . $row['feedback_id'] . "' class='del_btn'> Delete </a>" . "</td></tr>"; 
					echo "</table></div>";
				}
			?>
			<h2>Manage Charity Requests</h2>
			<?php
				while($row = mysqli_fetch_array($creqResult)){  
					echo "<div><table>";
					echo "<tr><td>" . "Username:" . "</td><td>" . $row['username'] . "</td></tr>"; 
					echo "<tr><td>" . "Charity Name:" . "</td><td>" . $row['charity_name'] . "</td></tr>"; 
					echo "<tr><td>" . "Description:" . "</td><td>" . $row['content'] . "</td></tr>"; 
					echo "<tr><td>" . "<a href='approveCharity.php?approve=" . $row['request_id'] . "' class='del_btn'> Approve Request </a>" . "</td></tr>";
					echo "<tr><td>" . "<a href='approveCharity.php?deny=" . $row['request_id'] . "' class='del_btn'> Deny Request </a>" . "</td></tr>"; 
					echo "</table></div>";
				}
			?>
		</div>
	</body>
</html>