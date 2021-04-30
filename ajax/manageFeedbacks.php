<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
ini_set('display_errors', 1);
require_once('./config.php'); //to connect to the database


if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'hs2fw_b') {
    echo "<script>alert('Unauthorized!')</script>";
	exit;
}

$sqlFeedback = "SELECT * FROM `Feedbacks` WHERE username='{$_SESSION['name']}'";
$feedbackResult = mysqli_query($con, $sqlFeedback);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Manage Feedbacks</title>
		<link href="styles/css/feedback.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php
		include("base.php");
		?>
		<div class="content">
			<h2>Manage feedbacks here.</h2>
			<?php
				while($row = mysqli_fetch_array($feedbackResult)){  
					echo "<div><table>";
					echo "<tr><td>" . "Username:" . "</td><td>" . $row['username'] . "</td></tr>"; 
					echo "<tr><td>" . "Title:" . "</td><td>" . $row['title'] . "</td></tr>"; 
					echo "<tr><td>" . "Comment:" . "</td><td>" . $row['content'] . "</td></tr>"; 
					echo "<tr><td>" . "<a href='feedbackform.php?edit=" . $row['id'] . "' class='edit_btn'> Edit </a>" . "</td>"; 
					echo "<td>" . "<a href='feedback.php?del=" . $row['id'] . "' class='del_btn'> Delete </a>" . "</td></tr>"; 
					echo "</table></div>";
				}
			?>
		</div>
	</body>
</html>