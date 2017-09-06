<?php
include('directives/db.php');
include('directives/session.php');

$location = $_GET['site'];

if(isset($_SESSION['date']))
{
	$date = $_SESSION['date'];
}
else
{
	$date = strftime("%B %d, %Y");
}

$site = "SELECT * FROM employee WHERE site = '$location'";

$siteQuery = mysql_query($site);
$empNum = mysql_num_rows($siteQuery);

// Checker if there are inputs in the attendance
for($count = 0; $count <= $empNum; $count++)
{
	if(isset($_POST['timein'][$count]) && isset($_POST['timeout'][$count]))
	{
		//break;
	}
}

$initialQuery = "INSERT INTO attendance(	empid, 
											position,
											timein,
											timeout,
											workhours,
											overtime,
											undertime,
											nightdiff,
											remarks,
											absent,
											[date]) VALUES";
$AttQuery = "";
for($counter = 0; $counter < $empNum; $counter++)
{
	if($AttQuery == "")
	{
		$AttQuery .= ",";
	}
	if(isset($_POST['timein'][$counter]) && isset($_POST['timeout'][$counter]) )
	{	
		$timein = $_POST['timein'][$counter];
		$timeout = $_POST['timeout'][$counter];
		 Print "<script>alert('counter ". $counter ."')</script>";
		// Print "<script>alert('timein ". $timein ."')</script>";
		// Print "<script>alert('timeout ". $timeout ."')</script>";
		$empid = $_POST['empid'][$counter];
		
		$workinghrs = $_POST['workinghrs'][$counter];

		if(isset($_POST['othrs'][$counter]))
		{
			$OtHrs = $_POST['othrs'][$counter];
		}
		else 
		{
			$OtHrs = "";
		}
		if(isset($_POST['undertime'][$counter]))
		{
			$undertime = $_POST['undertime'][$counter];
		}
		else 
		{
			$undertime = "";
		}
		if(isset($_POST['nightdiff'][$counter]))
		{
			$nightdiff = $_POST['nightdiff'][$counter];
		}
		else 
		{
			$nightdiff = "";
		}
		if(isset($_POST['remarks'][$counter]))
		{
			$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
		}
		else 
		{
			$remarks = "";
		}

		$absent = 0;
		$employee = "SELECT * FROM employee WHERE empid = '$empid'";
		$employeeQuery = mysql_query($employee);
		$employeeArr = mysql_fetch_assoc($employeeQuery);

		// Print "<script>alert('empid ". $empid ."')</script>";
		// Print "<script>alert('timein ". $timein ."')</script>";
		// Print "<script>alert('timeout ". $timeout ."')</script>";
		// Print "<script>alert('workinghours ". $workingHrs ."')</script>";
		// Print "<script>alert('OT ". $OtHrs ."')</script>";
		// Print "<script>alert('UT ". $undertime ."')</script>";
		// Print "<script>alert('ND ". $nightdiff ."')</script>";
		// Print "<script>alert('remarks ". $remarks ."')</script>";
		// Print "<script>alert('Absent ". $absent ."')</script>";

		$AttQuery = "('".$empid."',
					  '".$employeeArr['position']."',
					  '".$timein."',
					  '".$timeout."',
					  '".$workinghrs."',
					  '".$OtHrs."',
					  '".$undertime."',
					  '".$nightdiff."',
					  '".$remarks."',
					  '".$absent."',
					  '".$date."')";
		
	}
	// else // ABSENT
	// {
	// 	$empid = $_POST['empid'][$counter];
	// 	$timein = "";
	// 	$timeout = "";
	// 	$workingHrs = "";
	// 	$OtHrs = "";
	// 	$undertime = "";
	// 	$nightdiff = "";
	// 	$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
	// 	$absent = 1;
	// 	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	// 	$employeeQuery = mysql_query($employee);
	// 	$employeeArr = mysql_fetch_assoc($employeeQuery);

	// 	$AttQuery = "('".$empid."',
	// 				  '".$employeeArr['position']."',
	// 				  '".$timein."',
	// 				  '".$timeout."',
	// 				  '".$workingHrs."',
	// 				  '".$OtHrs."',
	// 				  '".$undertime."',
	// 				  '".$nightdiff."',
	// 				  '".$remarks."',
	// 				  '".$absent."',
	// 				  '".$date."')";
	// }
}
//Make separate forloop for absent that will just enter the Empid, position, absent to 0, and date 
$query = $site . $AttQuery;

$queryAttendance = mysql_query($query);
Print "<script>alert('query ". $query ."')</script>";

//Print "<script>window.location.assign('attendance.php')</script>";

?>












