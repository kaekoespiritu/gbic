<?php
include('directives/db.php');
include('directives/session.php');
date_default_timezone_set('Asia/Hong_Kong');

if(isset($_SESSION['date']))
	{
		$date = $_SESSION['date'];
	}
	else
	{
		$date = strftime("%B %d, %Y");// Gets the current date
	}

$employee = "SELECT * FROM employee ORDER BY site, empid DESC";
$employeeQuery = mysql_query($employee);

$initialQuery = "INSERT INTO loans(	empid, 
									sss, 
									pagibig, 
									vale, 
									date) VALUES";
$empNum = mysql_num_rows($employeeQuery);
$bool = false;
$loansQuery = "";
for($counter = 0; $counter <= $empNum; $counter++)
{
	
	if(isset($_POST['empid'][$counter])) // This if there is an existing loan for that specific day to update the info
	{
		$empid = $_POST['empid'][$counter];

		$dateChecker = "SELECT * FROM attendance WHERE date = '$date' AND empid = '$empid'";
		$dateQuery = mysql_query($dateChecker);
		$dateNum = mysql_num_rows($dateQuery);
		Print "<script>alert('". $dateNum ."')</script>";
		if($dateNum != 0)
		{
			$dateArray = mysql_fetch_assoc($dateQuery);
			if(!empty($_POST['sss'][$counter]))
			{
				if($dateArray['sss'] != $_POST['sss'][$counter])//Check if there are changes done in the sss
				{
					$sss = $_POST['sss'][$counter];
				}
				else
				{
					$sss = null;
				}
			}
			else
			{
				$sss = null;
			}
			if(!empty($_POST['pagibig'][$counter]))
			{
				if($dateArray['pagibig'] != $_POST['pagibig'][$counter])//Check if there are changes done in the pagibig
				{
					$pagibig = $_POST['pagibig'][$counter];
				}
				else
				{
					$pagibig = null;
				}
			}
			else
			{
				$pagibig = null;
			}
			if(!empty($_POST['vale'][$counter]))
			{
				if($dateArray['vale'] != $_POST['vale'][$counter])//Check if there are changes done in the vale
				{
					$vale = $_POST['vale'][$counter];
				}
				else
				{
					$vale = null;
				}
			}
			if($sss != null || $pagibig != null || $vale != null)
			{
				Print "<script>alert('update')</script>";
			$update = "UPDATE loans SET sss = '$sss', 
										pagibig = '$pagibig', 
										vale = '$vale' WHERE empid = '$empid' AND date = '$date'";
			$updateQuery = mysql_query($update);
			}
		}
		else // This is if there is no information on the specific date
		{
			
			if(!empty($_POST['sss'][$counter]))
			{
				$sss = $_POST['sss'][$counter];
			}
			else
			{
				$sss = null;
			}
			if(!empty($_POST['pagibig'][$counter]))
			{
				$pagibig = $_POST['pagibig'][$counter];
			}
			else
			{
				$pagibig = null;
			}
			if(!empty($_POST['vale'][$counter]))
			{
				$vale = $_POST['vale'][$counter];
			}
			else
			{
				$vale = null;
			}
			if($sss != null || $pagibig != null || $vale != null)
			{
				$bool = true;
				if($loansQuery != "")
				{
					$loansQuery .= ",";
				}
				Print "<script>alert('New')</script>";
				$loansQuery .= "(	'". $empid ."',
											'". $sss ."',
											'". $pagibig ."',
											'". $vale ."',
											'". $date ."')";

			} 
		}
	}
}
if($bool == true)
{
	$finalQuery = $initialQuery . $loansQuery;
	Print "<script>alert('".$finalQuery."')</script>";
	mysql_query($finalQuery);
}

Print "<script>window.location.assign('loans.php')</script>";
?>
















