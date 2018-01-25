<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$empid = $_GET['empid'];
	$period = $_GET['per'];
	$employeeChecker = "SELECT * FROM employee WHERE empid = '$empid'";
	$employeeCheckerQuery = mysql_query($employeeChecker);

	if(mysql_num_rows($employeeCheckerQuery))
	{
		$empArr = mysql_fetch_assoc($employeeCheckerQuery);
		$employeeInfo = $empArr['lastname'].", ".$empArr['firstname']." | ".$empArr['position']." at ".$empArr['site'];
	}
	else//empid on http is altered manually
		header("location: reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null");
	
	if($period == "week" || $period == "month" || $period == "year")
	{}
	else
		header("location: reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null");
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
						<li><a href='reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Individual 13th Month Pay Report for <?php Print $employeeInfo?></li>
					</ol>
				</div>
			</div>

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
			
				
			<div class="col-md-6 col-md-offset-3">
				
				<?php
				 switch($period)
				 {
				 	case 'week': $printButton = "Weekly";break;
				 	case 'month': $printButton = "Monthly";break;
				 	case 'year': $printButton = "Yearly";break;
				 }
				?>
				<button class="btn btn-default" id="printButton">
					Print <?php Print $printButton?>
				</button>
				<table class="table table-bordered pull-down">
				<tr>
					<td colspan="2">
						13th Month pay
					</td>
				</tr>	
				<tr>
					<td>
						<?php Print $printButton?>
					</td>
					<td>
						Amount
					</td>
				</tr>
				<?php
					$oneThreeMonthBool = false;//for print button
					if($period == "week")
					{
						$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY date ASC";
						$payrollQuery = mysql_query($payrollDate);
						$dateLength = mysql_num_rows($payrollQuery);

						//weekly
						$overallPayment = 0;
						//Evaluates the attendance and compute the 13th monthpay
						while($payDateArr = mysql_fetch_assoc($payrollQuery))
						{
							$endDate = $payDateArr['date'];
							$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));
							Print "<script>console.log('".$endDate." - ".$startDate."')</script>";
							$attendance = "SELECT * FROM attendance WHERE empid = '$empid' AND date BETWEEN '$startDate' AND '$endDate' ORDER BY date ASC";
							$attQuery = mysql_query($attendance);

							$daysAttended = 0;//counter for days attended
							//Computes the 13th month
							while($attArr = mysql_fetch_assoc($attQuery))
							{
								$date = $attArr['date'];

								Print "<script>console.log('".$date."')</script>";
								$workHrs = $attArr['workhours'];

								$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
								$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

								if(mysql_num_rows($holidayCheckQuery) == 0)
								{
									if($attArr['attendance'] == '2')//check if student is present
									{
										if($attArr['workhours'] >= 8)//check if employee attended 8hours
										{
											$daysAttended++;
										}
									}
								}
							}
							Print "<script>console.log('".$daysAttended."')</script>";
							$thirteenthMonth = ($daysAttended * $empArr['rate']) / 12; 
							Print "
									<tr>
										<td>
											".$startDate." - ".$endDate."
										</td>
										<td>
											".$thirteenthMonth."
										</td>
									</tr>";

							$overallPayment += $thirteenthMonth;
						}
					}
					else if($period == "month")
					{
						$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' ORDER BY date ASC";
						$attQuery = mysql_query($attendance);

						$daysAttended = 0;//counter for days attended
						$noRepeat = null;
						$overallPayment = 0;
						//Computes 13th monthpay per month
						while($attDate = mysql_fetch_assoc($attQuery))
						{
							$dateExploded = explode(" ", $attDate['date']);
							$month = $dateExploded[0];
							$year = $dateExploded[2];

							if ($noRepeat != $month.$year  || $noRepeat == null)
							{
								$attMonth = "SELECT * FROM attendance WHERE empid = '$empid' AND date LIKE '$month%' AND date LIKE '%$year' ORDER BY date ASC";
								$attMonthQuery = mysql_query($attMonth);
								//Computes 13th month per day of the month
								while($attArr = mysql_fetch_assoc($attMonthQuery))
								{
									$date = $attArr['date'];

									$workHrs = $attArr['workhours'];

									$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
									$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

									if(mysql_num_rows($holidayCheckQuery) == 0)
									{
										if($attArr['attendance'] == '2')//check if student is present
										{
											if($attArr['workhours'] >= 8)//check if employee attended 8hours
											{
												$daysAttended++;
											}
										}
									}
								}
								$thirteenthMonth = ($daysAttended * $empArr['rate']) / 12; 
								Print "
										<tr>
											<td>
												".$month." ".$year."
											</td>
											<td>
												".numberExactFormat($thirteenthMonth, 2, '.')."
											</td>
										</tr>";
								$overallPayment += $thirteenthMonth;
							}
							
							$noRepeat = $month.$year;
						}


					}
					else if($period == "year")
					{
						$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' ORDER BY date ASC";
						$attQuery = mysql_query($attendance);

						$daysAttended = 0;//counter for days attended
						$noRepeat = null;
						$overallPayment = 0;
						//Computes 13th monthpay per month
						while($attDate = mysql_fetch_assoc($attQuery))
						{
							$dateExploded = explode(" ", $attDate['date']);
							$year = $dateExploded[2];

							if ($noRepeat != $year || $noRepeat == null)
							{
								$attYear = "SELECT * FROM attendance WHERE empid = '$empid' AND date LIKE '%$year' ORDER BY date ASC";
								$attYearQuery = mysql_query($attYear);
								//Computes 13th month per day of the month
								while($attArr = mysql_fetch_assoc($attYearQuery))
								{
									$date = $attArr['date'];

									$workHrs = $attArr['workhours'];

									$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
									$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

									if(mysql_num_rows($holidayCheckQuery) == 0)
									{
										if($attArr['attendance'] == '2')//check if student is present
										{
											if($attArr['workhours'] >= 8)//check if employee attended 8hours
											{
												$daysAttended++;
											}
										}
									}
								}
								$thirteenthMonth = ($daysAttended * $empArr['rate']) / 12; 
								$yearBefore = $year - 1;
								Print "
										<tr>
											<td>
												".$yearBefore." - ".$year."
											</td>
											<td>
												".numberExactFormat($thirteenthMonth, 2, '.')."
											</td>
										</tr>";
								$overallPayment += $thirteenthMonth;
							}
							
							$noRepeat = $year;
						}

					}
					

				?>
				

				<tr>
					<td>
						Total
					</td>
					<td>
						<?php Print numberExactFormat($overallPayment, 2, '.')?>
					</td>
				</tr>
				</table>
			</div>

		</div>

	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function periodChange(period) {
			window.location.assign('reports_individual_13thmonthpay.php?empid=<?php Print $empid?>&per='+period);
		}
	</script>
</body>
</html>