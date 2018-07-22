<!DOCTYPE html>
<?php
include('directives/session.php');
require_once('directives/db.php');
if(!isset($_GET['site']) && !isset($_GET['position']))
{
	header("location:payroll_login.php");
}


$site = $_GET['site'];
$position = $_GET['position'];
$empid = $_GET['empid'];
// $date = (isset($_SESSION['payrollDate']) ? $_SESSION['payrollDate'] : strftime("%B %d, %Y")); // Gets the payroll date if admin didn't finish the payroll for the week
$date = "July 11, 2018";
// $date = "May 9, 2018";

$time = strftime("%X");//TIME

$day1 = date('F d, Y', strtotime('-1 day', strtotime($date)));
$day2 = date('F d, Y', strtotime('-2 day', strtotime($date)));
$day3 = date('F d, Y', strtotime('-3 day', strtotime($date)));
$day4 = date('F d, Y', strtotime('-4 day', strtotime($date)));
$day5 = date('F d, Y', strtotime('-5 day', strtotime($date)));
$day6 = date('F d, Y', strtotime('-6 day', strtotime($date)));
$day7 = date('F d, Y', strtotime('-7 day', strtotime($date)));
$day8 = date('F d, Y', strtotime('-8 day', strtotime($date)));
$day9 = date('F d, Y', strtotime('-9 day', strtotime($date)));
$day10 = date('F d, Y', strtotime('-10 day', strtotime($date)));
$day11 = date('F d, Y', strtotime('-11 day', strtotime($date)));
$day12 = date('F d, Y', strtotime('-12 day', strtotime($date)));
$day13 = date('F d, Y', strtotime('-13 day', strtotime($date)));
$day14 = date('F d, Y', strtotime('-14 day', strtotime($date)));

// Validate 14 days prior to the payroll day for adjustments
$validateDays = array($day1, $day2, $day3, $day4, $day5, $day6, $day7, $day8, $day9, $day10, $day11, $day12, $day13, $day14);
$disabledDates = "";

function getMonth($month)
{
	switch($month)
	{
		case "January": $output = "1";break;
		case "February": $output = "2";break;
		case "March": $output = "3";break;
		case "April": $output = "4";break;
		case "May": $output = "5";break;
		case "June": $output = "6";break;
		case "July": $output = "7";break;
		case "August": $output = "8";break;
		case "September": $output = "9";break;
		case "October": $output = "10";break;
		case "November": $output = "11";break;
		case "December": $output = "12";break;
	}
	return $output;
}
function getDay($day)
{
	switch($day)
	{
		case "01": $output = "1";break;
		case "02": $output = "2";break;
		case "03": $output = "3";break;
		case "04": $output = "4";break;
		case "05": $output = "5";break;
		case "06": $output = "6";break;
		case "07": $output = "7";break;
		case "08": $output = "8";break;
		case "09": $output = "9";break;
		default: $output = $day;
	}

	return $output;
}

foreach($validateDays as $validate)
{
	$attCheck = "SELECT * FROM attendance WHERE empid = '$empid' AND date = '$validate' AND attendance != '2'";
	$attQuery = mysql_query($attCheck);
	if(mysql_num_rows($attQuery) != 1)
	{
		//Print "<script>console.log('validate: ".$validate."')</script>";
		if($disabledDates != "")
			$disabledDates .= "+";
		$dateArr = explode(' ', $validate);
		$dateMonth = $dateArr[0];// Gets the month
		$dateDay = $dateArr[1];// Gets the day
		$dateYear = $dateArr[2];// Gets the year

		$dateMonth = getMonth($dateMonth);
		$dateDay = substr($dateDay, 0, -1);
		$dateDay = getDay($dateDay);
		$validated = $dateMonth."-".$dateDay."-".$dateYear;
		// Print "<script>console.log('month: ".$dateMonth." \ day: ".$dateDay." \ year: ".$dateYear."')</script>";
		// Print "<script>console.log('disabled: ".$disabledDates."')</script>";
		// $dateDay
		$disabledDates .= $validated;
	}
}




//Holiday Checker
$holiday = "SELECT * FROM holiday WHERE date = '$date'";
$holidayQuery = mysql_query($holiday);
$holidayExist = mysql_num_rows($holidayQuery);
$holidayName = "";
if($holidayExist > 0)
{
	$holidayRow = mysql_fetch_assoc($holidayQuery);
	$holidayName = $holidayRow['holiday'];//Gets holiday name
}
//Sunday Checker
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">

</head>
<body style="font-family: Quicksand;" onload="checkloans()">
	<form action="logic_payroll.php" method="POST" id="payrollForm">
		<div class="container-fluid">

			<!-- Navigation bar -->
			<?php
			require_once("directives/nav.php");
			?>

			<!-- MODALS -->
			<?php
			require_once('directives/modals/addNewVale.php');
			require_once('directives/modals/payrollAdjustment.php');
			?>

			<!-- Breadcrumbs -->
			<input type="hidden" name="employeeID" value="<?php Print $empid?>">
			<div class="row pull-down">
				<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
					<ol class="breadcrumb text-left" style="margin-bottom: 0px">

						<li><a href="payroll_table.php?position=<?php Print $position?>&site=<?php Print $site?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Table of Employees</a></li>
						<li class="active"><?php Print "Payroll for site " .$site." on ".$date ?></li>

						<button type="submit" class="btn btn-success pull-right" style="margin-right:5px" href="#" data-toggle="tooltip" data-placement="bottom" title="Note: Proceeding will prevent you from editing values entered. If you need to come back here and change anything, you will have to redo everything.">Save and compute</button>

						<input type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#attendanceAdjustment" value="Make attendance adjustment">
					</ol>
				</div>

				<!-- Employee information -->
				<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
					<?php
					$employee = "SELECT * FROM employee WHERE empid = '$empid'";
					$employeeQuery = mysql_query($employee);
					$empArr = mysql_fetch_assoc($employeeQuery);
			//For deduction section 4 for 4 weeks in a month
					$deductionSSS = $empArr['sss']/4;
					$deductionPagibig = $empArr['pagibig']/4;
					$deductionPhilhealth = $empArr['philhealth']/4;
					
			//2 decimal places
					$deductionSSS =  numberExactFormat($deductionSSS, 2, '.', true);
					$deductionPagibig = numberExactFormat($deductionPagibig, 2, '.', true);
					$deductionPhilhealth = numberExactFormat($deductionPhilhealth, 2, '.', true);
			//Change to no value string if the employee has no document
					if($deductionSSS == 0)
					{
						$deductionSSS = "";
					}
					else
					{
						$deductionSSS = $deductionSSS." PHP";
					}
					if($deductionPagibig == 0)
					{
						$deductionPagibig = "";
					}
					else
					{
						$deductionPagibig = $deductionPagibig." PHP";
					}
					if($deductionPhilhealth == 0)
					{
						$deductionPhilhealth = "";
					}
					else
					{
						$deductionPhilhealth = $deductionPhilhealth." PHP";
					}
					Print "
					<h2 class='text-left'>". $empArr['lastname'] .", ". $empArr['firstname'] ."</h2>
					<div class='row'>
					<div class='col-md-8 col-lg-8 text-left' style='word-break: keep-all'>
					
					<h4>
					<b style='font-family: QuickSandMed'>
					Employee ID:
					</b>". $empArr['empid'] ."
					</h4>
					<h4>
					<b style='font-family: QuickSandMed'>
					Position:
					</b>". $empArr['position'] ."
					</h4>
					<h4>
					<b style='font-family: QuickSandMed'>
					Address:
					</b>". $empArr['address'] ."
					</h4>
					<h4>
					<b style='font-family: QuickSandMed'>
					Contact Number:
					</b>". $empArr['contactnum'] ."
					</h4>
					</div>";
					Print "
					<div class='col-md-4 col-lg-4 text-right'>";
				if($empArr['philhealth'] != 0)//Phil Health Display
				{
					Print "<h4><span class='glyphicon glyphicon-ok'></span> PhilHealth documents</h4>";
				}
				else
				{
					Print "<h4><span class='glyphicon glyphicon-remove'></span> PhilHealth documents</h4>";
				}
				if($empArr['pagibig'] != 0)//Pagibig Display
				{
					Print "<h4><span class='glyphicon glyphicon-ok'></span> Pag-IBIG documents</h4>";
				}
				else
				{
					Print "<h4><span class='glyphicon glyphicon-remove'></span> Pag-IBIG documents</h4>";
				}
				if($empArr['sss'] != 0)//SSS Display
				{
					Print "<h4><span class='glyphicon glyphicon-ok'></span> SSS documents</h4>";
				}
				else
				{
					Print "<h4><span class='glyphicon glyphicon-remove'></span> SSS documents</h4>";
				}
				Print "</div>
				</div>";
				?>
			</div>


			<!-- Attendance table -->
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
				<table class="table-bordered table-condensed" style="background-color:white;">
					<?php
				//Sample query for debugging purposes
					$payrollDate = "SELECT * FROM attendance WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$day7', '%M %e, %Y') AND STR_TO_DATE('$day1', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 7";
					$payrollQuery = mysql_query($payrollDate);
					//Boolean for the conditions not to repeat just incase the employee does't attend sundays
					$monBool = true;
					$tueBool = true;
					$wedBool = true;
					$thuBool = true;
					$friBool = true;
					$satBool = true;
					$sunBool = true;
					//for absent dates
					$monAbsent = false;
					$tueAbsent = false;
					$wedAbsent = false;
					$thuAbsent = false;
					$friAbsent = false;
					$satAbsent = false;
					$sunAbsent = false;
					$totalHours = 0;//for total work hours
					$totalNightDiff = 0;//for Total night diff
					$totalOT = 0;// for total Overtime
					//for badge of Overtime(OT) and  Night diff(ND)
					$OtMon = false;
					$OtTue = false;
					$OtWed = false;
					$OtThu = false;
					$OtFri = false;
					$OtSat = false;
					$OtSun = false;
					$NdMon = false;
					$NdTue = false;
					$NdWed = false;
					$NdThu = false;
					$NdFri = false;
					$NdSat = false;
					$NdSun = false;
					$allowCounter = 0;//Counter for the allowance
					$holidayName = '';
					$holidayType = '';
					$holidayDate = '';
					$holidayDay = '';
					$holidayCounter = 0;//count the days of holiday
					while($dateRow = mysql_fetch_assoc($payrollQuery))
					{
						$holDateChecker = $dateRow['date'];
						//Holiday Checker
						$holiday = "SELECT * FROM holiday WHERE date = '$holDateChecker' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
						$holidayQuery = mysql_query($holiday);
						$holidayExist = mysql_num_rows($holidayQuery);

						
						if($holidayExist > 0)//if holiday exist
						{
							$holidayRow = mysql_fetch_assoc($holidayQuery);
							$holDay = date('l', strtotime($holidayRow['date']));
							if($holidayCounter > 0)//if holiday lasted for more than 1day
							{
								$holidayCounter++;
								$holidayName .= "+".$holidayRow['holiday'];
								$holidayType .= "+".$holidayRow['type'];
								$holidayDate .= "+".$holidayRow['date'];
								$holidayDay .= "+".$holDay;
							}
							else
							{
								$holidayCounter++;
								$holidayName = $holidayRow['holiday'];//Gets holiday name
								$holidayType = $holidayRow['type'];
								$holidayDate = $holidayRow['date'];
								$holidayDay = $holDay;
							}
						}
						$day = date('l', strtotime($dateRow['date']));
						if($day == "Sunday" && $sunBool)
						{
							$sunDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$hrsComp = explode('.', $dateRow['workhours']);//Get the total workhours
								if(count($hrsComp) == 2)
								{
									$totalHrs = $hrsComp[0];
									$totalHrsMins = $hrsComp[1];
									$totalHrsMins = $totalHrsMins / 60;

									$hrsResult = $totalHrs+$totalHrsMins;

									$totalHours += $hrsResult;
								}
								else
								{
									$totalHours +=  $hrsComp[0];
									$hrsResult = $hrsComp[0];
								}
								
								//Night differential computation
								$NDcomp = explode('.', $dateRow['nightdiff']);//Get the total Overtime
								if(count($NDcomp) == 2)
								{
									$NDhours = $NDcomp[0];
									$NDmins = $NDcomp[1];
									$NDcomp = $NDmins / 60;

									$NDresult = $NDhours+$NDcomp;

									$totalNightDiff += $NDresult;
								}
								else
								{
									$totalNightDiff +=  $NDcomp[0];
								}

								$OTcomp = explode('.', $dateRow['overtime']);//Get the total Overtime
								// if(count($OTcomp) == 2)
								// {
								// 	$OThours = $OTcomp[0];
								// 	$OTmins = $OTcomp[1];
								// 	$OTcomp = $OTmins / 60;

								// 	$OTresult = $OThours+$OTcomp;

								// 	$totalOT += $OTresult;
								// }
								// else
								// {
								// 	$totalOT +=  $OTcomp[0];
								// 	// $OTresult = $OTcomp[0];
								// }
								

								$sunTimeIn = $dateRow['timein'];
								$sunTimeOut = $dateRow['timeout'];
								$ABsunTimeIn = $dateRow['afterbreak_timein'];
								$ABsunTimeOut = $dateRow['afterbreak_timeout'];
								$NSsunTimeIn = $dateRow['nightshift_timein'];
								$NSsunTimeOut = $dateRow['nightshift_timeout'];

								$sunWorkHrs = $dateRow['workhours'];//Get the workhours
								$sunNDHrs = $dateRow['nightdiff'];//Get the night diff
								$sunOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdSun = true;
								if($dateRow['overtime'] != 0)
									$OtSun = true;
								$allowCounter++; //Counter for allowance
							}
							else
							{
								$sunAbsent = true;
							}
							$sunBool = false;
						}
						else if($day == "Monday" && $monBool)// Monday
						{
							$monDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$hrsComp = explode('.', $dateRow['workhours']);//Get the total workhours
								if(count($hrsComp) == 2)
								{
									$totalHrs = $hrsComp[0];
									$totalHrsMins = $hrsComp[1];
									$totalHrsMins = $totalHrsMins / 60;

									$hrsResult = $totalHrs+$totalHrsMins;

									$totalHours += $hrsResult;
								}
								else
								{
									$totalHours +=  $hrsComp[0];
									$hrsResult = $hrsComp[0];
								}

								//Night differential computation
								$NDcomp = explode('.', $dateRow['nightdiff']);//Get the total Overtime
								if(count($NDcomp) == 2)
								{
									$NDhours = $NDcomp[0];
									$NDmins = $NDcomp[1];
									$NDcomp = $NDmins / 60;

									$NDresult = $NDhours+$NDcomp;

									$totalNightDiff += $NDresult;
								}
								else
								{
									$totalNightDiff +=  $NDcomp[0];
								}

								//Overtime Computation
								$OTcomp = explode('.', $dateRow['overtime']);//Get the total Overtime)
								if(count($OTcomp) == 2)
								{
									$OThours = $OTcomp[0];
									$OTmins = $OTcomp[1];
									$OTcomp = $OTmins / 60;

									$OTresult = $OThours+$OTcomp;

									$totalOT += $OTresult;
								}
								else
								{
									$totalOT +=  $OTcomp[0];
									$OTresult = $OTcomp[0];
								}
								

								$monTimeIn = $dateRow['timein'];
								$monTimeOut = $dateRow['timeout'];
								$ABmonTimeIn = $dateRow['afterbreak_timein'];
								$ABmonTimeOut = $dateRow['afterbreak_timeout'];
								$NSmonTimeIn = $dateRow['nightshift_timein'];
								$NSmonTimeOut = $dateRow['nightshift_timeout'];

								$monWorkHrs = $dateRow['workhours'];//Get the workhours
								$monNDHrs = $dateRow['nightdiff'];//Get the night diff
								$monOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdMon = true;
								if($dateRow['overtime'] != 0)
									$OtMon = true;
								$allowCounter++; //Counter for allowance
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$monAbsent = true;
							}
							$monBool = false;
							
						}
						else if($day == "Tuesday" && $tueBool)//Tuesday
						{
							$tueDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$hrsComp = explode('.', $dateRow['workhours']);//Get the total workhours
								if(count($hrsComp) == 2)
								{
									$totalHrs = $hrsComp[0];
									$totalHrsMins = $hrsComp[1];
									$totalHrsMins = $totalHrsMins / 60;

									$hrsResult = $totalHrs+$totalHrsMins;

									$totalHours += $hrsResult;
								}
								else
								{
									$totalHours +=  $hrsComp[0];
									$hrsResult = $hrsComp[0];
								}

								//Night differential computation
								$NDcomp = explode('.', $dateRow['nightdiff']);//Get the total Overtime
								if(count($NDcomp) == 2)
								{
									$NDhours = $NDcomp[0];
									$NDmins = $NDcomp[1];
									$NDcomp = $NDmins / 60;

									$NDresult = $NDhours+$NDcomp;

									$totalNightDiff += $NDresult;
								}
								else
								{
									$totalNightDiff +=  $NDcomp[0];
								}
								
								//Overtime computation
								$OTcomp = explode('.', $dateRow['overtime']);//Get the total Overtime)
								if(count($OTcomp) == 2)
								{
									$OThours = $OTcomp[0];
									$OTmins = $OTcomp[1];
									$OTcomp = $OTmins / 60;

									$OTresult = $OThours+$OTcomp;

									$totalOT += $OTresult;
								}
								else
								{
									$totalOT +=  $OTcomp[0];
									$OTresult = $OTcomp[0];
								}

								$tueTimeIn = $dateRow['timein'];
								$tueTimeOut = $dateRow['timeout'];
								$ABtueTimeIn = $dateRow['afterbreak_timein'];
								$ABtueTimeOut = $dateRow['afterbreak_timeout'];
								$NStueTimeIn = $dateRow['nightshift_timein'];
								$NStueTimeOut = $dateRow['nightshift_timeout'];
								
								$tueWorkHrs = $dateRow['workhours'];//Get the workhours
								$tueNDHrs = $dateRow['nightdiff'];//Get the night diff
								$tueOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0){
									$NdTue = true;
								}
								if($dateRow['overtime'] != 0){
									$OtTue = true;
								}
								$allowCounter++; //Counter for allowance
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$tueAbsent = true;
							}
							$tueBool = false;
						}
						else if($day == "Wednesday" && $wedBool)//Wednesday
						{
							$wedDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$hrsComp = explode('.', $dateRow['workhours']);//Get the total workhours
								if(count($hrsComp) == 2)
								{
									$totalHrs = $hrsComp[0];
									$totalHrsMins = $hrsComp[1];
									$totalHrsMins = $totalHrsMins / 60;

									$hrsResult = $totalHrs+$totalHrsMins;

									$totalHours += $hrsResult;
								}
								else
								{
									$totalHours +=  $hrsComp[0];
									$hrsResult = $hrsComp[0];
								}

								//Night differential computation
								$NDcomp = explode('.', $dateRow['nightdiff']);//Get the total Overtime
								if(count($NDcomp) == 2)
								{
									$NDhours = $NDcomp[0];
									$NDmins = $NDcomp[1];
									$NDcomp = $NDmins / 60;

									$NDresult = $NDhours+$NDcomp;

									$totalNightDiff += $NDresult;
								}
								else
								{
									$totalNightDiff +=  $NDcomp[0];
								}
								
								//Overtime computation
								$OTcomp = explode('.', $dateRow['overtime']);//Get the total Overtime)
								if(count($OTcomp) == 2)
								{
									$OThours = $OTcomp[0];
									$OTmins = $OTcomp[1];
									$OTcomp = $OTmins / 60;

									$OTresult = $OThours+$OTcomp;

									$totalOT += $OTresult;
								}
								else
								{
									$totalOT +=  $OTcomp[0];
									$OTresult = $OTcomp[0];
								}

								$wedTimeIn = $dateRow['timein'];
								$wedTimeOut = $dateRow['timeout'];
								$ABwedTimeIn = $dateRow['afterbreak_timein'];
								$ABwedTimeOut = $dateRow['afterbreak_timeout'];
								$NSwedTimeIn = $dateRow['nightshift_timein'];
								$NSwedTimeOut = $dateRow['nightshift_timeout'];

								$wedWorkHrs = $dateRow['workhours'];//Get the workhours
								$wedNDHrs = $dateRow['nightdiff'];//Get the night diff
								$wedOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if(!empty($dateRow['nightdiff']))
									$NdWed = true;
								if(!empty($dateRow['overtime']))
									$OtWed = true;
								$allowCounter++; //Counter for allowance
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$wedAbsent = true;
							}
							$wedBool = false;
							
						}
						else if($day == "Thursday" && $thuBool)//Thursday
						{
							$thuDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$hrsComp = explode('.', $dateRow['workhours']);//Get the total workhours
								if(count($hrsComp) == 2)
								{
									$totalHrs = $hrsComp[0];
									$totalHrsMins = $hrsComp[1];
									$totalHrsMins = $totalHrsMins / 60;


									$hrsResult = $totalHrs+$totalHrsMins;

									$totalHours += $hrsResult;
								}
								else
								{
									$totalHours +=  $hrsComp[0];
									$hrsResult = $hrsComp[0];
								}

								//Night differential computation
								$NDcomp = explode('.', $dateRow['nightdiff']);//Get the total Overtime
								if(count($NDcomp) == 2)
								{
									$NDhours = $NDcomp[0];
									$NDmins = $NDcomp[1];
									$NDcomp = $NDmins / 60;

									$NDresult = $NDhours+$NDcomp;

									$totalNightDiff += $NDresult;
								}
								else
								{
									$totalNightDiff +=  $NDcomp[0];
								}
								
								//Overtime computation
								$OTcomp = explode('.', $dateRow['overtime']);//Get the total Overtime)
								if(count($OTcomp) == 2)
								{
									$OThours = $OTcomp[0];
									$OTmins = $OTcomp[1];
									$OTcomp = $OTmins / 60;

									$OTresult = $OThours+$OTcomp;

									$totalOT += $OTresult;
								}
								else
								{
									$totalOT +=  $OTcomp[0];
									$OTresult = $OTcomp[0];
								}

								$thuTimeIn = $dateRow['timein'];
								$thuTimeOut = $dateRow['timeout'];
								$ABthuTimeIn = $dateRow['afterbreak_timein'];
								$ABthuTimeOut = $dateRow['afterbreak_timeout'];
								$NSthuTimeIn = $dateRow['nightshift_timein'];
								$NSthuTimeOut = $dateRow['nightshift_timeout'];

								$thuWorkHrs = $dateRow['workhours'];//Get the workhours
								$thuNDHrs = $dateRow['nightdiff'];//Get the night diff
								$thuOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdThu = true;
								if($dateRow['overtime'] != 0)
									$OtThu = true;
								$allowCounter++; //Counter for allowance
								
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$thuAbsent = true;
							}
							$thuBool = false;
						}
						else if($day == "Friday" && $friBool)//Friday
						{	
							$friDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$hrsComp = explode('.', $dateRow['workhours']);//Get the total workhours
								if(count($hrsComp) == 2)
								{
									$totalHrs = $hrsComp[0];
									$totalHrsMins = $hrsComp[1];
									$totalHrsMins = $totalHrsMins / 60;

									$hrsResult = $totalHrs+$totalHrsMins;

									$totalHours += $hrsResult;
								}
								else
								{
									$totalHours +=  $hrsComp[0];
									$hrsResult = $hrsComp[0];
								}

								//Night differential computation
								$NDcomp = explode('.', $dateRow['nightdiff']);//Get the total Overtime
								if(count($NDcomp) == 2)
								{
									$NDhours = $NDcomp[0];
									$NDmins = $NDcomp[1];
									$NDcomp = $NDmins / 60;

									$NDresult = $NDhours+$NDcomp;

									$totalNightDiff += $NDresult;
								}
								else
								{
									$totalNightDiff +=  $NDcomp[0];
								}
								
								//Overtime computation
								$OTcomp = explode('.', $dateRow['overtime']);//Get the total Overtime)
								if(count($OTcomp) == 2)
								{
									$OThours = $OTcomp[0];
									$OTmins = $OTcomp[1];
									$OTcomp = $OTmins / 60;

									$OTresult = $OThours+$OTcomp;

									$totalOT += $OTresult;
								}
								else
								{
									$totalOT +=  $OTcomp[0];
									$OTresult = $OTcomp[0];
								}

								$friTimeIn = $dateRow['timein'];
								$friTimeOut = $dateRow['timeout'];
								$ABfriTimeIn = $dateRow['afterbreak_timein'];
								$ABfriTimeOut = $dateRow['afterbreak_timeout'];
								$NSfriTimeIn = $dateRow['nightshift_timein'];
								$NSfriTimeOut = $dateRow['nightshift_timeout'];

								$friWorkHrs = $dateRow['workhours'];//Get the workhours
								$friNDHrs = $dateRow['nightdiff'];//Get the night diff
								$friOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdFri = true;
								if($dateRow['overtime'] != 0)
									$OtFri = true;
								$allowCounter++; //Counter for allowance
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$friAbsent = true;
							}
							$friBool = false;
						}
						else if($day == "Saturday" && $satBool)//Saturday
						{
							$satDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$hrsComp = explode('.', $dateRow['workhours']);//Get the total workhours
								if(count($hrsComp) == 2)
								{
									$totalHrs = $hrsComp[0];
									$totalHrsMins = $hrsComp[1];
									$totalHrsMins = $totalHrsMins / 60;

									$hrsResult = $totalHrs+$totalHrsMins;

									$totalHours += $hrsResult;
								}
								else
								{
									$totalHours +=  $hrsComp[0];
									$hrsResult = $hrsComp[0];
								}

								//Night differential computation
								$NDcomp = explode('.', $dateRow['nightdiff']);//Get the total Overtime
								if(count($NDcomp) == 2)
								{
									$NDhours = $NDcomp[0];
									$NDmins = $NDcomp[1];
									$NDcomp = $NDmins / 60;

									$NDresult = $NDhours+$NDcomp;

									$totalNightDiff += $NDresult;
								}
								else
								{
									$totalNightDiff +=  $NDcomp[0];
								}
								
								//Overtime computation
								$OTcomp = explode('.', $dateRow['overtime']);//Get the total Overtime)
								if(count($OTcomp) == 2)
								{
									$OThours = $OTcomp[0];
									$OTmins = $OTcomp[1];
									$OTcomp = $OTmins / 60;

									$OTresult = $OThours+$OTcomp;

									$totalOT += $OTresult;

								}
								else
								{
									$totalOT +=  $OTcomp[0];
									$OTresult = $OTcomp[0];
								}

								$satTimeIn = $dateRow['timein'];
								$satTimeOut = $dateRow['timeout'];
								$ABsatTimeIn = $dateRow['afterbreak_timein'];
								$ABsatTimeOut = $dateRow['afterbreak_timeout'];
								$NSsatTimeIn = $dateRow['nightshift_timein'];
								$NSsatTimeOut = $dateRow['nightshift_timeout'];

								$satWorkHrs = $dateRow['workhours'];//Get the workhours
								$satNDHrs = $dateRow['nightdiff'];//Get the night diff
								$satOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdSat = true;
								if($dateRow['overtime'] != 0)
									$OtSat = true;
								$allowCounter++; //Counter for allowance
								
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$satAbsent = true;
							}
							$satBool = false;
						}	
						
					}

					$payrollRow = "2";//For payroll column if employee has NIGHTSHIFT which will trigger how much the column span needed
					if(	!empty($NSsatTimeIn) || 
						!empty($NSsunTimeIn) || 
						!empty($NSmonTimeIn) || 
						!empty($NStueTimeIn) || 
						!empty($NSwedTimeIn) || 
						!empty($NSthuTimeIn) || 
						!empty($NSfriTimeIn) || 
						!empty($NSsunTimeIn))
					{
						$payrollRow = "3";
					}

					$holMon = false; 
					$holTue = false; 
					$holWed = false; 
					$holThu = false; 
					$holFri = false; 
					$holSat = false;
					$holSun = false;
					if($holidayCounter > 1)//Output as Hidden Name, Type, Date of holiday 
					{
						$holNameArr = explode("+", $holidayName);
						$holTypeArr = explode("+", $holidayType);
						$holDateArr = explode("+", $holidayDate);
						$holDayArr = explode("+", $holidayDay);
						$holidayCounter -= 1;
						for($a = 0; $a <= $holidayCounter; $a++)
						{
							Print "<input type='hidden' name='holidayName[]' value='".$holNameArr[$a]."'>";
							Print "<input type='hidden' name='holidayType[]' value='".$holTypeArr[$a]."'>";
							Print "<input type='hidden' name='holidayDate[]' value='".$holDateArr[$a]."'>";
							if($holDayArr[$a] == "Monday")
								$holMon = true; 
							else if($holDayArr[$a] == "Tuesday")
								$holTue = true; 
							else if($holDayArr[$a] == "Wednesday")
								$holWed = true; 
							else if($holDayArr[$a] == "Thursday")
								$holThu = true; 
							else if($holDayArr[$a] == "Friday")
								$holFri = true; 
							else if($holDayArr[$a] == "Saturday")
								$holSat = true; 
							else if($holDayArr[$a] == "Sunday")
								$holSun = true; 
						}
					}
					else if($holidayCounter == 1)//if holiday only lasted 1 day
					{	
						
						if($holidayDay == "Monday")
							$holMon = true; 
						else if($holidayDay == "Tuesday")
							$holTue = true; 
						else if($holidayDay == "Wednesday")
							$holWed = true; 
						else if($holidayDay == "Thursday")
							$holThu = true; 
						else if($holidayDay == "Friday")
							$holFri = true; 
						else if($holidayDay == "Saturday")
							$holSat = true; 
						else if($holidayDay == "Sunday")
							$holSun = true; 
						//Print "<script>alert('".$holidayName."')</script>";
						Print "<input type='hidden' name='holidayName[]' value='".$holidayName."'>";
						Print "<input type='hidden' name='holidayType[]' value='".$holidayType."'>";
						Print "<input type='hidden' name='holidayDate[]' value='".$holidayDate."'>";
					}
					
					?>
					<tr style="white-space: nowrap">
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $day7 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $day6 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $day5 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $day4 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $day3 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $day2 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $day1 ?></td>
					</tr>
					<tr>
						<td colspan="2">Wednesday</td>
						<td colspan="2">Thursday</td>
						<td colspan="2">Friday</td>
						<td colspan="2">Saturday</td>
						<td colspan="2">Sunday</td>
						<td colspan="2">Monday</td>
						<td colspan="2">Tuesday</td>
					</tr>
					<tr>
						<?php
						if(!$wedAbsent)
						{
							if(isset($wedTimeIn) && isset($wedTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($wedTimeIn) ."</td>
								<td>Time Out:<br>". trim($wedTimeOut) ."</td>
								<input type='hidden' name='wedWorkHrs' value='".$wedWorkHrs."'>";
								
								if($wedNDHrs != 0)
								{
									Print "<input type='hidden' name='wedNDHrs' value='".$wedNDHrs."'>";
								}
								if($wedOTHrs != 0)
								{
									Print "<input type='hidden' name='wedOTHrs' value='".$wedOTHrs."'>";
								}
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='".$payrollRow."' class='danger'> Absent </td>";
						}
						if(!$thuAbsent)
						{
							if(isset($thuTimeIn) && isset($thuTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($thuTimeIn) ."</td>
								<td>Time Out:<br>". trim($thuTimeOut) ."</td>
								<input type='hidden' name='thuWorkHrs' value='".$thuWorkHrs."'>";
								if($thuNDHrs != 0)
								{
									Print "<input type='hidden' name='thuNDHrs' value='".$thuNDHrs."'>";
								}
								if($thuOTHrs != 0)
								{
									Print "<input type='hidden' name='thuOTHrs' value='".$thuOTHrs."'>";
								}
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='".$payrollRow."' class='danger'> Absent </td>";
						}
						if(!$friAbsent)
						{
							if(isset($friTimeIn) && isset($friTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($friTimeIn) ."</td>
								<td>Time Out:<br>". trim($friTimeOut) ."</td>
								<input type='hidden' name='friWorkHrs' value='".$friWorkHrs."'>";
								if($friNDHrs != 0)
								{
									Print "<input type='hidden' name='friNDHrs' value='".$friNDHrs."'>";
								}
								if($friOTHrs != 0)
								{
									Print "<input type='hidden' name='friOTHrs' value='".$friOTHrs."'>";
								}
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='".$payrollRow."' class='danger'> Absent </td>";
						}
						if(!$satAbsent)
						{
							if(isset($satTimeIn) && isset($satTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($satTimeIn) ."</td>
								<td>Time Out:<br>". trim($satTimeOut) ."</td>
								<input type='hidden' name='satWorkHrs' value='".$satWorkHrs."'>";
								if($satNDHrs !=  0)
								{
									Print "<input type='hidden' name='satNDHrs' value='".$satNDHrs."'>";
								}
								if($satOTHrs != 0)
								{
									Print "<input type='hidden' name='satOTHrs' value='".$satOTHrs."'>";
								}
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='".$payrollRow."' class='danger'> Absent </td>";
						}
						if(!$sunAbsent)
						{
							if(isset($sunTimeIn) && isset($sunTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($sunTimeIn) ."</td>
								<td>Time Out:<br>". trim($sunTimeOut) ."</td>
								<input type='hidden' name='sunWorkHrs' value='".$sunWorkHrs."'>";
								if($sunNDHrs !=  0)
								{
									Print "<input type='hidden' name='sunNDHrs' value='".$sunNDHrs."'>";
								}
								if($sunOTHrs != 0)
								{
									Print "<input type='hidden' name='sunOTHrs' value='".$sunOTHrs."'>";
								}
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Day off </td>";
							}
							
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='".$payrollRow."' class='danger'> Day off </td>";
						}
						if(!$monAbsent)
						{
							if(isset($monTimeIn) && isset($monTimeIn))
							{
								Print 	"	<td>Time In:<br>". trim($monTimeIn) ."</td>
								<td>Time Out:<br>". trim($monTimeOut) ."</td>
								<input type='hidden' name='monWorkHrs' value='".$monWorkHrs."'>";
								if($monNDHrs != 0)
								{
									Print "<input type='hidden' name='monNDHrs' value='".$monNDHrs."'>";
								}
								if($monOTHrs != 0)
								{
									Print "<input type='hidden' name='monOTHrs' value='".$monOTHrs."'>";
								}
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='".$payrollRow."' class='danger'> Absent </td>";
						}
						if(!$tueAbsent)
						{
							if(isset($tueTimeIn) && isset($tueTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($tueTimeIn) ."</td>
								<td>Time Out:<br>". trim($tueTimeOut) ."</td>
								<input type='hidden' name='tueWorkHrs' value='".$tueWorkHrs."'>";
								if($tueNDHrs != 0)
								{
									Print "<input type='hidden' name='tueNDHrs' value='".$tueNDHrs."'>";
								}
								if($tueOTHrs != 0)
								{
									Print "<input type='hidden' name='tueOTHrs' value='".$tueOTHrs."'>";
								}
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='".$payrollRow."' class='danger'> Absent </td>";
						}

						?>
					</tr>
					<tr> <!-- ================ AFTER BREAK TIME IN AND TIME OUT ================ -->
						<?php
						if(!$wedAbsent)
						{
							//if Halfday
							if(!empty($ABwedTimeIn) && !empty($ABwedTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($ABwedTimeIn) ."</td>
								<td>Time Out:<br>". trim($ABwedTimeOut) ."</td>";
							}
							else if (isset($wedTimeIn) && isset($wedTimeOut))
							{
								Print 	"	<td colspan='2'>Half Day/Straight</td>";
							}
							
						}
						if(!$thuAbsent)
						{
							//if halfday
							if(!empty($ABthuTimeIn) && !empty($ABthuTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($ABthuTimeIn) ."</td>
								<td>Time Out:<br>". trim($ABthuTimeOut) ."</td>";
							}
							else if (isset($thuTimeIn) && isset($thuTimeOut))
							{
								Print 	"	<td colspan='2'>Half Day/Straight</td>";
							}
						}
						if(!$friAbsent)
						{
							//if halfday
							if(!empty($ABfriTimeOut) && !empty($ABfriTimeIn))
							{
								Print 	"	<td>Time In:<br>". trim($ABfriTimeIn) ."</td>
								<td>Time Out:<br>". trim($ABfriTimeOut) ."</td>";
							}
							else if (isset($friTimeOut) && isset($friTimeIn))
							{
								Print 	"	<td colspan='2'>Half Day/Straight</td>";
							}
						}
						if(!$satAbsent)
						{
							//if halfday
							if(!empty($ABsatTimeIn) && !empty($ABsatTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($ABsatTimeIn) ."</td>
								<td>Time Out:<br>". trim($ABsatTimeOut) ."</td>";
							}
							else if (isset($satTimeIn) && isset($satTimeOut))
							{
								Print 	"	<td colspan='2'>Half Day/Straight</td>";
							}
						}
						if(!$sunAbsent)
						{
							//If Admin didnt input attendance on sunday
							if(isset($sunTimeIn) && isset($sunTimeOut))
							{
							//if halfday
								Print "<script>console.log('ABsunTimeIn: ".$ABsunTimeIn."/ ABsunTimeOut: ".$ABsunTimeOut."')</script>";
								if(!empty($ABsunTimeIn) && !empty($ABsunTimeOut))
								{
									Print 	"	<td>Time In:<br>". trim($ABsunTimeIn) ."</td>
									<td>Time Out:<br>". trim($ABsunTimeOut) ."</td>";
								}
								else
								{
									Print 	"	<td colspan='2'>Half Day/Straight</td>";
								}
							}
						}
						if(!$monAbsent)
						{
							//if halfday
							if(!empty($ABmonTimeIn) && !empty($ABmonTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($ABmonTimeIn) ."</td>
								<td>Time Out:<br>". trim($ABmonTimeOut) ."</td>";
							}
							else if (isset($monTimeIn) && isset($monTimeOut))
							{
								Print 	"	<td colspan='2'>Half Day/Straight</td>";
							}
						}
						if(!$tueAbsent)
						{
							//if halfday
							if(!empty($ABtueTimeIn) && !empty($ABtueTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($ABtueTimeIn) ."</td>
								<td>Time Out:<br>". trim($ABtueTimeOut) ."</td>";
							}
							else if (isset($tueTimeIn) && isset($tueTimeOut))
							{
								Print 	"	<td colspan='2'>Half Day/Straight</td>";
							}
						}

						?>
					</tr>
					<!--  -------------------- NIGHTSHIFT TIME IN AND TIME OUT -------------------- -->
						<?php
						if($payrollRow != '2')// if employee has nightshift
						{
							Print "<tr>";
							if(!$wedAbsent)
							{
								//if there's nightshift
								if(!empty($NSwedTimeIn) && !empty($NSwedTimeOut))
								{
									Print 	"	<td>Time In:<br>". trim($NSwedTimeIn) ."</td>
									<td>Time Out:<br>". trim($NSwedTimeOut) ."</td>";
								}
								else if($payrollRow == '3')
								{
									Print 	"	<td colspan='2'></td>";
								}
							}
							if(!$thuAbsent)
							{
								//if there's nightshift
								if(!empty($NSthuTimeIn) && !empty($NSthuTimeOut))
								{

									Print 	"	<td>Time In:<br>". trim($NSthuTimeIn) ."</td>
									<td>Time Out:<br>". trim($NSthuTimeOut) ."</td>";
								}
								else if($payrollRow == '3')
								{
									Print 	"	<td colspan='2'></td>";
								}
							}
							if(!$friAbsent)
							{
								//if there's nightshift
								if(!empty($NSfriTimeOut) && !empty($NSfriTimeIn))
								{
									Print 	"	<td>Time In:<br>". trim($NSfriTimeIn) ."</td>
									<td>Time Out:<br>". trim($NSfriTimeOut) ."</td>";
								}
								else if($payrollRow == '3')
								{
									Print 	"	<td colspan='2'></td>";
								}
							}
							if(!$satAbsent)
							{
								//if there's nightshift
								if(!empty($NSsatTimeIn) && !empty($NSsatTimeOut))
								{
									Print 	"	<td>Time In:<br>". trim($NSsatTimeIn) ."</td>
									<td>Time Out:<br>". trim($NSsatTimeOut) ."</td>";
								}
								else if($payrollRow == '3')
								{
									Print 	"	<td colspan='2'></td>";
								}
							}
							if(!$sunAbsent)
							{
								//If Admin didnt input attendance on sunday
								if(isset($sunTimeIn) && isset($sunTimeOut))
								{
								//if they have nightshift
									if(!empty($NSsunTimeIn) && !empty($NSsunTimeOut))
									{
										Print 	"	<td>Time In:<br>". trim($NSsunTimeIn) ."</td>
										<td>Time Out:<br>". trim($NSsunTimeOut) ."</td>";
									}
									else if($payrollRow == '3')
									{
										Print 	"	<td colspan='2'></td>";
									}

								}
							}
							if(!$monAbsent)
							{
								//if there's nightshift
								if(!empty($NSmonTimeIn) && !empty($NSmonTimeOut))
								{
									Print 	"	<td>Time In:<br>". trim($NSmonTimeIn) ."</td>
									<td>Time Out:<br>". trim($NSmonTimeOut) ."</td>";
								}
								else if($payrollRow == '3')
								{
									Print 	"	<td colspan='2'></td>";
								}
							}
							if(!$tueAbsent)
							{
								//if there's nightshift
								if(!empty($NStueTimeIn) && !empty($NStueTimeOut))
								{
									Print 	"	<td>Time In:<br>". trim($NStueTimeIn) ."</td>
									<td>Time Out:<br>". trim($NStueTimeOut) ."</td>";
								}
								else if($payrollRow == '3')
								{
									Print 	"	<td colspan='2'></td>";
								}
							}
							Print "</tr>";
						}


						if(	$holWed || $OtWed || $NdWed ||
							$holThu || $OtThu || $NdThu ||
							$holFri || $OtFri || $NdFri ||
							$holSat || $OtSat || $NdSat ||
							$holSun || $OtSun || $NdSun ||
							$holMon || $OtMon || $NdMon ||
							$holTue || $OtTue || $NdTue)
						{
							Print "	<tr>
										<td class='nopadding' colspan='2'>
											";
							if($holWed)
								Print 	 "	<span class='label block label-success'>Holiday</span>";
							if($OtWed)
								Print 	"	<span class='label block label-primary'>Overtime</span>";
							if($NdWed)
								Print 	"	<span class='label block label-warning'>Night Differential</span>";
							Print "			
										</td>
										<td class='nopadding' colspan='2'>
											";
							if($holThu)
								Print 	 "	<span class='label block label-success'>Holiday</span>";
							if($OtThu)
								Print 	"	<span class='label block label-primary'>Overtime</span>";
							if($NdThu)
								Print 	"	<span class='label block label-warning'>Night Differential</span>";
							
								Print "			
										</td>
										<td class='nopadding' colspan='2'>
											";
							
							if($holFri)
								Print 	 "	<span class='label block label-success'>Holiday</span>";
							if($OtFri)
								Print 	"	<span class='label block label-primary'>Overtime</span>";
							if($NdFri)
								Print 	"	<span class='label block label-warning'>Night Differential</span>";
							
								Print "		
										</td>
										<td class='nopadding' colspan='2'>
											";
							
							if($holSat)
								Print 	 "<span class='label block label-success'>Holiday</span>";
							if($OtSat)
								Print 	"<span class='label block label-primary'>Overtime</span>";
							if($NdSat)
								Print 	"<span class='label block label-warning'>Night Differential</span>";

								Print "		
										</td>
										<td class='nopadding' colspan='2'>
										";
							
							if($holSun)
								Print 	 "<span class='label block label-success'>Holiday</span>";
							if($OtSun)
								Print 	"<span class='label block label-primary'>Overtime</span>";
							if($NdSun)
								Print 	"<span class='label block label-warning'>Night Differential</span>";
						
								Print "		
										</td>
										<td class='nopadding' colspan='2'>
											";
							
							if($holMon)
								Print 	"<span class='label block label-success'>Holiday</span>"; 
							if($OtMon)
								Print 	"<span class='label block label-primary'>Overtime</span>"; 
							if($NdMon)
								Print 	"<span class='label block label-warning'>Night Differential</span>"; 
						
								Print "		
										</td>
										<td class='nopadding' colspan='2'>
											";
						
							if($holTue)
								Print 	 "<span class='label block label-success'>Holiday</span>";
							if($OtTue)
								Print 	"<span class='label block label-primary'>Overtime</span>";
							if($NdTue)
								Print 	"<span class='label block label-warning'>Night Differential</span>";
						
								Print "		
										</td>";
							Print "</tr>";
						}
						?>
				

		</table>
	</div>

	<!-- Summary of attendance -->
	<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
		<div class="panel">
			<table class="table table-bordered table-responsive">
				<tr>
					<td style="background-color: peachpuff">
						<h4>Total hours rendered: <?php Print $totalHours ?></h4>
					</td>
					<td style="background-color: lemonchiffon">
						<h4>Total overtime: <?php Print $totalOT ?></h4>
					</td>
					<td style="background-color: powderblue">
						<h4>Total night differential: <?php Print $totalNightDiff ?></h4>
					</td>
					<input type="hidden" name="totalOverTime" value="<?php Print $totalOT?>">
					<input type="hidden" name="totalNightDiff" value="<?php Print $totalNightDiff?>">
				</tr>
			</table>


			<!-- Deductions to be made -->
			<!-- LOANS -->
			<div class="row">
				<div class="col-md-2 col-lg-2">
					<h4>Loans</h4>
					<?php
					// 		//this is to check if employee has multiple new vales in a week
					// $day1 = $date;
					// $day2 = date('F d, Y', strtotime('-1 day', strtotime($date)));
					// $day3 = date('F d, Y', strtotime('-2 day', strtotime($date)));
					// $day4 = date('F d, Y', strtotime('-3 day', strtotime($date)));
					// $day5 = date('F d, Y', strtotime('-4 day', strtotime($date)));
					// $day6 = date('F d, Y', strtotime('-5 day', strtotime($date)));
					// $day7 = date('F d, Y', strtotime('-6 day', strtotime($date)));
					// $days = array("$day1","$day2","$day3","$day4","$day5","$day6","$day7");
					// $newVale = 0;
					// foreach($days as $checkDay)
					// {
								//Check if overall attendance for a certain site is done
						// $loanChecker = "SELECT * FROM loans WHERE date = '$checkDay' AND type = 'newVale' AND empid = '$empid'";
						// $loanCheckerQuery = mysql_query($loanChecker);
						// if($loanCheckerQuery)
						// {
						// 	$newValeNum = mysql_num_rows($loanCheckerQuery);
						// 	if($newValeNum != 0)
						// 	{
						// 		if($newValeNum > 1)
						// 		{
						// 			while($newValeArr = mysql_fetch_assoc($loanCheckerQuery))
						// 			{
						// 				if($newValeNum > 1)
						// 				{
						// 					while($newValeArr = mysql_fetch_assoc($loanCheckerQuery))
						// 					{
						// 						$newVale += $newValeArr['amount'];
						// 					}
						// 				}
						// 				else
						// 				{
						// 					$newValeRow = mysql_fetch_assoc($loanCheckerQuery);
						// 					$newVale += $newValeRow['amount'];
						// 				}
						// 			}
						// 		}
						// 		else
						// 		{
						// 			$newValeRow = mysql_fetch_assoc($loanCheckerQuery);
						// 			$newVale += $newValeRow['amount'];
						// 		}
						// 	}
						// }
					// }
					
					$getSSS = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'SSS' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1";
					$getPAGIBIG = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'PagIBIG' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1";
					
					$getOldVALE = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC  LIMIT 1";
					$getNewVALE = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'newVale' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC  LIMIT 1";
							//Query
					$sssQuery = mysql_query($getSSS);
					$pagibigQuery = mysql_query($getPAGIBIG);
					$oldValeQuery = mysql_query($getOldVALE);
					$newValeQuery = mysql_query($getNewVALE);

							//SSS Loan
					if(mysql_num_rows($sssQuery) > 0)
					{
						$sssArr = mysql_fetch_assoc($sssQuery);

						if($sssArr['balance'] > 0)
							$sss = $sssArr['balance'];
						else
							$sss = "N/A";
					}
					else
					{
						$sss = "N/A";
					}
							//Pagibig Loan
					if(mysql_num_rows($pagibigQuery) > 0)
					{
						$pagibigArr = mysql_fetch_assoc($pagibigQuery);

						if($pagibigArr['balance'] > 0)
							$pagibig = $pagibigArr['balance'];
						else
							$pagibig = "N/A";
					}
					else
					{
						$pagibig = "N/A";
					}
							//New Vale
					if(mysql_num_rows($newValeQuery) > 0)
					{
						$newValeArr = mysql_fetch_assoc($newValeQuery);

						if($newValeArr['balance'] > 0)
							$newVale = $newValeArr['balance'];
						else
							$newVale = "N/A";
					}
					else
					{
						$newVale = "N/A";
					}

							//Old Vale
					if(mysql_num_rows($oldValeQuery) > 0)
					{
						$oldValeArr = mysql_fetch_assoc($oldValeQuery);

						if($oldValeArr['balance'] > 0)
							$oldVale = $oldValeArr['balance'];
						else
							$oldVale = "N/A";
					}
					else
					{
						$oldVale = "N/A";
					}
					?>
					<div class="form-group row">
						<label class="control-label col-md-3 col-lg-3" for="sss" >SSS</label>
						<div class="col-md-9 col-lg-9">
							<?php
							if($sss != "N/A")
							{
								Print "<span class='pull-right' id='sssValue'>".number_format($sss, 2, '.', ',')."</span>";
							}
							else
							{
								Print "--";
							}
							?>
						</div>
						<div class="col-md-1 col-lg-12">
							<input type="text" class="form-control" id="sssDeduct" name="sssDeduct" placeholder="To deduct" onblur="addDecimal(this)" onchange="setsssLimit(this)">
						</div>
					</div>
					<div class="form-group row">
						<label class="control-label col-md-3 col-lg-3" for="pagibig" style="white-space: nowrap;">Pag-IBIG</label>
						<div class="col-md-9 col-lg-9">
							<?php
							if($pagibig != "N/A")
							{
								Print "<span class='pull-right' id='pagibigValue'>".number_format($pagibig, 2, '.', ',')."</span>";
							}
							else
							{
								Print "--";
							}
							?>
						</div>
						<div class="col-md-1 col-lg-12">
							<input type="text" class="form-control" id="pagibigDeduct" name="pagibigDeduct" placeholder="To deduct" onblur="addDecimal(this)" onchange="setpagibigLimit(this)">
						</div>
					</div>
				</div>

				<!-- OLD VALE -->
				<div class="col-md-2 col-lg-2">
					<div class="col-md-6 col-lg-6">
						<h4 class="text-left" style="white-space: nowrap;">Old Vale</h4>
						<h5 class="text-right" style="white-space: nowrap;">
							<span class="pull-right">
								<?php 
								if($oldVale != "N/A")
									Print "<span class='pull-right' id='oldvaleValue'>".number_format($oldVale, 2, '.', ',')."</span";
								else
									Print $oldVale;	
								?>
							</span>
						</h5>
						<div class="row">
							<input type='text' placeholder='Deduct' id='oldValeDeduct' name='oldValeDeduct'class='form-control input-sm pull-down' onchange='setoldvaleLimit(this)'>
						</div>
					</div>

					<!-- NEW VALE -->
					<div class="col-md-6 col-lg-6">
						<h4 class="text-left" style="white-space: nowrap;">New Vale</h4>
						<h5 class="text-right" style="white-space: nowrap;">
							<span class="vale pull-right" id="parent">
								<?php 
								if($newVale != "N/A")
									Print "<span id = 'newValeText' value='".$newVale."'>".$newVale."</span>";
								else
									Print "<span id = 'newValeText'>N/A</span>";
								?>
							</span>
							<br>
							<!-- <span id="dynamicCompute"></span> -->
						</h5>
						<?php 
						//hidden input the Current Newvale
						if($newVale != "N/A")
							Print "<input type='hidden' name='newVale' value='".$newVale."'>";
						else
							Print "<input type='hidden' name='newVale'>";
						?> 

						<!-- Hidden inputs for the new vale -->
						<input type="hidden" name="newValeAdded" class="added">
						<input type="hidden" name="newValeRemarks" class="addRemarks">

						<div class="row" style="margin-top:9px">
							<button type='button' class='btn btn-success btn-sm col-md-1 col-lg-12' data-toggle='modal' data-target='#addVale'><span class='glyphicon glyphicon-plus'></span> Add</button>
						</div>
					</div>

					<!-- COLA -->
					<div class="col-md-1 col-lg-12">
						<?php
						$colaValue = $empArr['cola'];
						if($colaValue == NULL)
						{
							$colaValue = "N/A";
						}
						?>
						<h4>COLA</h4>
						<input type="text" value="<?php Print $colaValue?>" name="cola" class="form-control">
					</div>
				</div>

				<!-- Contributions -->
				<div class="col-md-3 col-lg-3">
					<h4 class="text-center">Contributions</h4>
					<div class="form-group">
						<label class="control-label col-md-5 col-lg-5" for="tax">Tax</label>
						<div class="col-md-7 col-lg-7">
							<input type="text" id="tax" name="tax" class="form-control input-sm" onkeypress="validatenumber(event)" onblur="addDecimal(this)">
						</div>
						<label class="control-label col-md-5 col-lg-5" for="sssContribution">SSS</label>
						<div class="col-md-7 col-lg-7">
							<input type="text" id="sssContribution" name="sss" placeholder="No document" class="form-control input-sm" value="<?php Print $deductionSSS?>" onkeypress="validatenumber(event)" readonly>

						</div>
						<label class="control-label col-md-5 col-lg-5" for="pagibigContribution" style="white-space: nowrap;">Pag-IBIG</label>
						<div class="col-md-7 col-lg-7">
							<input type="text" id="pagibigContribution" name="pagibig" class="form-control input-sm" value="<?php Print $deductionPagibig?>" placeholder="No document" readonly>
						</div>
						<label class="control-label col-md-5 col-lg-5" for="philhealth">PhilHealth</label>
						<div class="col-md-7 col-lg-7">
							<input type="text" id="philhealth" name="philhealth" placeholder="No document" class="form-control input-sm" value="<?php Print $deductionPhilhealth?>" onkeypress="validatenumber(event)" readonly>
						</div>
					</div>
				</div>

				<!-- Allowance computation -->
				<?php
						//Computation for overall allowance
				$overallAllow = "";
				if(!empty($empArr['allowance']))
				{
					$overallAllow = $empArr['allowance'] * $allowCounter;
					$overallAllow = number_format($overallAllow, 2, '.', ',');
				}
				?>
				<!-- Days the employee came to work -->
				<input type="hidden" name="daysAttended" value="<?php Print $allowCounter?>">
				<div class="col-md-5 col-lg-5">
					<h4 class="text-left">Allowance</h4>
					<div class="form-group">
						<label class="control-label col-md-2 col-lg-2">Daily</label>
						<div class="col-md-2 col-lg-2 nopadding">
							<input type="text" id="allowance" name="allowance" class="form-control input-sm" placeholder="Daily allowance" value="<?php Print $empArr['allowance']?>" readonly>
						</div>
						<label class="control-label col-md-2 col-lg-2">Overall</label>
						<div class="col-md-2 col-lg-2 nopadding">
							<input type="text" id="OverallAllowance" name="OverallAllowance" class="form-control input-sm" placeholder="Overall Allow."  value="<?php Print $overallAllow?>" readonly>
						</div>
						<label class="control-label col-md-2 col-lg-2">Extra</label>
						<div class="col-md-2 col-lg-2 nopadding">
							<input type="text" id="allowance" name="extra_allowance" name="extra_allowance" class="form-control input-sm" onblur="addDecimal(this)">
						</div>
					</div>

					<!-- Tools deductions -->
					<div class="col-md-1 col-lg-12">
						<h4 class="text-left">Tools</h4>
						<span class="col-md-4 col-lg-4">Name</span>
						<span class="col-md-4 col-lg-4">Cost</span>
						<span class="col-md-4 col-lg-4">Quantity</span>
						<a class="btn btn-sm btn-primary col-md-1 col-lg-1" onclick="addRow()"><span class="glyphicon glyphicon-plus"></span></a>
						<div class="form-group" id="toolform">
							<div>
								<div class="col-md-4 col-lg-4">
									<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onchange="checkName(this)">
								</div>
								<div class="col-md-4 col-lg-4">
									<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)">
								</div>
								<div class="col-md-3 col-lg-3">
									<input type="text" id="quantity" name="toolquantity[]" class="form-control input-sm" onchange="getTotal(this)" onblur="addDecimal(this)">
								</div>
							</div>	
						</div>
						<div class="col-md-1 col-lg-12 pull-down">
							<label class="col-md-5 col-lg-5">
								Total Cost
							</label>
							<div class="col-md-6 col-lg-6">
								<input type="text" class="form-control" id="totalcost" name="totalcost" value="" readonly>
							</div>
						</div>
						<div class="col-md-1 col-lg-12">
							<label class="col-md-5 col-lg-5">
								Previous Payable
							</label>
							<?php
							//Outstanding payable
							
							//gets the previous payroll result
							$previousPayable = "SELECT * FROM payroll WHERE empid = '$empid' AND date != '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
							$payableQuery = mysql_query($previousPayable);

							$outstanding = null;//pre set outstanding payable
							if(mysql_num_rows($payableQuery) > 0);
							{
								$outstArr = mysql_fetch_assoc($payableQuery);
								if($outstArr['tools_outstanding'] != 0)
									$outstanding = $outstArr['tools_outstanding'];
							}
							?>
							<div class="col-md-6 col-lg-6">
								<input type="text" class="form-control" name="previousPayable" id="outstandingPayable" value="<?php Print $outstanding ?>" readonly>
							</div>
						</div>
						<div class="col-md-1 col-lg-12">
							<label class="col-md-5 col-lg-5">
								Amount to Pay
							</label>
							<div class="col-md-6 col-lg-6">
								<input type="text" id="amountToPay" name="amountToPay" class="form-control" onblur="addDecimal(this)" onchange="settotalLimit(this)" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
</div>
<!-- Hidden inputs for disabled dates -->
<input type="hidden" id="disabledDates" value="<?php Print $disabledDates?>">


<!-- DUMMY MODAL FOR REMARKS -->

<div class="modal fade" tabindex="-1" id="remarks" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="dito">Remarks</h4>
			</div>
			<div class="modal-body">
				<input class="form-control" id="remark"  maxlength="100"onkeyup="remarksListener(this.value)">
			</div>
			<div class="modal-footer">
				<h5 class="pull-left" >Characters left: &nbsp<span id="remarksCounter">100<span>&nbsp</h5>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveRemarks">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
<script src="js/jquery.tmpl.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script rel="javascript" src="js/payroll.js"></script>
<script src="js/enterAttendance.js"></script>

<script id="hidden-template" type="text/x-custom-template">	
		<table class="table table-bordered table-responsive">
			<tr>
				<td colspan='13'>
					<h2 class="dateheader text-center col-md-11 col-md-push-1">${date}</h2>
					<input type="button" class="btn btn-danger col-md-1" value="Remove" onclick="removeAdjustment(this)">
				</td>
			</tr>
			<tr class="attendance-header">
		              <td>Time In</td>
		              <td>Time Out</td>
		              <td>H.D. / Straight</td>
		              <td>A.B. Time In</td>
		              <td>A.B. Time Out</td>
		              <td>N.S.</td>
		              <td>Time In</td>
		              <td>Time Out</td>
		              <td>Working Hours</td>
		              <td>Overtime</td>
		              <td>Undertime</td>
		              <td>Night Differential</td>
		              <td colspan="2">Actions</td>
		    </tr>
			<tr class="input-fields" id="input-field-${inputCounter}">

				<input type='hidden' class='driver' value='<?php $driverBool = ($empArr['position'] == 'Driver' ? true : false )?>' >
				{{if sunday}}
			      <input type="hidden" id="isSunday">
			    {{else}}
			    {{/if}}
				<!-- Time In -->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timein1 timepicker form-control input-sm' value='' name='timein1[${inputCounter}]'>
				</td> 
				<!-- Time Out-->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' value='' name='timeout1[${inputCounter}]'>
				</td> 
				<!-- Half Day Checkbox-->
				<td>
					<input type='checkbox' class='halfdayChk' name='halfday[${inputCounter}]' onclick="halfDay('input-field-${inputCounter}')" disabled>
				</td>
				<!-- AFTER BREAK Time In -->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timein2 timepicker form-control input-sm' value=''  name='timein2[${inputCounter}]'>
				</td> 
				<!-- AFTER BREAK Time Out-->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' value='' name='timeout2[${inputCounter}]'>
				</td> 
				<!-- Night Shift Checkbox-->
				<td>
					<input type='checkbox' class='nightshiftChk' name='nightshift[${inputCounter}]' onclick="nightshift_ChkBox('input-field-${inputCounter}')" disabled>
				</td>
				<!-- NIGHT SHIFT Time In -->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[${inputCounter}]' readonly>
				</td> 
				<!-- NIGHT SHIFT Time Out-->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[${inputCounter}]' readonly>
				</td> 
				<!-- Working Hours -->
				<td>
					<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
					<input type='hidden' class='workinghoursH'  name='workinghrs[${inputCounter}]' >
				</td> 
				<!-- Overtime -->
				<td>
					<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
					<input type='hidden' class='overtimeH' name='othrs[${inputCounter}]' >
				</td> 
				<!-- Undertime -->
				<td>
					<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
					<input type='hidden' class='undertimeH' name='undertime[${inputCounter}]' >
				</td>
				<!-- Night Differential --> 
				<td>
					<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
					<input type='hidden' class='nightdiffH' name='nightdiff[${inputCounter}]' >
				</td>
				<!-- Remarks Input --> 
					<input type='hidden' name='remarks[${inputCounter}]' class='hiddenRemarks'>

				<!-- Attendance Status -->
					<input type='hidden' name='attendance[${inputCounter}]' class='attendance'>
				<!-- Remarks Button --> 
				<td>
					<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarksFunc("${inputCounter}")'>Remarks <span class='icon'></span></a>
				</td>
			</tr>
		</table>
</script>
<script >

	function remarksFunc(id) {
		remarksPayroll("input-field-"+id);
		remarksValidation("input-field-"+id);
	}

	localStorage.setItem("inputcounter", 0);
	localStorage.setItem("tablecounter", 0);

	$("#dateValue").change(function() {
		var day = $("#dateValue").val();
		var d = new Date(day);
		var sundayBool = false;

		if(d.getDay()===0){
		 	sundayBool = true;
		 }

		localStorage.inputcounter = $('#adjustmentFields table').length;
		var inputcounter = localStorage.getItem("inputcounter");

		// if there are id's already available, add 1 otherwise retain number
		var data = [{ 
					date: day,
					inputCounter: inputcounter,
					sunday: d.getDay()===0 ? true : false
				}];
		$('#hidden-template').tmpl(data).appendTo('#adjustmentFields');
		

		timeVerify();
	});
	var currentDate = "<?php Print "$date"; ?>";
	//Date picker for adjustments
	$("#dateValue").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'MM d, yy',
			showAnim: 'blind',
			// minDate:(-1),
			maxDate: currentDate,
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			},
			// beforeShowDay: disabledDays
		});
	
	$("#dateValue").datepicker("setDate", currentDate);
	
	

	// function disabledDays(date){
	// 	var disDates = document.getElementById('disabledDates').value;
	// 	var disArr = disDates.split('+');
		
	// 	var month = date.getMonth();
	// 	var day = date.getDate();
	// 	var year = date.getFullYear();

	// 	var currentDate = (month + 1) + '-' + day + '-' + year;
	// 	// console.log(currentDate);
	// 	// for(var count = 0; count < disArr.length ; count++) {
	// 		if(localStorage.datesCounter != 0)
	// 			localStorage.datesCounter--;
	// 		console.log("local: "+localStorage.datesCounter);
	// 		console.log("disDate: "+disArr[localStorage.datesCounter]);
	// 		if( $.inArray(currentDate, disArr[localStorage.datesCounter]) != -1)
	// 		{
	// 			return [false];
	// 		}
	// 		else
	// 		{
	// 			return [true];
	// 		}
	// 	// }
	// }
	// $(document).ready(function(){
	// 	var disDates = $('#disabledDates').val();
	// 	var disArr = disDates.split('+');
	// 	var counter = disArr.length;
	// 	localStorage.setItem("datesCounter", counter);
	// });
	// Tooltip for computing payroll
	$(function () {
  		$('[data-toggle="tooltip"]').tooltip()
	});

	function removeAdjustment(date) {
		localStorage.inputcounter--;
		date.parentNode.parentNode.parentNode.parentNode.remove();
	}

	function nightshift_ChkBox(id) {
		var mainRow = document.getElementById(id);//gets the row of the user checked

		if(mainRow.querySelector('.nightshiftChk').checked == true) {

			mainRow.querySelector('.timein3').readOnly = false;
			mainRow.querySelector('.timeout3').readOnly = false;

			// disable halfday checkbox
			mainRow.querySelector('.halfdayChk').disabled = true;

			// delete values to prepare for the 3rd timein and timeout
			mainRow.querySelector('.workinghours').value = "";
			mainRow.querySelector('.overtime').value = "";
			mainRow.querySelector('.undertime').value = "";
			mainRow.querySelector('.nightdiff').value = "";
			//for hidden rows
			mainRow.querySelector('.workinghoursH').value = "";
			mainRow.querySelector('.overtimeH').value = "";
			mainRow.querySelector('.undertimeH').value = "";
			mainRow.querySelector('.nightdiffH').value = "";

			mainRow.querySelector('.attendance').value = "";//reset the attendance status
			
			// If absent was initially placed, changed to success
			if(mainRow.classList.contains('danger'))
			{
				mainRow.classList.remove('danger');
			}
			else 
			{
				mainRow.classList.remove('success');
			}
		}
		else {

			// enable halfday checkbox
			mainRow.querySelector('.halfdayChk').disabled = false;

			mainRow.querySelector('.timein3').readOnly = true;
			mainRow.querySelector('.timeout3').readOnly = true;
			mainRow.querySelector('.timein3').value = '';
			mainRow.querySelector('.timeout3').value = '';
			timeIn(id);//call function to revert the results to just 4 inputs
		}
	}

	function remarksPayroll(id) {
		// show modal here to input for remarks
		console.log('in remarks: 123 '+id);
		id = String(id);
		var mainRow = document.getElementById(id);
		if(mainRow.querySelector('.hiddenRemarks').value != null)
		{

			var input = mainRow.querySelector('.hiddenRemarks').value;
			input = input.replace(/\\/g, '');
			document.getElementById('remark').value = input;
		}
		else
		{
			document.getElementById('remark').value = "";
		}
		document.getElementById('saveRemarks').setAttribute('onclick', "saveRemarksPayroll(\""+ id +"\")");
	}

	function saveRemarksPayroll(id) {
		console.log('remark123: '+ id)
		var mainRow = document.getElementById(id);
		var remarks = document.getElementById('remark').value.trim();
		var hiddenRemarks = mainRow.querySelector('.hiddenRemarks').setAttribute('value', remarks);

		if(remarks !== null && remarks !== "")
		{
			mainRow.querySelector('.icon').classList.add('glyphicon', 'glyphicon-edit');
		}
		else
		{
			mainRow.querySelector('.icon').classList.remove('glyphicon', 'glyphicon-edit');
		}

	}

</script>
</div>
</form>
</body>
</html>