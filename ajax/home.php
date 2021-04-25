<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

require_once('./config.php');
	
// query for colors
$sqlColor = "SELECT * FROM `Colors`";
$sqlCharities = "SELECT * FROM `Charities`";
$sqlPixels = "SELECT * FROM `Pixels`";
$sqlUsers = "SELECT * FROM `Users`";

// make the query and get the result
$colorResult = mysqli_query($con, $sqlColor);
$charitiesResult = mysqli_query($con, $sqlCharities);
$pixelsResult = mysqli_query($con, $sqlPixels);
$usersResult = mysqli_query($con, $sqlUsers);

// fetch resulting colors row as an array
// $colors = mysqli_fetch_array($colorResult);  // this needs to be commented out bc it returns and deletes the first row when fetch is called.
$charities = mysqli_fetch_array($charitiesResult);
$pixels = mysqli_fetch_array($pixelsResult);
$users = mysqli_fetch_array($usersResult);

// close connection
mysqli_close($con);

// // example of it working
// while($row = mysqli_fetch_array($colorResult)) {
// 	echo $row['color_name'];
// 	echo " " . $row['hexcode'];
// 	echo "<br>";
// }

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
		<link href="login.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
                <h1><a href="home.php"> Pixels for Humanity </a></h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
            <h2>Pick a color or Charity of your choice</h2>
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
