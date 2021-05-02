<?php

session_start();

require_once('./config.php');

// query for pixel colors
$pixelColor = "SELECT pixel_id, color, hexcode FROM `Pixel_Colors` pc LEFT OUTER JOIN `Colors` c ON c.color_name = pc.color ORDER BY pixel_id ASC";
$pixelColorResult = mysqli_query($con, $pixelColor);

// https://stackoverflow.com/questions/383631/json-encode-mysql-results
$jsrows = array();
while($r = mysqli_fetch_assoc($pixelColorResult)){
    $jsrows[] = $r;
}
$jsPixelColors = json_encode($jsrows); //jsPixelColors is now a JSON encoded object, to be set to a js var later

echo $jsPixelColors;

// close connection
mysqli_close($con);

?>