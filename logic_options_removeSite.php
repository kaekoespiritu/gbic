<?php
	include_once('directives/db.php');
	include('directives/session.php');
	
	$date = strftime("%B %d, %Y");

	//Print "<script>alert('".$siteNum."')</script>";
	//Query for employees that is in the chosen site to remove
	$primaryQuery = "UPDATE site SET active = 'pending' WHERE ";
	$secondaryQuery = "(";

	$successArchive = "You have successfully archived";
	$success = 0;//Counter if there is a successful query 
	$withEmployee = "";//Display site with employees
	$empExist = 0;//if Employee still exist in the chosen site
	
	$site = $_GET['site'];
	$empChecker = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
	$checkerQuery = mysql_query($empChecker);
	if(mysql_num_rows($checkerQuery) == 0)
	{
		$success++;
		$removeSite = "UPDATE site SET active = '0', end = '$date' WHERE location = '$site'";
		mysql_query($removeSite);
		if($successArchive != "")
		{

		}
		$successArchive .= " [".$site."]"; 
	}
	else
	{
		if($secondaryQuery != "(")
		{
			$secondaryQuery .= " OR ";
		}
		$secondaryQuery .= "location = '$site'";
		$withEmployee .= " [".$site."]"; 
	}
	
	$secondaryQuery .= ")";

	if($secondaryQuery == "()")//No employees in the site
	{
		Print "<script>alert('".$successArchive."')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}
	else
	{
		if($success != 0)
		{
			Print "<script>alert('".$successArchive.". These sites still have employees ".$withEmployee."')</script>";
		}
		else
		{
			Print "<script>alert('The site you chosen still have employees assigned to it. ".$withEmployee."')</script>";
		}
		//Print "<script>alert('".$successArchive."')</script>";
		$sitePending = $primaryQuery.$secondaryQuery;
		mysql_query($sitePending);
		//Print '<script>alert("'.$primaryQuery.''.$secondaryQuery.'")</script>';
		Print "<script>window.location.assign('site_movement.php?site=pending')</script>";
	}

?>























