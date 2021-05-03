<?php
session_start();
ini_set('display_errors', 1);
require_once('./config.php');

$stmtPurch = $con->stmt_init();
$purchSQL = "SELECT purchase_date, pp.pixel_id, color, charity_name, chars.charity_id FROM `Pixel_Purchases` pp
LEFT OUTER JOIN `Pixel_Colors` pc on pc.pixel_id = pp.pixel_id 
LEFT OUTER JOIN `Colors` c on c.color_name = pc.color
LEFT OUTER JOIN `Purchases` p on p.purchase_id = pp.purchase_id
LEFT OUTER JOIN `Pixel_Charities` pchars on pchars.pixel_id = pp.pixel_id
LEFT OUTER JOIN `Charities` chars on chars.charity_id = pchars.charity_id
WHERE charity_name like ? AND p.purchaser_id=? ORDER BY purchase_date DESC";

if($stmtPurch->prepare($purchSQL)){
    $searchString = '%' . $_GET['charity_name'] . '%';
    $stmtPurch->bind_param("si", $searchString, $_SESSION["id"]);
    $stmtPurch->execute();
    $stmtPurch->bind_result($pdate, $pixid, $color, $charname, $cid);
    echo '<table id="user-purchases">';
    echo '<tr><th>Date</th><th>Pixel ID</th><th>Pixel Color</th><th>Charity</th></tr>';
    while ($stmtPurch->fetch()) {
        echo "<tr><td>".$pdate."</td><td>".$pixid."</td><td>".$color."</td><td>".$charname."</td></tr>";
    }
    echo "</table>";
    $stmtPurch->close();
}
?>