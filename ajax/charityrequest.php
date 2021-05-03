<?php
session_start();
require_once('./config.php');

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Charity Request - Pixels for Humanity</title>
		<link href="styles/css/feedback.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php
        include("base.php")
        ?>
		<div class="register">
			<h1>Charity Request Form</h1>
			<form action="charityRequestSubmit.php" method="post" autocomplete="off" onSubmit="alert('Successful!')">
					<input type="text" name="charity_name" placeholder="Charity Name" id="title" required>
					<textarea type="text" name="content" placeholder="Describe your charity..." id="content" required></textarea>
					<input type="submit" value="Submit" id="submitForm" name="submitForm">
			</form>
		</div>
	</body>
</html>