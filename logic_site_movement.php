<?php
include('directives/session.php');
include('directives/db.php');

$empNum = count($_POST['empid']);
$s = $_GET['s'];
// Print "<script>console.log('".$empNum."')</script>";


if(isset($_POST['groupChange']))//group site movement
{
	$site = $_POST['groupChange'];
	$empNum = count($_POST['chkbox_chosen']);
	for($counter = 0; $counter < $empNum; $counter++)
	{
		$empid = $_POST['chkbox_chosen'][$counter];
		mysql_query("UPDATE employee SET site = '$site' WHERE empid = '$empid'");
	}
	Print "<script>alert('Successfully transfered employees.')</script>";
	
}
else
{
	$empChange = "";// store employees that has been changed
	$positionNum = "";//store index of new site to trasfer
	$moreThanTwo = false;
	for($counter = 0; $counter < $empNum; $counter++)
	{
		if($_POST['newSite'][$counter] != '')
		{
			if($empChange != "")
			{
				$empChange .= ",";
				$positionNum .= ",";
				$moreThanTwo = true;
			}
			$empChange .= $_POST['empid'][$counter];
			$positionNum .= $_POST['newSite'][$counter];
		}
	}
	if($moreThanTwo)
	{
		$empSite = explode(",", $empChange);
		$newSite = explode(",", $positionNum);
		$changeNum = count($empSite);
	 	for($count = 0; $count < $changeNum; $count++)
		{
			$empid = $empSite[$count];
			$site = $newSite[$count];
			mysql_query("UPDATE employee SET site = '$site' WHERE empid = '$empid'");
		}
		Print "<script>alert('Successfully transfered employees.')</script>";

	}
	else
	{
		mysql_query("UPDATE employee SET site = '$positionNum' WHERE empid = '$empChange'");
		Print "<script>alert('Successfully transfered employees.')</script>";
	}
}
Print "<script>window.location.assign('site_movement.php?site=".$s."')</script>";
?>

















