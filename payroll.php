<!DOCTYPE html>
<?php
include('directives/session.php');
if(isset($_GET['site']) && isset($_GET['position']))
{}
else
{
	header("location:payroll_login.php");
}

$site = $_GET['site'];
$position = $_GET['position'];
$empid = $_GET['empid'];
//Sample date for debugging purposes
//$date = strftime("%B %d, %Y");
$date = "October 24, 2017";

$time = strftime("%X");//TIME

$day1 = $date;
$day2 = date('F j, Y', strtotime('-1 day', strtotime($date)));
$day3 = date('F j, Y', strtotime('-2 day', strtotime($date)));
$day4 = date('F j, Y', strtotime('-3 day', strtotime($date)));
$day5 = date('F j, Y', strtotime('-4 day', strtotime($date)));
$day6 = date('F j, Y', strtotime('-5 day', strtotime($date)));
$day7 = date('F j, Y', strtotime('-6 day', strtotime($date)));
//Holiday Checker
$holiday = "SELECT * FROM holiday WHERE date = '$date'";
$holidayQuery = mysql_query($holiday);
$holidayExist = mysql_num_rows($holidayQuery);
$holidayName = "";
if($holidayExist > 0)
{
	$holidayRow = mysql_fetch_assoc($holidayQuery);
	$holidayName = $holiday['holiday'];//Gets holiday name
}
//Sunday Checker
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">

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
			?>

			<!-- Breadcrumbs -->
			<input type="hidden" name="employeeID" value="<?php Print $empid?>">
			<div class="row pull-down">
				<div class="col-md-10 col-md-offset-1">
					<ol class="breadcrumb text-left" style="margin-bottom: 0px">

						<li><a href="payroll_table.php?position=<?php Print $position?>&site=<?php Print $site?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Table of Employees</a></li>
						<li class="active"><?php Print "Payroll for site " .$site." on ".$date ?></li>

						<input type="submit" value="Save and compute" class="btn btn-success pull-right" style="margin-right:5px" onclick="saveChanges()" href="#">
					</ol>
				</div>

				<!-- Employee information -->
				<div class="col-md-10 col-md-offset-1">
					<?php
					$employee = "SELECT * FROM employee WHERE empid = '$empid'";
					$employeeQuery = mysql_query($employee);
					$empArr = mysql_fetch_assoc($employeeQuery);
			//For deduction section 4 for 4 weeks in a month
					$deductionSSS = $empArr['sss']/4;
					$deductionPagibig = $empArr['pagibig']/4;
					$deductionPhilhealth = $empArr['philhealth']/4;
			//2 decimal places
					$deductionSSS =  number_format($deductionSSS, 2, '.', ',');
					$deductionPagibig = number_format($deductionPagibig, 2, '.', ',');
					$deductionPhilhealth = number_format($deductionPhilhealth, 2, '.', ',');
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
					<div class='col-md-8 text-left' style='word-break: keep-all'>
					
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
					<div class='col-md-4 text-right'>";
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
			<div class="col-md-10 col-md-offset-1">
				<table class="table-bordered table-condensed" style="background-color:white;">
					<?php
				//Sample query for debugging purposes
					$payrollDate = "SELECT * FROM attendance WHERE empid = '$empid' ORDER BY date DESC LIMIT 7";
				//$payrollDate = "SELECT * FROM attendance WHERE empid = '2017-0000011' ORDER BY date DESC LIMIT 7";
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
						$holiday = "SELECT * FROM holiday WHERE date = '$holDateChecker' ORDER BY date DESC";
						$holidayQuery = mysql_query($holiday);
						$holidayExist = mysql_num_rows($holidayQuery);
						if($holidayExist > 0)//if holiday exist
						{
							$holidayRow = mysql_fetch_assoc($holidayQuery);
							$holDay = date('l', strtotime($holidayRow['date']));
							if($holidayCounter > 0)//if holiday lasted for more than 1day
							{
								//Print "<script>console.log('if')</script>";
								$holidayCounter++;
								$holidayName .= "+".$holidayRow['holiday'];
								$holidayType .= "+".$holidayRow['type'];
								$holidayDate .= "+".$holidayRow['date'];
								$holidayDay .= "+".$holDay;
							}
							else
							{
								//Print "<script>console.log('else')</script>";
								$holidayCounter++;
								$holidayName = $holidayRow['holiday'];//Gets holiday name
								$holidayType = $holidayRow['type'];
								$holidayDate = $holidayRow['date'];
								$holidayDay = $holDay;
							}
						}
						//Print "<script>console.log('holyeah: ".$holidayName."')</script>";
						//Print "<script>alert('".$dateRow['date']."')</script>";
						$day = date('l', strtotime($dateRow['date']));
						if($day == "Sunday" && $sunBool)
						{
							$sunDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$totalHours += $dateRow['workhours'];//Get the total workhours
								$totalNightDiff += $dateRow['nightdiff'];//Get the total Night Diff
								$totalOT += floatval($dateRow['overtime']);//Get the total Over
								$sunTimeIn = $dateRow['timein'];
								$sunTimeOut = $dateRow['timeout'];
								$ABsunTimeIn = $dateRow['afterbreak_timein'];
								$ABsunTimeOut = $dateRow['afterbreak_timeout'];
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
						else if($day == "Monday" && $monBool)
						{
							$monDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$totalHours += $dateRow['workhours'];//Get the total workhours
								$totalNightDiff += $dateRow['nightdiff'];//Get the total Night Diff
								$totalOT += floatval($dateRow['overtime']);//Get the total Overtime
								//Print "<script>alert('".$totalOT."')</script>";
								$monTimeIn = $dateRow['timein'];
								$monTimeOut = $dateRow['timeout'];
								$ABmonTimeIn = $dateRow['afterbreak_timein'];
								$ABmonTimeOut = $dateRow['afterbreak_timeout'];
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
								$totalHours += $dateRow['workhours'];//Get the total workhours
								$totalNightDiff += $dateRow['nightdiff'];//Get the total Night Diff
								$totalOT += floatval($dateRow['overtime']);//Get the total Overtime
								//Print "<script>alert('".$totalOT."')</script>";
								$tueTimeIn = $dateRow['timein'];
								$tueTimeOut = $dateRow['timeout'];
								$ABtueTimeIn = $dateRow['afterbreak_timein'];
								$ABtueTimeOut = $dateRow['afterbreak_timeout'];
								
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
								$totalHours += $dateRow['workhours'];//Get the total workhours
								$totalNightDiff += $dateRow['nightdiff'];//Get the total Night Diff
								$totalOT += floatval($dateRow['overtime']);//Get the total Overtime
								//Print "<script>alert('".$totalOT."')</script>";
								$wedTimeIn = $dateRow['timein'];
								$wedTimeOut = $dateRow['timeout'];
								$ABwedTimeIn = $dateRow['afterbreak_timein'];
								$ABwedTimeOut = $dateRow['afterbreak_timeout'];
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
								$totalHours += $dateRow['workhours'];//Get the total workhours
								$totalNightDiff += $dateRow['nightdiff'];//Get the total Night Diff
								$totalOT += floatval($dateRow['overtime']);//Get the total Overtime
								//Print "<script>alert('".$totalOT."')</script>";
								$thuTimeIn = $dateRow['timein'];
								$thuTimeOut = $dateRow['timeout'];
								$ABthuTimeIn = $dateRow['afterbreak_timein'];
								$ABthuTimeOut = $dateRow['afterbreak_timeout'];
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
								$totalHours += $dateRow['workhours'];//Get the total workhours
								$totalNightDiff += $dateRow['nightdiff'];//Get the total Night Diff
								$totalOT += floatval($dateRow['overtime']);//Get the total Overtime
								//Print "<script>alert('".$totalOT."')</script>";
								$friTimeIn = $dateRow['timein'];
								$friTimeOut = $dateRow['timeout'];
								$ABfriTimeIn = $dateRow['afterbreak_timein'];
								$ABfriTimeOut = $dateRow['afterbreak_timeout'];
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
								$totalHours += $dateRow['workhours'];//Get the total workhours
								$totalNightDiff += $dateRow['nightdiff'];//Get the total Night Diff
								$totalOT += floatval($dateRow['overtime']);//Get the total Overtime
								//Print "<script>alert('".$totalOT."')</script>";
								$satTimeIn = $dateRow['timein'];
								$satTimeOut = $dateRow['timeout'];
								$ABsatTimeIn = $dateRow['afterbreak_timein'];
								$ABsatTimeOut = $dateRow['afterbreak_timeout'];
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
						Print "<script>console.log('".$holNameArr[0]." + ".$holNameArr[1]."')</script>";
						$holidayCounter -= 1;
						for($a = 0; $a <= $holidayCounter; $a++)
						{
							//Print "<script>console.log('".$holNameArr[$a]." + ".$a."')</script>";
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
					// $start_date = $date;
					// $end_date = 'September 22, 2017';
					// if ($end_date >= $start_date)
					// {
					//   for ($day = 0; $day < 7; $day++)
					//   {
					//     echo "<br />" . date("F j, Y", strtotime("$start_date +$day day"));
					//     $yea = strtotime($start_date + $day);
					//     echo $yea;
					//   }
					// }
					?>
					<tr style="white-space: nowrap">
						<td colspan="2" class="navibar col-md-1"><?php Print $day1 ?></td>
						<td colspan="2" class="navibar col-md-1"><?php Print $day2 ?></td>
						<td colspan="2" class="navibar col-md-1"><?php Print $day3 ?></td>
						<td colspan="2" class="navibar col-md-1"><?php Print $day4 ?></td>
						<td colspan="2" class="navibar col-md-1"><?php Print $day5 ?></td>
						<td colspan="2" class="navibar col-md-1"><?php Print $day6 ?></td>
						<td colspan="2" class="navibar col-md-1"><?php Print $day7 ?></td>
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
								Print 	"<td colspan='2' rowspan='2' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='2' class='danger'> Absent </td>";
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
								Print 	"<td colspan='2' rowspan='2' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='2' class='danger'> Absent </td>";
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
								Print 	"<td colspan='2' rowspan='2' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='2' class='danger'> Absent </td>";
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
								Print 	"<td colspan='2' rowspan='2' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='2' class='danger'> Absent </td>";
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
								Print 	"<td colspan='2' rowspan='2' class='danger'> Day off </td>";
							}
							
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='2' class='danger'> Day off </td>";
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
								Print 	"<td colspan='2' rowspan='2' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='2' class='danger'> Absent </td>";
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
								Print 	"<td colspan='2' rowspan='2' class='danger'> Holiday </td>";
							}
						}
						else
						{
							Print 	"	<td colspan='2' rowspan='2' class='danger'> Absent </td>";
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
								Print 	"	<td colspan='2'>Half Day</td>";
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
								Print 	"	<td colspan='2'>Half Day</td>";
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
								Print 	"	<td colspan='2'>Half Day</td>";
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
								Print 	"	<td colspan='2'>Half Day</td>";
							}
						}
						if(!$sunAbsent)
						{
							//If Admin didnt input attendance on sunday
							if(isset($sunTimeIn) && isset($sunTimeOut))
							{
							//if halfday
								if(!empty($ABsunTimeIn) && !empty($ABsunTimeOut))
								{
									Print 	"	<td>Time In:<br>". trim($ABsunTimeIn) ."</td>
									<td>Time Out:<br>". trim($ABsunTimeOut) ."</td>";
								}
								else
								{
									Print 	"	<td colspan='2'>Half Day</td>";
								}
							}
						}
						if(!$monAbsent)
						{
							//if halfday
							if(isset($ABmonTimeIn) && isset($ABmonTimeOut))
							{
								Print 	"	<td>Time In:<br>". trim($ABmonTimeIn) ."</td>
								<td>Time Out:<br>". trim($ABmonTimeOut) ."</td>";
							}
							else if (isset($monTimeIn) && isset($monTimeOut))
							{
								Print 	"	<td colspan='2'>Half Day</td>";
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
								Print 	"	<td colspan='2'>Half Day</td>";
							}
						}

						?>
					</tr>
					<tr>
						<td class="nopadding" colspan="2">
							<h4>
						<?php //Badges to display Holiday, OT, ND
						if($holWed)
							Print 	 "<span class='label label-success'>Holiday</span>&nbsp";
						if($OtWed)
							Print 	"<span class='label label-primary'>OT</span>&nbsp";
						if($NdWed)
							Print 	"<span class='label label-warning'>ND</span>";
						?>
					</h4>
				</td>
				<td class="nopadding" colspan="2">
					<h4>
						<?php
						if($holThu)
							Print 	 "<span class='label label-success'>Holiday</span>&nbsp;";
						if($OtThu)
							Print 	"<span class='label label-primary'>OT</span>&nbsp;";
						if($NdThu)
							Print 	"<span class='label label-warning'>ND</span>";
						?>
					</h4>
				</td>
				<td class="nopadding" colspan="2">
					<h4>
						<?php
						if($holFri)
							Print 	 "<span class='label label-success'>Holiday</span>&nbsp;";
						if($OtFri)
							Print 	"<span class='label label-primary'>OT</span>&nbsp;";
						if($NdFri)
							Print 	"<span class='label label-warning'>ND</span>";
						?>
					</h4>
				</td>
				<td class="nopadding" colspan="2">
					<h4>
						<?php
						if($holSat)
							Print 	 "<span class='label label-success'>Holiday</span>&nbsp;";
						if($OtSat)
							Print 	"<span class='label label-primary'>OT</span>&nbsp;";
						if($NdSat)
							Print 	"<span class='label label-warning'>ND</span>";
						?>
					</h4>
				</td>
				<td class="nopadding" colspan="2">
					<h4>
						<?php
						if($holSun)
							Print 	 "<span class='label label-success'>Holiday</span>&nbsp;";
						if($OtSun)
							Print 	"<span class='label label-primary'>OT</span>&nbsp;";
						if($NdSun)
							Print 	"<span class='label label-warning'>ND</span>";
						?>
					</h4>
				</td>
				<td class="nopadding" colspan="2">
					<h4>
						<?php
						if($holMon)
							Print 	"<span class='label label-success'>Holiday</span>&nbsp;"; 
						if($OtMon)
							Print 	"<span class='label label-primary'>OT</span>&nbsp;"; 
						if($NdMon)
							Print 	"<span class='label label-warning'>ND</span>"; 
						?>
					</h4>
				</td>
				<td class="nopadding" colspan="2">
					<h4>
						<?php
						if($holTue)
							Print 	 "<span class='label label-success'>Holiday</span>&nbsp;";
						if($OtTue)
							Print 	"<span class='label label-primary'>OT</span>&nbsp;";
						if($NdTue)
							Print 	"<span class='label label-warning'>ND</span>";
						?>
					</h4>
				</td>
			</tr>
		</table>
	</div>

	<!-- Summary of attendance -->
	<div class="col-md-10 col-md-offset-1">
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
				<div class="col-md-2">
					<h4>Loans</h4>
					<?php
							//this is to check if employee has multiple new vales in a week
					$day1 = $date;
					$day2 = date('F j, Y', strtotime('-1 day', strtotime($date)));
					$day3 = date('F j, Y', strtotime('-2 day', strtotime($date)));
					$day4 = date('F j, Y', strtotime('-3 day', strtotime($date)));
					$day5 = date('F j, Y', strtotime('-4 day', strtotime($date)));
					$day6 = date('F j, Y', strtotime('-5 day', strtotime($date)));
					$day7 = date('F j, Y', strtotime('-6 day', strtotime($date)));
					$days = array("$day1","$day2","$day3","$day4","$day5","$day6","$day7");
					$newVale = 0;
					foreach($days as $checkDay)
					{
								//Check if overall attendance for a certain site is done
						$loanChecker = "SELECT * FROM loans WHERE date = '$checkDay' AND type = 'newVale' AND empid = '$empid'";
								//Print '<script>colsole.log("'.$loanChecker.'")</script>';
						$loanCheckerQuery = mysql_query($loanChecker);
						if($loanCheckerQuery)
						{
							$newValeNum = mysql_num_rows($loanCheckerQuery);
							if($newValeNum != 0)
							{
										//Print "<script>alert('yea')</script>";
								if($newValeNum > 1)
								{
									while($newValeArr = mysql_fetch_assoc($loanCheckerQuery))
									{
										//Print "<script>alert('yea')</script>";
										if($newValeNum > 1)
										{
											while($newValeArr = mysql_fetch_assoc($loanCheckerQuery))
											{
												$newVale += $newValeArr['amount'];
											}
										}
										else
										{
											//Print "<script>alert('yea')</script>";
											$newValeRow = mysql_fetch_assoc($loanCheckerQuery);
											$newVale += $newValeRow['amount'];
											Print "<script>console.log('".$newVale."')</script>";
										}
									}
								}
								else
								{
											//Print "<script>alert('yea')</script>";
									$newValeRow = mysql_fetch_assoc($loanCheckerQuery);
									$newVale += $newValeRow['amount'];
								}
							}
						}
					}
					$getSSS = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'SSS' ORDER BY date DESC LIMIT 1";
					$getPAGIBIG = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'PagIBIG' ORDER BY date DESC LIMIT 1";

							$getSSS = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'SSS' ORDER BY date DESC, time DESC LIMIT 1";
							$getPAGIBIG = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'PagIBIG' ORDER BY date DESC, time DESC LIMIT 1";
							
							$getOldVALE = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' ORDER BY date DESC, time DESC  LIMIT 1";
							//Query
					$sssQuery = mysql_query($getSSS);
					$pagibigQuery = mysql_query($getPAGIBIG);
					$oldValeQuery = mysql_query($getOldVALE);

							//SSS Loan
					if(mysql_num_rows($sssQuery) > 0)
					{
						$sssArr = mysql_fetch_assoc($sssQuery);
						$sss = $sssArr['balance'];
					}
					else
					{
						$sss = "N/A";
					}
							//Pagibig Loan
					if(mysql_num_rows($pagibigQuery) > 0)
					{
						$pagibigArr = mysql_fetch_assoc($pagibigQuery);
						$pagibig = $pagibigArr['balance'];
					}
					else
					{
						$pagibig = "N/A";
					}
							//New Vale
					if($newVale == 0)
					{
						$newVale = "<span id = 'newValeText'>N/A</span>";
					}

							//Old Vale
					if(mysql_num_rows($oldValeQuery) > 0)
					{
						$oldValeArr = mysql_fetch_assoc($oldValeQuery);
						$oldVale = $oldValeArr['balance'];
					}
					else
					{
						$oldVale = "N/A";
					}
					?>
					<div class="form-group row">
						<label class="control-label col-md-3" for="sss" >SSS</label>
						<div class="col-md-9">
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
						<div class="col-md-12 has-error">
							<input type="number" class="form-control" id="sssDeduct" placeholder="To deduct" onblur="addDecimal(this)" onchange="setsssLimit(this)">
						</div>
					</div>
					<div class="form-group row">
						<label class="control-label col-md-3" for="pagibig" style="white-space: nowrap;">Pag-IBIG</label>
						<div class="col-md-9 has-error">
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
						<div class="col-md-12">
							<input type="number" class="form-control" id="pagibigDeduct" placeholder="To deduct" onblur="addDecimal(this)" onchange="setpagibigLimit(this)">
						</div>
					</div>
				</div>

				<!-- OLD VALE -->
				<div class="col-md-2">
					<div class="col-md-6">
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
						<div class="row has-error">
							<?php
							if($oldVale != "N/A")
							{
								Print "
								<input type='text' placeholder='Deduct' id='oldValeDeduct' class='form-control input-sm pull-down' onchange='setoldvaleLimit(this)'>";
							}
							?>
						</div>
					</div>

					<!-- NEW VALE -->
					<div class="col-md-6">
						<h4 class="text-left" style="white-space: nowrap;">New Vale</h4>
						<h5 class="text-right" style="white-space: nowrap;">
							<span class="vale pull-right" id="parent">
								<?php 
								if($newVale != "<span id = 'newValeText'>N/A</span>")
									Print $newVale;
								else
									Print $newVale;
								?>
							</span>
							<br>
							<!-- <span id="dynamicCompute"></span> -->
						</h5>
						<input type="hidden" name="newValeAdded" class="added">

						<div class="row" style="margin-top:9px">
							<button type='button' class='btn btn-success btn-sm col-md-12' data-toggle='modal' data-target='#addVale'><span class='glyphicon glyphicon-plus'></span> Add</button>
						</div>
					</div>

					<!-- COLA -->
					<div class="col-md-12">
						<?php
						$cola = "SELECT * FROM site WHERE location = '$site'";
						$colaQuery = mysql_query($cola);
						$colaArr = mysql_fetch_assoc($colaQuery);
						$colaValue = $colaArr['cola'];
						if($colaValue == NULL)
						{
							$colaValue = "N/A";
						}
						?>
						<h4>COLA</h4>
						<input type="text" value="<?php Print $colaValue?>" name="cola" class="form-control" readonly>
					</div>
				</div>

				<!-- Contributions -->
				<div class="col-md-3">
					<h4 class="text-center">Contributions</h4>
					<div class="form-group">
						<label class="control-label col-md-5" for="tax">Tax</label>
						<div class="col-md-7">
							<input type="number" id="tax" name="tax" class="form-control input-sm" onkeypress="validatenumber(event)" onblur="addDecimal(this)">
						</div>
						<label class="control-label col-md-5" for="sssContribution">SSS</label>
						<div class="col-md-7">
							<input type="text" id="sssContribution" name="sss" placeholder="No document" class="form-control input-sm" value="<?php Print $deductionSSS?>" onkeypress="validatenumber(event)" readonly>

						</div>
						<label class="control-label col-md-5" for="pagibigContribution" style="white-space: nowrap;">Pag-IBIG</label>
						<div class="col-md-7">
							<input type="text" id="pagibigContribution" name="pagibig" class="form-control input-sm" value="<?php Print $deductionPagibig?>" placeholder="No document" readonly>
						</div>
						<label class="control-label col-md-5" for="philhealth">PhilHealth</label>
						<div class="col-md-7">
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
				<div class="col-md-5">
					<h4 class="text-left">Allowance</h4>
					<div class="form-group">
						<label class="control-label col-md-2">Daily</label>
						<div class="col-md-2 nopadding">
							<input type="text" id="allowance" name="allowance" class="form-control input-sm" placeholder="Daily allowance" value="<?php Print $empArr['allowance']?>" readonly>
						</div>
						<label class="control-label col-md-2">Overall</label>
						<div class="col-md-2 nopadding">
							<input type="text" id="OverallAllowance" name="OverallAllowance" class="form-control input-sm" placeholder="Overall Allow."  value="<?php Print $overallAllow?>" readonly>
						</div>
						<label class="control-label col-md-2">Extra</label>
						<div class="col-md-2 nopadding">
							<input type="number" id="allowance" name="extra_allowance" name="extra_allowance" class="form-control input-sm" onblur="addDecimal(this)">
						</div>
					</div>

					<!-- Tools deductions -->
					<div class="col-md-12">
						<h4 class="text-left">Tools</h4>
						<a class="btn btn-sm btn-primary col-md-1" onclick="addRow()"><span class="glyphicon glyphicon-plus"></span></a>
						<div class="form-group" id="toolform">
							<div id="1">
								<label class="control-label col-md-2" for="tools">Name</label>
								<div class="col-md-4">
									<input type="text" id="tools" name="toolname" class="form-control input-sm" onchange="checkName(this)">
								</div>
								<label class="control-label col-md-1" for="price">Cost</label>
								<div class="col-md-4">
									<input type="number" id="price" name="toolprice" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)">
								</div>
							</div>	
						</div>
						<div class="col-md-12 pull-down">
							<label class="col-md-5">
								Total Cost
							</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="totalcost" name="totalcost" value="" readonly>
							</div>

						</div>
						<div class="col-md-12">
							<label class="col-md-5">
								Previous Payable
							</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="previousPayable" id="outstandingPayable" value="" readonly>
							</div>
						</div>
						<div class="col-md-12">
							<label class="col-md-5">
								Amount to Pay
							</label>
							<div class="col-md-6">
								<input type="number" id="amountToPay" class="form-control" onblur="addDecimal(this)" onchange="settotalLimit(this)" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script rel="javascript" src="js/payroll.js"></script>

</div>
</form>
</body>
</html>