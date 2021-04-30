<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
ini_set('display_errors', 1);
require_once('./library.php'); //to connect to the database
$con = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);

//check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] != 'hs2fw_a') {
    echo "<script>alert('Unauthorized!')</script>";
	exit;
}

$sqlFeedback = "SELECT * FROM `Feedbacks`";
$feedbackResult = mysqli_query($con, $sqlFeedback);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="styles/css/feedback.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php
		include("base.php");
		?>
		<div class="content">
			<h2>Admin Page, manage feedbacks here.</h2>
			<?php
				while($row = mysqli_fetch_array($feedbackResult)){  
					echo "<div><table>";
					echo "<tr><td>" . "Username:" . "</td><td>" . $row['username'] . "</td></tr>"; 
					echo "<tr><td>" . "Title:" . "</td><td>" . $row['title'] . "</td></tr>"; 
					echo "<tr><td>" . "Comment:" . "</td><td>" . $row['content'] . "</td></tr>"; 
					echo "</table></div>";
				}
			?>
		</div>
	</body>
</html>