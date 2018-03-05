<?php
// Saving local database configuration
date_default_timezone_set('Asia/Hong_Kong');
session_start();

if(!isset($_SESSION['user_logged_in']))
{
	header('location: login.php');
}

function restrictions($page) 
{
	$pageNumber = $page; // page number restriction
	$admin = $_SESSION['user_logged_in'];//gets the logged in admin
	$restrictCheck = "SELECT restrictions FROM adminsitrator WHERE username = '$admin'";
	$restrictQuery = mysql_query($restrictCheck);

	$adminRestriction = mysql_fetch_assoc($restrictQuery);

	$restrictions = explode(" " ,$adminRestriction['restrictions']);

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