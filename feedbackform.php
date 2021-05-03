<?php
session_start();
require_once('./config.php');

$update = false;

if (isset($_GET['edit'])) {
	$feedback_id = $_GET['edit'];
	if($_SESSION['userrole'] == "hs2fw_a"){
		$update = true;
		$record = mysqli_query($con, "SELECT * FROM Feedbacks WHERE feedback_id='{$feedback_id}'");
		$n = mysqli_fetch_array($record);
		if (!empty($n)) {
			$title = $n['title'];
			$content = $n['content'];
		}
	}
	else if($_SESSION['userrole'] == "hs2fw_b"){
		$update = true;
		$record = mysqli_query($con, "SELECT * FROM Feedbacks WHERE feedback_id='{$feedback_id}' AND user_id='{$_SESSION['id']}'");
		$n = mysqli_fetch_array($record);
		if (!empty($n)) {
			$title = $n['title'];
			$content = $n['content'];
		}
		else{
			$update = false;
		}
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
					<input type="text" name="title" placeholder="Subject" id="title" required>
					<textarea type="text" name="content" placeholder="Comment" id="content" required></textarea>
					<input type="submit" value="Submit" id="submitForm" name="submitForm">
				<?php endif ?>
			</form>
		</div>
	</body>
</html>