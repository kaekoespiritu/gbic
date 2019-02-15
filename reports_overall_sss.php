<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	//middleware
	include('directives/reports/middleware/reports_overall_contributions.php');

	$site = $_GET['site'];
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
						<li><a href='reports_overall_contributions.php?type=Contributions&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Contributions</a></li>
						<li>Overall SSS Contributions Report for <?php Print $breadcrumInfo?></li>
						<button class='btn btn-primary pull-right' onclick="printSSSContributions()">
							Print SSS Contributions
						</button>

						<!-- Shortcut button for other reports -->
						<button class='btn btn-danger pull-right disabletotally'>
							SSS
						</button>
						<button class='btn btn-danger pull-right' onclick="PagibigShortcut()">
							Pagibig
						</button>
						<button class='btn btn-danger pull-right' onclick="PhilhealthShortcut()">
							Philhealth
						</button>
						<button class='btn btn-danger pull-right' onclick="OverallShortcut()">
							Overall
						</button>
					</ol>
				</div>
			</div>

			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
				<div class="form-inline">
					<div class="col-md-6 col-lg-6">
						<h4>Select Period</h4>
						<select onchange="periodChange(this.value)" class="form-control" id="period">
							<?php 
								if($period == "week")
									Print "<option value='week' selected>Weekly</option>";
								else
									Print "<option value='week'>Weekly</option>";
								if($period == "month")
									Print "<option value='month'selected>Monthly</option>";
								else
									Print "<option value='month'>Monthly</option>";
								if($period == "year")
									Print "<option value='year' selected>Yearly</option>";
								else
									Print "<option value='year'>Yearly</option>";
							?>
						</select>
					</div>
					<div class="col-md-6 col-lg-6">
						<h4>Select <?php Print $period?></h4>
						<select class="form-control" onchange="changeDate(this.value)">
							<?php
							if(isset($_POST['date']))
							{
								if($_POST['date'] == 'all')
									Print "<option value = 'all' selected>All</option>";
								else
									Print "<option value = 'all' selected>All</option>";
							}
							else
									Print "<option value = 'all' selected>All</option>";

							$payrollDates = "SELECT DISTINCT date FROM payroll p INNER JOIN employee e ON e.empid = p.empid WHERE e.site = '$site' AND p.sss != 0";
							$payrollDQuery = mysql_query($payrollDates) or die(mysql_error());

							$earlyCuttoff = '';// for printable
							if(mysql_num_rows($payrollDQuery) > 0)//check if there's payroll
							{
								$monthNoRep = "";
								$yearNoRep = "";

								$cutoffBool = false;// Boolean for the suceeding week after the initial cutoff
								$cutoffClearPlaceholderBool = false;
								$cutoffInitialDate = '';// Placeh

								$selectionArrWeek = array();
								$selectionArrMonth = array();
								$selectionArrYear = array();
								while($payrollDateArr = mysql_fetch_assoc($payrollDQuery))
								{
									
									if($_GET['period'] == 'week')
									{
										$payDay = $payrollDateArr['date'];
										$payrollEndDate = date('F d, Y', strtotime('-1 day', strtotime($payrollDateArr['date'])));
										$payrollStartDate = date('F d, Y', strtotime('-6 day', strtotime($payrollEndDate)));

										// Check for early cutoff 
										$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$payDay' LIMIT 1";
										$cutoffQuery = mysql_query($cutoffCheck);
										if(mysql_num_rows($cutoffQuery) > 0)
										{
											$cutoffArr = mysql_fetch_assoc($cutoffQuery);
											$payrollStartDate = $cutoffArr['start'];
											$payrollEndDate = $cutoffArr['end'];

											$cutoffInitialDate = date('F d, Y', strtotime('+1 day', strtotime($cutoffArr['end'])));
										}

										if($cutoffBool == true)
										{
											$payrollStartDate = $cutoffInitialDate;
											$cutoffClearPlaceholderBool = true;// This is to reset the placeholder
											$cutoffBool = false;// Reset the cutoffBoolean
										}


										if(isset($_POST['date']))
										{
											if($_POST['date'] == $payDay)
											{
												array_push($selectionArrWeek, $payDay."-".$payrollStartDate."-".$payrollEndDate."-selected");
											}
											else
											{
												array_push($selectionArrWeek, $payDay."-".$payrollStartDate."-".$payrollEndDate);
											}
										}
										else
										{
											array_push($selectionArrWeek, $payDay."-".$payrollStartDate."-".$payrollEndDate);
										}
									}
									else if($_GET['period'] == 'month')
									{
										$payrollArrDate = explode(" ", $payrollDateArr['date']);
										$payrollMonth = $payrollArrDate[0];
										$payrollYear = $payrollArrDate[2];

										if($monthNoRep != $payrollMonth." ".$payrollYear)
										{	
											if(isset($_POST['date']))
											{
												if($_POST['date'] == $payrollMonth." ".$payrollYear)
												{
													array_push($selectionArrMonth, $payrollMonth."-".$payrollYear."-selected");
												}
												else
												{
													array_push($selectionArrMonth, $payrollMonth."-".$payrollYear);
												}
											}
											else
											{
												array_push($selectionArrMonth, $payrollMonth."-".$payrollYear);
											}
										}
										$monthNoRep = $payrollMonth." ".$payrollYear;
									}
									else if($_GET['period'] == 'year')
									{
										$payrollArrDate = explode(" ", $payrollDateArr['date']);
										$payrollYear = $payrollArrDate[2];
										$yearBef = $payrollYear -1;//gets the year before

										if($yearNoRep != $payrollYear)
										{	
											if(isset($_POST['date']))
											{
												if($_POST['date'] == $payrollYear)
												{
													array_push($selectionArrYear, $payrollYear."-".$yearBef."-selected");
												}
												else
												{
													array_push($selectionArrYear, $payrollYear."-".$yearBef);
												}
											}
											else
											{
												array_push($selectionArrYear, $payrollYear."-".$yearBef);
											}
											
										}
										$yearNoRep = $payrollYear;
									}
									
									// Early cutoff Reset
									if($cutoffClearPlaceholderBool == true)
									{
										$cutoffInitialDate = '';
										$cutoffClearPlaceholderBool = false;
									}
									if(mysql_num_rows($cutoffQuery) > 0)
									{
										$cutoffBool = true;// set to true, to trigger the next payroll that it has an extended attendance
									}
								}
								// week
								$revSelectionArrWeek = array_reverse($selectionArrWeek);
								// month
								$revSelectionArrMonth = array_reverse($selectionArrMonth);
								// year
								$revSelectionArrYear = array_reverse($selectionArrYear);

								// week
								if($_GET['period'] == 'week')
								{
									foreach($revSelectionArrWeek as $selection)
									{
										$selectExp = explode("-", $selection);
										if(count($selectExp) == 4)
										{
											Print "<option value = '".$selectExp[0]."' selected>".$selectExp[1]." - ".$selectExp[2]."</option>";
										}
										else
										{
											Print "<option value = '".$selectExp[0]."'>".$selectExp[1]." - ".$selectExp[2]."</option>";
										}
									}
								}
								// month
								else if($_GET['period'] == 'month')
								{
									foreach($revSelectionArrMonth as $selection)
									{
										$selectExp = explode("-", $selection);
										if(count($selectExp) == 3)
										{
											Print "<option value = '".$selectExp[0]." ".$selectExp[1]."' selected>".$selectExp[0]." ".$selectExp[1]."</option>";
										}
										else
										{
											Print "<option value = '".$selectExp[0]." ".$selectExp[1]."'>".$selectExp[0]." ".$selectExp[1]."</option>";
										}
									}
								}
								// year
								else if($_GET['period'] == 'year')
								{
									foreach($revSelectionArrYear as $selection)
									{
										$selectExp = explode("-", $selection);
										if(count($selectExp) == 3)
										{
											Print "<option value = '".$selectExp[0]."' selected>".$selectExp[1]." - ".$selectExp[0]."</option>";
										}
										else
										{
											Print "<option value = '".$selectExp[0]."'>".$selectExp[1]." - ".$selectExp[0]."</option>";
										}
									}
								}
							}
							?>
						</select>
					</div>
					
					
				</div>
				<div class="col-md-1 col-lg-12 pull-down">
				
					<table class="table table-bordered pull-down">
						<tr>
							<td colspan="7">
								 Overall SSS Contribution of employees for  <?php Print $site?>
							</td>
						</tr>
						<tr>
							<td rowspan="2">
								<?php Print substr($printButton,0,-2)//removes the "ly" in weekly, monthly, yearly?>
							</td>
							<td rowspan="2">
								Name
							</td>
							<td rowspan="2">
								Position
							</td>
							<td colspan="2">
								SSS
							</td>
							<td rowspan="2">
								Total
							</td>
							<tr>
								<td>
									Employee
								</td>
								<td>
									Employer
								</td>
							</tr>
						</tr>

						<?php

						if($period == "week")
						{
							$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
							$empQuery = mysql_query($employee) or die (mysql_error());
							$sssBool = false;//if employee dont have sss contribution
							if(mysql_num_rows($empQuery))//there's employee in the site
							{
								$overallSSS = 0;
								while($empArr = mysql_fetch_assoc($empQuery))
								{
									$empid = $empArr['empid'];
									if(isset($_POST['date']))
									{
										$changedPeriod = $_POST['date'];
										if($changedPeriod == 'all'){
											$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
										}
										else {
											$payrollDate = "SELECT DISTINCT date FROM payroll WHERE date= '$changedPeriod' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
										}
										
									}
									else
										$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";

									$payrollDateQuery = mysql_query($payrollDate);
									
									while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
									{
										
										//For the specfied week in first column
										$payDay = $payDateArr['date'];
										$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDateArr['date'])));
										$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

										// Check for early cutoff 
										$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$payDay' LIMIT 1";
										$cutoffQuery = mysql_query($cutoffCheck);
										if(mysql_num_rows($cutoffQuery) > 0)
										{
											$cutoffArr = mysql_fetch_assoc($cutoffQuery);
											$startDate = $cutoffArr['start'];
											$endDate = $cutoffArr['end'];
										}
										else
										{
											// Check the before payroll for early cutoff to alter the begining day of the payroll
											$suceedingCutoffPayroll = date('F d, Y', strtotime('-14 day', strtotime($payDay)));

											$suceedingCutoffCheck = "SELECT * FROM early_payroll WHERE start = '$suceedingCutoffPayroll' LIMIT 1";
											$suceedingCutoffQuery = mysql_query($suceedingCutoffCheck);
											if(mysql_num_rows($suceedingCutoffQuery) > 0)
											{
												$cutoffArr = mysql_fetch_assoc($suceedingCutoffQuery);
												$startDate = date('F d, Y', strtotime('+1 day', strtotime($cutoffArr['end'])));;// Get the end payroll of the cutoff to get the start of the current payroll

												// Pass the date if only there is a chosen date
												if(isset($_POST['date']))
												{
													if($_POST['date'] != 'all')
													{
														echo "<script>console.log('".$_POST['date']."')</script>";
														$earlyCuttoff = $startDate;//Pass the start of payroll to the printables
													}
												}
											}
										}


										$payroll = "SELECT * FROM payroll WHERE date = '$payDay' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
										$payrollQuery = mysql_query($payroll);
										if(mysql_num_rows($payrollQuery) > 0)
										{
											$payrollArr = mysql_fetch_assoc($payrollQuery);
											if($payrollArr['sss'] != 0)
											{
												$sssBool = true;
												$monthly = $payrollArr['rate'] * 25;

												$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table
												$sssContribution = $sssEmployer;

												$totalSSSContribution = $sssContribution + $payrollArr['sss'];
												Print "
														<tr>
															<td>
																".$startDate." - ".$endDate."
															</td>
															<td align='left'>
																".$empArr['lastname'].", ".$empArr['firstname']."
															</td>
															<td>
																".$empArr['position']."
															</td>
															<td>
																".numberExactFormat($payrollArr['sss'], 2, '.', true)."
															</td>
															<td>
																".numberExactFormat($sssContribution, 2, '.', true)."
															</td>
															<td>
																".numberExactFormat($totalSSSContribution, 2, '.', true)."
															</td>
														</tr>";

												$overallSSS += $totalSSSContribution;
											}
										}
									}
								}
								if($sssBool)
								{
									Print "
									<tr>
										<td colspan='4'>
										</td>
										<td>
											Grand Total
										</td>
										<td>
											".numberExactFormat($overallSSS, 2, '.', true)."
										</td>
									</tr>";
								}
								if(!$sssBool)
								{
									Print "
											<tr>
												<td colspan='6'>
												 	No Report data as of the moment
												</td>
											</tr>";
								}
							}
							else
							{
								Print "
											<tr>
												<td colspan='6'>
												 	No Report data as of the moment
												</td>
											</tr>";
							}
						}
						else if($period == "month")
						{
							$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
							$empQuery = mysql_query($employee) or die (mysql_error());
							$sssBool = false;//if employee dont have sss contribution
							$overallSSS = 0;
							if(mysql_num_rows($empQuery))//there's employee in the site
							{
								
								$sssBool = false;//if employee dont have sss contribution
								if(isset($_POST['date']))
								{
									
									if($_POST['date'] == "all"){
										$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
									}
									else {
										$changedPeriod = explode(' ',$_POST['date']);
										$monthPeriod = $changedPeriod[0];
										$yearPeriod = $changedPeriod[1];
										$payrollDate = "SELECT DISTINCT date FROM payroll WHERE (date LIKE '$monthPeriod%' AND date LIKE '%$yearPeriod') ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
									}
								}
								else
								{
									$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
								}
								while($empArr = mysql_fetch_assoc($empQuery))
								{
									$empid = $empArr['empid'];

									$payrollDateQuery = mysql_query($payrollDate);

									$monthNoRepeat = "";

									$sssBool = true;
									$EEContribution = 0;
									$ERContribution = 0;
									$totalSSSContribution = 0;
									//Evaluates the attendance and compute the sss contribution
									while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
									{
										$dateExploded = explode(" ", $payDateArr['date']);
										$month = $dateExploded[0];//gets the month
										$year = $dateExploded[2];// gets the year

										$payrollDay = $payDateArr['date'];

										$payroll = "SELECT * FROM payroll WHERE (date LIKE '$month%' AND date LIKE '%$year') AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
										$payrollQuery = mysql_query($payroll);
										if(mysql_num_rows($payrollQuery) > 0)
										{
											

											while($payrollArr = mysql_fetch_assoc($payrollQuery))
											{
												if($payrollArr['sss'] != 0)
												{
													$sssBool = true;
													$monthly = $payrollArr['rate'] * 25;

													$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table
													$ERContribution += $sssEmployer;

													$totalSSSContribution = $ERContribution + $payrollArr['sss'];

													$EEContribution += $payrollArr['sss'];
													
													
												}
												else
												{
													$sssBool = false;
												}
											}
											if($sssBool)
											{
												if($monthNoRepeat != $month.$year)
												{
													$totalSSSContribution = $ERContribution+$EEContribution;
													$overallSSS += $totalSSSContribution;

													Print "
															<tr>
																<td>
																	".$month." ".$year."
																</td>
																<td>
																	".$empArr['lastname'].", ".$empArr['firstname']."
																</td>
																<td>
																	".$empArr['position']."
																</td>
																<td>
																	".numberExactFormat($EEContribution, 2, '.', true)."
																</td>
																<td>
																	".numberExactFormat($ERContribution, 2, '.', true)."
																</td>
																<td>
																	".numberExactFormat($totalSSSContribution, 2, '.', true)."
																</td>
															</tr>";

												}
												
											}
										}

										$monthNoRepeat = $month.$year;
									}
								}
							}
							if($sssBool)//only display when employee has sss
							{
								Print "
								<tr>
									<td colspan='4'>
									</td>
									<td>
										Grand Total
									</td>
									<td>
										".numberExactFormat($overallSSS, 2, '.', true)."
									</td>
								</tr>";
							}
							if(!$sssBool)
							{
								Print "
										<tr>
											<td colspan='6'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}
					
						}
						else if($period = "year")
						{
							$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
							$empQuery = mysql_query($employee) or die (mysql_error());
							$sssBool = false;//if employee dont have sss contribution
							$overallSSS = 0;
							if(mysql_num_rows($empQuery))//there's employee in the site
							{
								
								$sssBool = false;//if employee dont have sss contribution
								if(isset($_POST['date']))
								{
									if($_POST['date'] == 'all'){
										$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
									}
									else {
										$changedPeriod = explode(' ',$_POST['date']);
										$yearPeriod = $changedPeriod[0];
										$payrollDate = "SELECT DISTINCT date FROM payroll WHERE  date LIKE '%$yearPeriod' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
									}
								}
								else
								{
									$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
								}
								while($empArr = mysql_fetch_assoc($empQuery))
								{
									$empid = $empArr['empid'];

									$payrollDateQuery = mysql_query($payrollDate);

									$yearNoRepeat = "";

									$sssBool = true;
									$EEContribution = 0;
									$ERContribution = 0;
									$totalSSSContribution = 0;
									//Evaluates the attendance and compute the sss contribution
									while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
									{
										$dateExploded = explode(" ", $payDateArr['date']);
										$year = $dateExploded[2];// gets the year

										$payrollDay = $payDateArr['date'];

										$payroll = "SELECT * FROM payroll WHERE date LIKE '%$year' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
										$payrollQuery = mysql_query($payroll);
										if(mysql_num_rows($payrollQuery) > 0)
										{
											

											while($payrollArr = mysql_fetch_assoc($payrollQuery))
											{
												if($payrollArr['sss'] != 0)
												{
													$sssBool = true;
													$monthly = $payrollArr['rate'] * 25;

													$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table
													$ERContribution += $sssEmployer;

													$totalSSSContribution = $ERContribution + $payrollArr['sss'];

													$EEContribution += $payrollArr['sss'];
													
													
												}
												else
												{
													$sssBool = false;
												}
											}
											if($sssBool)
											{
												if($yearNoRepeat != $year)
												{
													$totalSSSContribution = $ERContribution+$EEContribution;
													$overallSSS += $totalSSSContribution;

													$yearBefore = $year - 1;
													Print "
															<tr>
																<td>
																	".$yearBefore." - ".$year."
																</td>
																<td>
																	".$empArr['lastname'].", ".$empArr['firstname']."
																</td>
																<td>
																	".$empArr['position']."
																</td>
																<td>
																	".numberExactFormat($EEContribution, 2, '.', true)."
																</td>
																<td>
																	".numberExactFormat($ERContribution, 2, '.', true)."
																</td>
																<td>
																	".numberExactFormat($totalSSSContribution, 2, '.', true)."
																</td>
															</tr>";

												}
												
											}
										}

										$yearNoRepeat = $year;
									}
								}
							}
							if($sssBool)//only display when employee has sss
							{
								Print "
								<tr>
									<td colspan='4'>
									</td>
									<td>
										Grand Total
									</td>
									<td>
										".numberExactFormat($overallSSS, 2, '.', true)."
									</td>
								</tr>";
							}
							if(!$sssBool)
							{
								Print "
										<tr>
											<td colspan='6'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}
					
						}
						

						?>
						
					</table>
				</div>
			</div>

		</div>
		<?php
			$postDate = "all";
			if(isset($_POST['date']))
				$postDate = $_POST['date'];
			else
				$postDate = "all";

		?>
		<input type="hidden" id="postDate" value="<?php Print $postDate?>">
	</div>
	<input type="hidden" id="printButton" value="<?php Print $sssBool?>">
	<form id="changeDateForm" method="post" action="reports_overall_sss.php?site=<?php Print $site?>&period=<?php Print $period?>">
		<input type="hidden" name="date">
		<input type="hidden" name="numLen">
	</form>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function changeDate(date) {
			var period = date.split(' ');
			var numLen = period.length;
			document.getElementsByName('date')[0].value = date;
			document.getElementsByName('numLen')[0].value = numLen;
			document.getElementById('changeDateForm').submit();
		}

		function periodChange(period) {
			window.location.assign("reports_overall_sss.php?site=<?php Print $site?>&period="+period);

		}

		function printSSSContributions() {
			var period = document.getElementById('period').value;
			var date = document.getElementById('postDate').value;
			window.location.assign("print_overall_contribution.php?site=<?php Print $site ?>&period="+period+"&date="+date+"&contribution=SSS&cutoff=<?php Print $earlyCuttoff ?>");
		}

		function PagibigShortcut(){
			window.location.assign("reports_overall_pagibig.php?site=<?php Print $site?>&period=week");
		}

		function OverallShortcut(){
			window.location.assign("reports_overall_allcontributions.php?site=<?php Print $site?>&period=week");
		}
		function PhilhealthShortcut(){
			window.location.assign("reports_overall_philhealth.php?site=<?php Print $site?>&period=week");
		}

		//Disables the button if there's no data
		$(document).ready(function(){
			if($("#printButton").val() == 0) {
			    $("#printSSS").attr("disabled", "disabled");
			}
		});
	</script>
</body>
</html>