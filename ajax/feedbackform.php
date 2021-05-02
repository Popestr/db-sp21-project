<?php
session_start();
require_once('./config.php');

$update = false;

if (isset($_GET['edit'])) {
	$feedback_id = $_GET['edit'];
	$update = true;
	$record = mysqli_query($con, "SELECT * FROM Feedbacks WHERE feedback_id='{$feedback_id}'");
	if (count($record) == 1) {
		$n = mysqli_fetch_array($record);
		$title = $n['title'];
		$content = $n['content'];
	}
}
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
			<form action="feedback.php" method="post" autocomplete="off" onSubmit="alert('Successful!')">
				<?php if ($update == true): ?>
					<input type="hidden" name="id" value="<?php echo $feedback_id;?>">
					<input type="text" name="title" value="<?php echo $title?>" id="title" required>
					<textarea type="text" name="content" id="content" required><?php echo $content?></textarea>
					<input type="submit" value="Update" id="updateForm" name="updateForm">
				<?php else: ?>
					<input type="text" name="title" placeholder="Title" id="title" required>
					<textarea type="text" name="content" placeholder="Comment" id="content" required></textarea>
					<input type="submit" value="Submit" id="submitForm" name="submitForm">
				<?php endif ?>
			</form>
		</div>
		<div class="register">
			<a href="manageFeedbacks.php">Manage Past Feedback</a>
		</div>
	</body>
</html>