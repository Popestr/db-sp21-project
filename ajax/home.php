<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

require_once('./config.php');
	
// query for colors
$sqlColor = "SELECT * FROM `Colors`";

// make the query and get the result
$colorResult = mysqli_query($con, $sqlColor);

// query for Charities
$sqlCharity = "SELECT * FROM `Charities`";

// make the query and get the result
$charityResult = mysqli_query($con, $sqlCharity);

// close connection
mysqli_close($con);

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Pixels for Humanity</title>
		<link href="styles/css/login.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <script src="js/jquery-1.6.2.min.js"></script>
	</head>
	<body class="loggedin">
		<?php
        include("base.php")
        ?>
        <div id="grid-container">
		<div class="content" id="main-content">
            
            <div class="container" <?php if($_SESSION["userrole"] == "hs2fw_a"){echo "hidden";}?>>
            <h2>Select a color and a charity of your choice</h2><br>
                <?php
                    echo "<span id='colorsel'><label id='color-label'>Color:</label><span id='color-preview'></span><select name='color-list' id='color-list'>";
                    while ($row = mysqli_fetch_array($colorResult)) {
                        echo "<option id='color-input' value='" . $row['hexcode'] . "'>" . $row['color_name'] . "</option>";
                    }
                    echo "</select></span>";
                    echo "<span id='charity'><label id='charity-label'>Charity:</label><select name='charity-list' id='charity-list'>";
                    while ($row = mysqli_fetch_array($charityResult)) {
                        echo "<option id='charity-input' value='" . $row['charity_id'] . "'>" . $row['charity_name'] . "</option>";
                    }
                    echo "</select></span>";
                    /* debugging code, shows that DB query works correctly. Now pass it to js file
                    while ($row = mysqli_fetch_array($pixelColorResult)) {
                        echo $row['pixel_id'];
                        echo $row['color'];
                    }*/
                ?>
            </div>
            <div id="pixel-info">
                    Hover over a pixel to view its info!
            </div>

            <canvas id="pixelCanvas" width="1000" height="1000"></canvas>
            <!-- https://stackoverflow.com/questions/2928827/access-php-var-from-external-javascript-file -->
            <!-- <script type="text/javascript">
                var pixel_colors = <?php echo $jsPixelColors; ?>; //pass to app.js
            </script> -->
            <script src="app.js"></script>
            <button id="clear-button" onClick="window.location.reload();" style="float: right; width: 1000px; height: 50px" <?php if($_SESSION["userrole"] == "hs2fw_a"){echo "hidden";}?>>Clear Selections</button>
		</div>
        <?php if(isset($_SESSION['userrole']) && $_SESSION['userrole'] == 'hs2fw_b'){ ?>
            <div id="purchase-info">
                <div id="purchase-header">Purchase Info</div>
                <hr />
                <div id="purchase-contents">
                </div>
                <hr />
                <div id="purchase-bottom">
                    <!-- <form action="https://www.sandbox.paypal.com/donate" method="post" target="_top">
                        <input type="hidden" name="hosted_button_id" value="HYSZKYCADBDKW" />
                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                        <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                    </form> -->
                    <button id="purchase-button" onclick="collectAndPurchase()">Complete Purchase</button>
                    <div id="purchase-total"><strong>Order Total</strong><br/>$<span id="total-num">0</span>.00</div>
                </div>
            </div>
        <?php } ?>
    </div>
	</body>
</html>
