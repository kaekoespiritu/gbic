<?php
include('directives/db.php');
include('directives/session.php');
date_default_timezone_set('Asia/Hong_Kong');
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
//Print "<script>alert('".$empNum."')</script>";
// Checker if there are inputs in the attendance
$count = 0;
for($count; $count <= $empNum; $count++)
{
	if(!empty($_POST['timein'][$count]) && !empty($_POST['timeout'][$count]) )
	{
		//Print "<script>alert('yea')</script>";
		break 1;
	}
}
$max = $empNum - 1;

if($count == $empNum +1)
{
	Print "<script>alert('You have not inputted any values.');
			window.location.assign('enterattendance.php?site=".$location."')</script>";
}


$dateChecker = "SELECT * FROM attendance WHERE date = '$date' AND site = '$location'";
$checkerQuery = mysql_query($dateChecker);
if($checkerQuery)
{
	$dateRows = mysql_num_rows($checkerQuery);
}
else 
{
	$dateRows = 0;
}
if(!empty($dateRows))// Updating attendance
{
  	//$initialQuery = "UPDATE attendance SET "; 
  										// 		empid, 
												// position,
												// timein,
												// timeout,
												// workhours,
												// overtime,
												// undertime,
												// nightdiff,
												// remarks,
												// absent,
												// date";
	$AttQuery = "";
	for($counter = 0; $counter < $empNum; $counter++)
	{
		if($AttQuery != "")
		{
			$AttQuery .= ",";
		}
		if((!empty($_POST['timein'][$counter]) && !empty($_POST['timeout'][$counter])) && $_POST['attendance'][$counter] == "PRESENT")
		{	
			//Print "<script>alert('present')</script>";
			$empid = $_POST['empid'][$counter];
			
			$timein = $_POST['timein'][$counter];
			$timeout = $_POST['timeout'][$counter];
			 // Print "<script>alert('counter ". $counter ."')</script>";
			 // Print "<script>alert('timein ". $timein ."')</script>";
			 // Print "<script>alert('timeout ". $timeout ."')</script>";
			

			if(!empty($_POST['workinghrs'][$counter]))
			{
				$workinghrs = mysql_real_escape_string($_POST['workinghrs'][$counter]);
				$workinghrs = $workinghrs[0].$workinghrs[1];
				$workinghrs = str_replace(' ', '', $workinghrs);
				 //Print "<script>alert('workinghrs ". $workinghrs ."')</script>";
			}
			else 
			{
				$workinghrs = "";
			}

			if(!empty($_POST['othrs'][$counter]))
			{
				$OtHrs = $_POST['othrs'][$counter];
				$OtHrs = $OtHrs[0].$OtHrs[1];
				$OtHrs = str_replace(' ', '', $OtHrs);
				
			}
			else 
			{
				$OtHrs = "";
			}
			if(!empty($_POST['undertime'][$counter]))
			{
				$undertime = mysql_real_escape_string($_POST['undertime'][$counter]);
				//$undertime_length = (strlen($undertime) - 1) - strlen($undertime);
				//Print "<script>alert('undertime_length ". $undertime_length ."')</script>";
				//$undertime = substr($undertime, 2, 0);
				$undertime = $undertime[0].$undertime[1];
				 //Print "<script>alert('undertime ". $undertime ."')</script>";
				$undertime = str_replace(' ', '', $undertime);
			}
			else 
			{
				$undertime = "";
			}
			if(!empty($_POST['nightdiff'][$counter]))
			{
				$nightdiff = mysql_real_escape_string($_POST['nightdiff'][$counter]);
				$nightdiff = $nightdiff[0].$nightdiff[1];
				$nightdiff = str_replace(' ', '', $nightdiff);
				//Print "<script>alert('ND ". $nightdiff ."')</script>";
			}
			else 
			{
				$nightdiff = "";
			}
			if(!empty($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			else 
			{
				$remarks = "";
			}

			$attendance = 2;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);

			
			// Print "<script>alert('timein ". $timein ."')</script>";
			// Print "<script>alert('timeout ". $timeout ."')</script>";
			// Print "<script>alert('remarks ". $remarks ."')</script>";
			// Print "<script>alert('Absent ". $absent ."')</script>";

			$AttQuery =   "UPDATE attendance SET 	empid='".$empid."',
												  	position= '".$employeeArr['position']."',
												  	timein= '".$timein."',
												  	timeout='".$timeout."',
												  	workhours='".$workinghrs."',
												  	overtime='".$OtHrs."',
												  	undertime='".$undertime."',
												 	nightdiff='".$nightdiff."',
												  	remarks='".$remarks."',
												  	attendance='".$attendance."',
												  	date='".$date."',
												  	site='".$location."' WHERE date = '$date' AND empid = '$empid'";
			//Print "<script>alert('timein ". $AttQuery ."')</script>";
		}
		else if($_POST['attendance'][$counter] == "ABSENT")// ABSENT
		{
			//Print "<script>alert('absent')</script>";
			$empid = $_POST['empid'][$counter];
			$timein = "";
			$timeout = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			$attendance = 1;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);

			$AttQuery =   "UPDATE attendance SET 	empid='".$empid."',
												  	position= '".$employeeArr['position']."',
												  	timein= '".$timein."',
												  	timeout='".$timeout."',
												  	workhours='".$workinghrs."',
												  	overtime='".$OtHrs."',
												  	undertime='".$undertime."',
												 	nightdiff='".$nightdiff."',
												  	remarks='".$remarks."',
												  	attendance='".$attendance."',
												  	date='".$date."',
												  	site='".$location."' WHERE date = '$date' AND empid = '$empid'";
		}
		else if(empty($_POST['attendance'][$counter]))
		{
			//Print "<script>alert('no input')</script>";
			$empid = $_POST['empid'][$counter];
			$timein = "";
			$timeout = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			$attendance = 0;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$AttQuery =   "UPDATE attendance SET 	empid='".$empid."',
												  	position= '".$employeeArr['position']."',
												  	timein= '".$timein."',
												  	timeout='".$timeout."',
												  	workhours='".$workinghrs."',
												  	overtime='".$OtHrs."',
												  	undertime='".$undertime."',
												 	nightdiff='".$nightdiff."',
												  	remarks='".$remarks."',
												  	attendance='".$attendance."',
												  	date='".$date."',
												  	site='".$location."' WHERE date = '$date' AND empid = '$empid'";
		}
		//Print "<script>alert('empid ". $empid ."')</script>";
		//Print "<script>alert('". $AttQuery ."')</script>";
		mysql_query($AttQuery);//query
	}
}
else// NEW attendance
{
	$initialQuery = "INSERT INTO attendance(	empid, 
												position,
												timein,
												timeout,
												workhours,
												overtime,
												undertime,
												nightdiff,
												remarks,
												attendance,
												date,
												site) VALUES";
	$AttQuery = "";
	for($counter = 0; $counter < $empNum; $counter++)
	{
		if($AttQuery != "")
		{
			$AttQuery .= ",";
		}
		if((!empty($_POST['timein'][$counter]) && !empty($_POST['timeout'][$counter])) && $_POST['attendance'][$counter] == "PRESENT")
		{	
			
			$empid = $_POST['empid'][$counter];
			// Print "<script>alert('empid ". $empid ."')</script>";
			$timein = $_POST['timein'][$counter];
			$timeout = $_POST['timeout'][$counter];
			 // Print "<script>alert('counter ". $counter ."')</script>";
			 // Print "<script>alert('timein ". $timein ."')</script>";
			 // Print "<script>alert('timeout ". $timeout ."')</script>";
			

			if(!empty($_POST['workinghrs'][$counter]))
			{
				$workinghrs = mysql_real_escape_string($_POST['workinghrs'][$counter]);
				$workinghrs = $workinghrs[0].$workinghrs[1];
				$workinghrs = str_replace(' ', '', $workinghrs);
				 //Print "<script>alert('workinghrs ". $workinghrs ."')</script>";
			}
			else 
			{
				$workinghrs = "";
			}

			if(!empty($_POST['othrs'][$counter]))
			{
				$OtHrs = $_POST['othrs'][$counter];
				$OtHrs = $OtHrs[0].$OtHrs[1];
				$OtHrs = str_replace(' ', '', $OtHrs);
				
			}
			else 
			{
				$OtHrs = "";
			}
			if(!empty($_POST['undertime'][$counter]))
			{
				$undertime = mysql_real_escape_string($_POST['undertime'][$counter]);
				//$undertime_length = (strlen($undertime) - 1) - strlen($undertime);
				//Print "<script>alert('undertime_length ". $undertime_length ."')</script>";
				//$undertime = substr($undertime, 2, 0);
				$undertime = $undertime[0].$undertime[1];
				 //Print "<script>alert('undertime ". $undertime ."')</script>";
				$undertime = str_replace(' ', '', $undertime);
			}
			else 
			{
				$undertime = "";
			}
			if(!empty($_POST['nightdiff'][$counter]))
			{
				$nightdiff = mysql_real_escape_string($_POST['nightdiff'][$counter]);
				$nightdiff = $nightdiff[0].$nightdiff[1];
				$nightdiff = str_replace(' ', '', $nightdiff);
				//Print "<script>alert('ND ". $nightdiff ."')</script>";
			}
			else 
			{
				$nightdiff = "";
			}
			if(!empty($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			else 
			{
				$remarks = "";
			}

			$attendance = 2;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);

			
			// Print "<script>alert('timein ". $timein ."')</script>";
			// Print "<script>alert('timeout ". $timeout ."')</script>";
			// Print "<script>alert('remarks ". $remarks ."')</script>";
			// Print "<script>alert('Absent ". $absent ."')</script>";

			$AttQuery .= "('".$empid."',
						  '".$employeeArr['position']."',
						  '".$timein."',
						  '".$timeout."',
						  '".$workinghrs."',
						  '".$OtHrs."',
						  '".$undertime."',
						  '".$nightdiff."',
						  '".$remarks."',
						  '".$attendance."',
						  '".$date."',
						  '".$location."')";
			
		}
		else if($_POST['attendance'][$counter] == "ABSENT")// ABSENT
		{
			$empid = $_POST['empid'][$counter];
			$timein = "";
			$timeout = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			$attendance = 1;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);

			$AttQuery .= "('".$empid."',
						  '".$employeeArr['position']."',
						  '".$timein."',
						  '".$timeout."',
						  '".$workinghrs."',
						  '".$OtHrs."',
						  '".$undertime."',
						  '".$nightdiff."',
						  '".$remarks."',
						  '".$attendance."',
						  '".$date."',
						  '".$location."')";
		}
		else if(empty($_POST['attendance'][$counter]))
		{
			$empid = $_POST['empid'][$counter];
			$timein = "";
			$timeout = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			$attendance = 0;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$AttQuery .= "('".$empid."',
						  '".$employeeArr['position']."',
						  '".$timein."',
						  '".$timeout."',
						  '".$workinghrs."',
						  '".$OtHrs."',
						  '".$undertime."',
						  '".$nightdiff."',
						  '".$remarks."',
						  '".$attendance."',
						  '".$date."',
						  '".$location."')";
		}
	}
	$FinalQuery = $initialQuery . $AttQuery;

	$queryAttendance = mysql_query($FinalQuery);
}


Print "<script>window.location.assign('attendance.php')</script>";

?>












