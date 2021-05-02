<?php

session_start();

require_once('./config.php');

// query for pixel colors
$pixelColor = "SELECT pc.pixel_id, pc.color, c.hexcode, chars.charity_name, username, purchase_date FROM `Pixel_Colors` pc 
LEFT OUTER JOIN `Colors` c ON c.color_name = pc.color 
LEFT OUTER JOIN `Pixel_Purchases` pp on pp.pixel_id = pc.pixel_id 
LEFT OUTER JOIN `Purchases` purch on purch.purchase_id = pp.purchase_id 
LEFT OUTER JOIN `Pixel_Charities` pchar on pchar.pixel_id = pc.pixel_id 
LEFT OUTER JOIN `Charities` chars on chars.charity_id = pchar.charity_id 
LEFT OUTER JOIN `Users` u on u.id = purch.purchaser_id ORDER BY pixel_id ASC";
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