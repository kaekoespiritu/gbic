<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');


	if(isset($_GET['empid']) || isset($_GET['period']))
	{
		$period = $_GET['period'];
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
			header("location: reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null");
		}
	}
	else
	{
		header("location: reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null");
	}

	//bread crum
	$breadcrumInfo = $empArr['lastname'].", ".$empArr['firstname']." - ".$empArr['position']." at ".$empArr['site']; 

	//Print button name
	switch($period)
	{
	 	case 'week': $printButton = "Weekly";break;
	 	case 'month': $printButton = "Monthly";break;
	 	case 'year': $printButton = "Yearly";break;
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
						<li><a href='reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Contributions</a></li>
						<li>Individual PhilHealth Contributions Report for <?php Print $breadcrumInfo?></li>
					</ol>
				</div>
			</div>

			<div class="col-md-10 col-md-offset-1">
				<div class="form-inline">
					<h4>Select view</h4>
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
					<h4>Select period</h4>
					<select class="form-control">
						<option>Sample date</option>
					</select>
				</div>
				<div class="pull-down">
				<button class="btn btn-default" id="printSSS">
					Print <?php Print $printButton?>
				</button>
				<table class="table table-bordered pull-down">
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

					function sssTable($monthly)//SSS Table
						{
							$sssEmployer = 0;

							if($monthly >= 1000 && $monthly <= 1249.9)
								$sssEmployer = 83.70;
							//1250 ~ 1749.9 = 54.50
							else if($monthly >= 1250 && $monthly <= 1749.9)
								$sssEmployer = 120.50;
							//1750 ~ 2249.9 = 72.70
							else if($monthly >= 1750 && $monthly <= 2249.9)
								$sssEmployer = 157.30;
							//2250 ~ 2749.9 = 90.80
							else if($monthly >= 2250 && $monthly <= 2749.9)
								$sssEmployer = 194.20;
							//2750 ~ 3249.9 = 109.0
							else if($monthly >= 2750 && $monthly <= 3249.9)
								$sssEmployer = 231.00;
							//3250 ~ 3749.9 = 127.20
							else if($monthly >= 3250 && $monthly <= 3749.9)
								$sssEmployer = 267.80;
							//3750 ~ 4249.9 = 145.30
							else if($monthly >= 3750 && $monthly <= 4249.9)
								$sssEmployer = 304.70;
							//4250 ~ 4749.9 = 163.50
							else if($monthly >= 4250 && $monthly <= 4749.9)
								$sssEmployer = 341.50;
							//4750 ~ 5249.9 = 181.70
							else if($monthly >= 4750 && $monthly <= 5249.9)
								$sssEmployer = 378.30;
							//5250 ~ 5749.9 = 199.80
							else if($monthly >= 5250 && $monthly <= 5749.9)
								$sssEmployer = 415.20;
							//5750 ~ 6249.9 = 218.0
							else if($monthly >= 5750 && $monthly <= 6249.9)
								$sssEmployer = 452.00;
							//6250 ~ 6749.9 = 236.20
							else if($monthly >= 6250 && $monthly <= 6749.9)
								$sssEmployer = 488.80;
							//6750 ~ 7249.9 = 254.30
							else if($monthly >= 6750 && $monthly <= 7249.9)
								$sssEmployer = 525.70;
							//7250 ~ 7749.9 = 272.50
							else if($monthly >= 7250 && $monthly <= 7749.9)
								$sssEmployer = 562.50;
							//7750 ~ 8249.9 = 290.70
							else if($monthly >= 7750 && $monthly <=  8249.9)
								$sssEmployer = 599.30;
							//8250 ~ 8749.9 = 308.80
							else if($monthly >= 8250 && $monthly <= 8749.9)
								$sssEmployer = 636.20;
							//8750 ~ 9249.9 = 327.0
							else if($monthly >= 8750 && $monthly <= 9249.9 )
								$sssEmployer = 673.00;
							//9250 ~ 9749.9 = 345.20
							else if($monthly >= 9250 && $monthly <= 9749.9)
								$sssEmployer = 709.80;
							//9750 ~ 10249.9 = 363.30
							else if($monthly >= 9750 && $monthly <= 10249.9)
								$sssEmployer = 746.70;
							//10250 ~ 10749.9 = 381.50
							else if($monthly >= 10250 && $monthly <=  10749.9)
								$sssEmployer = 783.50;
							//10750 ~ 11249.9 = 399.70
							else if($monthly >= 10750 && $monthly <= 11249.9)
								$sssEmployer = 820.30;
							//11250 ~ 11749.9 = 417.80
							else if($monthly >= 11250 && $monthly <= 11749.9)
								$sssEmployer = 857.20;
							//11750 ~ 12249.9 = 436.0
							else if($monthly >= 11750 && $monthly <= 12249.9)
								$sssEmployer = 894.00;
							//12250 ~ 12749.9 = 454.20
							else if($monthly >= 12250 && $monthly <= 12749.9)
								$sssEmployer = 930.80;
							//12750 ~ 13249.9 = 472.30
							else if($monthly >= 12750 && $monthly <= 13249.9)
								$sssEmployer = 967.70;
							//13250 ~ 13749.9 = 490.50
							else if($monthly >= 13250 && $monthly <= 13749.9)
								$sssEmployer = 1004.5;
							//13750 ~ 14249.9 = 508.70
							else if($monthly >= 13750 && $monthly <= 14249.9 )
								$sssEmployer = 1041.30;
							//14250 ~ 14749.9 = 526.80
							else if($monthly >= 14250 && $monthly <= 14749.9)
								$sssEmployer = 1070.20;
							//14750 ~ 15249.9 = 545.0
							else if($monthly >= 14750 && $monthly <= 15249.9 )
								$sssEmployer = 1135.00;
							//15250 ~ 15749.9 = 563.20
							else if($monthly >= 15250 && $monthly <= 15749.9)
								$sssEmployer = 1171.80;
							//15750 ~ higher = 581.30
							else if($monthly >= 15750)
								$sssEmployer = 1208.70;

							return $sssEmployer;
						}

					if($period == "week")
					{
						$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY date ASC";
						$payrollDateQuery = mysql_query($payrollDate);

						//weekly
						$overallSSS = 0;

						$sssBool = false;//if employee dont have sss contribution

						//Evaluates the attendance and compute the 13th monthpay
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							Print "<script>console.log('bool: ".$sssBool."')</script>";
							//For the specfied week in first column
							$endDate = $payDateArr['date'];
							$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));
							Print "<script>console.log('".$endDate." - ".$startDate."')</script>";

							$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$endDate' ORDER BY date ASC";
							$payrollQuery = mysql_query($payroll);
							if(mysql_num_rows($payrollQuery) > 0)
							{
								$payrollArr = mysql_fetch_assoc($payrollQuery);
								if($payrollArr['sss'] != 0)
								{
									$sssBool = true;
									Print "<script>console.log('bool: ".$sssBool."')</script>";
									$monthly = $payrollArr['rate'] * 25;

									$sssEmployer = sssTable($monthly);//Gets the value in the sss table

									Print "<script>console.log('".$sssEmployer."')</script>";
									$sssContribution = $sssEmployer / 4;

									$totalSSSContribution = $sssContribution + $payrollArr['sss'];
									Print "
											<tr>
												<td>
													".$startDate." - ".$endDate."
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

								if($sssBool)
								{
									Print "
									<tr>
										<td colspan='2'>
										</td>
										<td>
											Grand Total
										</td>
										<td>
											".numberExactFormat($overallSSS, 2, '.')."
										</td>
									</tr>";
								}
								
							}
							else
							{
								$sssBool = true;
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

							if(!$sssBool)
							{
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

						}
					}
					else if($period == "month")
					{
						$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY date ASC";
						$payrollDateQuery = mysql_query($payrollDate);

						//monthly
						$overallSSS = 0;

						$sssBool = false;//if employee dont have sss contribution
						//Evaluates the attendance and compute the sss contribution
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							$dateExploded = explode(" ", $payDateArr['date']);
							$month = $dateExploded[0];//gets the month
							$year = $dateExploded[2];// gets the year

							$payrollDay = $payDateArr['date'];

							Print "<script>console.log('".$month." - ".$year."')</script>";

							$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date LIKE '$month%' AND date LIKE '%$year' ORDER BY date ASC";
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
										Print "<script>console.log('yess')</script>";
										$monthly = $payrollArr['rate'] * 25;

										$sssEmployer = sssTable($monthly);//Gets the value in the sss table

										//Print "<script>console.log('".$sssEmployer."')</script>";
										$ERContribution += $sssEmployer / 4;

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
							else
							{
								$sssBool = true;
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

							if(!$sssBool)
							{
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

						}
						if($sssBool)//only display when employee has sss
						{
							Print "
							<tr>
								<td colspan='2'>
								</td>
								<td>
									Grand Total
								</td>
								<td>
									".numberExactFormat($overallSSS, 2, '.')."
								</td>
							</tr>";
						}
					}
					else if($period = "year")
					{
						$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY date ASC";
						$payrollDateQuery = mysql_query($payrollDate);

						//monthly
						$overallSSS = 0;

						$sssBool = false;//if employee dont have sss contribution
						//Evaluates the attendance and compute the sss contribution
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							$dateExploded = explode(" ", $payDateArr['date']);
							$year = $dateExploded[2];// gets the year
							$yearBefore = $year - 1;

							$payrollDay = $payDateArr['date'];

							Print "<script>console.log('".$year."')</script>";

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
										Print "<script>console.log('yess')</script>";
										$monthly = $payrollArr['rate'] * 25;

										$sssEmployer = sssTable($monthly);//Gets the value in the sss table

										//Print "<script>console.log('".$sssEmployer."')</script>";
										$ERContribution += $sssEmployer / 4;

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
							else
							{
								$sssBool = true;
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

							if(!$sssBool)
							{
								Print "
										<tr>
											<td colspan='4'>
											 	No Report data as of the moment
											</td>
										</tr>";
							}

						}
						if($sssBool)//only display when employee has sss
						{
							Print "
							<tr>
								<td colspan='2'>
								</td>
								<td>
									Grand Total
								</td>
								<td>
									".numberExactFormat($overallSSS, 2, '.')."
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

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>