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

if (!isset($_POST['title']) ) {
    echo "<script>alert('Missing title!')</script>";
    include('feedbackform.php');
}

if (!isset($_POST['content']) ) {
    echo "<script>alert('Missing content!')</script>";
    include('feedbackform.html');
}

if ($stmt = $con->prepare('INSERT INTO Feedbacks (username, title, content) VALUES (?, ?, ?)')) {
    $username = $_SESSION['name'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt->bind_param('sss', $username, $title, $content);
    $stmt->execute();
    header('Location: feedbackform.php');
    echo "<script>alert('Feedback Submitted!')</script>";
    $stmt->close();
}else{
    echo "<script>alert('failed insert')</script>";
}
$con->close();
?>
