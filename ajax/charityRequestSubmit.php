<?php
session_start();
ini_set('display_errors', 1);
require_once('./config.php');

$update = false;

if(isset($_POST['submitForm'])){
    if ($stmt = $con->prepare('INSERT INTO `Charity_Requests` (user_id, charity_name, content) VALUES (?, ?, ?)')) {
        $charity_name = $_POST['charity_name'];
        $content = $_POST['content'];
        $stmt->bind_param('iss', $_SESSION["id"], $title, $content);
        $stmt->execute();
        header('Location: profile.php');
        $_SESSION['message'] = "Feedback Submitted!"; 
        $stmt->close();
    }else{
        echo "<script>alert('failed insert')</script>";
        // header('Location: profile.php');
    }
}

$con->close();
?>
