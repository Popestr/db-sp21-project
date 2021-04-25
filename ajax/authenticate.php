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

if ( !isset($_POST['username'], $_POST['password']) ) {
    echo "<script>alert('Please fill both the username and password fields!')</script>";
    include('index.html');
}

if ($stmt = $con->prepare('SELECT id, password FROM Users WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        if ($_POST['password'] === $password) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: home.php');
        } else {
            echo "<script>alert('Incorrect username and/or password!')</script>";
            include('index.html');
        }
    } else {
        echo "<script>alert('Incorrect username and/or password!')</script>";
        include('index.html');
    }
	$stmt->close();
}
?>