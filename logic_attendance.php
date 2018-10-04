<?php
include_once('directives/db.php');
include('directives/session.php');
require "directives/attendance/attendance_query.php";
error_reporting(0);// fetch no error
date_default_timezone_set('Asia/Hong_Kong');
$location = $_GET['site'];
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


$day1 = date('F d, Y', strtotime('-1 day', strtotime($date)));
$day2 = date('F d, Y', strtotime('-2 day', strtotime($date)));
$day3 = date('F d, Y', strtotime('-3 day', strtotime($date)));
$day4 = date('F d, Y', strtotime('-4 day', strtotime($date)));
$day5 = date('F d, Y', strtotime('-5 day', strtotime($date)));
$day6 = date('F d, Y', strtotime('-6 day', strtotime($date)));
$day7 = date('F d, Y', strtotime('-7 day', strtotime($date)));


//Checks if there is a sunday on the days of attendance
$daysChecker = array($day1, $day2, $day3, $day4, $day5, $day6, $day7);
$isSunday = 6;
foreach($daysChecker as $checkSun)
{
	if(date('l', strtotime($checkSun)) == "Sunday")
	{
		$isSunday = 7;
	}
}


function first($array) { 
	if (!is_array($array)) 
		return $array; 
	if (!count($array)) 
		return null; 
	reset($array); 
	return $array[key($array)]; 
} 

function last($array) { 
	if (!is_array($array)) 
		return $array; 
	if (!count($array)) 
		return null; 
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

$filterQuery = ''; 
if($_GET['filter'] != 'null')
{
	$filterQuery = "AND position = '".$_GET['filter']."'";
}


$site = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' $filterQuery ";

$siteQuery = mysql_query($site);
$empNum = mysql_num_rows($siteQuery);
// Checker if there are inputs in the attendance
$count = 0;

$max = $empNum - 1;

for($count; $count <= $empNum; $count++)
{
	if((!empty($_POST['timein1'][$count]) && 
		!empty($_POST['timeout1'][$count])) && 
		((empty($_POST['timein2'][$count]) && empty($_POST['timeout2'][$count])) || (!empty($_POST['timein2'][$count]) && !empty($_POST['timeout2'][$count]))) 
		 || $_POST['attendance'][$count] == "ABSENT")
	{
		break 1;
	}
}
if($count == $empNum+1)
{
	Print "<script>alert('You have not inputted any values.');
			window.location.assign('enterattendance.php?site=".$location."')</script>";
}


$employees = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' $filterQuery";
$empCheckerQuery = mysql_query($employees);

$siteBool = false;

$empNum = count($_POST['empid']);
// $empNum = mysql_num_rows($empCheckerQuery);// gets the number of employees in the query
// Print "<script>alert('".$empNum."')</script>";
$count = 1;// counter for number of loops
$checkerBuilder = "";
if($empNum != 0)
{
	$siteBool = true;
	$checkerBuilder = " AND (";
	while($empArr = mysql_fetch_assoc($empCheckerQuery))
	{
		$employeeId = $empArr['empid'];
		$checkerBuilder .= " empid = '".$employeeId."' ";

		if($empNum != $count)
			$checkerBuilder .= " OR ";

		$count++;
	}
	$checkerBuilder .= ")";
}

$dateRows = 0;
if($siteBool)
{
	$dateChecker = "SELECT * FROM attendance WHERE date = '$date' $checkerBuilder";
	$checkerQuery = mysql_query($dateChecker);
	if(mysql_num_rows($checkerQuery) > 0)
	{
		$dateRows = mysql_num_rows($checkerQuery);
	}
}

if($dateRows != 0)// Updating attendance
{
	$initialQuery = "INSERT INTO attendance(	empid, 
												position,
												timein,
												timeout,
												afterbreak_timein,
												afterbreak_timeout,
												nightshift_timein,
												nightshift_timeout,
												workhours,
												overtime,
												undertime,
												nightdiff,
												remarks,
												attendance,
												date,
												sunday,
												holiday,
												xallow) VALUES";//ADD HOLIDAY HERE
	$AttQuery = "";
	for($counter = 0; $counter < $empNum; $counter++)
	{
		// if(((!empty($_POST['timein1'][$counter]) && !empty($_POST['timeout1'][$counter])) || (!empty($_POST['timein2'][$counter]) && !empty($_POST['timeout2'][$counter]))) || ((empty($_POST['timein2'][$counter]) && empty($_POST['timeout2'][$counter])) && $_POST['attendance'][$counter] == "PRESENT"))
		if(((!empty($_POST['timein1'][$counter]) && !empty($_POST['timeout1'][$counter])) && $_POST['attendance'][$counter] == "PRESENT"))
		{	
			$empid = $_POST['empid'][$counter];
			
			$timein1 = $_POST['timein1'][$counter];
			$timeout1 = $_POST['timeout1'][$counter];
			
			if(!empty($_POST['timein2'][$counter]))//If employee took a halfday
			{
				$timein2 = $_POST['timein2'][$counter];
				$timeout2 = $_POST['timeout2'][$counter];
			}
			else
			{
				$timein2 = "";
				$timeout2 = "";
			}

			if(!empty($_POST['timein3'][$counter]))// if employee has  night shift
			{
				$timein3 = $_POST['timein3'][$counter];
				$timeout3 = $_POST['timeout3'][$counter];
			}
			else
			{
				$timein3 = "";
				$timeout3 = "";
			}
			

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
				}
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
				if($justMins == true && $hasMins == false)
				{
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
				// $nightdiff = $nightdiff[0].$nightdiff[1];
				// $nightdiff = str_replace(' ', '', $nightdiff);

				
				$hasMins = strpos($nightdiff, ",");//Search the string if it has comma
				$justMins = strpos($nightdiff, "mins");
				if($justMins == true && $hasMins == false)
				{
					$nightdiff = "0.".$nightdiff[0].$nightdiff[1];//Gets the first 2 characters
					$nightdiff = str_replace(' ', '', $nightdiff);//removes all the spaces
				}
				else if($hasMins == false)
				{
					$nightdiff = $nightdiff[0].$nightdiff[1];//Gets the first 2 characters
					$nightdiff = str_replace(' ', '', $nightdiff);//removes all the spaces
				}
				else
				{
					$work = explode(",", $nightdiff);//Separates the string
					$hrs = $work[0];//gets the Hours
					$mins = $work[1];//Gets the minutes
					
					$nightdiff = $hrs[0].$hrs[1].".".$mins[1].$mins[2];
					$nightdiff = str_replace(' ', '', $nightdiff);//removes all the spaces
				}
			}
			else 
			{
				$nightdiff = "";
			}
			if(!empty($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
				// $remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			else 
			{
				$remarks = "";
			}

			if(!empty($_POST['xallow'][$counter]))
			{
				$xAllowance = mysql_real_escape_string($_POST['xallow'][$counter]);
			}
			else 
			{
				$xAllowance = "";
			}

			$attendance = 2;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid' ";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];

			$attChecker = "SELECT * from attendance WHERE date = '$date' AND empid = '$empid' LIMIT 1";
			$attCheckerQuery = mysql_query($attChecker);
			if(mysql_num_rows($attCheckerQuery) != 0)//update
			{
				$AttQuery = updateQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $holidayDate, $xAllowance);
			}
			else//new attendance
			{
				$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, "",$holidayDate, $xAllowance);

				$AttQuery = $initialQuery.$AttQuery; 
			}
		}
		else if($_POST['attendance'][$counter] == "ABSENT")// ABSENT
		{
			$empid = $_POST['empid'][$counter];

			//Make Algorithm that will check if this employee is AWOL
			$Awol = "SELECT * FROM attendance WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$day7', '%M %e, %Y') AND STR_TO_DATE('$day1', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC LIMIT $isSunday";

			// $Awol = "SELECT * FROM attendance WHERE empid = '$empid' and date != '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC LIMIT 7";
			$AwolQuery = mysql_query($Awol);
			$AwolCounter = 0;
			$absentCounter = 0;
			$loopCounter = 0;
			while($AwolChecker = mysql_fetch_assoc($AwolQuery))
			{
				if(date('l', strtotime($AwolChecker['date'])) != "Sunday")
				{
					if($AwolChecker['attendance'] == 1)
					{
						$AwolCounter++;
					}
					else
					{
						$AwolCounter = 0;
					}
				}
				if($loopCounter == 0)
					$end = $AwolChecker['date'];
				if($loopCounter == 6)
					$start = $AwolChecker['date'];

				$loopCounter++;
			}

			if($AwolCounter >= 6)
			{
				$checkAwol = "SELECT * FROM awol_employees WHERE empid = '$empid'";
				$checkAwolQuery = mysql_query($checkAwol);
				if(mysql_num_rows($checkAwolQuery) == 0)
				{
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

				//update employment status of employee to 2 = pending
				$empAwolPending = "UPDATE employee SET employment_status = '2' WHERE empid = '$empid'";
				mysql_query($empAwolPending);//update employment status of employee to 3 = pending
			}

			if($AwolCounter == 3 || $AwolCounter == 4)// Notification if employee accumulated 4 absences
			{
				$checkAbsence = "SELECT * FROM absence_notif WHERE empid = '$empid'";
				$checkAbsenceQuery = mysql_query($checkAbsence);
				if(mysql_num_rows($checkAbsenceQuery) == 0)
				{
					$AbsenceNotif = "INSERT absence_notif(empid) 
												VALUES('$empid')";
					mysql_query($AbsenceNotif);
				}
				$emp = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
				$empQuery = mysql_query($emp);
				$empArr = mysql_fetch_assoc($empQuery);
				Print "<script>alert('ABSENCE NOTICE: [".$empArr['lastname'].", ".$empArr['firstname']."] has already accumulated 4 consecutive Absences.')</script>";
			}

			
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
			$timein3 = "";
			$timeout3 = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			$xAllowance = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			if(isset($_POST['xallow'][$counter]))
			{
				$xAllowance = mysql_real_escape_string($_POST['xallow'][$counter]);
			}

			$attendance = 1;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";

			$attChecker = "SELECT * from attendance WHERE date = '$date' AND empid = '$empid' LIMIT 1";
			$attCheckerQuery = mysql_query($attChecker);
			if(mysql_num_rows($attCheckerQuery) != 0)//update
			{
				$AttQuery = updateQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $holidayDate, $xAllowance);
			}
			else//new attendance
			{
				$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, "",$holidayDate, $xAllowance);
				
				$AttQuery = $initialQuery.$AttQuery;

			}
		}
		else if($_POST['attendance'][$counter] == "NOWORK")
		{
			$empid = $_POST['empid'][$counter];
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
			$timein3 = "";
			$timeout3 = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			$xAllowance = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			if(isset($_POST['xallow'][$counter]))
			{
				$xAllowance = mysql_real_escape_string($_POST['xallow'][$counter]);
			}
			$attendance = 3;// 0 - no input / 1 - Absent / 2 - Present / 3 - No work
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";

			$attChecker = "SELECT * from attendance WHERE date = '$date' AND empid = '$empid' LIMIT 1";
			$attCheckerQuery = mysql_query($attChecker) or die (mysql_error());

			if(mysql_num_rows($attCheckerQuery) != 0)//update
			{
				$AttQuery = updateQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $holidayDate, $xAllowance);

			}
			else//new attendance
			{
				$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, "",$holidayDate, $xAllowance);

				$AttQuery = $initialQuery.$AttQuery; 

			}					  	
		}
		else if(empty($_POST['attendance'][$counter]))// NO INPUT
		{

			$empid = $_POST['empid'][$counter];
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
			$timein3 = "";
			$timeout3 = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			$xAllowance = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			if(isset($_POST['xallow'][$counter]))
			{
				$xAllowance = mysql_real_escape_string($_POST['xallow'][$counter]);
			}
			$attendance = 0;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";

			$attChecker = "SELECT * from attendance WHERE date = '$date' AND empid = '$empid' LIMIT 1";
			$attCheckerQuery = mysql_query($attChecker) or die (mysql_error());

			if(mysql_num_rows($attCheckerQuery) != 0)//update
			{
				$AttQuery = updateQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $holidayDate, $xAllowance);

			}
			else//new attendance
			{
				$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, "",$holidayDate, $xAllowance);

				$AttQuery = $initialQuery.$AttQuery; 

			}					  	
		}
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
												nightshift_timein,
												nightshift_timeout,
												workhours,
												overtime,
												undertime,
												nightdiff,
												remarks,
												attendance,
												date,
												sunday,
												holiday,
												xallow) VALUES";//ADD HOLIDAY HERE
	$AttQuery = "";
	for($counter = 0; $counter < $empNum; $counter++)
	{
		if($AttQuery != "")
		{
			$AttQuery .= ",";
		}

		if(((!empty($_POST['timein1'][$counter]) && !empty($_POST['timeout1'][$counter])) ||
			(!empty($_POST['timein2'][$counter]) && !empty($_POST['timeout2'][$counter]))) || ((empty($_POST['timein2'][$counter]) && empty($_POST['timeout2'][$counter])) && $_POST['attendance'][$counter] == "PRESENT"))
		{

			$empid = $_POST['empid'][$counter];
			$timein1 = $_POST['timein1'][$counter];
			$timeout1 = $_POST['timeout1'][$counter];

			if(!empty($_POST['timein2'][$counter]))//if employee took a halfday
			{
				$timein2 = $_POST['timein2'][$counter];
				$timeout2 = $_POST['timeout2'][$counter];
			}
			else
			{
				$timein2 = "";
				$timeout2 = "";
			}
			if(!empty($_POST['timein3'][$counter]))// if employee has night shift
			{
				$timein3 = $_POST['timein3'][$counter];
				$timeout3 = $_POST['timeout3'][$counter];
			}
			else
			{
				$timein3 = "";
				$timeout3 = "";
			}
			

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
				if($justMins == true && $hasMins == false)
				{
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

				$hasMins = strpos($nightdiff, ",");//Search the string if it has comma
				$justMins = strpos($nightdiff, "mins");
				if($justMins == true && $hasMins == false)
				{
					$nightdiff = "0.".$nightdiff[0].$nightdiff[1];//Gets the first 2 characters
					$nightdiff = str_replace(' ', '', $nightdiff);//removes all the spaces
				}
				else if($hasMins == false)
				{
					$nightdiff = $nightdiff[0].$nightdiff[1];//Gets the first 2 characters
					$nightdiff = str_replace(' ', '', $nightdiff);//removes all the spaces
				}
				else
				{
					$work = explode(",", $nightdiff);//Separates the string
					$hrs = $work[0];//gets the Hours
					$mins = $work[1];//Gets the minutes
					
					$nightdiff = $hrs[0].$hrs[1].".".$mins[1].$mins[2];
					$nightdiff = str_replace(' ', '', $nightdiff);//removes all the spaces
				}

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
			if(!empty($_POST['xallow'][$counter]))
			{
				$xAllowance = mysql_real_escape_string($_POST['xallow'][$counter]);
			}
			else 
			{
				$xAllowance = "";
			}

			$attendance = 2;// 0 - no input / 1 - Absent / 2 - Present / 3 - No work
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			
			$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate, $xAllowance);
		}
		else if($_POST['attendance'][$counter] == "ABSENT")// ABSENT
		{
			//Make algorithm that will check if this employee is AWOL
			$empid = $_POST['empid'][$counter];

			//Make Algorithm that will check if this employee is AWOL
			$Awol = "SELECT * FROM attendance WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$day7', '%M %e, %Y') AND STR_TO_DATE('$day1', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC LIMIT $isSunday";

			$AwolQuery = mysql_query($Awol);
			$AwolCounter = 0;
			$start = null;
			$end = $date;
			$absentCounter = 0;
			while($AwolChecker = mysql_fetch_assoc($AwolQuery))
			{
				if(date('l', strtotime($AwolChecker['date'])) != "Sunday")
				{
					if($AwolChecker['attendance'] == 1)
					{
						$AwolCounter++;
					}
					else
					{
						$AwolCounter = 0;
					}
				}
					
				if($AwolCounter >= 6)
					$start = $AwolChecker['date'];
			}

			if($AwolCounter >= 6)
			{
				//insert to AWOL PENDING
				$AwolPending = "INSERT awol_employees(empid, start_date, end_date, status) 
												VALUES(	'$empid',
														'$start',
														'$end',
														'Pending')";
				mysql_query($AwolPending);//insert AWOL pending to awol_employees
				$emp = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
				$empQuery = mysql_query($emp);
				$empArr = mysql_fetch_assoc($empQuery);
				Print "<script>alert('[".$empArr['lastname'].", ".$empArr['firstname']."] has already accumulated 7 Absences and is now pending for AWOL. Go to Employees tab > Absence Notification')</script>";

				
				$empAwolPending = "UPDATE employee SET employment_status = '2' WHERE empid = '$empid'";
				mysql_query($empAwolPending);//update employment status of employee to 2 = pending
			}

			if($AwolCounter == 3)// Notification if employee accumulated 4 absences
			{
				$checkAbsence = "SELECT * FROM absence_notif WHERE empid = '$empid'";
				$checkAbsenceQuery = mysql_query($checkAbsence);
				if(mysql_num_rows($checkAbsenceQuery) == 0)
				{
					$AbsenceNotif = "INSERT absence_notif(empid) 
												VALUES('$empid')";
					mysql_query($AbsenceNotif);
				}
				$emp = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
				$empQuery = mysql_query($emp);
				$empArr = mysql_fetch_assoc($empQuery);
				Print "<script>alert('ABSENCE NOTICE: [".$empArr['lastname'].", ".$empArr['firstname']."] has already accumulated 4 consecutive Absences.')</script>";
			}

			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
			$timein3 = "";
			$timeout3 = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			$xAllowance = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			if(isset($_POST['xallow'][$counter]))
			{
				$xAllowance = mysql_real_escape_string($_POST['xallow'][$counter]);
			}
			$attendance = 1;// 0 - no input / 1 - Absent / 2 - Present / 3 - No work
			$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1' ";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";

			$attChecker = "SELECT * from attendance WHERE date = '$date' AND empid = '$empid' LIMIT 1";
			$attCheckerQuery = mysql_query($attChecker) or die (mysql_error());

			$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate, $xAllowance);
		}
		else if($_POST['attendance'][$counter] == "NOWORK")
		{
			$empid = $_POST['empid'][$counter];
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
			$timein3 = "";
			$timeout3 = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			$xAllowance = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			if(isset($_POST['xallow'][$counter]))
			{
				$xAllowance = mysql_real_escape_string($_POST['xallow'][$counter]);
			}
			$attendance = 3;// 0 - no input / 1 - Absent / 2 - Present / 3 - No work
			$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1' ";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";

			$attChecker = "SELECT * from attendance WHERE date = '$date' AND empid = '$empid' LIMIT 1";
			$attCheckerQuery = mysql_query($attChecker) or die (mysql_error());

			$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate, $xAllowance );
		}
		else if(empty($_POST['attendance'][$counter]))
		{
			$empid = $_POST['empid'][$counter];
			$timein1 = "";
			$timeout1 = "";
			$timein2 = "";
			$timeout2 = "";
			$timein3 = "";
			$timeout3 = "";
			$workinghrs = "";
			$OtHrs = "";
			$undertime = "";
			$nightdiff = "";
			$remarks = "";
			$xAllowance = "";
			if(isset($_POST['remarks'][$counter]))
			{
				$remarks = mysql_real_escape_string($_POST['remarks'][$counter]);
			}
			if(isset($_POST['xallow'][$counter]))
			{
				$xAllowance = mysql_real_escape_string($_POST['xallow'][$counter]);
			}
			$attendance = 0;// 0 - no input / 1 - Absent / 2 - Present
			$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1' ";
			$employeeQuery = mysql_query($employee);
			$employeeArr = mysql_fetch_assoc($employeeQuery);
			$position = $employeeArr['position'];
			//require "directives/attendance/attendance_query.php";

			$attChecker = "SELECT * from attendance WHERE date = '$date' AND empid = '$empid' LIMIT 1";
			$attCheckerQuery = mysql_query($attChecker);

			$AttQuery = newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate, $xAllowance );
		}
	}
	$FinalQuery = $initialQuery . $AttQuery;
	$queryAttendance = mysql_query($FinalQuery);
}

//require "directives/attendance/attendance_query.php";

Print "<script>window.location.assign('enterattendance.php?position=null&site=". $location."');</script>";

?>












