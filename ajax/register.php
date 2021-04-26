<?php
session_start();
ini_set('display_errors', 1);
include('./lib/password.php');
require_once('./library.php'); //to connect to the database
$con = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);

//check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    echo "<script>alert('Please complete the registration form!')</script>";
    include('register.html');
}
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    echo "<script>alert('Please complete the registration form')</script>";
    include('register.html');
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Email is not valid!')</script>";
    include('register.html');
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    echo "<script>alert('Username is not valid!')</script>";
    include('register.html');
}

if ($stmt = $con->prepare('SELECT id, password FROM Users WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		echo "<script>alert('Username exists, please choose another!')</script>";
        include('register.html');
	} else {
        if ($stmt = $con->prepare('INSERT INTO Users (username, password, email) VALUES (?, ?, ?)')) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
            $stmt->execute();
            header('Location: success.html');
        }
	}
	$stmt->close();
}
$con->close();
?>