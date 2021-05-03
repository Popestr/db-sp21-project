<?php

session_start();

require_once('./config.php');

// query for pixel colors
$pixelColor = "CALL RetrievePixelInfo()";
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