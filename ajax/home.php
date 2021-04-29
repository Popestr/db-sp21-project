<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

require_once('./config.php');
	
// query for colors
$sqlColor = "SELECT * FROM `Colors`";

// make the query and get the result
$colorResult = mysqli_query($con, $sqlColor);

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
	</head>
	<body class="loggedin">
		<?php
        include("base.php")
        ?>
		<div class="content">
            <h2>Pick a color or a charity of your choice</h2>
            <div class="container">
                <?php
                    echo "<select name='color-list' id='color-list'>";
                    while ($row = mysqli_fetch_array($colorResult)) {
                        echo "<option id='color-input' value='" . $row['hexcode'] . "'>" . $row['color_name'] . "</option>";
                    }
                    echo "</select>";
                ?>
            </div>

            <canvas id="pixelCanvas" width="1000" height="1000"></canvas>
            <form action="https://www.sandbox.paypal.com/donate" method="post" target="_top">
            <input type="hidden" name="hosted_button_id" value="HYSZKYCADBDKW" />
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
            <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
            </form>
            <script src="app.js"></script>
		</div>
	</body>
</html>
