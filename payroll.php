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
$date = "September 26, 2017";

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
	<style>
		.well
		{
			margin-bottom: 0px !important;
		}
		h3
		{
			margin: 0px !important;
		}
		.removePadding
		{
			padding: 0px !important;
		}
	</style>
</head>
<body style="font-family: Quicksand;">

	<div class="container-fluid">

	<!-- Navigation bar -->
	<?php
	require_once("directives/nav.php");
	?>


	<div class="row pull-down">
		<div class="col-md-10 col-md-offset-1">
			<ol class="breadcrumb text-left" style="margin-bottom: 0px">

				<li><a href="payroll_table.php?position=<?php Print $position?>&site=<?php Print $site?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Table of Employees</a></li>
				<li class="active"><?php Print "Payroll for site " .$site." on ".$date ?></li>

			<a class="btn btn-success pull-right" style="margin-right:5px" onclick="saveChanges()" href="#">Save and compute <span class="glyphicon glyphicon-floppy-saved"></span></a>
			</ol>
		</div>


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


		<div class="col-md-10 col-md-offset-1">
			<table class="table-bordered table-condensed" style="background-color:white;">
				<?php
				//Sample query for debugging purposes
					//$payrollDate = "SELECT * FROM attendance WHERE empid = '$empid' ORDER BY date DESC LIMIT 7";
				$payrollDate = "SELECT * FROM attendance WHERE empid = '2017-0000011' ORDER BY date DESC LIMIT 2,8";
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
					$totalUT = 0; // for total undertime
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

					$holidayCounter = 0;//count the days of holiday
					while($dateRow = mysql_fetch_assoc($payrollQuery))
					{
						$holDateChecker = $dateRow['date'];
						//Holiday Checker
						$holiday = "SELECT * FROM holiday WHERE date = '$holDateChecker'";
						$holidayQuery = mysql_query($holiday);
						$holidayExist = mysql_num_rows($holidayQuery);

						if($holidayExist > 0)//if holiday exist
						{

							$holidayCounter++;
							$holidayRow = mysql_fetch_assoc($holidayQuery);
							$holidayName = $holidayRow['holiday'];//Gets holiday name
							$holidayType = $holidayRow['type'];
							$holidayDate = $holidayRow['date'];
							$holidayDay = date('l', strtotime($holidayRow['date']));;
							//Print "<script>alert('".$holidayName."')</script>";
							if($holidayCounter > 1)//if holiday lasted for more than 1day
							{

								$holidayName .= "+".$holidayRow['holiday'];
								$holidayType .= "+".$holidayRow['type'];
								$holidayDate .= "+".$holidayRow['date'];
								$holidayDay .= "+".$holidayRow['date'];
							}
						}
						

						//Print "<script>alert('".$dateRow['date']."')</script>";
						$day = date('l', strtotime($dateRow['date']));
						if($day == "Sunday" && $sunBool)
						{
							$sunDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$totalHours += $dateRow['workhours'];//Get the total workhours
								$totalNightDiff += $dateRow['nightdiff'];//Get the total Night Diff
								$totalOT += floatval($dateRow['overtime']);//Get the total Overtime
								//Print "<script>alert('".$totalOT."')</script>";
								$totalUT += $dateRow['undertime'];//Get the total Undertime
								$sunTimeIn = $dateRow['timein'];
								$sunTimeOut = $dateRow['timeout'];

								$sunWorkHrs = $dateRow['workhours'];//Get the workhours
								$sunNDHrs = $dateRow['nightdiff'];//Get the night diff
								$sunOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdSun = true;
								if($dateRow['overtime'] != 0)
									$OtSun = true;
								
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
								$totalOT = floatval($dateRow['overtime']);//Get the total Overtime
								//Print "<script>alert('".$totalOT."')</script>";
								$totalUT += $dateRow['undertime'];//Get the total Undertime
								$monTimeIn = $dateRow['timein'];
								$monTimeOut = $dateRow['timeout'];

								$monWorkHrs = $dateRow['workhours'];//Get the workhours
								$monNDHrs = $dateRow['nightdiff'];//Get the night diff
								$monOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdMon = true;
								if($dateRow['overtime'] != 0)
									$OtMon = true;
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
								$totalUT += $dateRow['undertime'];//Get the total Undertime
								$tueTimeIn = $dateRow['timein'];
								$tueTimeOut = $dateRow['timeout'];
								
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
								$totalUT += $dateRow['undertime'];//Get the total Undertime
								$wedTimeIn = $dateRow['timein'];
								$wedTimeOut = $dateRow['timeout'];

								$wedWorkHrs = $dateRow['workhours'];//Get the workhours
								$wedNDHrs = $dateRow['nightdiff'];//Get the night diff
								$wedOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if(!empty($dateRow['nightdiff']))
									$NdWed = true;
								if(!empty($dateRow['overtime']))
									$OtWed = true;
								
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
								$totalUT += $dateRow['undertime'];//Get the total Undertime
								$thuTimeIn = $dateRow['timein'];
								$thuTimeOut = $dateRow['timeout'];

								$thuWorkHrs = $dateRow['workhours'];//Get the workhours
								$thuNDHrs = $dateRow['nightdiff'];//Get the night diff
								$thuOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdThu = true;
								if($dateRow['overtime'] != 0)
									$OtThu = true;
								
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
								$totalUT += $dateRow['undertime'];//Get the total Undertime
								$friTimeIn = $dateRow['timein'];
								$friTimeOut = $dateRow['timeout'];

								$friWorkHrs = $dateRow['workhours'];//Get the workhours
								$friNDHrs = $dateRow['nightdiff'];//Get the night diff
								$friOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdFri = true;
								if($dateRow['overtime'] != 0)
									$OtFri = true;
								
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
								$totalUT += $dateRow['undertime'];//Get the total Undertime
								$satTimeIn = $dateRow['timein'];
								$satTimeOut = $dateRow['timeout'];

								$satWorkHrs = $dateRow['workhours'];//Get the workhours
								$satNDHrs = $dateRow['nightdiff'];//Get the night diff
								$satOTHrs = $dateRow['overtime'];//Get the Overtime
								//For badge of Night diff and Overtime

								if($dateRow['nightdiff'] != 0)
									$NdSat = true;
								if($dateRow['overtime'] != 0)
									$OtSat = true;
								
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
				<tr>
					<td colspan="2" class="navibar col-md-1"><?php Print $wedDate ?></td>
					<td colspan="2" class="navibar col-md-1"><?php Print $thuDate ?></td>
					<td colspan="2" class="navibar col-md-1"><?php Print $friDate ?></td>
					<td colspan="2" class="navibar col-md-1"><?php Print $satDate ?></td>
					<td colspan="2" class="navibar col-md-1"><?php Print $sunDate ?></td>
					<td colspan="2" class="navibar col-md-1"><?php Print $monDate ?></td>
					<td colspan="2" class="navibar col-md-1"><?php Print $tueDate ?></td>
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
							Print 	"	<td style='padding-top: 20px; padding-bottom: 20px'>Time In: ". trim($wedTimeIn) ."</td>
										<td>Time Out: ". trim($wedTimeOut) ."</td>
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
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$thuAbsent)
						{
							Print 	"	<td>Time In: ". trim($thuTimeIn) ."</td>
										<td>Time Out: ". trim($thuTimeOut) ."</td>
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
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$friAbsent)
						{
							Print 	"	<td>Time In: ". trim($friTimeIn) ."</td>
										<td>Time Out: ". trim($friTimeOut) ."</td>
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
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$satAbsent)
						{
							Print 	"	<td>Time In: ". trim($satTimeIn) ."</td>
										<td>Time Out: ". trim($satTimeOut) ."</td>
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
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$sunAbsent)
						{
							Print 	"	<td>Time In: ". trim($sunTimeIn) ."</td>
										<td>Time Out: ". trim($sunTimeOut) ."</td>
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
							Print 	"	<td colspan='2' class='danger'> Day off </td>";
						}
						if(!$monAbsent)
						{
							Print 	"	<td>Time In: ". trim($monTimeIn) ."</td>
										<td>Time Out: ". trim($monTimeOut) ."</td>
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
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$tueAbsent)
						{
							Print 	"	<td>Time In: ". trim($tueTimeIn) ."</td>
										<td>Time Out: ". trim($tueTimeOut) ."</td>
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
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
							
					?>
				</tr>
				<tr>
					<td class="removePadding" colspan="2">
						<h4>
						<?php
							if($holWed)
								Print 	 "<span class='label label-succees'>Holiday</span>&emsp;";
							if($OtWed)
								Print 	"<span class='label label-primary'>OT</span>&emsp;";
							if($NdWed)
								Print 	"<span class='label label-warning'>ND</span>";
						?>
						</h4>
					</td>
					<td class="removePadding" colspan="2">
						<h4>
						<?php
							if($holThu)
								Print 	 "<span class='label label-succees'>Holiday</span>&emsp;";
							if($OtThu)
								Print 	"<span class='label label-primary'>OT</span>&emsp;";
							if($NdThu)
								Print 	"<span class='label label-warning'>ND</span>";
						?>
						</h4>
					</td>
					<td class="removePadding" colspan="2">
						<h4>
						<?php
							if($holFri)
								Print 	 "<span class='label label-succees'>Holiday</span>&emsp;";
							if($OtFri)
								Print 	"<span class='label label-primary'>OT</span>&emsp;";
							if($NdFri)
								Print 	"<span class='label label-warning'>ND</span>";
						?>
						</h4>
					</td>
					<td class="removePadding" colspan="2">
						<h4>
						<?php
							if($holSat)
								Print 	 "<span class='label label-succees'>Holiday</span>&emsp;";
							if($OtSat)
								Print 	"<span class='label label-primary'>OT</span>&emsp;";
							if($NdSat)
								Print 	"<span class='label label-warning'>ND</span>";
						?>
						</h4>
					</td>
					<td class="removePadding" colspan="2">
						<h4>
						<?php
							if($holSun)
								Print 	 "<span class='label label-succees'>Holiday</span>&emsp;";
							if($OtSun)
								Print 	"<span class='label label-primary'>OT</span>&emsp;";
							if($NdSun)
								Print 	"<span class='label label-warning'>ND</span>";
						?>
						</h4>
					</td>
					<td class="removePadding" colspan="2">
						<h4>
						<?php
							if($holMon)
								Print 	"<span class='label label-succees'>Holiday</span>&emsp;"; 
							if($OtMon)
								Print 	"<span class='label label-primary'>OT</span>&emsp;"; 
							if($NdMon)
								Print 	"<span class='label label-warning'>ND</span>"; 
						?>
						</h4>
					</td>
					<td class="removePadding" colspan="2">
						<h4>
						<?php
							if($holTue)
								Print 	 "<span class='label label-succees'>Holiday</span>&emsp;";
							if($OtTue)
								Print 	"<span class='label label-primary'>OT</span>&emsp;";
							if($NdTue)
								Print 	"<span class='label label-warning'>ND</span>";
						?>
						</h4>
					</td>
				</tr>
			</table>
		</div>


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
						<td style="background-color: darkgrey">
							<h4>Total undertime: <?php Print $totalUT ?></h4>
						</td>
					</tr>
				</table>


				<div class="row">
					<form class="horizontal">
						<div class="col-md-2 col-md-offset-1">
							<h4>Loans</h4>
							<div class="form-group">
								<label class="control-label col-md-3" for="sss" >SSS</label>
								<div class="col-md-9">
									<?php
									$getSSS = "SELECT sss FROM loans WHERE empid = '$empid' AND sss IS NOT NULL ORDER BY date DESC";
									$getPAGIBIG = "SELECT pagibig FROM loans WHERE empid = '$empid' AND pagibig IS NOT NULL ORDER BY date DESC";
									$getVALE = "SELECT vale FROM loans WHERE empid = '$empid' AND vale IS NOT NULL ORDER BY date DESC";
									//Query
									$sssQuery = mysql_query($getSSS);
									$pagibigQuery = mysql_query($getPAGIBIG);
									$valeQuery = mysql_query($getVALE);

									//Get the number of Rows
									$sssNum = mysql_num_rows($sssQuery);
									$pagibigNum = mysql_num_rows($pagibigQuery);
									$valeNum = mysql_num_rows($valeQuery);
									if($sssNum > 0)
									{
										while($sssLatests = mysql_fetch_assoc($sssQuery))
										{	
											
											if($sssLatests['sss'] != NULL)
											{
												$sss = $sssLatests['sss'];
												break 1;
											}
											else
											{
												$sss = "N/A";
											}
										}
									}
									else
									{
										$sss = "N/A";
									}

									if($pagibigNum > 0)
									{
										while($pagibigLatest = mysql_fetch_assoc($pagibigQuery))
										{
											if($pagibigLatest['pagibig'] != NULL)
											{
												$pagibig = $pagibigLatest['pagibig'];
												break 1;
											}
											else
											{
												$pagibig = "N/A";
											}
										}
									}
									else
									{
										$pagibig = "N/A";
									}

									if($valeNum > 0)
									{
										while($valeLatest = mysql_fetch_assoc($valeQuery))
										{
											if($valeLatest['vale'] != NULL)
											{
												$vale = $valeLatest['vale'];
												break 1;
											}
											else
											{
												$vale = "N/A";
											}
										}
									}
									else
									{
										$vale = "N/A";
									}
									if($sss != "N/A")
									{
										Print "<input type='text' id='sss' class='form-control input-sm' placeholder='".number_format($sss, 2, '.', ',')." PHP' onkeypress='validatenumber(event)'>";
									}
									else
									{
										Print "<input type='text' id='sss' class='form-control input-sm' placeholder='N/A' onkeypress='validatenumber(event)' readonly>";
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3" for="pagibig">Pag-IBIG</label>
								<div class="col-md-9">
									<?php
									if($pagibig != "N/A")
									{
										Print "<input type='text' id='pagibig' class='form-control input-sm' placeholder='".number_format($pagibig, 2, '.', ',')." PHP' onkeypress='validatenumber(event)'>";
									}
									else
									{
										Print "<input type='text' id='pagibig' class='form-control input-sm' placeholder='N/A' onkeypress='validatenumber(event)' readonly>";
									}
									?>
								</div>
							</div>
						</div>


						<div class="col-md-1">
							<h4 class="text-left">Vale</h4>
								<h5 class="text-right" style="white-space: nowrap;">
									<span class="vale pull-right">
										<?php 
										if($vale != "N/A")

								        Print number_format($vale, 2, '.', ',');
								        else
								        Print $vale;	
										?>
									</span>
									<br>
									<span id="dynamicCompute"></span>
								</h5>
								<input type="hidden" class="added">
								<input type="hidden" class="deducted">
								<div class="row">
									<button type='button' class='btn btn-success btn-sm col-md-12' data-toggle='modal' data-target='#addVale'><span class='glyphicon glyphicon-plus'></span> Add new</button>
									<?php
									if($vale != "N/A")
									{
									Print "
									<button type='button' class='btn btn-danger btn-sm col-md-12' data-toggle='modal' data-target='#deductVale'><span class='glyphicon glyphicon-minus'></span> Deduct</button>";
									}
									?>
								</div>
						</div>

						<div class="col-md-3">
							<h4 class="text-left">Contributions</h4>
							<div class="form-group">
								<label class="control-label col-md-5" for="tax">Tax</label>
								<div class="col-md-7">
									<input type="text" id="tax" class="form-control input-sm" onkeypress="validatenumber(event)">
								</div>
								<label class="control-label col-md-5" for="sssContribution">SSS</label>
								<div class="col-md-7">
									
									<input type="text" id="sssContribution" placeholder="No document" class="form-control input-sm" value="<?php Print $deductionSSS?>" onkeypress="validatenumber(event)" readonly>
									
								</div>
								<label class="control-label col-md-5" for="pagibigContribution" style="white-space: nowrap;">Pag-IBIG</label>
								<div class="col-md-7">
									<input type="text" id="pagibigContribution" class="form-control input-sm" value="<?php Print $deductionPagibig?>" placeholder="No document" readonly>
								</div>
								<label class="control-label col-md-5" for="philhealth">PhilHealth</label>
								<div class="col-md-7">
									<input type="text" id="philhealth" placeholder="No document" class="form-control input-sm" value="<?php Print $deductionPhilhealth?>" onkeypress="validatenumber(event)" readonly>
								</div>
							</div>
						</div>


						<div class="col-md-5">
								<h4 class="text-left">Allowance</h4>
								<div class="form-group">
									<label class="control-label col-md-3">Weekly</label>
									<div class="col-md-3">
										<input type="text" id="allowance" class="form-control input-sm" placeholder="500.00 PHP">
									</div>
									<label class="control-label col-md-2">Extra</label>
									<div class="col-md-3">
										<input type="text" id="allowance" class="form-control input-sm">
									</div>
								</div>


							<div class="col-md-12">
								<h4 class="text-left">Tools</h4>
								<a class="btn btn-sm btn-primary col-md-1" onclick="addRow()"><span class="glyphicon glyphicon-plus"></span></a>
								<div class="form-group" id="toolform">
									<div id="1">
										<label class="control-label col-md-2" for="tools">Name</label>
										<div class="col-md-4">
											<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onkeypress="validateletter(event)">
										</div>
										<label class="control-label col-md-1" for="price">Price</label>
										<div class="col-md-4">
											<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)">
										</div>
									</div>	
								</div>
							</div>


							<!-- TEMPLATE -->
							<div class="form-group" id="template" style="display:none">
								<label class="control-label col-md-2" for="tools">Name</label>
								<div class="col-md-4">
									<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onkeypress="validateletter(event)">
								</div>
								<label class="control-label col-md-1" for="price">Price</label>
								<div class="col-md-4">
									<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)">
								</div>
							</div>
						</div>

					</form>
				</div>
				

				<br>


				<!-- MODALS -->
				<div class="modal fade" id="addVale">
				  <div class="modal-dialog modal-sm" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Add new vale</h4>
				      </div>
				      <div class="modal-body">
				      	<div class="row">
				      		<div class="col-md-8 col-md-offset-2">
				        		<input type="text" id="newAddVale" class="form-control" placeholder="Add as new vale" onkeypress="validatenumber(event)">
				    		</div>
				    	</div>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary" onclick="addvale()" data-dismiss="modal">Add</button>
				      </div>
				    </div>
				  </div>
				</div>

				<div class="modal fade" id="deductVale">
				  <div class="modal-dialog modal-sm" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Deduct old vale</h4>
				      </div>
				      <div class="modal-body">
				      	<div class="row">
				      		<div class="col-md-8 col-md-offset-2">
				        		<input type="text" id="newDeductVale" class="form-control col-md-4" placeholder="Deduct from old vale" onkeypress="validatenumber(event)">
				        	</div>
				        </div>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary" onclick="deductvale()" data-dismiss="modal">Deduct</button>
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
<script>
document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");

var ct = 1;
function addRow(){
	ct++;
	var div1 = document.createElement('div');
	div1.id = ct;
	var delLink = '<div class="col-md-1" style="padding:0px"><button class="btn-sm btn btn-danger" onclick="deleteRow('+ ct +')"><span class="glyphicon glyphicon-minus"></span></button></div>';
	div1.innerHTML = delLink + document.getElementById('template').innerHTML;
	document.getElementById('toolform').appendChild(div1);
}

function deleteRow(eleId){
	var ele = document.getElementById(eleId);
	var parentEle = document.getElementById('toolform');
	parentEle.removeChild(ele);
	console.log(parentEle);
	console.log(ele);
	console.log(parentEle.children);
}

function validatenumber(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /[0-9]|\./;
	if( !regex.test(key) ) {
		 theEvent.returnValue = false;
	if(theEvent.preventDefault) 
		theEvent.preventDefault();
	}
}

function validateletter(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /^[a-zA-Z ]*$/;
	if( !regex.test(key) ) {
		 theEvent.returnValue = false;
	if(theEvent.preventDefault) 
		theEvent.preventDefault();
	}
}

function validateprice(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /^[0-9.,]+$/;
	if( !regex.test(key) ) {
		 theEvent.returnValue = false;
	if(theEvent.preventDefault) 
		theEvent.preventDefault();
	}
}
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

// Adding new vale and displaying an addition format
function addvale() {

	// Exception when Vale is N/A


	// Get current amount in vale
	var original = document.querySelector(".vale");
	var oldVale = original.innerHTML;
	var division = oldVale.split(',');
	var len = division.length;
	var builder="";

	for(var a=0; a<len; a++)
	{
		
		builder += division[a];
		
	}
	
	builder = parseFloat(builder).toFixed(2);

	// Get vale from modal and format to currency
	var modalValue = document.querySelector("#newAddVale").value;
	var addVale = parseFloat(modalValue).toFixed(2); 

	// Append innerHTML addition format
	var computation = document.querySelector('#dynamicCompute');
	computation.innerHTML = "<span style='right:5px;position:relative;'>+</span>" + addCommas(addVale) + "<br>";

	// Add border to show computation
	var result = document.createElement("div");
	result.setAttribute("class","result pull-right");
	result.style.borderTop = "thin solid black";

	if(oldVale.trim()!=="N/A")
	{
		var compute = parseFloat(builder) + parseFloat(addVale);
	}
	else
	{
		var compute = parseFloat(addVale);
	}

	// Add computed value
	result.innerHTML = addCommas(compute.toFixed(2));

	computation.appendChild(result);

	// Save to hidden input for database access
	if(document.querySelector('.deducted').value)
	{
		var saveToAdd = document.querySelector(".added");
		saveToAdd.value = modalValue;
		var removePrevious = document.querySelector('.deducted');
		removePrevious.value = "";
		document.querySelector("#newDeductVale").value = "";
	}
	else
	{
		var saveToAdd = document.querySelector(".added");
		saveToAdd.value = modalValue;
	}
	
}

// Subtracting new vale and displaying an subtraction format
function deductvale() {

	// Get current amount in vale
	var original = document.querySelector(".vale");
	var oldVale = original.innerHTML;
	var division = oldVale.split(',');
	var len = division.length;
	var builder="";

	for(var a=0; a<len; a++)
	{
		
		builder += division[a];
		
	}
	
	builder = parseFloat(builder).toFixed(2);

	// Get vale from modal and format to currency
	var modalValue = document.querySelector("#newDeductVale").value;
	var addVale = parseFloat(modalValue).toFixed(2); 


	// Add border to show computation
	var result = document.createElement("div");
	result.setAttribute("class","result pull-right");
	result.style.borderTop = "thin solid black";

	var compute = parseFloat(builder) - parseFloat(addVale);
	
	if(compute<1)
	{
		alert("You entered an invalid amount for vale.");
	}
	else
	{
		// Append innerHTML addition format
		var computation = document.querySelector('#dynamicCompute');
		computation.innerHTML = "<span style='right:5px;position:relative;'>-</span>"+addCommas(addVale)+"<br>";

		// Add computed value
		result.innerHTML = addCommas(compute.toFixed(2));

		computation.appendChild(result);

		// Save to hidden input for database access
		if(document.querySelector('.added').value)
		{
			var saveToAdd = document.querySelector(".deducted");
			saveToAdd.value = modalValue;
			var removePrevious = document.querySelector('.added');
			removePrevious.value = "";
			document.querySelector("#newAddVale").value = "";
		}
		else
		{
			var saveToAdd = document.querySelector(".deducted");
			saveToAdd.value = modalValue;
		}
	}
	
	

	
	
}

</script>

</div>
</body>
</html>
