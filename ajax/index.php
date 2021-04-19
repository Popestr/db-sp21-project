<?php
	require_once('./library.php'); //to connect to the database
	$con = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);
	
	//check connection
	if (mysqli_connect_errno())
	{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pixels For Humanity</title>
    <link rel="stylesheet" href="app.css">
	<script src="js/jquery-1.6.2.min.js" type="text/javascript"></script> 
</head>
<body>
    <h1>Pixels For Humanity</h1>

    <h2>Pick A Color</h2>
	<div class="container">
		<?php
			echo "<select name='color-list' id='color-list'>";
			while ($row = mysqli_fetch_array($colorResult)) {
				echo "<option id='color-input' value='" . $row['hexcode'] . "'>" . $row['color_name'] . "</option>";
			}
			echo "</select>";
		?>
	</div>
	<br>

    <canvas id="pixelCanvas" width="1000" height="1000"></canvas>

    <script src="app.js"></script>
</body>
</html>