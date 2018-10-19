<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$empid = $_GET['empid'];

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);

//verifies the empid in the http
	if(mysql_num_rows($empQuery))
	{
		$empArr = mysql_fetch_assoc($empQuery);
	}
	else
	{
		header("location:reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null");
	}
	$site = $empArr['site'];
	// Modals				
	require_once('directives/modals/addNewVale.php');
	require_once('directives/modals/payrollAdjustment.php');


?>
<html>
<head>
	<title>Payroll</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">

	<!-- For pagination -->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />
</head>
<body style="font-family: Quicksand;">

	<div class="container-fluid">

		<?php
		require_once("directives/nav.php");
		?>

		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Payroll Input History for <?php Print $empArr['firstname']." ".$empArr['lastname']." | ".$empArr['position']." at ". $empArr['site']?></li>
					</ol>
				</div>
			</div>

			<div class="form-inline">
				<h4>Select period</h4>
				<select class="form-control" id="dd_payrollDate" onchange="payrollDateChange(this.value)">
					<option hidden>Select date</option>
					<?php
						$payrollDates = "SELECT date FROM payroll WHERE empid = '$empid'";
						$payrollDateQuery = mysql_query($payrollDates);

						if(mysql_num_rows($payrollDateQuery))//check if there's payroll
						{
							while($payrollDateArr = mysql_fetch_assoc($payrollDateQuery))
							{
								$payDay = $payrollDateArr['date'];
								$payrollEndDate = date('F d, Y', strtotime('-1 day', strtotime($payrollDateArr['date'])));
								$payrollStartDate = date('F d, Y', strtotime('-6 day', strtotime($payrollEndDate)));
								if(isset($_POST['dateChange']))
								{
									if($_POST['dateChange'] == $payDay)
									{
										Print "<option value = '".$payDay."' selected>".$payrollStartDate." - ".$payrollEndDate."</option>";
									}
									else
									{
										Print "<option value = '".$payDay."'>".$payrollStartDate." - ".$payrollEndDate."</option>";
									}
								}
								else
								{
									Print "<option value = '".$payDay."'>".$payrollStartDate." - ".$payrollEndDate."</option>";
								}
							}
						}
						else
						{
							Print "<option>No payroll date available</option>";
						}
					?>
				</select>
			</div>

			<div class="pull-down">
				<!-- Payroll display -->
				<?php
				if(isset($_POST['dateChange']))
				{
					$date = $_POST['dateChange'];
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

					$payroll = "SELECT * FROM payroll WHERE date = '$date' AND empid = '$empid'";
					$payrollQuery = mysql_query($payroll);
					$payrollArr = mysql_fetch_assoc($payrollQuery);// gets the payroll data from the specific date

					Print '
						<div class="container-fluid">';
							
							
							
					Print	'<!-- Breadcrumbs -->
							<input type="hidden" name="employeeID" value="'.$empid.'">
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
									          		
									          	<div id="adjustmentFields" class="pull-down"></div>
									          	</div>';
									          
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
	          					$workingHours = $workingHoursDisp[0]." hrs, ".$workingHoursDisp[1]."mins";
	          				else
	          				{
	          					if($workingHoursDisp[0] != 0)
	          						$workingHours = $workingHoursDisp[0]." hrs";
	          				}

	          				$overtimeDisp = explode('.',$adjDate['overtime']);
	          				if(Count($overtimeDisp) > 1)
	          					$overtimeHours = $overtimeDisp[0]." hrs, ".$overtimeDisp[1]."mins";
	          				else
	          				{
	          					if($overtimeDisp[0] != 0)
	          						$overtimeHours = $overtimeDisp[0]." hrs";
	          				}

	          				$undertimeDisp = explode('.',$adjDate['undertime']);
	          				if(Count($undertimeDisp) > 1)
	          					$undertimeHours = $undertimeDisp[0]." hrs, ".$undertimeDisp[1]."mins";
	          				else
	          				{
	          					if($undertimeDisp[0] != 0)
	          						$undertimeHours = $undertimeDisp[0]." hrs";
	          				}

	          				$nightdiffDisp = explode('.',$adjDate['nightdiff']);
	          				if(Count($nightdiffDisp) > 1)
	          					$nightdiffHours = $nightdiffDisp[0]." hrs, ".$nightdiffDisp[1]."mins";
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
	          				
									          				Print "
										          			<table class='table table-bordered table-responsive'>
																<tr>
																	<td colspan='13'>
																		<input type='hidden' name='adjustmentDate[]' value='".$adjDate['date']."'>
																		<h2 class='dateheader text-center col-md-11 col-md-push-1'>".$adjDate['date']."</h2>
																		
																	</td>
																</tr>
																<tr class='attendance-header'>
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
																	<!-- Time In -->
																	<td>
																		<input type='text' onblur='timeValidation(this)' class='timein1 timepicker form-control input-sm' value='".$adjDate['timein']."' name='timein1[]' disabled>
																	</td> 
																	<!-- Time Out-->
																	<td>
																		<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' value='".$adjDate['timeout']."' name='timeout1[]' disabled>
																	</td> 
																	<!-- Half Day Checkbox-->
																	<td>
																		<input type='checkbox' class='halfdayChk' name='halfday[]' onclick='halfDay('input-field-".$counter."')' disabled>
																	</td>
																	<!-- AFTER BREAK Time In -->
																	<td>
																		<input type='text' onblur='timeValidation(this)' class='timein2 timepicker form-control input-sm' value='".$adjDate['afterbreak_timein']."'  name='timein2[]' disabled>
																	</td> 
																	<!-- AFTER BREAK Time Out-->
																	<td>
																		<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' value='".$adjDate['afterbreak_timeout']."' name='timeout2[]' disabled>
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
																		<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='".($adjDate['workhours'])."' disabled>
																		<input type='hidden' value='".$adjDate['workhours']."' class='workinghoursH'  name='workinghrs[]' >
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
																	<!-- Remarks Input --> 
																		<input type='hidden' name='remarks[]' class='hiddenRemarks' value='".$adjDate['remarks']."'>

																	<!-- Attendance Status -->
																		<input type='hidden' name='attendance[]' class='attendance' value='".$attendanceStatus."'>
																	<!-- Remarks Button --> 
																	<td>
																		<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarksFunc(".$counter.")'>Remarks <span class='icon'></span></a>
																	</td>
																</tr>
															</table>";
															$counter++;
															$badgeCounter++;
									          			}	
									          		}
									          	
								Print		'</div>
									    </div>
									</div>
								</div>
								<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
									<div class="panel panel-primary">
									  <div class="panel-heading">
									    <h3>Payroll Inputs for <br>'.$day7.' - '.$day1.'</h3>
									    <a class="btn btn-danger pull-right pull-up-more" data-toggle="modal" data-target="#attendanceAdjustment">View Attendance Adjustments &nbsp<span class="badge" id="badge">'.$badgeCounter.'</span></a>
									  </div>
									</div>


								</div>
								<!-- Employee information -->
								<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">';
									
							//For deduction section 4 for 4 weeks in a month
									$deductionSSS = $payrollArr['sss']/4;
									$deductionPagibig = $payrollArr['pagibig']/4;
									$deductionPhilhealth = $payrollArr['philhealth']/4;
									
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
									if($payrollArr['philhealth'] != 0)//Phil Health Display
									{
										Print "<h4><span class='glyphicon glyphicon-ok'></span> PhilHealth documents</h4>";
									}
									else
									{
										Print "<h4><span class='glyphicon glyphicon-remove'></span> PhilHealth documents</h4>";
									}
									if($payrollArr['pagibig'] != 0)//Pagibig Display
									{
										Print "<h4><span class='glyphicon glyphicon-ok'></span> Pag-IBIG documents</h4>";
									}
									else
									{
										Print "<h4><span class='glyphicon glyphicon-remove'></span> Pag-IBIG documents</h4>";
									}
									if($payrollArr['sss'] != 0)//SSS Display
									{
										Print "<h4><span class='glyphicon glyphicon-ok'></span> SSS documents</h4>";
									}
									else
									{
										Print "<h4><span class='glyphicon glyphicon-remove'></span> SSS documents</h4>";
									}
									Print '</div>
									</div>
									
								</div>


								<!-- Attendance table -->
								<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
									<table class="table-bordered table-condensed" style="background-color:white;">';
										
									//Sample query for debugging purposes
										$payrollDate = "SELECT * FROM attendance WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$day7', '%M %e, %Y') AND STR_TO_DATE('$day1', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC";
										$payrollQuery2 = mysql_query($payrollDate) or die(mysql_error());										//Boolean for the conditions not to repeat just incase the employee does't attend sundays
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
										$AttExtraAllowance = 0;// extra allowance that has accumulated through 
										

										while($dateRow = mysql_fetch_assoc($payrollQuery2))
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
										
										Print '
										<tr style="white-space: nowrap">
											<td colspan="2" class="navibar col-md-1 col-lg-1">'.$day7 .'</td>
											<td colspan="2" class="navibar col-md-1 col-lg-1">'.$day6 .'</td>
											<td colspan="2" class="navibar col-md-1 col-lg-1">'.$day5 .'</td>
											<td colspan="2" class="navibar col-md-1 col-lg-1">'.$day4 .'</td>
											<td colspan="2" class="navibar col-md-1 col-lg-1">'.$day3 .'</td>
											<td colspan="2" class="navibar col-md-1 col-lg-1">'.$day2 .'</td>
											<td colspan="2" class="navibar col-md-1 col-lg-1">'.$day1 .'</td>
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
										<tr>';
										
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
												else if($wedNoWork)
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
												}
												else
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
												}
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
												else if($thuNoWork)
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
												}
												else
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
												}
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
												else if($friNoWork)
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
												}
												else
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
												}
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
												else if($satNoWork)
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
												}
												else
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
												}
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
												else if($sunNoWork)
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
												}
												else
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Day off </td>";
												}
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
												else if($monNoWork)
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
												}
												else
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
												}
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
												else if($tueNoWork)
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> No Work </td>";
												}
												else
												{
													Print 	"<td colspan='2' rowspan='".$payrollRow."' class='danger'> Holiday </td>";
												}
											}
											else if($tueAbsent)// Absent
											{
												Print 	"	<td colspan='2' rowspan='".$payrollRow."' class='danger'> Absent </td>";
											}

										Print '	
										</tr>
										<tr> <!-- ================ AFTER BREAK TIME IN AND TIME OUT ================ -->';
											
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

										Print '
										</tr>
										<!--  -------------------- NIGHTSHIFT TIME IN AND TIME OUT -------------------- -->';
											
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
											
									Print '

										</table>
									</div>

									<!-- Summary of attendance -->
									<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
										<div class="panel">
											<table class="table table-bordered table-responsive">
												<tr>
													<td style="background-color: peachpuff">
														<h4>Total hours rendered: '.numberExactFormat($totalHours, 2, '.', true) .'</h4>
													</td>
													<td style="background-color: lemonchiffon">
														<h4>Total overtime: '.numberExactFormat($totalOT, 2, '.', true) .'</h4>
													</td>
													<td style="background-color: powderblue">
														<h4>Total night differential: '.numberExactFormat($totalNightDiff, 2, '.', true).'</h4>
													</td>
													<input type="hidden" name="totalOverTime" value="'.$totalOT.'">
													<input type="hidden" name="totalNightDiff" value="'.$totalNightDiff.'">
												</tr>
											</table>


											<!-- Deductions to be made -->
											<!-- LOANS -->
											<div class="row">
												<div class="col-md-2 col-lg-2">
													<h4>Loans</h4>';
													
													$getSSS = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'SSS' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') AND date != '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
													$getPAGIBIG = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'PagIBIG' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') AND date != '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
													
													$getOldVALE = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') AND date != '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
													$getNewVALE = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'newVale' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') AND date != '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
													
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
													Print '
													<div class="form-group row">
														<label class="control-label col-md-3 col-lg-3" for="sss" >SSS</label>
														<div class="col-md-9 col-lg-9">';
															
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

													Print '	
														</div>
														<div class="col-md-1 col-lg-12">
															<input type="text" class="form-control" id="sssDeduct" name="sssDeduct" placeholder="To deduct" value="'.($payrollArr['loan_sss'] != 0 ? $payrollArr['loan_sss'] : "").'" disabled>
														</div>
													</div>
													<div class="form-group row">
														<label class="control-label col-md-3 col-lg-3" for="pagibig" style="white-space: nowrap;">Pag-IBIG</label>
														<div class="col-md-9 col-lg-9">';
															
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
														Print '
														</div>
														<div class="col-md-1 col-lg-12">
															<input type="text" class="form-control" id="pagibigDeduct" name="pagibigDeduct" placeholder="To deduct" value="'.($payrollArr['loan_pagibig'] != 0 ? $payrollArr['loan_pagibig'] : "").'" disabled>
														</div>
													</div>
												</div>

												<!-- OLD VALE -->
												<div class="col-md-2 col-lg-2">
													<div class="col-md-6 col-lg-6">
														<h4 class="text-left" style="white-space: nowrap;">Old Vale</h4>
														<h5 class="text-right" style="white-space: nowrap;">
															<span class="pull-right">';
																
																if($oldVale != "N/A")
																	Print "<span class='pull-right' id='oldvaleValue'>".numberExactFormat($oldVale, 2, '.', true)."</span";
																else
																	Print $oldVale;	
															Print '
															</span>
														</h5>
														<div class="row">
															<input type="text" placeholder="Deduct" id="oldValeDeduct" name="oldValeDeduct" class="form-control input-sm pull-down" value="'.($payrollArr['old_vale'] != 0 ? $payrollArr['old_vale'] : "").'" disabled>
														</div>
													</div>

													<!-- NEW VALE -->
													<div class="col-md-6 col-lg-6">
														<h4 class="text-left" style="white-space: nowrap;">New Vale</h4>
														<h5 class="text-right" style="white-space: nowrap;">
															<span class="vale pull-right" id="parent">';
																
																if($newVale != "N/A")
																	Print "<span id = 'newValeText' value='".$newVale."'>".$newVale."</span>";
																else
																	Print "<span id = 'newValeText'>N/A</span>";
															Print '
															</span>
															<br>
															<!-- <span id="dynamicCompute"></span> -->
														</h5>';
														
														//hidden input the Current Newvale
														if($newVale != "N/A")
															Print "<input type='hidden' name='newVale' value='".$newVale."'>";
														else
															Print "<input type='hidden' name='newVale'>";
														
														Print '
														<!-- Hidden inputs for the new vale -->
														<input type="hidden" name="newValeAdded" class="added">
														<input type="hidden" name="newValeRemarks" class="addRemarks">

														
													</div>

													<!-- COLA -->
													<div class="col-md-1 col-lg-12">';
														
														
														Print '
														<h4>COLA</h4>
														<input type="text" value="'.($payrollArr['cola'] != 0 ? $payrollArr['cola'] : "").'" name="cola" class="form-control" disabled>
														<h4>Insurance</h4>
														<input type="text" value="'.($payrollArr['insurance'] != 0 ? $payrollArr['insurance'] : "").'" name="insurance"  class="form-control" disabled>
													</div>
												</div>

												<!-- Contributions -->
												<div class="col-md-3 col-lg-3">
													<h4 class="text-center">Contributions</h4>
													<div class="form-group">
														<label class="control-label col-md-5 col-lg-5" for="tax">Tax</label>
														<div class="col-md-7 col-lg-7">
															<input type="text" id="tax" name="tax" class="form-control input-sm"  value="'.($payrollArr['tax'] != 0 ? $payrollArr['tax'] : "").'" disabled>
														</div>
														<label class="control-label col-md-5 col-lg-5" for="sssContribution">SSS</label>
														<div class="col-md-7 col-lg-7">
															<input type="text" id="sssContribution" name="sss" placeholder="No document" class="form-control input-sm" value="'.$deductionSSS.'" onkeypress="validatenumber(event)" readonly>

														</div>
														<label class="control-label col-md-5 col-lg-5" for="pagibigContribution" style="white-space: nowrap;">Pag-IBIG</label>
														<div class="col-md-7 col-lg-7">
															<input type="text" id="pagibigContribution" name="pagibig" class="form-control input-sm" value="'.$deductionPagibig.'" placeholder="No document" readonly>
														</div>
														<label class="control-label col-md-5 col-lg-5" for="philhealth">PhilHealth</label>
														<div class="col-md-7 col-lg-7">
															<input type="text" id="philhealth" name="philhealth" placeholder="No document" class="form-control input-sm" value="'.$deductionPhilhealth.'" onkeypress="validatenumber(event)" readonly>
														</div>
													</div>
												</div>

												<!-- Allowance computation -->';
												
												//Computation for overall allowance
												$overallAllow = "";
												if(!empty($payrollArr['allow']))
												{
													$overallAllow = $payrollArr['allow'] * $allowCounter;
													$overallAllow = numberExactFormat($overallAllow, 2, '.', true);
												}
												if($AttExtraAllowance == 0)// If extra allowance accumulated from attendance is Zero 
													$AttExtraAllowance = "";
												
												// Extra allowance daily
												$overallXAllowDaily = "";
												$xAllowDaily = "";
												if($payrollArr['x_allow_daily'] != 0)
												{
													$overallXAllowDaily = $payrollArr['x_allow_daily'] * $allowCounter;
													$xAllowDaily = $payrollArr['x_allow_daily'];
												}
												$xAllowWeekly = "";
												if($payrollArr['x_allow_weekly'] != 0)
												{
													$xAllowWeekly = $payrollArr['x_allow_weekly'];
												}
												Print '
												<!-- Days the employee came to work -->
												<input type="hidden" name="daysAttended" value="'.$allowCounter.'">
												<div class="col-md-5 col-lg-5">
													<h4 class="text-left">Allowance</h4>
													<div class="form-group">
														<label class="control-label col-md-2 col-lg-2">Daily</label>
														<div class="col-md-2 col-lg-2 nopadding">
															<input type="text" id="allowance" name="allowance" class="form-control input-sm" placeholder="Daily allowance" value="'.$payrollArr['allow'].'" disabled>
														</div>
														<label class="control-label col-md-2 col-lg-2">Overall</label>
														<div class="col-md-2 col-lg-2 nopadding">
															<input type="text" id="OverallAllowance" name="OverallAllowance" class="form-control input-sm" placeholder="Overall Allow."  value="'.$overallAllow.'" disabled>
														</div>
														
													</div>
													<div class="col-md-1 col-lg-12">
														<h4 class="text-left">Extra Allowance</h4>
														<label class="control-label col-md-2 col-lg-2">Extra</label>
														<div class="col-md-2 col-lg-2 nopadding">
															<input type="number" id="allowance" name="extra_allowance" name="extra_allowance" class="form-control input-sm" value="'. ($payrollArr['x_allowance'] != 0 ? $payrollArr['x_allowance'] : "").'" onblur="addDecimal(this)" readonly>
														</div>
														<label class="control-label col-md-2 col-lg-2">Extra Daily</label>
														<div class="col-md-2 col-lg-2 nopadding">
															<input type="text" name="xAllowanceDaily" class="form-control input-sm" 
															value="'. $xAllowDaily.'" readonly>
														</div>
														<label class="control-label col-md-2 col-lg-2">Overall Extra Daily</label>
														<div class="col-md-2 col-lg-2 nopadding">
															<input type="text" id="xAllowanceDailyOverall" name="xAllowanceDailyOverall" class="form-control input-sm"  value="'. $overallXAllowDaily.'" readonly>
														</div>
													</div>
													<div class="col-md-1 col-lg-12">
														<label class="control-label col-md-2 col-lg-2">Extra Weekly</label>
														<div class="col-md-2 col-lg-2 nopadding">
															<input type="text" id="xAllowanceWeekly" name="xAllowanceWeekly" class="form-control input-sm"   value="'. $xAllowWeekly.'" readonly>
														</div>
													</div>

													<!-- Tools deductions -->
													<div class="col-md-1 col-lg-12">
														<h4 class="text-left">Tools</h4>
														<span class="col-md-4 col-lg-4">Name</span>
														<span class="col-md-4 col-lg-4">Cost</span>
														<span class="col-md-4 col-lg-4">Quantity</span>
														
														<div class="form-group container-fluid" id="toolform">
															<a class="btn btn-sm btn-primary col-md-1 col-lg-1 disabletotally" ><span class="glyphicon glyphicon-plus"></span></a>';
															
															$toolsChecker = "SELECT * FROM tools WHERE empid = '$empid' AND date = '$date'";
															$toolsCheckerQuery = mysql_query($toolsChecker);
															$overallToolCost = 0;
															if(mysql_num_rows($toolsCheckerQuery) == 0)
															{
																Print '	<div class="row">
																			<div class="col-md-4 col-lg-4">
																				<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onchange="checkName(this)" disabled>
																			</div>
																			<div class="col-md-3 col-lg-3">
																				<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)" disabled>
																			</div>
																			<div class="col-md-3 col-lg-3">
																				<input type="text" id="quantity" name="toolquantity[]" class="form-control input-sm" onchange="getTotal(this)" onblur="addDecimal(this)" disabled>
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
																						<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onchange="checkName(this)" value="'.$toolsArr['tools'].'" disabled>
																					</div>
																					<div class="col-md-3 col-lg-3">
																						<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)" value="'.$toolsArr['cost'].'" disabled>
																					</div>
																					<div class="col-md-3 col-lg-3">
																						<input type="text" id="quantity" name="toolquantity[]" class="form-control input-sm" onchange="getTotal(this)" onblur="addDecimal(this)" value="'.$toolsArr['quantity'].'" disabled>
																					</div>
																				</div>	';
																			$overallToolCost += $toolsArr['cost'];
																	}
																	else
																	{
																		Print '
																			<a class="btn-sm btn btn-danger col-md-1 col-lg-1 disabletotally" name="rowDelete[]" >
																						<span class="glyphicon glyphicon-minus"></span>
																					</a>
																			<div class="row">
																				<div name="toolsRow[]" id="'.$toolsCounter.'">

																					<div class="col-md-4 col-lg-4">
																						<input type="text" id="toolstemp" name="toolname[]" class="form-control input-sm" onchange="checkName(this)" value="'.$toolsArr['tools'].'" disabled>
																					</div>
																					<div class="col-md-3 col-lg-3">
																						<input id="pricetemp" name="toolprice[]" class="form-control input-sm toolpricetemp" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)" value="'.$toolsArr['cost'].'" disabled>
																					</div> 
																					<div class="col-md-3 col-lg-3">
																						<input type="text" id="quantity" name="toolquantity[]" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)" value="'.$toolsArr['quantity'].'" disabled>
																					</div>
																				</div>
																			</div>';
																		$toolsCounter++;
																		$overallToolCost += $toolsArr['cost'] * $toolsArr['quantity'];
																	}
																}
																
															}

															
														Print '
														</div>
														<div class="col-md-1 col-lg-12 pull-down">
															<label class="col-md-5 col-lg-5">
																Total Cost
															</label>
															<div class="col-md-6 col-lg-6">
																<input type="text" class="form-control" id="totalcost" name="totalcost" value="'.$overallToolCost.'" readonly>
															</div>
														</div>
														<div class="col-md-1 col-lg-12">
															<label class="col-md-5 col-lg-5">
																Previous Payable
															</label>';
															
															//Outstanding payable
															
															//gets the previous payroll result
															$previousPayable = "SELECT * FROM payroll WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') <= STR_TO_DATE('$date', '%M %e, %Y') AND date != '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
															$payableQuery = mysql_query($previousPayable);

															$outstanding = null;//pre set outstanding payable
															if(mysql_num_rows($payableQuery) > 0);
															{
																$outstArr = mysql_fetch_assoc($payableQuery);
																if($outstArr['tools_outstanding'] != 0)
																	$outstanding = $outstArr['tools_outstanding'];
															}
														Print '
															<div class="col-md-6 col-lg-6">
																<input type="text" class="form-control" name="previousPayable" id="outstandingPayable" value="'.$outstanding.'" readonly>
															</div>
														</div>
														<div class="col-md-1 col-lg-12">
															<label class="col-md-5 col-lg-5">
																Amount to Pay
															</label>
															<div class="col-md-6 col-lg-6">';
															Print '	
																<input type="text" id="amountToPay" name="amountToPay" class="form-control" value="'.($payrollArr['tools_paid'] != 0 ? $payrollArr['tools_paid'] : "").'" onblur="addDecimal(this)" onchange="settotalLimit(this)" disabled>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>	
								</div>
							</div>
							
					';


					//------------------ PAYROLL COMPUTATION ---------------------------

					Print "
					<div class='col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down'>
						
						<div class='panel panel-success'>
						  <div class='panel-heading'>
						    <h3>Payroll Computation for <br>$day7 - $day1</h3>
						  </div>
						</div>";


					Print '
					</div>

					<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">

						<!-- Earnings -->
						<div class="col-md-6 col-lg-6 text-left">
							<h3>Earnings</h3>
							<table class="table">
								<thead>
									<tr>
										<th>Type</th>
										<th>Amount</th>
										<th>Days / Hours</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>';
										
										$numDays = $payrollArr['num_days']." Day(s)";

										$ratePerDaySub = 0;
										
										$ratePerDaySub = $payrollArr['num_days'];//for computation
										$numDaysArr = explode('.', $ratePerDaySub);
										if(count($numDaysArr) == 2)
										{
											if($numDaysArr[1] == 0)
												$ratePerDaySub = $numDaysArr[0];
										}
										else
											$ratePerDaySub = $numDaysArr[0];

										$ratePerDayDisp = $ratePerDaySub." Day(s)";// for display
										
										$subTotalRatePerDay = $ratePerDaySub * numberExactFormat($payrollArr['rate'],2,'.', true);
										// Print "<script>console.log('ratePerDaySub: ". $ratePerDaySub." | dailyRate: ".numberExactFormat($empArr['rate'],2,'.', true)."')</script>";//dito
										$totalRatePerDay = $subTotalRatePerDay;//for the Subtotal of Earnings

										if($subTotalRatePerDay == 0)
											$subTotalRatePerDay = "--";
										else
											$subTotalRatePerDay = numberExactFormat($subTotalRatePerDay, 2, '.', true);
										if($numDays == 0)
											$numDays = "--";
										Print "<script>console.log('num_days: ".$payrollArr['num_days']."')</script>";
									Print '
									<tr>
										<td>Rate per day</td>
										<td>'.$payrollArr['rate'].'</td>
										<td>'.$ratePerDayDisp.'</td>
										<td>'.$subTotalRatePerDay.'</td>
									</tr>';

										$allowDays =  $payrollArr['allow_days'];

										$subTotalAllowance = $payrollArr['allow'] * $allowDays;
										$totalAllowance = $subTotalAllowance;//for the Subtotal of Earnings

										if($subTotalAllowance == 0)
											$subTotalAllowance = "--";
										else
											$subTotalAllowance = numberExactFormat($subTotalAllowance, 2, '.', true);

										if($allowDays == 0)
											$allowDays = "--";
										else
											$allowDays = $allowDays." Day(s)";
										
									?>
									<tr>
										<td>Allowance</td>
										<td><?php Print $payrollArr['allow']?></td>
										<td><?php Print $allowDays?></td>
										<td><?php Print $subTotalAllowance?></td>
									</tr>

									<!-- Extra Allowance -->

									<?php
									$xAllowance = 0;
									if($payrollArr['x_allowance'] != 0)
									{
									Print "
											<tr>
												<td>Extra Allowance</td>
												<td>".numberExactFormat($payrollArr['x_allowance'], 2, '.', true)."</td>
												<td>--</td>
												<td>".numberExactFormat($payrollArr['x_allowance'], 2, '.', true)."</td>
											</tr>
											";
									$xAllowance = $payrollArr['x_allowance'];
									}
									$xAllowanceDaily = 0;
									if($payrollArr['x_allow_daily'] != 0)
									{
										$overallXAllowDaily = $allowDays * $payrollArr['x_allow_daily'];
									Print "
											<tr>
												<td>Extra Allowance</td>
												<td>".numberExactFormat($payrollArr['x_allow_daily'], 2, '.', true)."</td>
												<td>".$allowDays."</td>
												<td>".numberExactFormat($overallXAllowDaily, 2, '.', true)."</td>
											</tr>
											";
									}
									
									?>
									<!-- Extra Allowance Weekly -->

									<?php
									$xAllowanceWeekly = 0;
									if($payrollArr['x_allow_weekly'] != 0)
									{
									Print "
											<tr>
												<td>Extra Allowance</td>
												<td>".numberExactFormat($payrollArr['x_allow_weekly'], 2, '.', true)."</td>
												<td>--</td>
												<td>".numberExactFormat($payrollArr['x_allow_weekly'], 2, '.', true)."</td>
											</tr>
											";
									$xAllowanceWeekly = $payrollArr['x_allow_weekly'];
									}
					
					
									
									
										$subTotalOvertime = $payrollArr['ot_num']*$payrollArr['overtime'];
										$totalOvertime = $subTotalOvertime;//for the Subtotal of Earnings
										$ot_num = $payrollArr['ot_num']." Hour(s)";
										if($subTotalOvertime == 0)
											$subTotalOvertime = "--";
										else
											$subTotalOvertime = numberExactFormat($subTotalOvertime, 2, '.', true);
										if($ot_num == 0)
											$ot_num = "--";
									Print '
									<tr>
										<td>Overtime</td>
										<td>'.$payrollArr['overtime'].'</td>
										<td>'.$ot_num.'</td>
										<td>'.$subTotalOvertime.'</td>
									</tr>';

										$subTotalNightDifferential = $payrollArr['nightdiff_rate'] * $payrollArr['nightdiff_num'];
										$totalNightDifferential = $subTotalNightDifferential;//for the Subtotal of Earnings
										$nightdiffNum = $payrollArr['nightdiff_num']." Hour(s)";
										if($subTotalNightDifferential == 0)
											$subTotalNightDifferential = "--";
										else
											$subTotalNightDifferential = numberExactFormat($subTotalNightDifferential, 2, '.', true);
										if($nightdiffNum == 0)
											$nightdiffNum = "--";
									Print '
									<tr>
										<td>Night Differential</td>
										<td>'.$payrollArr['nightdiff_rate'].'</td>
										<td>'.$nightdiffNum.'</td>
										<td>'.$subTotalNightDifferential.'</td>
									</tr>';
									
										$sundayHrs = $payrollArr['sunday_hrs'];
										$sundayHoursComp = 0;
										if($sundayHrs == 0)
											$sundayHrs = "--";
										else
										{
											
											$sundayHoursComp = $sundayHrs;
											$sundayHrs =  $sundayHrs." Hour(s)";
											
										}

										$subTotalSundayRate = $payrollArr['sunday_rate'] * $payrollArr['sunday_hrs'];
										$totalSundayRate = $subTotalSundayRate;//for the Subtotal of Earnings
										
										if($subTotalSundayRate == 0)
											$subTotalSundayRate = "--";
										else
											$subTotalSundayRate = numberExactFormat($subTotalSundayRate, 2, '.', true);
											
									Print '
									<tr>
										<td>Sunday Rate</td>
										<td>'. $payrollArr['sunday_rate'].'</td>
										<td>'. $sundayHrs.'</td>
										<td>'. $subTotalSundayRate.'</td>
									</tr>';

										if($payrollArr['reg_holiday_num'] > 1)
										{
											$holidayRegChecker = "SELECT * FROM holiday AS h INNER JOIN attendance AS a ON h.date = a.date WHERE a.empid = '$empid' AND a.attendance = '2' AND h.type = 'regular'";
											$holidayRegQuery = mysql_query($holidayRegChecker);
											$regHolidayNum = mysql_num_rows($holidayRegQuery);
										}
										else if($payrollArr['reg_holiday_num'] == 1)
										{
											$regHolidayNum = 1;
										}
										else
										{
											$regHolidayNum = 0;
										}

										$subTotalRegularHolidayRate = ($payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday']) ;

										$totalRegularHolidayRate = $subTotalRegularHolidayRate;//for the Subtotal of Earnings
										$regHolNum = $regHolidayNum." Day(s)";
										if($subTotalRegularHolidayRate == 0)
											$subTotalRegularHolidayRate = "--";
										else
											$subTotalRegularHolidayRate = numberExactFormat($subTotalRegularHolidayRate, 2, '.', true);
										if($regHolNum == 0)
											$regHolNum = "--";
									Print '
									<tr>
										<td>Regular Holiday</td>
										<td>'.numberExactFormat($payrollArr['reg_holiday'], 2, '.', true) .'</td>
										<td>'.$regHolNum.'</td>
										<td>'.$subTotalRegularHolidayRate.'</td>
									</tr>';
									
										if($payrollArr['spe_holiday_num'] > 0)
											$subTotalSpecialHolidayRate = ($payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday']);
										else
											$subTotalSpecialHolidayRate = 0;
										$totalSpecialHolidayRate = $subTotalSpecialHolidayRate;//for the Subtotal of Earnings
										$speHolNum = $payrollArr['spe_holiday_num']." Day(s)";
										if($subTotalSpecialHolidayRate == 0)
											$subTotalSpecialHolidayRate = "--";
										else
											$subTotalSpecialHolidayRate = numberExactFormat($subTotalSpecialHolidayRate, 2, '.', true);
										if($speHolNum == 0)
											$speHolNum = "--";
										
									Print '
									<tr>
										<td>Special Holiday</td>
										<td>'.numberExactFormat($payrollArr['spe_holiday'], 2, '.', true).'</td>
										<td>'.$speHolNum.'</td>
										<td>'.$subTotalSpecialHolidayRate.'</td>
									</tr>';
									
										$totalCola = $payrollArr['cola'];
										if($totalCola == 0)
											$subTotalCola = "--";
										else
											$subTotalCola = numberExactFormat($totalCola, 2, '.', true);


										if($payrollArr['cola'] != 0)
										{
											$currentCola = $payrollArr['cola']/$allowDays;
											Print "
												<tr>
													<td>COLA</td>
													<td>".$currentCola."</td>
													<td>".$allowDays."</td>
													<td>".$subTotalCola."</td>
												</tr>
											";
										}
								
										$totalEarnings = $totalRegularHolidayRate + $totalSpecialHolidayRate + $totalSundayRate + $totalNightDifferential + $totalAllowance + $totalOvertime + $totalRatePerDay + $xAllowance + $totalCola + $overallXAllowDaily + $xAllowanceWeekly;
											

									Print '
									<tr style="font-family: QuicksandMed;">
										<td colspan="2" class="active">Subtotal</td>
										<td class="active"></td>
										<td class="active">'. numberExactFormat($totalEarnings, 2, '.', true).'</td>

									</tr>
								</tbody>
							</table>

							<!-- Tools -->
							<h3>Tools</h3>
							<table class="table">
								<thead>
									<tr>
										<th colspan="3">Name</th>
										<th>Quantity</th>
										<th>Cost</th>
									</tr>
								</thead>
								<tbody>';
							
								$tools = "SELECT * FROM tools WHERE empid = '$empid' AND date = '$date'";
								$toolsQuery = mysql_query($tools);
								$toolSubTotal = 0;
								$Notools = true;// if theres no tools
								$displayToolSubTotal = null;
								if(mysql_num_rows($toolsQuery) > 0)
								{
									$Notools = false;
									while($toolArr = mysql_fetch_assoc($toolsQuery))
									{
										$toolSubTotal += $toolArr['cost'];
										Print "
											<tr>
												<td colspan='3'>".$toolArr['tools']."</td>
												<td>".$toolArr['quantity']."</td>
												<td>".$toolArr['cost']."</td>
											</tr>
											";
									}
								}

								
								
								if($Notools)
								{
									Print "	<tr>
												<td>No Tools</td>
												<td colspan='3'></td>
												<td>--</td>
											</tr>";
								}

								if($toolSubTotal == 0)
								{
									$displayToolSubTotal = "--";
								}
								else
								{
									$displayToolSubTotal = numberExactFormat($toolSubTotal, 2, '.', true);
								}
								if($payrollArr['tools_paid'] != 0)
								{
									$displayToolPayed = numberExactFormat($payrollArr['tools_paid'], 2, '.', true);
								}
								else if($payrollArr['tools_paid'] == 0)//if employee didnot input any amount to pay
								{
									$displayToolPayed = numberExactFormat($toolSubTotal, 2, '.', true);
								}
								else
								{
									$displayToolPayed = "--";
								}

								$prevPayCheck = "SELECT * FROM payroll WHERE empid = '$empid' AND date <> '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
								$prevPayQuery = mysql_query($prevPayCheck) or die (mysql_error());

									//Previous payable
									if(mysql_num_rows($prevPayQuery) > 0)
									{
										$prevPayArr = mysql_fetch_assoc($prevPayQuery);
										if($prevPayArr['tools_outstanding'] > 0)
										Print 
											"<tr>
												<td>Previous Payable</td>
												<td colspan='3' ></td>
												<td>".$prevPayArr['tools_outstanding']."</td>
											</tr>";
									}
									$toolsSubTotal = "--";
									if($payrollArr['tools_paid'] != 0)//Tools paid
									{
										Print 
											"<tr>
												<td>Amount Paid</td>
												<td colspan='3' ></td>
												<td>".$displayToolPayed."</td>
											</tr>";
										$toolsSubTotal = numberExactFormat($payrollArr['tools_paid'], 2, '.', true);
									}
									if($payrollArr['tools_outstanding'] != 0)//outstanding Payable
									{
										Print 
											"<tr>
												<td>Outstanding Payable</td>
												<td colspan='3' ></td>
												<td>".numberExactFormat($payrollArr['tools_outstanding'], 2, '.', true)."</td>
											</tr>";
									}
									Print '
									<tr style="font-family: QuicksandMed;">
										<td class="active">Subtotal</td>
										<td colspan="3" class="active"></td>
										<td class="active">'.$toolsSubTotal.'</td>
									</tr>
								</tbody>
							</table>
							
						</div>

						<!-- Contributions -->
						<div class="col-md-6 col-lg-6 text-left">
							<h3>Contributions</h3>
							<table class="table">';
							
								$contributions = $payrollArr['pagibig']+$payrollArr['philhealth']+$payrollArr['sss']+$payrollArr['tax']+$payrollArr['insurance'];
								Print '
								<thead>
									<tr>
										<td>TAX</td>
										<td>';
											
											if($payrollArr['tax'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['tax'], 2, '.', true);
										Print '
										</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>SSS</td>
										<td>';
											 
											if($payrollArr['sss'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['sss'], 2, '.', true);
										Print '
										</td>
									</tr>
									<tr>
										<td>PhilHealth</td>
										<td>'; 
											if($payrollArr['philhealth'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['philhealth'], 2, '.', true);
										Print '
										</td>
									</tr>
									<tr>
										<td>PagIBIG</td>
										<td>'; 
											if($payrollArr['pagibig'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['pagibig'], 2, '.', true);
										Print '	
										</td>
									</tr>
									<tr>
										<td>Insurance</td>
										<td>';
											if($payrollArr['insurance'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['insurance'], 2, '.', true);
										Print '
										</td>
									</tr>
									<tr class="active" style="font-family: QuicksandMed;">
										<td>Subtotal</td>
										<td>'.numberExactFormat($contributions, 2, '.', true).'</td>
									</tr>
								</tbody>
							</table>';
								$totalLoans = $payrollArr['loan_pagibig'] + $payrollArr['loan_sss'] + $payrollArr['old_vale'] + $payrollArr['new_vale'];
							Print '
							<h3>Loans</h3>
							<table class="table">
								<thead>
									<tr>
										<td>New Vale</td>
										<td>';
											
											if($payrollArr['new_vale'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['new_vale'], 2, '.', true);
										Print '	
										</td>
									</tr>
									<tr>
										<td>Old Vale</td>
										<td>';
											if($payrollArr['old_vale'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['old_vale'], 2, '.', true);
										Print '
										</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>SSS</td>
										<td>'; 
											if($payrollArr['loan_sss'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['loan_sss'], 2, '.', true);
										Print '
										</td>
									</tr>
									<tr>
										<td>PagIBIG</td>
										<td>';
											if($payrollArr['loan_pagibig'] == 0)
												Print "--";
											else
												Print numberExactFormat($payrollArr['loan_pagibig'], 2, '.', true);
										Print '
										</td>
									</tr>
									<tr class="active" style="font-family: QuicksandMed;">
										<td>Subtotal</td>
										<td>';
											
											if($totalLoans  == 0)
												Print "--";
											else
												Print numberExactFormat($totalLoans, 2, '.', true);
										Print '
										</td>
									</tr>
							</table>
						</div>

						<!-- Overall Computation -->
						<div class="col-md-1 col-lg-12">
							<div class="panel panel-primary">
							  <div class="panel-heading">
							    <h3 style="margin:0px">Overall Computation</h3>
							  </div>
							  <div class="panel-body text-center">
							  	<div class="col-md-3 col-lg-3">
							  		<h4><span class="glyphicon glyphicon-plus" style="color:green;"></span> Total Earnings:<br>
							  			<b>
							  				'.numberExactFormat($totalEarnings, 2, '.', true).'
							  			</b>
							  		</h4>
							  	</div>
							    <div class="col-md-3 col-lg-3">
							    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Contributions:<br>
							    		<strong>
							    			'.numberExactFormat($contributions, 2, '.', true).'
							    		</strong>
							    	</h4>
							    </div>
							    <div class="col-md-3 col-lg-3">
							    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Loans:
							    	<br>
							    		<strong>
							    			'.numberExactFormat($totalLoans, 2, '.', true).'
							    		</strong>
							    	</h4>
							    </div>
							    <div class="col-md-3 col-lg-3">
							    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Tools:
							    	<br> 
							    		<b>
							    			'.numberExactFormat($payrollArr['tools_paid'], 2, '.', true).'
							    		</b>
							    	</h4>
							    </div>';
							    
							    	$grandTotal = abs($totalEarnings) - abs($contributions) - abs($totalLoans) - abs($payrollArr['tools_paid']);
							    	
							    	$grandTotal = abs($grandTotal);
							    Print '
							    <div class="col-md-1 col-lg-12">
							    	<h3><u>Grand total: '.numberExactFormat($grandTotal, 2, '.', true).'</u></h3>
								</div>
							  </div>
							</div>
						</div>
					</div>';

				}

				?>
				
						


					<!-- DUMMY MODAL FOR REMARKS -->

					<div class="modal fade" tabindex="-1" id="remarks" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="dito">Remarks</h4>
								</div>
								<div class="modal-body">
									<input class="form-control" id="remark"  maxlength="100"onkeyup="remarksListener(this.value)" readonly>
								</div>
								<div class="modal-footer">
									<h5 class="pull-left" >Characters left: &nbsp<span id="remarksCounter">100<span>&nbsp</h5>
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveRemarks">Save changes</button>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->





				<!-- /Payroll display -->
			</div>
		</div>

	</div>

	<input type="hidden" id="printBool" value="<?php Print $printBool?>">
	<form id="dateChangeForm" method="POST" action="reports_individual_payrollinput.php?empid=<?php Print $empid?>">
		<input type="hidden" id="dateChange" name="dateChange">
	</form>

	
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
	<script src="js/jquery.tmpl.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script rel="javascript" src="js/payroll.js"></script>
	<script src="js/enterAttendance.js"></script>
	<script>
		$( document ).ready(function() {
    	
    		var bool = $('#printBool').val();
    		if(bool != 1) {
    			$('#printButton').attr('disabled','disabled');
    			$('#printPayslip').attr('disabled','disabled');
    		}
    		

		});

		function printPayroll() {
			var payrollDate = document.getElementById('payrollDate').value;
			window.location.assign("print_individual_payroll.php?empid=<?php Print $empid?>&date="+payrollDate);
		}

		function printPayslip() {
			var payrollDate = document.getElementById('dd_payrollDate').value;
			window.location.assign("print_individual_payslip.php?empid=<?php Print $empid?>&date="+payrollDate);
		}

		function payrollDateChange(date) {
			if(date != "No payroll date available"){
				document.getElementById('dateChange').value = date;
				document.getElementById('dateChangeForm').submit();
			}
			
		}

		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
    		
	</script>
</body>
</html>