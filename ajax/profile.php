<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
ini_set('display_errors', 1);
require_once('./config.php');
$stmt = $con->prepare('SELECT password, email FROM Users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();

$stmt_purch = mysqli_prepare($con, "SELECT purchase_date, pp.pixel_id, color, charity_name, chars.charity_id FROM `Pixel_Purchases` pp
LEFT OUTER JOIN `Pixel_Colors` pc on pc.pixel_id = pp.pixel_id 
LEFT OUTER JOIN `Colors` c on c.color_name = pc.color
LEFT OUTER JOIN `Purchases` p on p.purchase_id = pp.purchase_id
LEFT OUTER JOIN `Pixel_Charities` pchars on pchars.pixel_id = pp.pixel_id
LEFT OUTER JOIN `Charities` chars on chars.charity_id = pchars.charity_id
WHERE p.purchaser_id=? ORDER BY purchase_date DESC");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {

	$stmt_charities = mysqli_prepare($con, "SELECT charity_name, subtotal FROM `Charity_Admins` ca LEFT OUTER JOIN `Charities` c ON c.charity_id = ca.charity_id WHERE admin_id = ?");
    
} catch (mysqli_sql_exception $exception) {
    throw $exception;
}

mysqli_stmt_bind_param($stmt_purch, "i", $_SESSION["id"]);
mysqli_stmt_bind_param($stmt_charities, "i", $_SESSION["id"]);
mysqli_stmt_execute($stmt_purch);
mysqli_stmt_bind_result($stmt_purch, $pdate, $pixid, $color, $charname, $cid);
mysqli_stmt_store_result($stmt_purch);

$sqlFeedback = "SELECT f.*, u.username FROM `Feedbacks` f LEFT OUTER JOIN `Users` u ON u.id = f.user_id WHERE user_id='{$_SESSION['id']}'";
$feedbackResult = mysqli_query($con, $sqlFeedback);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="styles/css/login.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php
		include("base.php");
		?>
		<div class="content">
			<h2>Profile Page</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
				</table>
			</div>
			<h2>Purchases</h2>
			<?php if(mysqli_stmt_num_rows($stmt_purch) == 0): ?>
				<div id="purchases-header"> It looks like you don't have any purchases yet.</div>
			<?php else: ?>
			<table id="user-purchases">
				<tr><th>Date</th><th>Pixel ID</th><th>Pixel Color</th><th>Charity</th></tr>
				<?php 
					while (mysqli_stmt_fetch($stmt_purch)) {
						echo "<tr><td>".$pdate."</td><td>".$pixid."</td><td>".$color."</td><td>".$charname."</td></tr>";
					}
					mysqli_stmt_close($stmt_purch);
					mysqli_stmt_execute($stmt_charities);
					mysqli_stmt_bind_result($stmt_charities, $cname, $subtotal);
					mysqli_stmt_store_result($stmt_charities);
				?>
			</table>
			<?php endif ?>
			<h2>Charities You Manage<a href="charityrequest.php"><button id="charity-request-button">Request a Charity</button></a></h2>
			<?php if(mysqli_stmt_num_rows($stmt_charities) == 0): ?>
				<div id="charity-manage-header"> It looks like you're not managing any charities yet.</div>
			<?php else: ?>
			<table id="user-purchases">
				<tr><th>Charity Name</th><th>Total Pixels Donated</th><th>Export</th></tr>
				<?php 
					while (mysqli_stmt_fetch($stmt_charities)) {

						echo "<tr><td>".$cname."</td><td>".$subtotal."</td><td><a href='exportDonatorInfo.php?cid=".$cid."'>Export Donator Info</a></td></tr>";
					}
				?>
			</table>
			<?php endif ?>
			<h2>Manage feedbacks here.</h2>
			<?php if(mysqli_num_rows($feedbackResult) == 0): ?>
				<div id="charity-manage-header"> It looks like no feedbacks were made by you.</div>
			<?php else: ?>
				<?php 
					while($row = mysqli_fetch_array($feedbackResult)){  
						echo "<div><table>";
						echo "<tr><td>" . "Username:" . "</td><td>" . $row['username'] . "</td></tr>"; 
						echo "<tr><td>" . "Title:" . "</td><td>" . $row['title'] . "</td></tr>"; 
						echo "<tr><td>" . "Comment:" . "</td><td>" . $row['content'] . "</td></tr>"; 
						echo "<tr><td>" . "<a href='feedbackform.php?edit=" . $row['feedback_id'] . "' class='edit_btn'> Edit </a>" . "</td>"; 
						echo "<td>" . "<a href='feedback.php?del=" . $row['feedback_id'] . "' class='del_btn'> Delete </a>" . "</td></tr>"; 
						echo "</table></div>";
					}
				?>
			<?php endif ?>
		</div>
	</body>
</html>

<?php
mysqli_stmt_close($stmt_charities);

mysqli_close($con);

?>