<?php
include_once('directives/db.php');
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


function first($array) { 
if (!is_array($array)) return $array; 
if (!count($array)) return null; 
reset($array); 
return $array[key($array)]; 
} 

function last($array) { 
if (!is_array($array)) return $array; 
if (!count($array)) return null; 
end($array); 
return $array[key($array)]; 
} 

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
	if((!empty($_POST['timein1'][$count]) && 
		!empty($_POST['timeout1'][$count])) && 
		((empty($_POST['timein2'][$count]) && empty($_POST['timeout2'][$count])) || (!empty($_POST['timein2'][$count]) && !empty($_POST['timeout2'][$count]))) 
		 || $_POST['attendance'][$count] == "ABSENT")
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
	$AttQuery = "";
	for($counter = 0; $counter < $empNum; $counter++)
	{
		//Print "<script>alert('4')</script>";
		if($AttQuery != "")
		{
			$AttQuery .= ",";
		}
		if((!empty($_POST['timein1'][$counter]) && !empty($_POST['timeout1'][$counter]) && 
			(!empty($_POST['timein2'][$counter]) && !empty($_POST['timeout2'][$counter])) || (empty($_POST['timein2'][$counter]) && empty($_POST['timeout2'][$counter]))) && $_POST['attendance'][$counter] == "PRESENT")
		{	
			//Print "<script>alert('present')</script>";
			$empid = $_POST['empid'][$counter];
			
			$timein1 = $_POST['timein1'][$counter];
			$timeout1 = $_POST['timeout1'][$counter];
			if(!empty($_POST['timein2'][$counter]))
			{
				$timein2 = $_POST['timein2'][$counter];
				$timeout2 = $_POST['timeout2'][$counter];
			}
			else
			{
				$timein2 = "";
				$timeout2 = "";
			}
			
			 // Print "<script>alert('counter ". $counter ."')</script>";
			 // Print "<script>alert('timein ". $timein ."')</script>";
			 // Print "<script>alert('timeout ". $timeout ."')</script>";
			

			if(!empty($_POST['workinghrs'][$counter]))
			{
				$workinghrs = mysql_real_escape_string($_POST['workinghrs'][$counter]);
				$hasMins = strpos($workinghrs, ",");//Search the string if it has comma
				if($hasMins == false)
				{
					$workinghrs = $workinghrs[0].$workinghrs[1];//Gets the first 2 characters
					$workinghrs = str_replace(' ', '', $workinghrs);//removes all the spaces
				}
				else
				{
					$work = explode(",", $workinghrs);//Separates the string
					$hrs = $work[0];//gets the Hours
					$mins = $work[1];//Gets the minutes
					
					$workinghrs = $hrs[0].$hrs[1].".".$mins[1].$mins[2];
					
					$workinghrs = str_replace(' ', '', $workinghrs);//removes all the spaces
					//Print "<script>alert('workinghrs ". $workinghrs ."')</script>";
				}
				 //Print "<script>alert('workinghrs ".$workinghrs."')</script>";
			}
			else 
			{
				$workinghrs = "";
			}

			if(!empty($_POST['othrs'][$counter]))
			{
				$OtHrs = $_POST['othrs'][$counter];
				$OtHrs = mysql_real_escape_string($_POST['othrs'][$counter]);
				$hasMins = strpos($OtHrs, ",");//Search the string if it has comma
				$justMins = strpos($OtHrs, "mins");
				//Print "<script>alert('yeah')</script>";
				if($justMins == true && $hasMins == false)
				{
					//Print "<script>alert('yeah')</script>";
					$OtHrs = "0.".$OtHrs[0].$OtHrs[1];//Gets the first 2 characters
					$OtHrs = str_replace(' ', '', $OtHrs);//removes all the spaces
				}
				else if($hasMins == false)
				{
					$OtHrs = $OtHrs[0].$OtHrs[1];//Gets the first 2 characters
					$OtHrs = str_replace(' ', '', $OtHrs);//removes all the spaces
				}
				else
				{
					//dito
					//Print "<script>alert('yepa')</script>";
					$work = explode(",", $OtHrs);//Separates the string
					$hrs = $work[0];//gets the Hours
					$mins = $work[1];//Gets the minutes
					
					$OtHrs = $hrs[0].$hrs[1].".".$mins[1].$mins[2];
					$OtHrs = str_replace(' ', '', $OtHrs);//removes all the spaces
				}
				
			}
			else 
			{
				$OtHrs = "";
			}
			if(!empty($_POST['undertime'][$counter]))
			{
				$undertime = mysql_real_escape_string($_POST['undertime'][$counter]);
				$hasMins = strpos($undertime, ",");//Search the string if it has comma
				$justMins = strpos($undertime, "mins");
				//Print "<script>alert('yeah')</script>";
				if($justMins == true && $hasMins == false)
				{
					//Print "<script>alert('yeah')</script>";
					$undertime = "0.".$undertime[0].$undertime[1];//Gets the first 2 characters
					$undertime = str_replace(' ', '', $undertime);//removes all the spaces
				}
				else if($hasMins == false)
				{
					$undertime = $undertime[0].$undertime[1];//Gets the first 2 characters
					$undertime = str_replace(' ', '', $undertime);//removes all the spaces
				}
				else
				{
					$work = explode(",", $undertime);//Separates the string
					$hrs = $work[0];//gets the Hours
					$mins = $work[1];//Gets the minutes
					
					$undertime = $hrs[0].$hrs[1].".".$mins[1].$mins[2];
					$undertime = str_replace(' ', '', $undertime);//removes all the spaces
				}
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
			
			//Print "<script>alert('workinghrs ". $workinghrs ."')</script>";
			$AttQuery = updateQuery($timein1, $timeout1, $timein2, $timeout2, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
			
		}
		else if($_POST['attendance'][$counter] == "ABSENT")// ABSENT
		{
			$empid = $_POST['empid'][$counter];
			//Make Algorithm that will check if this employee is AWOL
			$Awol = "SELECT * FROM attendance WHERE empid = '$empid' ORDER BY date ASC LIMIT 7";
			$AwolQuery = mysql_query($Awol);
			$AwolCounter = 0;
			$start = null;
			$end = $date;
			$absentCounter = 0;
			while($AwolChecker = mysql_fetch_assoc($AwolQuery))
			{
				$absentCounter++;
				if($absentCounter == 1)//1 is the numeric representation of ABSENT
				{
					$start = $AwolChecker['date'];// Gets the first date
					//Print "<script>alert('".$start."')</script>";
				}
				
				
				//Print "<script>alert('".$counter."')</script>";
				
				if($AwolChecker['attendance'] == 1)
				{
					$AwolCounter++;
				}
			}
			if($AwolCounter == 7)
			{
				
				$checkAwol = "SELECT * FROM awol_employees WHERE empid = '$empid'";
				$checkAwolQuery = mysql_query($checkAwol);
				if(mysql_num_rows($checkAwolQuery) == 0)
				{
					//Print "<script>alert('2')</script>";
					$AwolPending = "INSERT awol_employees(empid, start_date, end_date, status) 
												VALUES(	'$empid',
														'$start',
														'$end',
														'Pending')";
					mysql_query($AwolPending);
				}
				
				
				$emp = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
				$empQuery = mysql_query($emp);
				$empArr = mysql_fetch_assoc($empQuery);
				Print "<script>alert('[".$empArr['lastname'].", ".$empArr['firstname']."] has already accumulated 7 Absences and is now pending for AWOL. Go to Employees tab > Absence Notification')</script>";
			}


			//Print "<script>alert('absent')</script>";
			
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
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
			$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//Print "<script>alert('".$attendance."')</script>";
			//require "directives/attendance/attendance_query.php";
			$AttQuery = updateQuery($timein1, $timeout1, $timein2, $timeout2, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
		

		}
		else if(empty($_POST['attendance'][$counter]))// NO INPUT
		{
			//Print "<script>alert('no input')</script>";
			$empid = $_POST['empid'][$counter];
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
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
			$AttQuery = updateQuery($timein1, $timeout1, $timein2, $timeout2, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
									  	
		}

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
												afterbreak_timein,
												afterbreak_timeout,
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
		if((!empty($_POST['timein1'][$counter]) && !empty($_POST['timeout1'][$counter]) && 
			(!empty($_POST['timein2'][$counter]) && !empty($_POST['timeout2'][$counter])) || (empty($_POST['timein2'][$counter]) && empty($_POST['timeout2'][$counter]))) && $_POST['attendance'][$counter] == "PRESENT")
		{	
			//Print "<script>alert('Pasok')</script>";
			$empid = $_POST['empid'][$counter];
			$timein1 = $_POST['timein1'][$counter];
			$timeout1 = $_POST['timeout1'][$counter];
			if(!empty($_POST['timein2'][$counter]))
			{
				$timein2 = $_POST['timein2'][$counter];
				$timeout2 = $_POST['timeout2'][$counter];
			}
			else
			{
				$timein2 = "";
				$timeout2 = "";
			}
			
			 // Print "<script>alert('counter ". $counter ."')</script>";
			 // Print "<script>alert('timein ". $timein ."')</script>";
			 // Print "<script>alert('timeout ". $timeout ."')</script>";
			

			if(!empty($_POST['workinghrs'][$counter]))
			{
				$workinghrs = mysql_real_escape_string($_POST['workinghrs'][$counter]);
				$hasMins = strpos($workinghrs, ",");//Search the string if it has comma
				if($hasMins == false)
				{
					$workinghrs = $workinghrs[0].$workinghrs[1];//Gets the first 2 characters
					$workinghrs = str_replace(' ', '', $workinghrs);//removes all the spaces
				}
				else
				{
					$work = explode(",", $workinghrs);//Separates the string
					$hrs = $work[0];//gets the Hours
					$mins = $work[1];//Gets the minutes
					
					$workinghrs = $hrs[0].$hrs[1].".".$mins[1].$mins[2];
					//Print "<script>alert('workinghrs ". $mins[1].$mins[2] ."')</script>";
					$workinghrs = str_replace(' ', '', $workinghrs);//removes all the spaces
				}
				 //
			}
			else 
			{
				$workinghrs = "";
			}

			if(!empty($_POST['othrs'][$counter]))
			{
				$OtHrs = $_POST['othrs'][$counter];
				$hasMins = strpos($OtHrs, ",");//Search the string if it has comma
				$justMins = strpos($OtHrs, "mins");
				if($justMins == true && $hasMins == false)
				{
					$OtHrs = "0.".$OtHrs[0].$OtHrs[1];//Gets the first 2 characters
					$OtHrs = str_replace(' ', '', $OtHrs);//removes all the spaces
				}
				else if($hasMins == false)
				{
					$OtHrs = $OtHrs[0].$OtHrs[1];//Gets the first 2 characters
					$OtHrs = str_replace(' ', '', $OtHrs);//removes all the spaces
				}
				else
				{
					$work = explode(",", $OtHrs);//Separates the string
					$hrs = $work[0];//gets the Hours
					$mins = $work[1];//Gets the minutes
					
					$OtHrs = $hrs[0].$hrs[1].".".$mins[1].$mins[2];
					$OtHrs = str_replace(' ', '', $OtHrs);//removes all the spaces
				}
			}
			else 
			{
				$OtHrs = "";
			}
			if(!empty($_POST['undertime'][$counter]))
			{
				$undertime = mysql_real_escape_string($_POST['undertime'][$counter]);
				$hasMins = strpos($undertime, ",");//Search the string if it has comma
				$justMins = strpos($undertime, "mins");
				//Print "<script>alert('yeah')</script>";
				if($justMins == true && $hasMins == false)
				{
					//Print "<script>alert('yeah')</script>";
					$undertime = "0.".$undertime[0].$undertime[1];//Gets the first 2 characters
					$undertime = str_replace(' ', '', $undertime);//removes all the spaces
				}
				else if($hasMins == false)
				{
					$undertime = $undertime[0].$undertime[1];//Gets the first 2 characters
					$undertime = str_replace(' ', '', $undertime);//removes all the spaces
				}
				else
				{
					$work = explode(",", $undertime);//Separates the string
					$hrs = $work[0];//gets the Hours
					$mins = $work[1];//Gets the minutes
					
					$undertime = $hrs[0].$hrs[1].".".$mins[1].$mins[2];
					$undertime = str_replace(' ', '', $undertime);//removes all the spaces
				}
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
			
			
			$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
			
			//Print "<script>alert('yeah3')</script>";
			
		}
		else if($_POST['attendance'][$counter] == "ABSENT")// ABSENT
		{
			//Make algorithm that will check if this employee is AWOL
			$empid = $_POST['empid'][$counter];
			//Make Algorithm that will check if this employee is AWOL
			$Awol = "SELECT * FROM attendance WHERE empid = '$empid' ORDER BY date DESC LIMIT 7";
			$AwolQuery = mysql_query($Awol);
			$AwolCounter = 0;
			$end = $date;
			$absentCounter = 0;
			while($AwolChecker = mysql_fetch_assoc($AwolQuery))
			{
				$absentCounter++;
				if($absentCounter == 7)
				{
					$start = $AwolChecker['date'];// Gets the last date of the query
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
				//Print "<script>alert('1')</script>";
				$AwolPending = "INSERT awol_employees(empid, start_date, end_date, status) 
												VALUES(	'$empid',
														'$start',
														'$end',
														'Pending')";
				mysql_query($AwolPending);//dito
				$emp = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
				$empQuery = mysql_query($emp);
				$empArr = mysql_fetch_assoc($empQuery);
				Print "<script>alert('[".$empArr['lastname'].", ".$empArr['firstname']."] has already accumulated 7 Absences and is now pending for AWOL. Go to Employees tab > Absence Notification')</script>";
			}

			//Print "<script>alert('absent')</script>";
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
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
			$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
			//Print "<script>alert('".$AttQuery."')</script>";
			
		}
		else if(empty($_POST['attendance'][$counter]))
		{
			//Print "<script>alert('yeah1')</script>";
			$empid = $_POST['empid'][$counter];
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
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
			$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate);
		
		}
		//Print "<script>alert('yeah')</script>";
	}
	//Print "<script>alert('".$AttQuery."')</script>";
	$FinalQuery = $initialQuery . $AttQuery;
	Print "<script>console.log('".$FinalQuery."')</script>";
	$queryAttendance = mysql_query($FinalQuery);
}

//require "directives/attendance/attendance_query.php";
Print "<script>window.location.assign('enterattendance.php?position=null&site=". $location."')</script>";

?>












