<?php
include('directives/db.php');
include('directives/session.php');
require "directives/attendance/attendance_query.php";
//error_reporting(0);// fetch no error
date_default_timezone_set('Asia/Hong_Kong');
$location = $_GET['site'];
//Print "<script>alert('absent')</script>";
if(isset($_SESSION['date']))
{
	$date = $_SESSION['date'];// This gets the chosen date by the admin
}
else
{
	$date = strftime("%B %d, %Y");// This gets the current date
}
$day = date('l', strtotime($date));// This gets the day of the week
$sunday = 1;//Pre-sets the value of Sunday to the database

// Holiday
if(isset($_SESSION['holidayDate']))
{
	if($_SESSION['holidayDate'] == $date)
	{
		$holidayName = $_SESSION['holidayName'];
		$holidayType = $_SESSION['holidayType'];
		$holidayDate = $_SESSION['holidayDate'];
		//Print "<script>alert('".$holidayType."')</script>";
		
		$holidayChecker = "SELECT * FROM holiday WHERE date = '$holidayDate'";
		$holidayCheckerQuery = mysql_query($holidayChecker);
		if($holidayCheckerQuery)
		{
			$holidayCheckernum = mysql_num_rows($holidayCheckerQuery);
			if($holidayCheckernum < 1)
			{
				$holiday = "INSERT INTO holiday(holiday, date, type) VALUES('$holidayName','$holidayDate','$holidayType')";
				$holidayQuery = mysql_query($holiday);
			}
		}
	}
	else
	{
		$holidayDate = 0;
	} 
}
else
{
	$holidayDate = 0;
}


$site = "SELECT * FROM employee WHERE site = '$location'";

$siteQuery = mysql_query($site);
$empNum = mysql_num_rows($siteQuery);
//Print "<script>alert('".$empNum."')</script>";
// Checker if there are inputs in the attendance
$count = 0;
for($count; $count <= $empNum; $count++)
{
	if((!empty($_POST['timein'][$count]) && !empty($_POST['timeout'][$count])) || $_POST['attendance'][$count] == "ABSENT")
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
  	//Print "<script>alert('yow')</script>";
	$AttQuery = "";
	for($counter = 0; $counter < $empNum; $counter++)
	{
		//Print "<script>alert('4')</script>";
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
			$employee = "SELECT * FROM employee WHERE empid = '$empid' ";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			
			
			$AttQuery = updateQuery($timein, $timeout, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
			
		}
		else if($_POST['attendance'][$counter] == "ABSENT")// ABSENT
		{
			$empid = $_POST['empid'][$counter];
			//Make Algorithm that will check if this employee is AWOL
			$Awol = "SELECT * FROM attendance WHERE empid = '$empid' ORDER BY date ASC LIMIT 7";
			$AwolQuery = mysql_query($Awol);
			$AwolCounter = 0;
			$start = null;
			$end = null;
			$absentCounter = 0;
			while($AwolChecker = mysql_fetch_assoc($AwolQuery))
			{
				$absentCounter++;
				if($absentCounter == 1)
				{
					$start = $AwolChecker['date'];// Gets the first date
					//Print "<script>alert('".$start."')</script>";
				}
				else if($absentCounter == 7)
				{
					$end = $AwolChecker['date'];// Gets the last date of the query
					//Print "<script>alert('".$end."')</script>";
				}
				
				//Print "<script>alert('".$counter."')</script>";
				
				if($AwolChecker['attendance'] == 1)
				{
					$AwolCounter++;
				}
			}
			if($AwolCounter == 7)
			{
				$AwolPending = "INSERT awol_employees(empid, start_date, end_date, status) 
												VALUES(	'$empid',
														'$start',
														'$end',
														'Pending')";
				mysql_query($AwolPending);
				$emp = "SELECT * FROM employee WHERE empid = '$empid'";
				$empQuery = mysql_query($emp);
				$empArr = mysql_fetch_assoc($empQuery);
				Print "<script>alert('[".$empArr['lastname'].", ".$empArr['lastname']."] has already accumulated 7 Absences and is now pending for AWOL. Go to Employees tab > Absence Notification')</script>";
			}


			//Print "<script>alert('absent')</script>";
			
			$timein = "";
			$timeout = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			$attendance = 1;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//Print "<script>alert('".$attendance."')</script>";
			//require "directives/attendance/attendance_query.php";
			$AttQuery = updateQuery($timein, $timeout, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
		

		}
		else if(empty($_POST['attendance'][$counter]))
		{

			
			Print "<script>alert('no input')</script>";
			$empid = $_POST['empid'][$counter];
			$timein = "";
			$timeout = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			$attendance = 0;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";
			$AttQuery = updateQuery($timein, $timeout, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
									  	
		}

		//Print "<script>alert('". $AttQuery ."')</script>";
		mysql_query($AttQuery);//query
	}
}
else// NEW attendance
{
	Print "<script>alert('yow')</script>";
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
												site,
												sunday,
												holiday) VALUES";//ADD HOLIDAY HERE
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
			$position = $employeeArr['position'];
			
			
			$AttQuery = newQuery($timein, $timeout, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
			
			//Print "<script>alert('yeah3')</script>";
			
		}
		else if($_POST['attendance'][$counter] == "ABSENT")// ABSENT
		{
			//Make algorithm that will check if this employee is AWOL
			$empid = $_POST['empid'][$counter];
			//Make Algorithm that will check if this employee is AWOL
			$Awol = "SELECT * FROM attendance WHERE empid = '$empid' ORDER BY date ASC LIMIT 6";
			$AwolQuery = mysql_query($Awol);
			$AwolCounter = 0;
			$start = $date;
			$end = null;
			$absentCounter = 0;
			while($AwolChecker = mysql_fetch_assoc($AwolQuery))
			{
				$absentCounter++;
				if($absentCounter == 6)
				{
					$end = $AwolChecker['date'];// Gets the last date of the query
					//Print "<script>alert('".$end."')</script>";
				}
				//Print "<script>alert('".$counter."')</script>";
				
				if($AwolChecker['attendance'] == 1)
				{
					$AwolCounter++;
				}
			}
			if($AwolCounter == 6)
			{
				$AwolPending = "INSERT awol_employees(empid, start_date, end_date, status) 
												VALUES(	'$empid',
														'$start',
														'$end',
														'Pending')";
				mysql_query($AwolPending);
				mysql_query($AwolPending);
				$emp = "SELECT * FROM employee WHERE empid = '$empid'";
				$empQuery = mysql_query($emp);
				$empArr = mysql_fetch_assoc($empQuery);
				Print "<script>alert('[".$empArr['lastname'].", ".$empArr['lastname']."] has already accumulated 7 Absences and is now pending for AWOL. Go to Employees tab > Absence Notification')</script>";
			}

			//Print "<script>alert('absent')</script>";
			$timein = "";
			$timeout = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			$attendance = 1;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1' ";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";
			$AttQuery = newQuery($timein, $timeout, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
			//Print "<script>alert('".$AttQuery."')</script>";
			
		}
		else if(empty($_POST['attendance'][$counter]))
		{
			//Print "<script>alert('yeah1')</script>";
			$empid = $_POST['empid'][$counter];
			$timein = "";
			$timeout = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			$attendance = 0;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1' ";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";
			$AttQuery = newQuery($timein, $timeout, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
		
		}
		//Print "<script>alert('yeah')</script>";
	}
	//Print "<script>alert('".$AttQuery."')</script>";
	$FinalQuery = $initialQuery . $AttQuery;
	//Print "<script>alert('".$FinalQuery."')</script>";
	$queryAttendance = mysql_query($FinalQuery);
}

//require "directives/attendance/attendance_query.php";
Print "<script>window.location.assign('enterattendance.php?position=null&site=". $location."')</script>";

?>












