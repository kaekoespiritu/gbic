<?php
// Saving local database configuration
date_default_timezone_set('Asia/Hong_Kong');
session_start();

if(!isset($_SESSION['user_logged_in']))
{
	Print "<script>window.location.assign('login.php')</script>";
}

// Lockdown protocol
	$lockdownDate = 'November 1, 2018';
	$checkDateToday = strftime("%B %d, %Y");

	$unixLockdown = strtotime($lockdownDate);
	$unixDateToday = strtotime($checkDateToday);

	if($unixLockdown <= $unixDateToday)
	{
		Print "<script>
			alert('Freetrial has ended. License is needed to proceed, Please contact the developers.');
			window.location.assign('error404.php')</script>";
	}

function restrictions($page) 
{
	########################################
	################ LEGEND ################
	########################################
	#									   #
	#	1 - List of employees			   #
	#	2 - list of loan applications	   #
	#	3 - list of absence notification   #
	#	4 - list of site management  	   #	
	#	5 - attendance access 			   #
	#	6 - payroll access 				   #
	#	7 - earnings report 			   #
	#	8 - contributions report 		   #
	#	9 - loans report 				   #
	#	10 - attendance report 			   #
	#	11 - company expenses report 	   #
	#	12 - site management	           #
	#	13 - position management	       #
	#									   #
	########################################
	
	$pageNumber = $page; // page number restriction
	$admin = $_SESSION['user_logged_in'];//gets the logged in admin
	$restrictCheck = "SELECT restrictions FROM administrator WHERE username = '$admin'";
	$restrictQuery = mysql_query($restrictCheck);

	$adminRestriction = mysql_fetch_assoc($restrictQuery);

	$restrictions = explode("-" ,$adminRestriction['restrictions']);

	$restrictBool = true;//if boolean is false then employee is restricted from the module

	$resCount = count($restrictions);
	for($counter = 0; $counter < $resCount; $counter++)
	{
		if($restrictions[$counter] == $pageNumber)
		{
			$restrictBool = false;
		}
	}
	if($restrictBool)//which mean the pageNumber is not in the restrictions
	{
		Print "
				<script>
					alert('You dont have access to this module. Please contact the Administrator for inqueries.');
					window.location.assign('index.php');
				</script>";

	}
}
?>