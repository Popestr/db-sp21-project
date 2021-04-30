<?php
session_start();
ini_set('display_errors', 1);
require_once('./config.php');

$update = false;

if(isset($_POST['submitForm'])){
    if ($stmt = $con->prepare('INSERT INTO Feedbacks (username, title, content) VALUES (?, ?, ?)')) {
        $username = $_SESSION['name'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $stmt->bind_param('sss', $username, $title, $content);
        $stmt->execute();
        header('Location: feedbackform.php');
        $_SESSION['message'] = "Feedback Submitted!"; 
        $stmt->close();
    }else{
        echo "<script>alert('failed insert')</script>";
    }
}

if(isset($_POST['updateForm'])){
    if ($stmt = $con->prepare('UPDATE Feedbacks SET title=?, content=? WHERE id=?')) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $stmt->bind_param('ssi', $title, $content, $id);
        $stmt->execute();
        header('Location: manageFeedbacks.php');
        $_SESSION['message'] = "Feedback modified!"; 
        $stmt->close();
    }else{
        echo "<script>alert('failed update')</script>";
    }
}

if (isset($_GET['del'])) {
    if ($stmt = $con->prepare('DELETE FROM Feedbacks WHERE id=?')) {
        $id = $_GET['del'];
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if($_SESSION['userrole'] == 'hs2fw_a'){
            header('Location: admin.php');
        }else{
            header('Location: manageFeedbacks.php');
        }
        $_SESSION['message'] = "Feedback deleted!"; 
        $stmt->close();
    }else{
        echo "<script>alert('failed update')</script>";
    }
}


$con->close();
?>
