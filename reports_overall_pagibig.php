<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	//middleware
	include('directives/reports/middleware/reports_overall_contributions.php');

	$site = $_GET['site'];

	if(isset($_POST['date']))
	{
		Print "<script>console.log('".$_POST['date']."')</script>";
	}
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


		<div class="col-md-10 col-md-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_overall_contributions.php?type=Contributions&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Contributions</a></li>
						<li>Overall Pagibig Contributions Report for <?php Print $breadcrumInfo?></li>
						<button class='btn btn-primary pull-right'>
							Print Pagibig Contributions
						</button>
					</ol>
				</div>
			</div>

			<div class="col-md-10 col-md-offset-1">
				<div class="form-inline">
					<div class="col-md-6">
						<h4>Step 1: Select a period type</h4>
						<select onchange="periodChange(this.value)" class="form-control">
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
					<div class="col-md-6">
						<h4>Step 2: Select duration in <?php Print $period?></h4>
						<select class="form-control" onchange="changeDate(this.value)">
							<option hidden>Choose a <?php Print $period?></option>
							<?php
							$payrollDates = "SELECT DISTINCT date FROM payroll";
							$payrollDQuery = mysql_query($payrollDates) or die(mysql_error());

							if(mysql_num_rows($payrollDQuery) > 0)//check if there's payroll
							{
								$monthNoRep = "";
								$yearNoRep = "";
								while($payrollDateArr = mysql_fetch_assoc($payrollDQuery))
								{
									
									if($_GET['period'] == 'week')
									{
										$payrollEndDate = $payrollDateArr['date'];
										$payrollStartDate = date('F j, Y', strtotime('-6 day', strtotime($payrollEndDate)));
										if(isset($_POST['date']))
										{
											if($_POST['date'] == $payrollEndDate)
											{
												Print "<option value = '".$payrollEndDate."' selected>".$payrollStartDate." - ".$payrollEndDate."</option>";
											}
											else
											{
												Print "<option value = '".$payrollEndDate."'>".$payrollStartDate." - ".$payrollEndDate."</option>";
											}
										}
										else
										{
											Print "<option value = '".$payrollEndDate."'>".$payrollStartDate." - ".$payrollEndDate."</option>";
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
				<div class="col-md-12 pull-down">
				
				<table class="table table-bordered pull-down">
					<tr>
						<td colspan="7">
							 <?php Print $breadcrumInfo?> Pagibig Contribution of employees for  <?php Print $site?>
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
							Pagibig
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
						$PagibigBool = false;//if employee dont have pagibig contribution
						if(mysql_num_rows($empQuery))//there's employee in the site
						{
							$overallPagibig = 0;
							while($empArr = mysql_fetch_assoc($empQuery))
							{
								$empid = $empArr['empid'];
								if(isset($_POST['date']))
								{
									$changedPeriod = $_POST['date'];
									$payrollDate = "SELECT DISTINCT date FROM payroll WHERE date= '$changedPeriod' AND empid = '$empid' ORDER BY date ASC";
								}
								else
									$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid'ORDER BY date ASC";

								$payrollDateQuery = mysql_query($payrollDate);
								
								while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
								{
									
									//For the specfied week in first column
									$endDate = $payDateArr['date'];
									$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));
									//Print "<script>console.log('".$endDate." - ".$startDate."')</script>";

									$payroll = "SELECT * FROM payroll WHERE date = '$endDate' AND empid = '$empid' ORDER BY date ASC";
									$payrollQuery = mysql_query($payroll);
									if(mysql_num_rows($payrollQuery) > 0)
									{
										$payrollArr = mysql_fetch_assoc($payrollQuery);
										if($payrollArr['pagibig'] != 0)
										{
											$PagibigBool = true;
											//Print "<script>console.log('bool: ".$PagibigBool."')</script>";
											$monthly = $payrollArr['rate'] * 25;

											$PagibigEmployer = $payrollArr['pagibig_er'];//Gets the value in the pagibig table

											//Print "<script>console.log('".$PagibigEmployer."')</script>";
											$PagibigContribution = $PagibigEmployer;

											$totalPagibigContribution = $PagibigContribution + $payrollArr['pagibig'];
											Print "
													<tr>
														<td>
															".$startDate." - ".$endDate."
														</td>
														<td>
															".$empArr['lastname'].", ".$empArr['firstname']."
														</td>
														<td>
															".$empArr['position']."
														</td>
														<td>
															".numberExactFormat($payrollArr['pagibig'], 2, '.')."
														</td>
														<td>
															".numberExactFormat($PagibigContribution, 2, '.')."
														</td>
														<td>
															".numberExactFormat($totalPagibigContribution, 2, '.')."
														</td>
													</tr>";

											$overallPagibig += $totalPagibigContribution;
										}
									}
								}
							}
							if($PagibigBool)
							{
								Print "
								<tr>
									<td colspan='4'>
									</td>
									<td>
										Grand Total
									</td>
									<td>
										".numberExactFormat($overallPagibig, 2, '.')."
									</td>
								</tr>";
							}
							if(!$PagibigBool)
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
						$PagibigBool = false;//if employee dont have Pagibig contribution
						$overallPagibig = 0;
						if(mysql_num_rows($empQuery))//there's employee in the site
						{
							
							$PagibigBool = false;//if employee dont have Pagibig contribution
							if(isset($_POST['date']))
							{
								$changedPeriod = explode(' ',$_POST['date']);
								$monthPeriod = $changedPeriod[0];
								$yearPeriod = $changedPeriod[1];
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE (date LIKE '$monthPeriod%' AND date LIKE '%$yearPeriod') ORDER BY date ASC";
							}
							else
							{
								$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY date ASC";
							}
							while($empArr = mysql_fetch_assoc($empQuery))
							{
								$empid = $empArr['empid'];

								$payrollDateQuery = mysql_query($payrollDate);

								$monthNoRepeat = "";

								$PagibigBool = true;
								$EEContribution = 0;
								$ERContribution = 0;
								$totalPagibigContribution = 0;
								//Evaluates the attendance and compute the Pagibig contribution
								while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
								{
									//Print "<script>console.log('".$payrollDate."')</script>";
									$dateExploded = explode(" ", $payDateArr['date']);
									$month = $dateExploded[0];//gets the month
									$year = $dateExploded[2];// gets the year

									$payrollDay = $payDateArr['date'];

									//Print "<script>console.log('".$month." - ".$year."')</script>";

									$payroll = "SELECT * FROM payroll WHERE (date LIKE '$month%' AND date LIKE '%$year') AND empid = '$empid' ORDER BY date ASC";
									$payrollQuery = mysql_query($payroll);
									if(mysql_num_rows($payrollQuery) > 0)
									{
										

										while($payrollArr = mysql_fetch_assoc($payrollQuery))
										{
											if($payrollArr['pagibig'] != 0)
											{
												$PagibigBool = true;
												//Print "<script>console.log('yess')</script>";
												$monthly = $payrollArr['rate'] * 25;

												$PagibigEmployer = $payrollArr['pagibig_er'];//Gets the value in the Pagibig table

												//Print "<script>console.log('".$PagibigEmployer."')</script>";
												$ERContribution += $PagibigEmployer;

												$totalPagibigContribution = $ERContribution + $payrollArr['pagibig'];

												$EEContribution += $payrollArr['pagibig'];
												
												
											}
											else
											{
												$PagibigBool = false;
											}
										}
										if($PagibigBool)
										{
											if($monthNoRepeat != $month.$year)
											{
												$totalPagibigContribution = $ERContribution+$EEContribution;
												$overallPagibig += $totalPagibigContribution;

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
																".numberExactFormat($EEContribution, 2, '.')."
															</td>
															<td>
																".numberExactFormat($ERContribution, 2, '.')."
															</td>
															<td>
																".numberExactFormat($totalPagibigContribution, 2, '.')."
															</td>
														</tr>";

											}
											
										}
									}

									$monthNoRepeat = $month.$year;
								}
							}
						}
						if($PagibigBool)//only display when employee has Pagibig
						{
							Print "
							<tr>
								<td colspan='4'>
								</td>
								<td>
									Grand Total
								</td>
								<td>
									".numberExactFormat($overallPagibig, 2, '.')."
								</td>
							</tr>";
						}
						if(!$PagibigBool)
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
						$PagibigBool = false;//if employee dont have Pagibig contribution
						$overallPagibig = 0;
						if(mysql_num_rows($empQuery))//there's employee in the site
						{
							
							$PagibigBool = false;//if employee dont have Pagibig contribution
							if(isset($_POST['date']))
							{
								$changedPeriod = explode(' ',$_POST['date']);
								$monthPeriod = $changedPeriod[0];
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE  date LIKE '%$yearPeriod' ORDER BY date ASC";
							}
							else
							{
								$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY date ASC";
							}
							while($empArr = mysql_fetch_assoc($empQuery))
							{
								$empid = $empArr['empid'];

								$payrollDateQuery = mysql_query($payrollDate);

								$yearNoRepeat = "";

								$PagibigBool = true;
								$EEContribution = 0;
								$ERContribution = 0;
								$totalPagibigContribution = 0;
								//Evaluates the attendance and compute the Pagibig contribution
								while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
								{
									//Print "<script>console.log('".$payrollDate."')</script>";
									$dateExploded = explode(" ", $payDateArr['date']);
									$year = $dateExploded[2];// gets the year

									$payrollDay = $payDateArr['date'];

									//Print "<script>console.log('".$month." - ".$year."')</script>";

									$payroll = "SELECT * FROM payroll WHERE date LIKE '%$year' AND empid = '$empid' ORDER BY date ASC";
									$payrollQuery = mysql_query($payroll);
									if(mysql_num_rows($payrollQuery) > 0)
									{
										

										while($payrollArr = mysql_fetch_assoc($payrollQuery))
										{
											if($payrollArr['pagibig'] != 0)
											{
												$PagibigBool = true;
												//Print "<script>console.log('yess')</script>";
												$monthly = $payrollArr['rate'] * 25;

												$PagibigEmployer = $payrollArr['pagibig_er'];//Gets the value in the Pagibig table

												//Print "<script>console.log('".$PagibigEmployer."')</script>";
												$ERContribution += $PagibigEmployer;

												$totalPagibigContribution = $ERContribution + $payrollArr['pagibig'];

												$EEContribution += $payrollArr['pagibig'];
												
												
											}
											else
											{
												$PagibigBool = false;
											}
										}
										if($PagibigBool)
										{
											if($yearNoRepeat != $year)
											{
												$totalPagibigContribution = $ERContribution+$EEContribution;
												$overallPagibig += $totalPagibigContribution;

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
																".numberExactFormat($EEContribution, 2, '.')."
															</td>
															<td>
																".numberExactFormat($ERContribution, 2, '.')."
															</td>
															<td>
																".numberExactFormat($totalPagibigContribution, 2, '.')."
															</td>
														</tr>";

											}
											
										}
									}

									$yearNoRepeat = $year;
								}
							}
						}
						if($PagibigBool)//only display when employee has Pagibig
						{
							Print "
							<tr>
								<td colspan='4'>
								</td>
								<td>
									Grand Total
								</td>
								<td>
									".numberExactFormat($overallPagibig, 2, '.')."
								</td>
							</tr>";
						}
						if(!$PagibigBool)
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

	</div>
	<input type="hidden" id="printButton" value="<?php Print $PagibigBool?>">
	<form id="changeDateForm" method="post" action="reports_overall_pagibig.php?site=<?php Print $site?>&period=<?php Print $period?>">
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
			window.location.assign("reports_overall_pagibig.php?site=<?php Print $site?>&period="+period);

		}
		//Disables the button if there's no data
		$(document).ready(function(){
			if($("#printButton").val() == 0) {
			    $("#printPagibig").attr("disabled", "disabled");
			}
		});
	</script>
</body>
</html>