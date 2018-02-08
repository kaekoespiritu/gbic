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
						<li>Overall SSS Contributions Report for <?php Print $breadcrumInfo?></li>
						<button class='btn btn-primary pull-right'>
							Print SSS Contributions
						</button>
					</ol>
				</div>
			</div>

			<div class="col-md-10 col-md-offset-1">
				<div class="form-inline">
					<h4>Select Period</h4>
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
					<h4>Select <?php Print $period?></h4>
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
					<h4>Select Requirements Type</h4>
					<select class='form-control'>
						<option>All</option>
						<option>With Requirements</option>
						<option>Without Requirements</option>
					</select>
				</div>
				<div class="pull-down">
				<button class="btn btn-default" id="printSSS">
					Print <?php Print $printButton?>
				</button>
				<table class="table table-bordered pull-down">
					<tr>
						<td colspan="7">
							 <?php Print $breadcrumInfo?> SSS Contribution of employees for  <?php Print $site?>
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
										if($payrollArr['sss'] != 0)
										{
											$sssBool = true;
											//Print "<script>console.log('bool: ".$sssBool."')</script>";
											$monthly = $payrollArr['rate'] * 25;

											$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table

											//Print "<script>console.log('".$sssEmployer."')</script>";
											$sssContribution = $sssEmployer;

											$totalSSSContribution = $sssContribution + $payrollArr['sss'];
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
															".numberExactFormat($payrollArr['sss'], 2, '.')."
														</td>
														<td>
															".numberExactFormat($sssContribution, 2, '.')."
														</td>
														<td>
															".numberExactFormat($totalSSSContribution, 2, '.')."
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
										".numberExactFormat($overallSSS, 2, '.')."
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
						if(mysql_num_rows($empQuery))//there's employee in the site
						{
							$overallSSS = 0;
							$sssBool = false;//if employee dont have sss contribution
							while($empArr = mysql_fetch_assoc($empQuery))
							{
								$empid = $empArr['empid'];

								if(isset($_POST['date']))
								{
									$changedPeriod = explode(' ',$_POST['date']);
									$monthPeriod = $changedPeriod[0];
									$yearPeriod = $changedPeriod[1];
									$payrollDate = "SELECT DISTINCT date FROM payroll WHERE (date LIKE '$monthPeriod%' AND date LIKE '%$yearPeriod') AND empid = '$empid' ORDER BY date ASC";
								}
								else
								{
									$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid'ORDER BY date ASC";
								}
								

								$payrollDateQuery = mysql_query($payrollDate);

								//Evaluates the attendance and compute the sss contribution
								while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
								{
									$dateExploded = explode(" ", $payDateArr['date']);
									$month = $dateExploded[0];//gets the month
									$year = $dateExploded[2];// gets the year

									$payrollDay = $payDateArr['date'];

									//Print "<script>console.log('".$month." - ".$year."')</script>";

									$payroll = "SELECT * FROM payroll WHERE (date LIKE '$month%' AND date LIKE '%$year') AND empid = '$empid' ORDER BY date ASC";
									$payrollQuery = mysql_query($payroll);
									if(mysql_num_rows($payrollQuery) > 0)
									{
										$sssBool = true;
										$EEContribution = 0;
										$ERContribution = 0;
										$totalSSSContribution = 0;

										while($payrollArr = mysql_fetch_assoc($payrollQuery))
										{
											if($payrollArr['sss'] != 0)
											{
												$sssBool = true;
												//Print "<script>console.log('yess')</script>";
												$monthly = $payrollArr['rate'] * 25;

												$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table

												//Print "<script>console.log('".$sssEmployer."')</script>";
												$ERContribution += $sssEmployer;

												$totalSSSContribution = $ERContribution + $payrollArr['sss'];

												$EEContribution += $payrollArr['sss'];
												
												$overallSSS += $totalSSSContribution;
											}
											else
											{
												$sssBool = false;
											}
										}
										if($sssBool)
										{
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
															".numberExactFormat($totalSSSContribution, 2, '.')."
														</td>
													</tr>";
										}
									}
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
									".numberExactFormat($overallSSS, 2, '.')."
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
						if(mysql_num_rows($empQuery))//there's employee in the site
						{
							$overallSSS = 0;
							while($empArr = mysql_fetch_assoc($empQuery))
							{
								$empid = $empArr['empid'];

								if(isset($_POST['date']))
								{
									$changedPeriod = $_POST['date'];
									$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' AND (date LIKE '%$changedPeriod') ORDER BY date ASC";
								}
								else
									$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY date ASC";
								$payrollDateQuery = mysql_query($payrollDate);

								//monthly

								//Evaluates the attendance and compute the sss contribution
								while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
								{
									$dateExploded = explode(" ", $payDateArr['date']);
									$year = $dateExploded[2];// gets the year
									$yearBefore = $year - 1;

									$payrollDay = $payDateArr['date'];


									$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date LIKE '%$year' ORDER BY date ASC";
									$payrollQuery = mysql_query($payroll);
									if(mysql_num_rows($payrollQuery) > 0)
									{
										$sssBool = true;
										$EEContribution = 0;
										$ERContribution = 0;
										$totalSSSContribution = 0;

										while($payrollArr = mysql_fetch_assoc($payrollQuery))
										{
											if($payrollArr['sss'] != 0)
											{
												$sssBool = true;
												//Print "<script>console.log('yess')</script>";
												$monthly = $payrollArr['rate'] * 25;

												$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table

												//Print "<script>console.log('".$sssEmployer."')</script>";
												$ERContribution += $sssEmployer;
												Print "<script>console.log('".$sssEmployer."')</script>";
												$totalSSSContribution = $ERContribution + $payrollArr['sss'];

												$EEContribution += $payrollArr['sss'];
												
												$overallSSS += $totalSSSContribution;
											}
											else
											{
												$sssBool = false;
											}
										}
										if($sssBool)
										{
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
															".numberExactFormat($totalSSSContribution, 2, '.')."
														</td>
													</tr>";
										}
									}
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
									".numberExactFormat($overallSSS, 2, '.')."
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
		//Disables the button if there's no data
		$(document).ready(function(){
			if($("#printButton").val() == 0) {
			    $("#printSSS").attr("disabled", "disabled");
			}
		});
	</script>
</body>
</html>