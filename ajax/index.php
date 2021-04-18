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
	$colors = mysqli_fetch_array($colorResult);
	$charities = mysqli_fetch_array($charitiesResult);
	$pixels = mysqli_fetch_array($pixelsResult);
	$users = mysqli_fetch_array($usersResult);

	// close connection
	mysqli_close($con);

	// example of it working
	while($row = mysqli_fetch_array($colorResult)) {
		echo $row['color_name'];
		echo " " . $row['hexcode'];
		echo "<br>";
	}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pixels For Humanity</title>
    <link rel="stylesheet" href="app.css">
</head>
<body>
    <h1>Pixels For Humanity</h1>
	
    <h2>Pick A Color</h2>

    <h2>Design Canvas</h2>
    <canvas id="pixelCanvas" width="1020" height="1020"></canvas>

    <script src="art.js"></script>
</body>
</html>