<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	if(isset($_GET['site']))
	{
		$site = mysql_real_escape_string($_GET['site']);
		$siteChecker = "SELECT * FROM site WHERE location = '$site'";
		$siteCheckQuery = mysql_query($siteChecker);

		if(mysql_num_rows($siteCheckQuery) == 0)
			header("location:index.php");
	}
	else
	{
		header("location:index.php");
	}

	$position = $_GET['position'];
	$require = $_GET['req'];

	$positionChecker = "SELECT * FROM job_position WHERE position = '$position' AND active = '1'";
	$posCheckQuery = mysql_query($positionChecker);
	if(mysql_num_rows($posCheckQuery) == 0)
	{
		if($position != "null")	
			header("location:index.php");
		
	}
	// Checks if requirement in HTTP is altered by user manually 
	switch($require) 
	{
		case "null":break;
		case "all":break;
		case "withReq":break;
		case "withOReq":break;
		default: header("location:index.php");;
	}
	
	$dateChosen = "onProcess";
	if(isset($_POST['date']))
		$dateChosen = $_POST['date'];
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
						<li><a href='reports_overall_attendance.php?type=Attendance&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Attendance</a></li>
						<li>Overall Weekly Attendance Report for <?php Print $site?></li>
						<button class="btn btn-primary pull-right" id="printButton" onclick="PrintAttendance()">
							Print Attendance
						</button>
					</ol>
				</div>
			</div>

			<div class="form-inline">
				<div class="col-md-4 col-lg-4">
					<h4>Select Period</h4>
					<select class="form-control" onchange="periodChange(this.value)">
						<option hidden>Select date period</option>
						<?php
							$payDateBool = true;//boolean for displaying the present date


						$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
						$payDateQuery = mysql_query($payrollDate);


							if(mysql_num_rows($payDateQuery) != 0)
							{
								$allPaydayNum = mysql_num_rows($payDateQuery);
								$counter = 0;// counter
								while($payDateArr = mysql_fetch_assoc($payDateQuery))
								{
									$counter++;
									
									$payDay = $payDateArr['date'];
									$startDate = date('F d, Y', strtotime('-7 day', strtotime($payDateArr['date'])));
									$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDateArr['date'])));
									// Check for early cutoff 
									$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$payDay' LIMIT 1";
									$cutoffQuery = mysql_query($cutoffCheck);
									if(mysql_num_rows($cutoffQuery) == 0)
									{
										if(isset($_POST['date']))
										{
											if($_POST['date'] == $endDate)
												Print "<option value='".$endDate."' selected>".$startDate." - ".$endDate."</option>";
											else
												Print "<option value='".$endDate."'>".$startDate." - ".$endDate."</option>";
										}	
										else
										{
											Print "<option value='".$endDate."'>".$startDate." - ".$endDate."</option>";	
										}
									}
									else
									{
										$cutoffArr = mysql_fetch_assoc($cutoffQuery);
										$startDate = $cutoffArr['start'];
										$endDate = date('F d, Y', strtotime('+6 day', strtotime($startDate)));

										if(isset($_POST['date']))
										{
											if($_POST['date'] == $endDate)
												Print "<option value='".$endDate."' selected>".$startDate." - ".$endDate."</option>";
											else
												Print "<option value='".$endDate."'>".$startDate." - ".$endDate."</option>";
										}
										else
										{
											Print "<option value='".$endDate."'>".$startDate." - ".$endDate."</option>";	
										}
										
									}
									// if(isset($_POST['date']))
									// 	if($_POST['date'] == $endDate)
									// 		Print "<option value='".$endDate."' selected>".$startDate." - ".$endDate."</option>";
									// 	else
									// 		Print "<option value='".$endDate."'>".$startDate." - ".$endDate."</option>";
									// else
									// 	Print "<option value='".$endDate."'>".$startDate." - ".$endDate."</option>";	
									if($counter == $allPaydayNum)// Get the latest attendance
									{
										$onProcessDate = $payDateArr['date'];
										if(isset($_POST['date']))
										{
											if($_POST['date'] == "onProcess")
												Print "<option value='onProcess' selected>".$onProcessDate." - Present</option>";
											else
												Print "<option value='onProcess'>".$onProcessDate." - Present</option>";
										}
										else
											Print "<option value='onProcess' selected>".$onProcessDate." - Present</option>";
									}
								}
							}

						?>
					</select>
				</div>

				<div class="col-md-3 col-lg-3">
					<h4>Select Requirements</h4>
					<select onchange="requirementChange(this.value)" class="form-control">
						<option hidden>Select Requirements</option>
						<?php
							if($require == 'all')
								Print "<option value='all' selected>All</option>";
							else
								Print "<option value='all'>All</option>";
							if($require == 'withReq')
								Print "<option value='withReq' selected>W/ Requirements</option>";
							else
								Print "<option value='withReq'>W/ Requirements</option>";
							if($require == 'withOReq')
								Print "<option value='withOReq' selected>W/o Requirements</option>";
							else
								Print "<option value='withOReq'>W/o Requirements</option>";
						?>
					</select>
				</div>

				<div class="col-md-3 col-lg-3">
					<h4>Select Position</h4>
					<select class="form-control" onchange="positionChange(this.value)">
						<option hidden>Select Position</option>
						<?php
							$pos = "SELECT position FROM job_position WHERE active = '1'";
							$pos_query = mysql_query($pos);

							while($row_position = mysql_fetch_assoc($pos_query))
							{
								$pos = mysql_real_escape_string($row_position['position']);
								if($pos == $_GET['position'])
								{
									Print '<option value="'. $pos .'" selected="selected">'. $pos .'</option>';
								}
								else
								{
									Print '<option value="'. $pos .'">'. $pos .'</option>';
								}
							}
						?>
					</select>
				</div>

			</div>

			<a type="button" class="btn btn-danger pull-down" href="reports_overall_empattendance.php?site=<?php Print $site ?>&position=null&req=null">Clear Filter</a>
		</div>

		<div class="pull-down col-md-1 col-lg-12 overflow">
			<table class="table table-bordered pull-down">
				<tr>
					<td colspan="3">
						SITE: <?php Print strtoupper($site)?>
					</td>
					<td rowspan="3" colspan="49">
						Weekly Time Record of Employee
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php
							if(isset($_POST['date']))
							{
								if($_POST['date'] == "onProcess")
								{
									$openPayroll = $onProcessDate;
									$closePayroll = date('F d, Y', strtotime('+6 day', strtotime($openPayroll)));
								}
								else
								{
									$closePayroll = $_POST['date'];
									$openPayroll = date('F d, Y', strtotime('-6 day', strtotime($closePayroll)));
								}
							}
							else
							{
								$openPayroll = $onProcessDate;
								$closePayroll = date('F d, Y', strtotime('+6 day', strtotime($openPayroll)));

							}

							Print $openPayroll." - ".$closePayroll;
						?>
						
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php 
							if($require == "withReq")
								Print "COMPLETE REQUIREMENTS";
							else if($require == "withOReq")
								Print "INCOMPLETE REQUIREMENTS";
							else
								Print "COMPLETE/INCOMPLETE REQUIREMENTS";
						?>
					</td>
				</tr>
				<tr>
					<td rowspan="4">
						#
					</td>
					<td rowspan="4">
						Name of Worker
					</td>
					<td rowspan="4">
						Position
					</td>
					<td colspan="7">
						Wednesday
					</td>
					<td colspan="7">
						Thursday
					</td>
					<td colspan="7">
						Friday
					</td>
					<td colspan="7">
						Saturday
					</td>
					<td colspan="7">
						Sunday
					</td>
					<td colspan="7">
						Monday
					</td>
					<td colspan="7">
						Tuesday
					</td>
				</tr>
				<tr>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
				</tr>
				<tr>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
				</tr>
				<tr>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
				</tr>

				<?php
					$attendanceBool = true;//Disable print if there's no attendance available

					$addQuery = "";
					if($require == "withReq")
						$addQuery = " AND complete_doc = '1' ";
					else if($require == "withOReq")
						$addQuery = " AND complete_doc = '0' ";

					if($position != "null")
						$addQuery .= " AND position = '$position' ";

					$emp = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' $addQuery ORDER BY lastname ASC, firstname ASC";
					$empQuery = mysql_query($emp);

					$rowColor = true;

					$rowCounter = 1;
					if(mysql_num_rows($empQuery))
					{
						while($empArr = mysql_fetch_assoc($empQuery))
						{
							$rowColor = ($rowColor ? false : true);

							$empid = $empArr['empid'];

							$attendance = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$openPayroll', '%M %e, %Y') AND STR_TO_DATE('$closePayroll', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							$attendanceQuery = mysql_query($attendance);
	 						
	 						//preset variable for time in and time out
							$wedIn1 = "";
							$wedOut1 = "";
							$wedIn2 = "";
							$wedOut2 = "";
							$wedIn3 = "";
							$wedOut3 = "";
							$wedRemarks = "";

							$thuIn1 = "";
							$thuOut1 = "";
							$thuIn2 = "";
							$thuOut2 = "";
							$thuIn3 = "";
							$thuOut3 = "";
							$thuRemarks = "";

							$friIn1 = "";
							$friOut1 = "";
							$friIn2 = "";
							$friOut2 = "";
							$friIn3 = "";
							$friOut3 = "";
							$friRemarks = "";

							$satIn1 = "";
							$satOut1 = "";
							$satIn2 = "";
							$satOut2 = "";
							$satIn3 = "";
							$satOut3 = "";
							$satRemarks = "";

							$sunIn1 = "";
							$sunOut1 = "";
							$sunIn2 = "";
							$sunOut2 = "";
							$sunIn3 = "";
							$sunOut3 = "";
							$sunRemarks = "";

							$monIn1 = "";
							$monOut1 = "";
							$monIn2 = "";
							$monOut2 = "";
							$monIn3 = "";
							$monOut3 = "";
							$monRemarks = "";

							$tueIn1 = "";
							$tueOut1 = "";
							$tueIn2 = "";
							$tueOut2 = "";
							$tueIn3 = "";
							$tueOut3 = "";
							$tueRemarks = "";

							//boolean for absences
							$wedBool = false;
							$thuBool = false;
							$friBool = false;
							$satBool = false;
							$sunBool = false;
							$monBool = false;
							$tueBool = false;

							//boolean for halfday
							$wedBoolHD = false;
							$thuBoolHD = false;
							$friBoolHD = false;
							$satBoolHD = false;
							$sunBoolHD = false;
							$monBoolHD = false;
							$tueBoolHD = false;

							//boolean for No repeat of day
							$wedBoolNoRep = true;
							$thuBoolNoRep = true;
							$friBoolNoRep = true;
							$satBoolNoRep = true;
							$sunBoolNoRep = true;
							$monBoolNoRep = true;
							$tueBoolNoRep = true;

							while($attArr = mysql_fetch_assoc($attendanceQuery))
							{
								$day = date('l', strtotime($attArr['date']));

								

								

								if($day == "Wednesday" && $wedBoolNoRep)
								{
									
									$wedBoolNoRep = false;//no repeat
									if($attArr['attendance'] == 2)//employee is present
									{
										$wedIn1 = $attArr['timein'];
										$wedOut1 = $attArr['timeout'];
										$wedIn2 = $attArr['afterbreak_timein'];
										$wedOut2 = $attArr['afterbreak_timeout'];
										$wedIn3 = $attArr['nightshift_timein'];
										$wedOut3 = $attArr['nightshift_timeout'];

										if($wedIn2 == "")
											$wedBoolHD = true;//trigger H.D in display
									}
									if($attArr['attendance'] == 1)//employee is present
									{
										$wedBool = true;//employee is absent
									}
									$wedRemarks = $attArr['remarks'];
								}
								else if($day == "Thursday" && $thuBoolNoRep)
								{
									$thuBoolNoRep = false;
									if($attArr['attendance'] == 2)//employee is present
									{
										$thuIn1 = $attArr['timein'];
										$thuOut1 = $attArr['timeout'];
										$thuIn2 = $attArr['afterbreak_timein'];
										$thuOut2 = $attArr['afterbreak_timeout'];
										$thuIn3 = $attArr['nightshift_timein'];
										$thuOut3 = $attArr['nightshift_timeout'];

										if($thuIn2 == "")
											$thuBoolHD = true;//trigger H.D in display
									}
									if($attArr['attendance'] == 1)//employee is present
									{
										$thuBool = true;//employee is absent
									}
									$thuRemarks = $attArr['remarks'];
								}
								else if($day == "Friday" && $friBoolNoRep)
								{
									$friBoolNoRep = false;
									if($attArr['attendance'] == 2)//employee is present
									{
										$friIn1 = $attArr['timein'];
										$friOut1 = $attArr['timeout'];
										$friIn2 = $attArr['afterbreak_timein'];
										$friOut2 = $attArr['afterbreak_timeout'];
										$friIn3 = $attArr['nightshift_timein'];
										$friOut3 = $attArr['nightshift_timeout'];

										if($friIn2 == "")
											$friBoolHD = true;//trigger H.D in display
									}
									if($attArr['attendance'] == 1)//employee is present
									{
										$friBool = true;//employee is absent
									}
									$friRemarks = $attArr['remarks'];
								}
								else if($day == "Saturday" && $satBoolNoRep)
								{
									$satBoolNoRep = false; // no repeat
									if($attArr['attendance'] == 2)//employee is present
									{
										$satIn1 = $attArr['timein'];
										$satOut1 = $attArr['timeout'];
										$satIn2 = $attArr['afterbreak_timein'];
										$satOut2 = $attArr['afterbreak_timeout'];
										$satIn3 = $attArr['nightshift_timein'];
										$satOut3 = $attArr['nightshift_timeout'];

										if($satIn2 == "")
											$satBoolHD = true;//trigger H.D in display
									}
									if($attArr['attendance'] == 1)//employee is present
									{
										$satBool = true;//employee is absent
									}
									$satRemarks = $attArr['remarks'];
								}
								else if($day == "Sunday" && $sunBoolNoRep)
								{
									$sunBoolNoRep = false;// no repeat
									if($attArr['attendance'] == 2)//employee is present
									{
										$sunIn1 = $attArr['timein'];
										$sunOut1 = $attArr['timeout'];
										$sunIn2 = $attArr['afterbreak_timein'];
										$sunOut2 = $attArr['afterbreak_timeout'];
										$sunIn3 = $attArr['nightshift_timein'];
										$sunOut3 = $attArr['nightshift_timeout'];

										if($sunIn2 == "")
											$sunBoolHD = true;//trigger H.D in display
									}
									if($attArr['attendance'] == 1)//employee is present
									{
										$sunBool = true;//employee is absent
									}
									$sunRemarks = $attArr['remarks'];
								}
								else if($day == "Monday" && $monBoolNoRep)
								{
									$monBoolNoRep = false; // no repeat
									if($attArr['attendance'] == 2)//employee is present
									{
										$monIn1 = $attArr['timein'];
										$monOut1 = $attArr['timeout'];
										$monIn2 = $attArr['afterbreak_timein'];
										$monOut2 = $attArr['afterbreak_timeout'];
										$monIn3 = $attArr['nightshift_timein'];
										$monOut3 = $attArr['nightshift_timeout'];

										if($monIn2 == "")
											$monBoolHD = true;//trigger H.D in display
									}
									if($attArr['attendance'] == 1)//employee is present
									{
										$monBool = true;//employee is absent
									}
									$monRemarks = $attArr['remarks'];
								}
								else if($day == "Tuesday" && $tueBoolNoRep)
								{
									$tueBoolNoRep = false; //no repeat
									if($attArr['attendance'] == 2)//employee is present
									{
										$tueIn1 = $attArr['timein'];
										$tueOut1 = $attArr['timeout'];
										$tueIn2 = $attArr['afterbreak_timein'];
										$tueOut2 = $attArr['afterbreak_timeout'];
										$tueIn3 = $attArr['nightshift_timein'];
										$tueOut3 = $attArr['nightshift_timeout'];

										if($tueIn2 == "")
											$tueBoolHD = true;//trigger H.D in display
									}
									if($attArr['attendance'] == 1)//employee is present
									{
										$tueBool = true;//employee is absent
									}
									$tueRemarks = $attArr['remarks'];
								}
							}

							if($rowColor)
								Print "
									<tr style='background-color:#EEEEEE'>";
							else
								Print "
									<tr style='background-color:#FAFAFA'>";

							Print 		"<td>
											".$rowCounter."
										</td>
										<td align='left'>
											".$empArr['lastname'].", ".$empArr['firstname']."
										</td>
										<td>
											".$empArr['position']."
										</td>";

							if($wedBool)//WEDNESDAY
							{
								Print	"<td colspan='6'>
											A B S E N T
										</td>";
							}
							else
							{
								Print	"<td>
											".$wedIn1."
										</td>
										<td>
											".$wedOut1."
										</td>";
								if($wedBoolHD)
								{
									Print	"<td colspan='4'>
												HALF DAY / STRAIGHT
											</td>";
								}
								else
								{
									Print	"<td>
												".$wedIn2."
											</td>
											<td>
												".$wedOut2."
											</td>
											<td>
												".$wedIn3."
											</td>
											<td>
												".$wedOut3."
											</td>";
								}
							}
							//Remarks
							Print  	"<td>
										".stripslashes($wedRemarks)."
									</td>";

							if($thuBool)//THURSDAY
							{
								Print	"<td colspan='6'>
											A B S E N T
										</td>";
							}
							else
							{
								Print	"<td>
											".$thuIn1."
										</td>
										<td>
											".$thuOut1."
										</td>";
								if($thuBoolHD)
								{
									Print	"<td colspan='4'>
												HALF DAY / STRAIGHT
											</td>";
								}
								else
								{
									Print	"<td>
												".$thuIn2."
											</td>
											<td>
												".$thuOut2."
											</td>
											<td>
												".$thuIn3."
											</td>
											<td>
												".$thuOut3."
											</td>";
								}
							}
							//Remarks
							Print  	"<td>
										".stripslashes($thuRemarks)."
									</td>";

							if($friBool)//FRIDAY
							{
								Print	"<td colspan='6'>
											A B S E N T
										</td>";
							}
							else
							{
								Print	"<td>
											".$friIn1."
										</td>
										<td>
											".$friOut1."
										</td>";
								if($friBoolHD)
								{
									Print	"<td colspan='4'>
												HALF DAY / STRAIGHT
											</td>";
								}
								else
								{
									Print	"<td>
												".$friIn2."
											</td>
											<td>
												".$friOut2."
											</td>
											<td>
												".$friIn3."
											</td>
											<td>
												".$friOut3."
											</td>";
									}
							}
							//Remarks
							Print  	"<td>
										".stripslashes($friRemarks)."
									</td>";

							if($satBool)//SATURDAY
							{
								Print	"<td colspan='6'>
											A B S E N T
										</td>";
							}
							else
							{
								Print	"<td>
											".$satIn1."
										</td>
										<td>
											".$satOut1."
										</td>";
								if($satBoolHD)
								{
									Print	"<td colspan='4'>
												HALF DAY / STRAIGHT
											</td>";
								}
								else
								{
									Print	"<td>
												".$satIn2."
											</td>
											<td>
												".$satOut2."
											</td>
											<td>
												".$satIn3."
											</td>
											<td>
												".$satOut3."
											</td>";
									}
							}
							//Remarks
							Print  	"<td>
										".stripslashes($satRemarks)."
									</td>";

							if($sunBool)//SUNDAY
							{
								Print	"<td colspan='6'>
											A B S E N T
										</td>";
							}
							else
							{
								Print	"<td>
											".$sunIn1."
										</td>
										<td>
											".$sunOut1."
										</td>";
								if($sunBoolHD)
								{
									Print	"<td colspan='4'>
												HALF DAY / STRAIGHT
											</td>";
								}
								else
								{
									Print	"<td>
												".$sunIn2."
											</td>
											<td>
												".$sunOut2."
											</td>
											<td>
												".$sunIn3."
											</td>
											<td>
												".$sunOut3."
											</td>";
									}
							}
							//Remarks
							Print  	"<td>
										".stripslashes($sunRemarks)."
									</td>";

							if($monBool)//MONDAY
							{
								Print	"<td colspan='6'>
											A B S E N T
										</td>";
							}
							else
							{
								Print	"<td>
											".$monIn1."
										</td>
										<td>
											".$monOut1."
										</td>";
								if($monBoolHD)
								{
									Print	"<td colspan='4'>
												HALF DAY / STRAIGHT
											</td>";
								}
								else
								{
									Print	"<td>
												".$monIn2."
											</td>
											<td>
												".$monOut2."
											</td>
											<td>
												".$monIn3."
											</td>
											<td>
												".$monOut3."
											</td>";
									}
							}
							//Remarks
							Print  	"<td>
										".stripslashes($monRemarks)."
									</td>";

							if($tueBool)//TUESDAY
							{
								Print	"<td colspan='6'>
											A B S E N T
										</td>";
							}
							else
							{
								Print	"<td>
											".$tueIn1."
										</td>
										<td>
											".$tueOut1."
										</td>";
								if($tueBoolHD)
								{
									Print	"<td colspan='4'>
												HALF DAY / STRAIGHT
											</td>";
								}
								else
								{
									Print	"<td>
												".$tueIn2."
											</td>
											<td>
												".$tueOut2."
											</td>
											<td>
												".$tueIn3."
											</td>
											<td>
												".$tueOut3."
											</td>";
									}
							}
							//Remarks
							Print  	"<td>
										".stripslashes($tueRemarks)."
									</td>
								</tr>
								";
							$rowCounter++;//increments the row
						}
					}
					else
					{
						$attendanceBool = false;
						Print 	"<tr>
									<td colspan='52'  style='background-color:#F44336'>
										<b>No records as of the moment.</b>
									</td>
								</tr>";
					}
					



				?>
			</table>
		</div>
		

	</div>

	<form id="dynamicForm" method="POST" action="reports_overall_empattendance.php?site=<?php Print $site?>&position=<?php Print $position?>&req=<?php Print $require?>">
		<input type="hidden" name="date" id="changedDate">
		<input type="hidden" name="print" value="<?php Print $attendanceBool?>" id="printSwitch">
	</form>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function requirementChange(req) {
			window.location.assign("reports_overall_empattendance.php?site=<?php Print $site?>&position=<?php Print $position?>&req="+req)
		}

		function positionChange(position) {
			window.location.assign("reports_overall_empattendance.php?site=<?php Print $site?>&position="+position+"&req=<?php Print $require?>")
		}

		function periodChange(date) {
			document.getElementById('changedDate').value = date;
			document.getElementById('dynamicForm').submit();
		}

		function PrintAttendance() {
			window.location.assign("print_overall_attendance.php?site=<?php Print $site?>&position=<?php Print $position?>&req=<?php Print $require?>&date=<?php Print $dateChosen?>");
		}

		$( document ).ready(function() {
		    if($('#printSwitch').val() == '1')
		    	$('#printButton').removeClass("disabletotally");
		    else
		    	$('#printButton').addClass("disabletotally");
		});
	</script>
</body>
</html>