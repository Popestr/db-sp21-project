<?php
session_start()

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Feedback - Pixels for Humanity</title>
		<link href="styles/css/feedback.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<?php
        include("base.php")
        ?>
		<div class="register">
			<h1>Feedback Form</h1>
			<form action="feedback.php" method="post" autocomplete="off" onSubmit="alert('Feedback has been submitted successfully.')">
				<input type="text" name="title" placeholder="Title" id="title" required>
				<textarea type="text" name="content" placeholder="Comment" id="content" required></textarea>
				<input type="submit" value="Submit" id="submitForm">
			</form>
		</div>
	</body>
</html>