<?php

session_start();

require_once('./config.php');

$ptp = json_decode($_POST["ptp"]); // pixels to purchase
$purchaser = $_SESSION['id'];


$stmt = mysqli_prepare($con, "UPDATE `Pixel_Colors` SET color=? WHERE pixel_id=?");
mysqli_stmt_bind_param($stmt, "si", $color, $id);

foreach($ptp as $pix){
    $color = $pix->color;
    $id = $pix->id;
    // echo $color." ".$id."\n";

    mysqli_stmt_execute($stmt);
}

// close connection
mysqli_stmt_close($stmt);
mysqli_close($con);

?>