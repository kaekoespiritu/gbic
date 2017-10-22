<?php
	include('directives/db.php');
	include('directives/session.php');
	
	$positionNum = count($_POST['position']);
	if($positionNum == 0)
	{
		Print "<script>alert('Please check the checkbox to choose a position to Archive.')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}
	//Print "<script>alert('".$siteNum."')</script>";
	//Query for employees that is in the chosen position to remove
	$primaryQuery = "UPDATE job_position SET active = 'pending' WHERE ";
	$secondaryQuery = "(";

	$successArchive = "You have successfully archived";
	$success = 0;//Counter if there is a successful query 
	$withEmployee = "";//Display position with employees
	$empExist = 0;//if Employee still exist in the chosen position
	for($counter = 0; $counter < $positionNum; $counter++)
	{
		//Print "<script>alert('".$_POST['position'][$counter]."')</script>";
		$position = $_POST['position'][$counter];
		$empChecker = "SELECT * FROM employee WHERE employment_status = '1' AND position = '$position'";
		$checkerQuery = mysql_query($empChecker);
		if(mysql_num_rows($checkerQuery) == 0)
		{
			$success++;
			$removePosition = "UPDATE job_position SET active = '0' WHERE position = '$position' ";
			mysql_query($removePosition);
			if($successArchive != "")
			{

			}
			$successArchive .= " [".$position."]"; 
		}
		else
		{
			if($secondaryQuery != "(")
			{
				$secondaryQuery .= " OR ";
			}
			$secondaryQuery .= "position = '$position'";
			$withEmployee .= " [".$position."]"; 
		}
	}
	$secondaryQuery .= ")";

	if($secondaryQuery == "()")//No employees in the position
	{
		Print "<script>alert('".$successArchive."')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}
	else
	{
		if($success != 0)
		{
			Print "<script>alert('".$successArchive.". These following positions still has employees: ".$withEmployee."')</script>";
		}
		else
		{
			Print "<script>alert('These following positions still has employees: ".$withEmployee."')</script>";
		}
		//Print "<script>alert('".$successArchive."')</script>";
		$positionPending = $primaryQuery.$secondaryQuery;
		mysql_query($positionPending);
		//Print '<script>alert("'.$primaryQuery.''.$secondaryQuery.'")</script>';
		Print "<script>window.location.assign('options.php')</script>";
	}

?>























