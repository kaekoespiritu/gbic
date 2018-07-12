<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	//middleware
	include('directives/reports/middleware/reports_individual_contributions.php');

	$empid = $_GET['empid'];
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
						<li><a href='reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Contributions</a></li>
						<li>Philhealth Contributions Report for <?php Print $breadcrumInfo?></li>
						<button class='btn btn-primary pull-right' onclick="printPhilHealthContribution()">
							Print Philhealth Contribution
						</button>
						<!-- Shortcut button for other reports -->
						<button class='btn btn-danger pull-right' onclick="SSSshortcut()">
							SSS
						</button>
						<button class='btn btn-danger pull-right' onclick="PagibigShortcut()">
							Pagibig
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
							<option hidden>Choose a <?php Print $period?></option>
							<?php
							$payrollDates = "SELECT date FROM payroll WHERE empid = '$empid'";
							$payrollDQuery = mysql_query($payrollDates) or die(mysql_error());

							if(isset($_POST['date']))
							{
								if($_POST['date'] == 'all')
									Print "<option value='all' selected>All</option>";
								else
									Print "<option value='all'>All</option>";
							}
							else
									Print "<option value='all'>All</option>";


							if(mysql_num_rows($payrollDQuery) > 0)//check if there's payroll
							{
								$monthNoRep = "";
								$yearNoRep = "";
								while($payrollDateArr = mysql_fetch_assoc($payrollDQuery))
								{
									
									if($_GET['period'] == 'week')
									{
										$payDay = $payrollDateArr['date'];
										$payrollEndDate = date('F d, Y', strtotime('-1 day', strtotime($payrollDateArr['date'])));
										$payrollStartDate = date('F d, Y', strtotime('-6 day', strtotime($payrollEndDate)));
										if(isset($_POST['date']))
										{
											if($_POST['date'] == $payDay)
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
													Print "<option value = '".$payrollMonth." ".$payrollYear."' selected>".$payrollMonth." ".$payrollYear."</option>";
												}
												else
												{
													Print "<option value = '".$payrollMonth." ".$payrollYear."'>".$payrollMonth." ".$payrollYear."</option>";
												}
											}
											else
											{
												Print "<option value = '".$payrollMonth." ".$payrollYear."'>".$payrollMonth." ".$payrollYear."</option>";
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
													Print "<option value = '".$payrollYear."' selected>".$yearBef." - ".$payrollYear."</option>";
												}
												else
												{
													Print "<option value = '".$payrollYear."'>".$yearBef." - ".$payrollYear."</option>";
												}
											}
											else
											{
												Print "<option value = '".$payrollYear."'>".$yearBef." - ".$payrollYear."</option>";
											}
											
										}
										$yearNoRep = $payrollYear;
									}
									
								}
							}
							
						
							?>
						</select>
					</div>
				</div>
				<div class="pull-down-even-more">
				<table class="table table-bordered">
					<tr>
						<td colspan="4">
							 <?php Print $breadcrumInfo?>
						</td>
					</tr>
					<tr>
						<td rowspan="2">
							<?php Print $printButton?>
						</td>
						<td colspan="2">
							Philhealth
						</td>
						<td rowspan="2">
							Total
						</td>
					</tr>
					<tr>
						<td>
							Employee
						</td>
						<td>
							Employer
						</td>
					</tr>

					<?php

					if($period == "week")
					{
						if(isset($_POST['date']))
						{
							if($_POST['date'] != 'all')
							{
								$changedPeriod = $_POST['date'];
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' AND date= '$changedPeriod' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							}
							else
							$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
						}
						else
							$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";

						$payrollDateQuery = mysql_query($payrollDate);

						//weekly
						$overallPhilhealth = 0;

						$philhealthBool = false;//if employee dont have Philhealth contribution

						//Evaluates the attendance and compute the 13th monthpay
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							//For the specfied week in first column
							$payDay = $payDateArr['date'];
							$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDateArr['date'])));
							$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

							$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$payDay' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							$payrollQuery = mysql_query($payroll);
							if(mysql_num_rows($payrollQuery) > 0)
							{
								$payrollArr = mysql_fetch_assoc($payrollQuery);
								if($payrollArr['philhealth'] != 0)
								{
									$philhealthBool = true;
									$monthly = $payrollArr['rate'] * 25;

									$philhealthEmployer = $payrollArr['philhealth_er'];//Gets the value in the Philhealth table
									$philhealthContribution = $philhealthEmployer;

									$totalPhilhealthContribution = $philhealthContribution + $payrollArr['philhealth'];
									Print "
											<tr>
												<td>
													".$startDate." - ".$endDate."
												</td>
												<td>
													".numberExactFormat($payrollArr['philhealth'], 2, '.', true)."
												</td>
												<td>
													".numberExactFormat($philhealthContribution, 2, '.', true)."
												</td>
												<td>
													".numberExactFormat($totalPhilhealthContribution, 2, '.', true)."
												</td>
											</tr>";

									$overallPhilhealth += $totalPhilhealthContribution;
								}

								
							}


						}
						if($philhealthBool)
						{
							Print "
							<tr>
								<td colspan='2'>
								</td>
								<td>
									Grand Total
								</td>
								<td>
									".numberExactFormat($overallPhilhealth, 2, '.', true)."
								</td>
							</tr>";
						}
						if(!$philhealthBool)
						{
							Print "
									<tr>
										<td colspan='4'>
										 	No Report data as of the moment
										</td>
									</tr>";
						}
					}
					else if($period == "month")
					{
						if(isset($_POST['date']))
						{
							if($_POST['date'] != 'all')
							{
								$changedPeriod = explode(' ',$_POST['date']);
								$monthPeriod = $changedPeriod[0];
								$yearPeriod = $changedPeriod[1];
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' AND (date LIKE '$monthPeriod%' AND date LIKE '%$yearPeriod') ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							}
							else
							{
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							}
						}
						else
							$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";

						$payrollDateQuery = mysql_query($payrollDate);

						//gets the overall philhealth total
						$overallPhilhealth = 0;

						$philhealthBool = false;//if employee dont have philhealth contribution

						$monthNoRepeat = "";
						//Evaluates the attendance and compute the philhealth contribution
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							$dateExploded = explode(" ", $payDateArr['date']);
							$month = $dateExploded[0];//gets the month
							$year = $dateExploded[2];// gets the year

							$payrollDay = $payDateArr['date'];

							$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date LIKE '$month%' AND date LIKE '%$year' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							$payrollQuery = mysql_query($payroll);
							if(mysql_num_rows($payrollQuery) > 0)
							{
								$philhealthBool = true;
								$EEContribution = 0;
								$ERContribution = 0;
								$totalPhilhealthContribution = 0;

								//prevent from repeating the same month
								if($monthNoRepeat != $month.$year)
								{
									while($payrollArr = mysql_fetch_assoc($payrollQuery))
									{
										if($payrollArr['philhealth'] != 0)
										{
											$philhealthBool = true;
											$monthly = $payrollArr['rate'] * 25;

											$philhealthEmployer = $payrollArr['philhealth_er'];//Gets the value in the philhealth table
											$ERContribution += $philhealthEmployer;
											$EEContribution += $payrollArr['philhealth'];

											$totalPhilhealthContribution = $ERContribution + $EEContribution;
											$overallPhilhealth += $philhealthEmployer + $payrollArr['philhealth'];

										}
										else
										{
											$philhealthBool = false;
										}
									}
								}
								if($philhealthBool)
								{
									if($monthNoRepeat != $month.$year)
									{
									Print "
											<tr>
												<td>
													".$month." ".$year."
												</td>
												<td>
													".numberExactFormat($EEContribution, 2, '.', true)."
												</td>
												<td>
													".numberExactFormat($ERContribution, 2, '.', true)."
												</td>
												<td>
													".numberExactFormat($totalPhilhealthContribution, 2, '.', true)."
												</td>
											</tr>";
									}
								}

								$monthNoRepeat = $month.$year;

								
							}
							else
							{
								$philhealthBool = true;
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

							if(!$philhealthBool)
							{
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

						}
						if($philhealthBool)//only display when employee has philhealth
						{
							Print "
							<tr>
								<td colspan='2'>
								</td>
								<td>
									Grand Total
								</td>
								<td>
									".numberExactFormat($overallPhilhealth, 2, '.', true)."
								</td>
							</tr>";
						}
					}
					else if($period = "year")
					{
						if(isset($_POST['date']))
						{
							if($_POST['date'] != 'all')
							{
								$changedPeriod = explode(' ',$_POST['date']);
								$yearPeriod = $changedPeriod[0];
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' AND date LIKE '%$yearPeriod' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							}
							else
							{
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							}
						}
						else
							$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";

						$payrollDateQuery = mysql_query($payrollDate);

						//gets the overall philhealth total
						$overallPhilhealth = 0;

						$philhealthBool = false;//if employee dont have philhealth contribution

						$yearNoRepeat = "";
						//Evaluates the attendance and compute the philhealth contribution
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							$dateExploded = explode(" ", $payDateArr['date']);
							$year = $dateExploded[2];// gets the year

							$payrollDay = $payDateArr['date'];

							$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date LIKE '%$year' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							$payrollQuery = mysql_query($payroll);
							if(mysql_num_rows($payrollQuery) > 0)
							{
								$philhealthBool = true;
								$EEContribution = 0;
								$ERContribution = 0;
								$totalPhilhealthContribution = 0;

								//prevent from repeating the same month
								if($yearNoRepeat != $year)
								{
									while($payrollArr = mysql_fetch_assoc($payrollQuery))
									{
										if($payrollArr['philhealth'] != 0)
										{
											$philhealthBool = true;
											$monthly = $payrollArr['rate'] * 25;

											$philhealthEmployer = $payrollArr['philhealth_er'];//Gets the value in the philhealth table
											$ERContribution += $philhealthEmployer;
											$EEContribution += $payrollArr['philhealth'];

											$totalPhilhealthContribution = $ERContribution + $EEContribution;
											$overallPhilhealth += $philhealthEmployer + $payrollArr['philhealth'];

										}
										else
										{
											$philhealthBool = false;
										}
									}
								}
								if($philhealthBool)
								{
									if($yearNoRepeat != $year)
									{
										$yearBefore = $year - 1;

									Print "
											<tr>
												<td>
													".$yearBefore." - ".$year."
												</td>
												<td>
													".numberExactFormat($EEContribution, 2, '.', true)."
												</td>
												<td>
													".numberExactFormat($ERContribution, 2, '.', true)."
												</td>
												<td>
													".numberExactFormat($totalPhilhealthContribution, 2, '.', true)."
												</td>
											</tr>";
									}
								}

								$yearNoRepeat = $year;

								
							}
							else
							{
								$philhealthBool = true;
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

							if(!$philhealthBool)
							{
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

						}
						if($philhealthBool)//only display when employee has philhealth
						{
							Print "
							<tr>
								<td colspan='2'>
								</td>
								<td>
									Grand Total
								</td>
								<td>
									".numberExactFormat($overallPhilhealth, 2, '.', true)."
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

		?>
		<input type="hidden" id="postDate" value="<?php Print $postDate?>">
	</div>
	<input type="hidden" id="printButton" value="<?php Print $philhealthBool?>">
	<form id="changeDateForm" method="post" action="reports_individual_philhealth.php?empid=<?php Print $empid?>&period=<?php Print $period?>">
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
			window.location.assign("reports_individual_philhealth.php?empid=<?php Print $empid?>&period="+period);
		}

		function printPhilHealthContribution() {
			var period = document.getElementById('period').value;
			var date = document.getElementById('postDate').value;
			window.location.assign("print_individual_contribution.php?empid=<?php Print $empid ?>&period="+period+"&date="+date+"&contribution=PhilHealth");
		}

		function SSSshortcut(){
			window.location.assign("reports_individual_sss.php?empid=<?php Print $empid?>&period=week");
		}

		function PagibigShortcut(){
			window.location.assign("reports_individual_pagibig.php?empid=<?php Print $empid?>&period=week");
		}

		//Disables the button if there's no data
		$(document).ready(function(){
			if($("#printButton").val() == 0) {
			    $("#printPhilhealth").attr("disabled", "disabled");
			}
		});
	</script>
</body>
</html>