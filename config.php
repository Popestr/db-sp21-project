<?php
	include('./lib/password.php');
	require_once('./library.php'); //to connect to the database
	if(!isset($_SESSION['userrole'])){
		$con = new mysqli($SERVER, $loginUSERNAME, $loginPASSWORD, $DATABASE);
		//echo "here1";
	}else if($_SESSION['userrole'] == 'hs2fw_a'){
		$con = new mysqli($SERVER, $adminUSERNAME, $adminPASSWORD, $DATABASE);
		//echo "here2";
	}else if($_SESSION['userrole'] == 'hs2fw_b'){
		$con = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);
		//echo "here3";
	}
	//check connection
	if (mysqli_connect_errno())
	{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?>