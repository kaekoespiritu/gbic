<?php
include('directives/session.php');
include('directives/db.php');

$empNum = count($_POST['empid']);
$siteFrom = $_GET['s'];
// Print "<script>console.log('".$empNum."')</script>";
//for admin history
$user = $_SESSION['user_logged_in'];
$admin = "SELECT * FROM administrator WHERE username = '$user'";
$adminQuery = mysql_query($admin);
$adminArr = mysql_fetch_assoc($adminQuery);
$adminName = $adminArr['firstname']." ".$adminArr['lastname'];

$date = strftime("%B %d, %Y");//Get the current date


if(isset($_POST['groupChange']))//group site movement
{
	$site = $_POST['groupChange'];
	$empNum = count($_POST['chkbox_chosen']);
	for($counter = 0; $counter < $empNum; $counter++)
	{
		$empid = $_POST['chkbox_chosen'][$counter];
		mysql_query("UPDATE employee SET site = '$site' WHERE empid = '$empid'");
		mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES(	'$empid', '$site', '$date', '$adminName')");
	}
	Print "<script>alert('Successfully transfered employees.')</script>";
	
}
else
{
	$empChange = "";// store employees that has been changed
	$siteNum = "";//store index of new site to trasfer
	$moreThanTwo = false;
	for($counter = 0; $counter < $empNum; $counter++)
	{
		if($_POST['newSite'][$counter] != '')
		{
			if($empChange != "")
			{
				$empChange .= ",";
				$siteNum .= ",";
				$moreThanTwo = true;
			}
			$empChange .= $_POST['empid'][$counter];
			$siteNum .= $_POST['newSite'][$counter];
		}
	}
	if($moreThanTwo)
	{
		$empSite = explode(",", $empChange);
		$newSite = explode(",", $siteNum);
		$changeNum = count($empSite);
	 	for($count = 0; $count < $changeNum; $count++)
		{
			$empid = $empSite[$count];
			$site = $newSite[$count];
			$siteTransfer = $siteFrom." -> ".$newSite[$count];
			mysql_query("UPDATE employee SET site = '$site' WHERE empid = '$empid'");
			mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES('$empid', '$siteTransfer', '$date', '$adminName')");

		}
		Print "<script>alert('Successfully transfered employees.')</script>";

	}
	else
	{
		$site = $siteNum;
		$siteNum = $siteFrom." -> ".$siteNum;
		mysql_query("UPDATE employee SET site = '$site' WHERE empid = '$empChange'");
		mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES('$empChange', '$siteNum', '$date', '$adminName')");
		Print "<script>alert('Successfully transfered employees.')</script>";
	}
}
Print "<script>window.location.assign('site_movement.php?site=".$siteFrom."')</script>";
?>

















