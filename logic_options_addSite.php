<?php
	include_once('directives/db.php');
	include('directives/session.php');

	$siteName = mysql_real_escape_string($_POST['site_name']);
	
	$siteName = ucwords($siteName);
	$start = strftime("%B %d, %Y");
	
	$siteChecker = "SELECT * FROM site WHERE location = '$siteName'";
	$checkerQuery = mysql_query($siteChecker);

	if(mysql_num_rows($checkerQuery) == 0)//Check if site name is already in the database
	{
		$site = "INSERT INTO site(location, active, start) VALUES('$siteName','1', '$start')";
		$siteQuery = mysql_query($site);
		Print "<script>alert('Successfully added ".$siteName." from active Sites')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}
	else
	{
		Print "<script>alert('Site name already exist.')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}

	
?>