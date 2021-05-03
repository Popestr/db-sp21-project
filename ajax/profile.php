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

$stmt_purch = mysqli_prepare($con, "SELECT purchase_date, pp.pixel_id, color, charity_name FROM `Pixel_Purchases` pp
LEFT OUTER JOIN `Pixel_Colors` pc on pc.pixel_id = pp.pixel_id 
LEFT OUTER JOIN `Colors` c on c.color_name = pc.color
LEFT OUTER JOIN `Purchases` p on p.purchase_id = pp.purchase_id
LEFT OUTER JOIN `Pixel_Charities` pchars on pchars.pixel_id = pp.pixel_id
LEFT OUTER JOIN `Charities` chars on chars.charity_id = pchars.charity_id
WHERE p.purchaser_id=? ORDER BY purchase_date DESC");

$stmt_charities = mysqli_prepare($con, "SELECT purchase_date, pp.pixel_id, color, charity_name FROM `Pixel_Purchases` pp
LEFT OUTER JOIN `Pixel_Colors` pc on pc.pixel_id = pp.pixel_id 
LEFT OUTER JOIN `Colors` c on c.color_name = pc.color
LEFT OUTER JOIN `Purchases` p on p.purchase_id = pp.purchase_id
LEFT OUTER JOIN `Pixel_Charities` pchars on pchars.pixel_id = pp.pixel_id
LEFT OUTER JOIN `Charities` chars on chars.charity_id = pchars.charity_id
WHERE p.purchaser_id=? ORDER BY purchase_date DESC");

mysqli_stmt_bind_param($stmt_purch, "i", $_SESSION["id"]);
mysqli_stmt_execute($stmt_purch);
mysqli_stmt_bind_result($stmt_purch, $pdate, $pixid, $color, $charname);

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
			<table id="user-purchases">
				<tr><th>Date</th><th>Pixel ID</th><th>Pixel Color</th><th>Charity</th></tr>
				<?php 
					while (mysqli_stmt_fetch($stmt_purch)) {
						echo "<tr><td>".$pdate."</td><td>".$pixid."</td><td>".$color."</td><td>".$charname."</td></tr>";
					}
				?>
			</table>
			<h2>Charities<a href="charityrequest.php"><button id="charity-request-button">Request a Charity</button></a></h2>
			<div id="charity-manage-header"> It looks like you're not managing any charities yet.</div>
			<table id="user-purchases">
				<!-- <tr><th>Date</th><th>Pixel ID</th><th>Pixel Color</th><th>Charity</th></tr> -->
				<!-- <?php 
					while (mysqli_stmt_fetch($stmt_purch)) {
						echo "<tr><td>".$pdate."</td><td>".$pixid."</td><td>".$color."</td><td>".$charname."</td></tr>";
					}
				?> -->
			</table>
		</div>
	</body>
</html>

<?php

mysqli_stmt_close($stmt_purch);

mysqli_close($con);

?>