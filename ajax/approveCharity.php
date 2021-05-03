<?php

session_start();

require_once('./config.php');

$aiq = "SELECT charity_id FROM `Charities` ORDER BY charity_id DESC LIMIT 1";

if (isset($_GET['approve'])) {

    if ($stmt = $con->prepare('SELECT * FROM `Charity_Requests` where request_id = ?')) {
        $feedback_id = $_GET['approve'];
        $stmt->bind_param('i', $feedback_id);
        $stmt->execute();
        $stmt->bind_result($user_id, $req_id, $charity_name, $content);
        $stmt->fetch();
        $stmt->close();
    }else{
        echo "<script>alert('failed select')</script>";
    }
    if ($stmt = $con->prepare('INSERT INTO `Charities` (charity_name) VALUES ( ? )')) {
        $stmt->bind_param('s', $charity_name);
        $stmt->execute();
        $stmt->close();
    }else{
        echo "<script>alert('failed charity insert')</script>";
    }

    $autoinc = mysqli_fetch_array(mysqli_query($con, $aiq));

    if ($stmt = $con->prepare('INSERT INTO `Charity_Admins` (charity_id, admin_id) VALUES (?, ?)')) {
        $stmt->bind_param('ii', $autoinc[0], $user_id);
        $stmt->execute();
        $stmt->close();
    }else{
        echo "<script>alert('failed admin insert')</script>";
    }

    if ($stmt = $con->prepare('DELETE FROM `Charity_Requests` WHERE request_id=?')) {
        $stmt->bind_param('i', $req_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Request approved!"; 
        header('Location: admin.php');
    }else{
        echo "<script>alert('failed delete')</script>";
    }
}

else if (isset($_GET['deny'])) {
    if ($stmt = $con->prepare('DELETE FROM `Charity_Requests` WHERE request_id=?')) {
        $request_id = $_GET['deny'];
        $stmt->bind_param('i', $request_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['message'] = "Request denied!"; 
        header('Location: admin.php');
    }else{
        echo "<script>alert('failed update')</script>";
    }
}


?>