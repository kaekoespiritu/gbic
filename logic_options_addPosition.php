<?php
	include_once('directives/db.php');
	include('directives/session.php');

	$positionName = mysql_real_escape_string($_POST['position_name']);
	
	$driver = "0";
	if(isset($_POST['driver']))
		$driver = 1;

	$positionName = ucwords($positionName);

	
	$positionChecker = "SELECT * FROM job_position WHERE position = '$positionName'";
	$checkerQuery = mysql_query($positionChecker);
	Print "<script>console.log('driver: ".$driver."')</script>";
	if(mysql_num_rows($checkerQuery) == 0)//Check if site name is already in the database
	{
		$position = "INSERT INTO job_position(position, active, driver) VALUES('$positionName','1', '$driver')";
		$positionQuery = mysql_query($position);
		Print "<script>alert('Successfully added ".$positionName." from active Positions')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}
	else
	{
		Print "<script>alert('Position already exist.')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}

	
?>