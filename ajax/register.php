<?php
session_start();
ini_set('display_errors', 1);
require_once('./library.php'); //to connect to the database
$con = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);

//check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	exit('Please complete the registration form!');
}
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	exit('Please complete the registration form');
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

if ($stmt = $con->prepare('SELECT id, password FROM Users WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		echo 'Username exists, please choose another!';
	} else {
        if ($stmt = $con->prepare('INSERT INTO Users (username, password, email) VALUES (?, ?, ?)')) {
            $password = $_POST['password'];
            $stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
            $stmt->execute();
            header('Location: success.html');
        }
	}
	$stmt->close();
}
$con->close();
?>