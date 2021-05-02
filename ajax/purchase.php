<?php

session_start();

require_once('./config.php');

$ptp = json_decode($_POST["ptp"]); // pixels to purchase
$purchaser = $_SESSION['id'];
$amount = $_POST["amt"];

$aiq = "SELECT purchase_id FROM `Purchases` ORDER BY purchase_id DESC LIMIT 1";

$stmt_color = mysqli_prepare($con, "UPDATE `Pixel_Colors` SET color=? WHERE pixel_id=?");
$stmt_purch = mysqli_prepare($con, "INSERT INTO `Purchases` (amount, purchaser_id) VALUES (?, ?)");
$stmt_ppx = mysqli_prepare($con, "INSERT INTO `Pixel_Purchases` (purchase_id, pixel_id) VALUES (?, ?)");
$stmt_pchar = mysqli_prepare($con, "INSERT INTO `Pixel_Charities` (pixel_id, charity_id) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt_color, "si", $color, $id);
mysqli_stmt_bind_param($stmt_purch, "ii", $amount, $purchaser);


mysqli_stmt_execute($stmt_purch);
$autoinc = mysqli_fetch_array(mysqli_query($con, $aiq));

mysqli_stmt_bind_param($stmt_ppx, "ii", $autoinc[0], $id);
mysqli_stmt_bind_param($stmt_pchar, "ii", $id, $chr);

foreach($ptp as $pix){
    $color = $pix->color;
    $id = $pix->id;
    $chr = $pix->charity;

    mysqli_stmt_execute($stmt_color);
    mysqli_stmt_execute($stmt_ppx);
    mysqli_stmt_execute($stmt_pchar);
}

// close statements & connection
mysqli_stmt_close($stmt_color);
mysqli_stmt_close($stmt_ppx);
mysqli_stmt_close($stmt_purch);
mysqli_stmt_close($stmt_pchar);
mysqli_close($con);

?>