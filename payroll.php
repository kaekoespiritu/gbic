<!DOCTYPE html>
<?php
include('directives/session.php');
require_once('directives/db.php');
if(!isset($_GET['site']) && !isset($_GET['position']))
{
	header("location:payroll_login.php");
}

// Check if the payroll is early cutoff
$earlyCutoff = false;
if(isset($_SESSION['earlyCutoff']))
{
	// earlyCutoff = start
	// payrollDate = end 
	echo "<script>console.log('payrollDate: ".$_SESSION['payrollDate']." | earlyCutoff: ".$_SESSION['earlyCutoff']."')</script>";
	$earlyCutoff = true;
}

$site = $_GET['site'];
$position = $_GET['position'];
$empid = $_GET['empid'];

$date = (isset($_SESSION['payrollDate']) ? $_SESSION['payrollDate'] : strftime("%B %d, %Y")); // Gets the payroll date if admin didn't finish the payroll for the week
// $date = "November 7, 2018";

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
if($earlyCutoff)// early cutoff
{
	$validateDays = array($day1, $day2, $day3, $day4, $day5, $day6, $day7, $day8, $day9, $day10, $day11, $day12, $day13, $day14);

	$endDateEarly = date('F d, Y', strtotime('+6 day', strtotime($_SESSION['earlyCutoff'])));
	echo "<script>console.log('endDateEarly: ".$endDateEarly."')</script>"; 
	
	$displayDay1 = $endDateEarly;
	$displayDay2 = date('F d, Y', strtotime('-1 day', strtotime($endDateEarly)));
	$displayDay3 = date('F d, Y', strtotime('-2 day', strtotime($endDateEarly)));
	$displayDay4 = date('F d, Y', strtotime('-3 day', strtotime($endDateEarly)));
	$displayDay5 = date('F d, Y', strtotime('-4 day', strtotime($endDateEarly)));
	$displayDay6 = date('F d, Y', strtotime('-5 day', strtotime($endDateEarly)));
	$displayDay7 = date('F d, Y', strtotime('-6 day', strtotime($endDateEarly)));
}
else// normal payroll
{
	$validateDays = array($day1, $day2, $day3, $day4, $day5, $day6, $day7, $day8, $day9, $day10, $day11, $day12, $day13, $day14);
	// for date display
	$displayDay1 = $day1;
	$displayDay2 = $day2;
	$displayDay3 = $day3;
	$displayDay4 = $day4;
	$displayDay5 = $day5;
	$displayDay6 = $day6;
	$displayDay7 = $day7;
}

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
	$attCheck = "SELECT * FROM attendance WHERE empid = '$empid' AND date = '$validate' AND attendance != '2' LIMIT 1";
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

$disableCompute = 0; // Incremental Value to check if employee has no attendance. this is for disabling the save and compute button
$disableComputeAdj = 0; // Incremental Value to check if employee has no attendance. this is for disabling the save and compute button
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
			?>
			
			<!-- Breadcrumbs -->
			<input type="hidden" name="employeeID" value="<?php Print $empid?>">
			<div class="row pull-down">
				
				<!-- DUMMY MODAL FOR Attendance adjustments -->
				<div class="modal fade" id="attendanceAdjustment">
					<div class="modal-dialog modal-lg" role="document" style="width: 100% !important; height: 100% !important; margin: 10; padding: 10;">
					    <div class="modal-content" >
					      	<div class="modal-header">
					        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					        		<span aria-hidden="true">&times;</span>
					        	</button>
					        	<h4 class="modal-title" id="myModalLabel">Attendance Adjustment</h4>
					      	</div>
					      	<div class="modal-body">
					        	<div class="row">
					          	<!-- Insert date picker and call attendance row here -->
					          		Choose a date: <input type="text" id="dateValue" readonly>
					          	<div id="adjustmentFields" class="pull-down"></div>
					          	</div>
					          	<?php
					          		$badgeCounter = 0;// this is for the adjust attendance badge
					          		$adjDateArr = "";
					          		$checkAttAdjustments = "SELECT * FROM payroll_adjustment WHERE payroll_date = '$date' AND empid = '$empid'";
					          		// Print $checkAttAdjustments;
					          		$checkAttAdjustmentQuery = mysql_query($checkAttAdjustments);
					          		if(mysql_num_rows($checkAttAdjustmentQuery) > 0)
					          		{
					          			$counter = 20;// this is for the ID of the table
					          			 
					          			$checkAdjArr = mysql_fetch_assoc($checkAttAdjustmentQuery);
					          			$persistAdjustDate = explode('+', $checkAdjArr['dates']);

					          			$attendanceAdjDates = "";
					          			foreach($persistAdjustDate as $adjDate)
					          			{
					          				if($attendanceAdjDates != "")
					          					$attendanceAdjDates .= " OR ";
					          				$attendanceAdjDates .= " date = '".$adjDate."'";
					          			}
					          			// Gets the position of the employee being payrolled
					          			$empPositionQuery = mysql_query("SELECT position FROM employee WHERE empid = '$empid'");
					          			$empPosition = mysql_fetch_assoc($empPositionQuery);

					          			$empNameQuery = mysql_query("SELECT firstname, lastname FROM employee WHERE empid = '$empid'");
					          			$empName = mysql_fetch_assoc($empNameQuery);

					          			$attendanceDate = "SELECT * FROM attendance WHERE empid = '$empid' AND ($attendanceAdjDates)";
					          			$attendanceDateQuery = mysql_query($attendanceDate);
					          			while($adjDate = mysql_fetch_assoc($attendanceDateQuery))
					          			{
					          				if($adjDateArr != "")
					          					$adjDateArr .= ',';
					          				$adjDateArr .= '"'.$adjDate['date'].'"';
					          				$isSunday = (date('l', strtotime($adjDate['date'])) == 'Sunday' ? true : false);
					          				$isDriver = (strtolower($empPosition['position']) == 'driver' || strtolower($empPosition['position']) == 'pahinante' ? 1 : 0);
					          				
					          				$workingHours = '';
					          				$overtimeHours = '';
					          				$undertimeHours = '';
					          				$nightdiffHours = '';

					          				$workingHoursDisp = explode('.',$adjDate['workhours']);
					          				if(Count($workingHoursDisp) > 1)
					          				{
					          					$workingHours = $workingHoursDisp[0]." hrs, ".$workingHoursDisp[1]." mins";
					          				}
					          				else
					          				{
					          					if($workingHoursDisp[0] != 0)
					          						$workingHours = $workingHoursDisp[0]." hrs";
					          				}

					          				$overtimeDisp = explode('.',$adjDate['overtime']);
					          				if(Count($overtimeDisp) > 1)
					          				{
					          					if($overtimeDisp[0] == 0)// only minutes
					          					{
					          						$overtimeHours = $overtimeDisp[1]." mins";
					          					}
					          					else
					          					{
					          						$overtimeHours = $overtimeDisp[0]." hrs, ".$overtimeDisp[1]." mins";
					          					}
					          				}
					          				else
					          				{
					          					if($overtimeDisp[0] != 0)
					          						$overtimeHours = $overtimeDisp[0]." hrs";
					          				}

					          				$undertimeDisp = explode('.',$adjDate['undertime']);
					          				if(Count($undertimeDisp) > 1)
					          				{
					          					if($overtimeDisp[0] == 0)// only minutes
					          					{
					          						$undertimeHours = $undertimeDisp[1]." mins";
					          					}
					          					else
					          					{
					          						$undertimeHours = $undertimeDisp[0]." hrs, ".$undertimeDisp[1]." mins";
					          					}
					          				}
					          				else
					          				{
					          					if($undertimeDisp[0] != 0)
					          						$undertimeHours = $undertimeDisp[0]." hrs";
					          				}

					          				$nightdiffDisp = explode('.',$adjDate['nightdiff']);
					          				if(Count($nightdiffDisp) > 1)
					          				{
					          					if($overtimeDisp[0] == 0)// only minutes
					          					{
					          						$nightdiffHours = $nightdiffDisp[1]." mins";
					          					}
					          					else
					          					{
					          						$nightdiffHours = $nightdiffDisp[0]." hrs, ".$nightdiffDisp[1]." mins";
					          					}
					          				}
					          				else
					          				{
					          					if($nightdiffDisp[0] != 0)
					          						$nightdiffHours = $nightdiffDisp[0]." hrs";
					          				}

					          				// Attendance status
					          				switch($adjDate['attendance'])
					          				{
					          					case 1: $attendanceStatus = "ABSENT"; break;
					          					case 2: $attendanceStatus = "PRESENT"; break;
					          					case 3: $attendanceStatus = "NOWORK"; break;
					          					default: $attendanceStatus = "";
					          				}

					          				//extra allowance
					          				$xallowBadge = 'badge';
					          				$xallowBadgeNum = '';
					          				if($adjDate['xallow'] == 0)
					          				{
					          					$xallowBadge = '';
					          				}
					          				else
					          				{
					          					$xallowBadgeNum = $adjDate['xallow'];
					          				}

					          				
					          				Print "
						          			<table class='table table-bordered table-responsive'>
												<tr>
													<td colspan='17'>
														<input type='hidden' name='adjustmentDate[]' value='".$adjDate['date']."'>
														<h2 class='dateheader text-center col-md-11 col-md-push-1'>".$adjDate['date']."</h2>
														<input type='button' class='btn btn-danger col-md-1' value='Remove' onclick='removeAdjustment(this, \"".$adjDate['date']."\")'>
													</td>
												</tr>
												<tr class='attendance-header'>
														  <td colspan='2'>Auto</td>
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
											              <td colspan='2'>Actions</td>
											    </tr>
												<tr class='input-fields success' id='input-field-".$counter."'>
													
													<input type='hidden' class='driver' value='".$isDriver."' >";
												if($isSunday)
												    Print "<input type='hidden' id='isSunday'>";
													Print"
													<!-- Auto -->
													<td>
														<input type='button' value='8-5' class='btn btn-primary auto' onclick='AutoTimeIn85(\"input-field-".$counter."\")'>
													</td>
													<td>
														<input type='button' value='7-4' class='btn btn-primary auto' onclick='AutoTimeIn74(\"input-field-".$counter."\")'>
													</td>
													<!-- Time In -->
													<td>
														<input type='text' onblur='timeValidation(this)' class='timein1 timepicker form-control input-sm' value='".$adjDate['timein']."' name='timein1[]'>
													</td> 
													<!-- Time Out-->
													<td>
														<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' value='".$adjDate['timeout']."' name='timeout1[]'>
													</td> 
													<!-- Half Day Checkbox-->
													<td>
														<input type='checkbox' class='halfdayChk' name='halfday[]' onclick='halfDay(\"input-field-".$counter."\")' disabled>
													</td>
													<!-- AFTER BREAK Time In -->
													<td>
														<input type='text' onblur='timeValidation(this)' class='timein2 timepicker form-control input-sm' value='".$adjDate['afterbreak_timein']."'  name='timein2[]'>
													</td> 
													<!-- AFTER BREAK Time Out-->
													<td>
														<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' value='".$adjDate['afterbreak_timeout']."' name='timeout2[]'>
													</td> 
													<!-- Night Shift Checkbox-->
													<td>
														<input type='checkbox' class='nightshiftChk' name='nightshift[]' onclick='nightshift_ChkBox('input-field-".$counter."')' disabled>
													</td>
													<!-- NIGHT SHIFT Time In -->
													<td>
														<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value='".$adjDate['nightshift_timein']."'  name='timein3[]' readonly>
													</td> 
													<!-- NIGHT SHIFT Time Out-->
													<td>
														<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='".$adjDate['nightshift_timeout']."' name='timeout3[]' readonly>
													</td> 
													<!-- Working Hours -->
													<td>
														<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='".$workingHours."' disabled>
														<input type='hidden' value='".$workingHours."' class='workinghoursH'  name='workinghrs[]' >
													</td> 
													<!-- Overtime -->
													<td>
														<input type='text' placeholder='--' class='form-control input-sm overtime' value='".$overtimeHours."'  disabled>
														<input type='hidden' class='overtimeH' name='othrs[]' value='".$overtimeHours."'>
													</td> 
													<!-- Undertime -->
													<td>
														<input type='text' placeholder='--' class='form-control input-sm undertime' value='".$undertimeHours."' disabled>
														<input type='hidden' class='undertimeH' name='undertime[]' value='".$undertimeHours."'>
													</td>
													<!-- Night Differential --> 
													<td>
														<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='".$nightdiffHours."' disabled>
														<input type='hidden' class='nightdiffH' name='nightdiff[]' value='".$nightdiffHours."'>
													</td>

													<!-- Extra allowance Input --> 
														<input type='hidden' name='xallow[]' class='hiddenXAllow' value='".$adjDate['xallow']."'>
									
													<!-- Remarks Input --> 
														<input type='hidden' name='remarks[]' class='hiddenRemarks' value='".$adjDate['remarks']."'>

													<!-- Attendance Status -->
														<input type='hidden' name='attendance[]' class='attendance' value='".$attendanceStatus."'>
													<!-- Remarks Button --> 
													<td>
														<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarksFunc(".$counter.")'>Remarks <span class='icon'></span></a>
													</td>
													<!-- Extra Allowance Button --> 
													<td>
														<a class='btn btn-sm btn-primary xallowance' data-toggle='modal' data-target='#XAllowanceModal' onclick='xAllowanceFunc(\"input-field-".$counter."\")'>X Allow <span class='xall-icon ".$xallowBadge."' id='xAllowValue'>
															$xallowBadgeNum</span></a>
													</td>
												</tr>
											</table>";
											$counter++;
											$badgeCounter++;

											$disableComputeAdj++;
					          			}	
					          		}
					          	?>
					      	</div>
					    </div>
					</div>
				</div>
				<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
					<ol class="breadcrumb text-left" style="margin-bottom: 0px">

						<li><a href="payroll_table.php?position=<?php Print $position?>&site=<?php Print $site?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Table of Employees</a></li>
						<li class="active"><?php Print "Payroll for site " .$site." on ".$date ?></li>

						<button type="submit" class="btn btn-success pull-right" id="saveCompute" style="margin-right:5px" href="#" data-toggle="tooltip" data-placement="bottom" title="Note: Proceeding will prevent you from editing values entered. If you need to come back here and change anything, you will have to redo everything.">Save and compute</button>

						<!-- <input type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#attendanceAdjustment" value="Make attendance adjustment"> -->
						<a class="btn btn-danger pull-right" data-toggle="modal" data-target="#attendanceAdjustment">Make attendance adjustment <span class="badge" id="badge"><?php Print $badgeCounter?></span></a>
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
						$deductionSSS = $deductionSSS;
					}
					if($deductionPagibig == 0)
					{
						$deductionPagibig = "";
					}
					else
					{
						$deductionPagibig = $deductionPagibig;
					}
					if($deductionPhilhealth == 0)
					{
						$deductionPhilhealth = "";
					}
					else
					{
						$deductionPhilhealth = $deductionPhilhealth;
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
					$payrollDate = "SELECT * FROM attendance WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$day7', '%M %e, %Y') AND STR_TO_DATE('$day1', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC";
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
					//for no work dates
					$monNoWork = false;
					$tueNoWork = false;
					$wedNoWork = false;
					$thuNoWork = false;
					$friNoWork = false;
					$satNoWork = false;
					$sunNoWork = false;
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
					$AttExtraAllowance = 0;// extra allowance that has accumulated through the attendance


					// Check Holiday
					// $holDateChecker = $dateRow['date'];

					//Holiday Checker
					$holiday = "SELECT * FROM holiday WHERE STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$day7', '%M %e, %Y') AND STR_TO_DATE('$day1', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC";
					// echo $holiday."<br>";

					$holidayQuery = mysql_query($holiday);
					$holidayExist = mysql_num_rows($holidayQuery);

					
					if($holidayExist > 0)//if holiday exist
					{
						while($holidayRow = mysql_fetch_assoc($holidayQuery))
						{
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
							
					}

					while($dateRow = mysql_fetch_assoc($payrollQuery))
					{
						$day = date('l', strtotime($dateRow['date']));
						if($day == "Sunday" && $sunBool)
						{
							$sunDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$disableCompute++;
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

								$AttExtraAllowance += $dateRow['xallow'];// Gets the extra allowance
								//For badge of Night diff and Overtime
								if($dateRow['nightdiff'] != 0)
									$NdSun = true;
								if($dateRow['overtime'] != 0)
									$OtSun = true;
								$allowCounter++; //Counter for allowance
							}
							else if($dateRow['attendance'] == 1)// absent
							{
								$sunAbsent = true;
							}
							else if($dateRow['attendance'] == 3)// no work
							{
								$sunNoWork = true;
							}
							$sunBool = false;
						}
						else if($day == "Monday" && $monBool)// Monday
						{
							$monDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$disableCompute++;
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

								$AttExtraAllowance += $dateRow['xallow'];// Gets the extra allowance
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
							else if($dateRow['attendance'] == 3)// no work
							{
								$monNoWork = true;
							}
							$monBool = false;
							
						}
						else if($day == "Tuesday" && $tueBool)//Tuesday
						{
							$tueDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$disableCompute++; // Increment disableCompute 
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

								$AttExtraAllowance += $dateRow['xallow'];// Gets the extra allowance
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
							else if($dateRow['attendance'] == 3)// no work
							{
								$tueNoWork = true;
							}
							$tueBool = false;
						}
						else if($day == "Wednesday" && $wedBool)//Wednesday
						{
							$wedDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$disableCompute++; // Increment disableCompute 
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

								$AttExtraAllowance += $dateRow['xallow'];// Gets the extra allowance
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
							else if($dateRow['attendance'] == 3)// no work
							{
								$wedNoWork = true;
							}
							$wedBool = false;
							
						}
						else if($day == "Thursday" && $thuBool)//Thursday
						{
							$thuDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$disableCompute++; // Increment disableCompute 
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

								$AttExtraAllowance += $dateRow['xallow'];// Gets the extra allowance
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
							else if($dateRow['attendance'] == 3)// no work
							{
								$thuNoWork = true;
							}
							$thuBool = false;
						}
						else if($day == "Friday" && $friBool)//Friday
						{	
							$friDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$disableCompute++; // Increment disableCompute 
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

								$AttExtraAllowance += $dateRow['xallow'];// Gets the extra allowance
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
							else if($dateRow['attendance'] == 3)// no work
							{
								$friNoWork = true;
							}
							$friBool = false;
						}
						else if($day == "Saturday" && $satBool)//Saturday
						{
							$satDate = $dateRow['date'];//Get the day of the week
							if($dateRow['attendance'] == 2)//Present
							{
								$disableCompute++; // Increment disableCompute 
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

								$AttExtraAllowance += $dateRow['xallow'];// Gets the extra allowance
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
							else if($dateRow['attendance'] == 3)// no work
							{
								$satNoWork = true;
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
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $displayDay7 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $displayDay6 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $displayDay5 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $displayDay4 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $displayDay3 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $displayDay2 ?></td>
						<td colspan="2" class="navibar col-md-1 col-lg-1"><?php Print $displayDay1 ?></td>
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
							else if($wedNoWork || $earlyCutoff)
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else if($earlyCutoff)
						{
							Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
						}
						else if($wedAbsent)
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
							else if($thuNoWork || $earlyCutoff)
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else if($earlyCutoff)
						{
							Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
						}
						else if($thuAbsent)
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
							else if($friNoWork || $earlyCutoff)
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else if($earlyCutoff)
						{
							Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
						}
						else if($friAbsent)
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
							else if($satNoWork || $earlyCutoff)
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else if($earlyCutoff)
						{
							Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
						}
						else if($satAbsent)
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
							else if($sunNoWork || $earlyCutoff)
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Day off </td>";
							}
						}
						else if($earlyCutoff)
						{
							Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
						}
						else if($sunAbsent)
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
							else if($monNoWork || $earlyCutoff)
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else if($earlyCutoff)
						{
							Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
						}
						else if($monAbsent)
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
							else if($tueNoWork || $earlyCutoff)
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
							}
							else
							{
								Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
							}
						}
						else if($earlyCutoff)
						{
							Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
						}
						else if($tueAbsent)// Absent
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
								else if($payrollRow == '3' && !$holWed && !$wedAbsent && !$wedNoWork)
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
								else if($payrollRow == '3' && !$holThu && !$thuAbsent && !$thuNoWork)
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
								else if($payrollRow == '3' && !$holFri && !$friAbsent && !$friNoWork)
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
								else if($payrollRow == '3' && !$holSat && !$satAbsent && !$satNoWork)
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
									else if($payrollRow == '3' && !$holSun && !$sunAbsent && !$sunNoWork)
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
								else if($payrollRow == '3' && !$holMon && !$monAbsent && !$monNoWork)
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
								else if($payrollRow == '3' && !$holTue && !$tueAbsent && !$tueNoWork)
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
						<h4>Total hours rendered: <?php Print numberExactFormat($totalHours, 2, '.', false)?></h4>
					</td>
					<td style="background-color: lemonchiffon">
						<h4>Total overtime: <?php Print numberExactFormat($totalOT, 2, '.', false) ?></h4>
					</td>
					<td style="background-color: powderblue">
						<h4>Total night differential: <?php Print numberExactFormat($totalNightDiff, 2, '.', false) ?></h4>
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
					
					$getSSS = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'SSS' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
					$getPAGIBIG = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'PagIBIG' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
					
					$getOldVALE = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
					$getNewVALE = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'newVale' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";

					// Make a query to get previous payroll grandTotal amount
					$payrollOutstanding = "SELECT total_salary FROM `payroll` WHERE empid = '$empid' AND total_salary < 0 AND date='$day7' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
					$payrollOutstandingQuery = mysql_query($payrollOutstanding);
					$payrollOutstandingArr = mysql_fetch_assoc($payrollOutstandingQuery);

							//Query
					$sssQuery = mysql_query($getSSS);
					$pagibigQuery = mysql_query($getPAGIBIG);
					$oldValeQuery = mysql_query($getOldVALE);
					$newValeQuery = mysql_query($getNewVALE);

							//SSS Loan
					if(mysql_num_rows($sssQuery) > 0)
					{
						$sssArr = mysql_fetch_assoc($sssQuery);

						if($sssArr['monthly'] > 0)
							$sss = $sssArr['monthly'];
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

						if($pagibigArr['monthly'] > 0)
							$pagibig = $pagibigArr['monthly'];
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
							$sssDisplay = "";
							if($sss != "N/A")
							{
								Print "<span class='pull-right' id='sssValue'>".numberExactFormat($sss, 2, '.', true)."</span>";
								$sssDisplay = numberExactFormat($sss, 2, '.', false)/4;
							}
							else
							{
								Print "--";
							}

							?>
						</div>
						<div class="col-md-1 col-lg-12">
							<input type="number" class="form-control" id="sssDeduct" name="sssDeduct" placeholder="To deduct" onblur="addDecimal(this)" value="<?php Print $sssDisplay?>" onchange="setsssLimit(this)">
						</div>
					</div>
					<div class="form-group row">
						<label class="control-label col-md-3 col-lg-3" for="pagibig" style="white-space: nowrap;">Pag-IBIG</label>
						<div class="col-md-9 col-lg-9">
							<?php
							$pagibigDisplay = "";
							if($pagibig != "N/A")
							{
								Print "<span class='pull-right' id='pagibigValue'>".numberExactFormat($pagibig, 2, '.', true)."</span>";
								$pagibigDisplay =  numberExactFormat($pagibig, 2, '.', false)/4;
							}
							else
							{
								Print "--";
							}
							?>
						</div>
						<div class="col-md-1 col-lg-12">
							<input type="number" class="form-control" id="pagibigDeduct" name="pagibigDeduct" placeholder="To deduct" onblur="addDecimal(this)" value="<?php Print $pagibigDisplay?>" onchange="setpagibigLimit(this)">
						</div>
					</div>
					<!-- OUTSTANDING PAYROLL -->
					<div class="form-group row">
						<label class="control-label col-md-3 col-lg-3" for="sss" >Outstanding Payroll</label>
						<div class="col-md-9 col-lg-9">
							<?php
							if(mysql_num_rows($payrollOutstandingQuery) > 0)
							{
								Print "<span class='pull-right' id='payrollOutstandingValue'>".numberExactFormat(abs($payrollOutstandingArr["total_salary"]), 2, '.', true)."</span>";
							}
							else
							{
								Print "--";
							}
							?>
						</div>
						<div class="col-md-1 col-lg-12">
							<input type="number" class="form-control" id="outstandingPayrollDisplay" name="outstandingPayrollDisplay" value="<?php if(mysql_num_rows($payrollOutstandingQuery) > 0) Print abs($payrollOutstandingArr['total_salary']); else Print '--'?>" placeholder="Excess from last payroll" readonly>
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
									Print "<span class='pull-right' id='oldvaleValue'>".numberExactFormat($oldVale, 2, '.', true)."</span";
								else
									Print $oldVale;	
								?>
							</span>
						</h5>
						<div class="row">
							<input type='number' placeholder='Deduct' id='oldValeDeduct' name='oldValeDeduct'class='form-control input-sm pull-down' onchange='setoldvaleLimit(this)'>
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
						if($colaValue == 0)
						{
							$colaValue = "";
						}
						$insurance = $empArr['insurance'];
						if($insurance == 0)
						{
							$insurance = "";
						}
						?>
						<h4>COLA</h4>
						<input type="number" value="<?php Print $colaValue?>" name="cola" class="form-control">
						<h4>Insurance</h4>
						<input type="number" value="<?php Print $insurance?>" name="insurance"  class="form-control">
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
							<input type="text" id="sssContribution" name="sss" placeholder="No document" class="form-control input-sm" value="<?php Print $deductionSSS?>" onkeypress="validatenumber(event)" >

						</div>
						<label class="control-label col-md-5 col-lg-5" for="pagibigContribution" style="white-space: nowrap;">Pag-IBIG</label>
						<div class="col-md-7 col-lg-7">
							<input type="text" id="pagibigContribution" name="pagibig" class="form-control input-sm" value="<?php Print $deductionPagibig?>" placeholder="No document" >
						</div>
						<label class="control-label col-md-5 col-lg-5" for="philhealth">PhilHealth</label>
						<div class="col-md-7 col-lg-7">
							<input type="text" id="philhealth" name="philhealth" placeholder="No document" class="form-control input-sm" value="<?php Print $deductionPhilhealth?>" onkeypress="validatenumber(event)" >
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
					$overallAllow = numberExactFormat($overallAllow, 2, '.', true);
				}
				if($AttExtraAllowance == 0)// If extra allowance accumulated from attendance is Zero 
					$AttExtraAllowance = "";
				// Extra allowance daily
				$overallXAllowDaily = "";
				$xAllowDaily = "";
				if($empArr['x_allow_daily'] != 0)
				{
					$overallXAllowDaily = $empArr['x_allow_daily'] * $allowCounter;
					$xAllowDaily = $empArr['x_allow_daily'];
				}
				$xAllowWeekly = "";
				if($empArr['x_allow_weekly'] != 0)
				{
					$xAllowWeekly = $empArr['x_allow_weekly'];
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
						<label class="control-label col-md-2 col-lg-2">Overall Daily</label>
						<div class="col-md-2 col-lg-2 nopadding">
							<input type="text" id="OverallAllowance" name="OverallAllowance" class="form-control input-sm" placeholder="Overall Allow."  value="<?php Print $overallAllow?>" readonly>
						</div>
					</div>

					
					<div class="col-md-1 col-lg-12">
						<h4 class="text-left">Extra Allowance</h4>
						<label class="control-label col-md-2 col-lg-2">Extra</label>
						<div class="col-md-2 col-lg-2 nopadding">
							<input type="number" id="allowance" name="extra_allowance" name="extra_allowance" class="form-control input-sm" value="<?php Print $AttExtraAllowance?>" onblur="addDecimal(this)">
						</div>
						<label class="control-label col-md-2 col-lg-2">Extra Daily</label>
						<div class="col-md-2 col-lg-2 nopadding">
							<input type="text" name="xAllowanceDaily" class="form-control input-sm" 
							value="<?php Print $xAllowDaily?>" readonly>
						</div>
						<label class="control-label col-md-2 col-lg-2">Overall Extra Daily</label>
						<div class="col-md-2 col-lg-2 nopadding">
							<input type="text" id="xAllowanceDailyOverall" name="xAllowanceDailyOverall" class="form-control input-sm"  value="<?php Print $overallXAllowDaily?>" readonly>
						</div>
					</div>
					<div class="col-md-1 col-lg-12">
						<label class="control-label col-md-2 col-lg-2">Extra Weekly</label>
						<div class="col-md-2 col-lg-2 nopadding">
							<input type="text" id="xAllowanceWeekly" name="xAllowanceWeekly" class="form-control input-sm"   value="<?php Print $xAllowWeekly?>" readonly>
						</div>
					</div>

					<!-- Tools deductions -->
					<div class="col-md-1 col-lg-12">
						<h4 class="text-left">Tools</h4>
						<span class="col-md-4 col-lg-4">Name</span>
						<span class="col-md-4 col-lg-4">Cost</span>
						<span class="col-md-4 col-lg-4">Quantity</span>
						
						<div class="form-group container-fluid" id="toolform">
							<a class="btn btn-sm btn-primary col-md-1 col-lg-1" onclick="addRow()"><span class="glyphicon glyphicon-plus"></span></a>
							<?php
							$toolsChecker = "SELECT * FROM tools WHERE empid = '$empid' AND date = '$date'";
							$toolsCheckerQuery = mysql_query($toolsChecker);
							$overallToolCost = 0;
							if(mysql_num_rows($toolsCheckerQuery) == 0)
							{
								Print '	<div class="row">
											<div class="col-md-4 col-lg-4">
												<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onchange="checkName(this)">
											</div>
											<div class="col-md-3 col-lg-3">
												<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)">
											</div>
											<div class="col-md-3 col-lg-3">
												<input type="number" id="quantity" name="toolquantity[]" class="form-control input-sm" onchange="getTotal(this)" onblur="addDecimal(this)">
											</div>
										</div>	';
							}
							else
							{
								$toolsCounter = 1;
								$toolsBoolOnce = true;
								while($toolsArr = mysql_fetch_assoc($toolsCheckerQuery))
								{
									if($toolsBoolOnce)
									{
										$toolsBoolOnce = false;
										Print '	<div class="row">
													<div class="col-md-4 col-lg-4">
														<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onchange="checkName(this)" value="'.$toolsArr['tools'].'">
													</div>
													<div class="col-md-3 col-lg-3">
														<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)" value="'.$toolsArr['cost'].'">
													</div>
													<div class="col-md-3 col-lg-3">
														<input type="text" id="quantity" name="toolquantity[]" class="form-control input-sm" onchange="getTotal(this)" onblur="addDecimal(this)" value="'.$toolsArr['quantity'].'">
													</div>
												</div>	';
											$overallToolCost += $toolsArr['cost'] * $toolsArr['quantity'];
									}
									else
									{
										Print '
											<a class="btn-sm btn btn-danger col-md-1 col-lg-1" name="rowDelete[]" onclick="deleteRow('.$toolsCounter.')">
														<span class="glyphicon glyphicon-minus"></span>
													</a>
											<div class="row">
												<div name="toolsRow[]" id="'.$toolsCounter.'">

													<div class="col-md-4 col-lg-4">
														<input type="text" id="toolstemp" name="toolname[]" class="form-control input-sm" onchange="checkName(this)" value="'.$toolsArr['tools'].'">
													</div>
													<div class="col-md-3 col-lg-3">
														<input id="pricetemp" name="toolprice[]" class="form-control input-sm toolpricetemp" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)" value="'.$toolsArr['cost'].'">
													</div> 
													<div class="col-md-3 col-lg-3">
														<input type="text" id="quantity" name="toolquantity[]" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)" value="'.$toolsArr['quantity'].'">
													</div>
												</div>
											</div>';
										$toolsCounter++;
										$overallToolCost += $toolsArr['cost'] * $toolsArr['quantity'];
										Print "<script>alert('".$toolsArr['quantity']."')</script>";
									}
								}
								
							}

							?>

						</div>
						<div class="col-md-1 col-lg-12 pull-down">
							<label class="col-md-5 col-lg-5">
								Total Cost
							</label>
							<div class="col-md-6 col-lg-6">
								<input type="text" class="form-control" id="totalcost" name="totalcost" value="<?php Print $overallToolCost?>" readonly>
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
								<?php 
								
									$toolsToPay = ($overallToolCost != 0 ? "" : "readonly");
								
								?>
								<input type="text" id="amountToPay" name="amountToPay" class="form-control" onblur="addDecimal(this)" onchange="settotalLimit(this)" <?php Print $toolsToPay?>>
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

<!-- Hidden inputs for disabled dates -->
<input type="hidden" id="disableCompute" value="<?php Print $disableCompute?>">
<input type="hidden" id="disableComputeAdj" value="<?php Print $disableComputeAdj?>">


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

<!-- DUMMY MODAL FOR EXTRA ALLOWANCE -->
<div class="modal fade" tabindex="-1" id="XAllowanceModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="AllowDisplay"></h4>
			</div>
			<div class="modal-body">
				<center>
					<input class="form-control" onkeypress="validatenumber(event)" style="width:50%;"id="xAllowanceInput"  maxlength="20">
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveXAllow">Save changes</button>
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
				<td colspan='16'>
					<input type="hidden" name="adjustmentDate[]" value="${date}">
					<h2 class="dateheader text-center col-md-11 col-md-push-1">${date}</h2>
					<input type="button" class="btn btn-danger col-md-1" value="Remove" onclick="removeAdjustment(this, '${date}')">
				</td>
			</tr>
			<tr class="attendance-header">
					  <td colspan='2'>Auto</td>
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
				<span class='empName'><?php $empArr['firstname'].", ".$empArr['lastname']?></span>
				<input type='hidden' class='driver' value='<?php $driverBool = ($empArr['position'] == 'Driver' ? true : false )?>' >
				{{if sunday}}
			      <input type="hidden" id="isSunday">
			    {{else}}
			    {{/if}}
			    <!-- Automatic timein -->
					<td>
						<input type='button' value='8-5' class='btn btn-primary auto' onclick='AutoTimeIn85("input-field-${inputCounter}")'>
					</td>
					<td>
						<input type='button' value='7-4' class='btn btn-primary auto' onclick='AutoTimeIn74("input-field-${inputCounter}")'>
					</td>
				<!-- Time In -->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timein1 timepicker form-control input-sm' value='' name='timein1[]'>
				</td> 
				<!-- Time Out-->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' value='' name='timeout1[]'>
				</td> 
				<!-- Half Day Checkbox-->
				<td>
					<input type='checkbox' class='halfdayChk' name='halfday[]' onclick="halfDay('input-field-${inputCounter}')" disabled>
				</td>
				<!-- AFTER BREAK Time In -->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timein2 timepicker form-control input-sm' value=''  name='timein2[]'>
				</td> 
				<!-- AFTER BREAK Time Out-->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' value='' name='timeout2[]'>
				</td> 
				<!-- Night Shift Checkbox-->
				<td>
					<input type='checkbox' class='nightshiftChk' name='nightshift[]' onclick="nightshift_ChkBox('input-field-${inputCounter}')" disabled>
				</td>
				<!-- NIGHT SHIFT Time In -->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[]' readonly>
				</td> 
				<!-- NIGHT SHIFT Time Out-->
				<td>
					<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[]' readonly>
				</td> 
				<!-- Working Hours -->
				<td>
					<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
					<input type='hidden' class='workinghoursH'  name='workinghrs[]' >
				</td> 
				<!-- Overtime -->
				<td>
					<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
					<input type='hidden' class='overtimeH' name='othrs[]' >
				</td> 
				<!-- Undertime -->
				<td>
					<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
					<input type='hidden' class='undertimeH' name='undertime[]' >
				</td>
				<!-- Night Differential --> 
				<td>
					<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
					<input type='hidden' class='nightdiffH' name='nightdiff[]' >
				</td>
				<!-- Remarks Input --> 
					<input type='hidden' name='remarks[]' class='hiddenRemarks'>

				<!-- Attendance Status -->
					<input type='hidden' name='attendance[]' class='attendance'>

				<!-- Extra allowance Input --> 
					<input type='hidden' name='xallow["${inputCounter}"]' class='hiddenXAllow'>

				<!-- Remarks Button --> 
				<td>
					<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarksFunc("${inputCounter}")'>Remarks <span class='icon'></span></a>
				</td>

				<!-- Extra Allowance Button --> 
				<td>
					<a class='btn btn-sm btn-primary xallowance' data-toggle='modal' data-target='#XAllowanceModal' onclick="xAllowanceFunc('input-field-${inputCounter}')">X Allow <span class='xall-icon'></span></a>
				</td>
			</tr>
		</table>
</script>
<script>

	function remarksFunc(id) {
		remarksPayroll("input-field-"+id);
		remarksValidation("input-field-"+id);
	}

	localStorage.setItem("inputcounter", 1);
	localStorage.setItem("tablecounter", 1);

	//var adjustedDays = [];
	var adjustedDays = [<?php Print $adjDateArr?>];
	console.log(adjustedDays);

	$("#dateValue").change(function() {
		var day = $("#dateValue").val();
		var d = new Date(day);
		var sundayBool = false;

		if(d.getDay()===0){
		 	sundayBool = true;
		 }

		localStorage.inputcounter = $('#adjustmentFields table').length;
		var inputcounter = localStorage.getItem("inputcounter");

		if($.inArray(day,adjustedDays) == -1) {
			adjustedDays[inputcounter] = day;
			console.log("ADDING: "+adjustedDays[inputcounter]);
			console.log(adjustedDays);

			if(document.getElementById('saveCompute').classList.contains('disabletotally')) {
				document.getElementById('saveCompute').classList.remove('disabletotally');
			}

		}
		else {
			
			alert("You have already selected " + day);
			return 0;
		}

		// if there are id's already available, add 1 otherwise retain number
		var data = [{ 
					date: day,
					inputCounter: inputcounter,
					sunday: d.getDay()===0 ? true : false
				}];
		$('#hidden-template').tmpl(data).appendTo('#adjustmentFields');
		
		var temp = document.getElementById('badge');
		temp.innerHTML = adjustedDays.length;
		console.log(temp);

		timeVerify();
	});
	var currentDate = "<?php Print $day8 ?>";
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
	
	// Tooltip for computing payroll
	$(function () {
  		$('[data-toggle="tooltip"]').tooltip()
	});

	function removeAdjustment(date, array) {
		var index = adjustedDays.indexOf(array);
		adjustedDays.splice(index, 1);
		localStorage.inputcounter--;
		console.log(adjustedDays[localStorage.inputcounter]);
		date.parentNode.parentNode.parentNode.parentNode.remove();

		var badge = document.getElementById('badge');
		var count = badge.innerHTML;
		count -= 1;
		badge.innerHTML = count;
		if(count == 0)
		{
			if(document.getElementById('disableCompute').value == 0)// Check if employee has no prior attendance
				document.getElementById('saveCompute').classList.add('disabletotally');
		}
		else
		{
			document.getElementById('saveCompute').classList.remove('disabletotally');
		}
			
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
		// console.log('in remarks: 123 '+id);
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

	function cancelSubmit(e) {
		e.preventDefault();
	}

	function xAllowanceFunc(id) {	
		allowInputsFromRow(id);
		// show modal here to input for remarks
		var mainRow = document.getElementById(id);
		if(mainRow.querySelector(".hiddenXAllow").value != null)
		{
			var input = mainRow.querySelector(".hiddenXAllow").value;
			input = input.replace(/\\/g, '');
			document.getElementById("xAllowanceInput").value = input;
		}

		document.getElementById("saveXAllow").setAttribute("onclick", "saveXAllowFunc('"+id+"')");
		if(mainRow.querySelector(".empName") !== null) {
			var empName = mainRow.querySelector(".empName").innerHTML.trim();
			var modal = document.getElementById("AllowDisplay").innerHTML = "Extra allowance for " + empName;
		}
		
	}
	// Transfer content to hidden input field
	function saveXAllowFunc(id) {
		var mainRow = document.getElementById(id);
		var xAllow = document.getElementById('xAllowanceInput').value.trim();
		var hiddenXAllow = mainRow.querySelector('.hiddenXAllow').setAttribute('value', xAllow);

		// var paragraph = document.createElement('span');
		// paragraph.innerHTML = xAllow;
		// paragraph.id = 'xAllowValue';

		if(xAllow !== null && xAllow !== "")
		{
			mainRow.querySelector('.xall-icon').classList.add('badge');
			mainRow.querySelector('#xAllowValue').innerHTML = xAllow;
			// if(mainRow.querySelector('#xAllowValue') !== null)
			// 	mainRow.querySelector('.xall-icon').removeChild(mainRow.querySelector('#xAllowValue'));
			// mainRow.querySelector('.xall-icon').appendChild(paragraph);
		}
		else
		{
			mainRow.querySelector('.xall-icon').classList.remove('badge');
			mainRow.querySelector('#xAllowValue').innerHTML = '';
			// mainRow.querySelector('.xall-icon').removeChild(mainRow.querySelector('#xAllowValue'));
		}

	}

</script>
</div>
</form>
</body>
</html>