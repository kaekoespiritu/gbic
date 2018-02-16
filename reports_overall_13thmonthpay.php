<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	
	$site = $_GET['site'];
	$require = $_GET['req'];
	$position = $_GET['position'];
	$period = $_GET['period'];

	//Checks if site in HTTP is altered by user manually
	$siteChecker = "SELECT * FROM site WHERE location = '$site'";
	//Checks if position in HTTP is altered by user manually 
	$positionChecker = "SELECT * FROM job_position WHERE position = '$position'";
	$siteCheckerQuery = mysql_query($siteChecker);
	$positionCheckerQuery = mysql_query($positionChecker);
	if(mysql_num_rows($siteCheckerQuery) == 0)
	{
		header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
	}
	if($position != 'all')
	{
		if(mysql_num_rows($positionCheckerQuery) == 0)
		{
			header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
		}
	}
		
	
	// Checks if requirement in HTTP is altered by user manually 
	switch($require) {
		case "null":break;
		case "all":break;
		case "withReq":break;
		case "withOReq":break;
		default: header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");;
	}
	//Checks if period in HTTP is altered by user manually 
	switch($period) {
		case "null":break;
		case "week":break;
		case "month":break;
		case "year":break;
		default: header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");;
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
						<li><a href='reports_overall_earnings.php?type=Earnings&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Overall 13th Month Pay Report for <?php Print $site?></li>
					</ol>
				</div>
			</div>

		<div class="form-inline">
			Filters:
			<select onchange="requirementChange(this.value)" class="form-control">
				<option hidden>Requirements</option>
				<option value='all'>All</option>
				<?php
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

			<select onchange="positionChange(this.value)" class="form-control">
				<option hidden>Position</option>
				<?php
					$pos = "SELECT * FROM job_position WHERE active='1'";
					$posQuery = mysql_query($pos);
					Print "<option value='all'>All</option>";
					while($posArr = mysql_fetch_assoc($posQuery))
					{
						if($position == $posArr['position'])
							Print "<option value='".$posArr['position']."' selected>".$posArr['position']."</option>";
						else
							Print "<option value='".$posArr['position']."'>".$posArr['position']."</option>";
					}

				?>
			</select>
			<select onchange="periodChange(this.value)" class="form-control">
				<option hidden>Period</option>
				<?php
					if($period == 'week')
						Print "<option value='week' selected>Weekly</option>";
					else
						Print "<option value='week'>Weekly</option>";
					if($period == 'month')
						Print "<option value='month' selected>Monthly</option>";
					else
						Print "<option value='month'>Monthly</option>";
					if($period == 'year')
						Print "<option value='year' selected>Yearly</option>";
					else
						Print "<option value='year'>Yearly</option>";
				?>
			</select>
		</div>
	<?php
	if($period == 'week')
	{
		Print "<br>
		<div class='form-inline'>
		Weeks:
		<select onchange='weekDates(this.value)' class='form-control'>
			<option hidden>Select date</option>";
			
			$payrollDays = "SELECT DISTINCT date FROM Payroll ORDER BY date ASC";//gets non repeatable dates
			$payrollDaysQuery = mysql_query($payrollDays);

			if(mysql_num_rows($payrollDaysQuery))
			{
				while($PdaysOptions = mysql_fetch_assoc($payrollDaysQuery))
				{

					$startDate = date('F j, Y', strtotime('-6 day', strtotime($PdaysOptions['date'])));

					if(isset($_POST['chosenDate']))
					{
						if($_POST['chosenDate'] == $PdaysOptions['date'])
						{
							Print "<option value='".$PdaysOptions['date']."' selected>".$startDate." - ".$PdaysOptions['date']."</option>";
						}
						else
						{
							Print "<option value='".$PdaysOptions['date']."'>".$startDate." - ".$PdaysOptions['date']."</option>";
						}
					}
					else
					{
						Print "<option value='".$PdaysOptions['date']."'>".$startDate." - ".$PdaysOptions['date']."</option>";
					}
				}
			}
			else
			{
				Print "<option>No payroll date</option>";
			}
			Print "</select></div>";	
	}
		
		?>
		<?php
		 switch($period)
		 {
		 	case 'week': $printButton = "Weekly";break;
		 	case 'month': $printButton = "Monthly";break;
		 	case 'year': $printButton = "Yearly";break;
		 }

		?>
		<?php

		//Query for employees
		if($position != 'all')
		{
			if($require != 'all')
			{
				if($require != 'withReq')
				{
					$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND position = '$position' AND complete_doc = '1'ORDER BY lastname ASC, position ASC";
				}
				else
				{
					$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND position = '$position' AND complete_doc = '0'ORDER BY lastname ASC, position ASC";
				}
			}
			else
			{
				$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND position = '$position' ORDER BY lastname ASC, position ASC";
			}
		}
		else if($require != 'all')
		{

			if($require != 'withReq')
			{
				if($position != 'all')
				{
					$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND position = '$position' AND complete_doc = '1'ORDER BY lastname ASC, position ASC";
				}
				else
				{
					$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND complete_doc = '1'ORDER BY lastname ASC, position ASC";
				}
			}
			else
			{
				if($position != 'all')
				{
					$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND position = '$position' AND complete_doc = '0'ORDER BY lastname ASC, position ASC";
				}
				else
				{
					$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND complete_doc = '0'ORDER BY lastname ASC, position ASC";
				}
			}
		}
		else
		{
			$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'ORDER BY lastname ASC, position ASC";
		}
						
		if(isset($_POST['chosenDate']))
		{
			Print "	<div class='pull-down'>
						<div class='col-md-10 col-md-offset-1'>
							<button class='btn btn-default'>
								Print ".$printButton."
							</button>
							<table class='table table-bordered pull-down'>
							
							<tr>
								<td colspan='5'>
									13th Month pay report for ".$printButton."
								</td>	
							</tr>
							<tr>
								<td>
									Employee ID
								</td>
								<td>
									Name
								</td>
								<td>
									Position
								</td>
								<td>
									Week
								</td>
								<td>
									Amount
								</td>
							</tr>";
				
				
						

						//weekly
						$overallPayment = 0;
						
						$employeeQuery = mysql_query($employee);

						while($empArr = mysql_fetch_assoc($employeeQuery))
						{
							$empid = $empArr['empid'];
							Print "<script>console.log('".$empid."')</script>";
							//Evaluates the attendance and compute the 13th monthpay
							
								$endDate = $_POST['chosenDate'];
								$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));
								Print "<script>console.log('".$endDate." - ".$startDate."')</script>";

								
								$attendance = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY date ASC";
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
												".$empid."
											</td>
											<td>
												".$empArr['lastname'].", ".$empArr['firstname']."
											</td>
											<td>
												".$empArr['position']."
											</td>
											<td>
												".$startDate." - ".$endDate."
											</td>
											<td>
												".numberExactFormat($thirteenthMonth, 2, '.')."
											</td>
										</tr>";

								$overallPayment += $thirteenthMonth;
							
						}
					
						
			if(mysql_num_rows($employeeQuery) == 0)	
			{
				Print "
						<tr bgcolor='#E74C3C'>
							<td colspan='5'>
								No 13th month pay report as of the moment.
							</td>
						</tr>
						";
			}	

			Print "	<tr>
						<td colspan='3'>
						</td>
						<td>
							Grand Total
						</td>
						<td>
							".numberExactFormat($overallPayment, 2, '.')."
						</td>
					</tr>
					</table>";
			}
			else if($period == 'month')
			{
				Print "	<div class='pull-down'>
						<div class='col-md-10 col-md-offset-1'>
							<button class='btn btn-default'>
								Print ".$printButton."
							</button>
							<table class='table table-bordered pull-down'>
							
							<tr>
								<td colspan='5'>
									13th Month pay report for ".$printButton."
								</td>	
							</tr>
							<tr>
								<td>
									Employee ID
								</td>
								<td>
									Name
								</td>
								<td>
									Position
								</td>
								<td>
									Month
								</td>
								<td>
									Amount
								</td>
							</tr>";

				$employeeQuery = mysql_query($employee);
				$overallPayment = 0;
				while($empArr = mysql_fetch_assoc($employeeQuery))
				{
					$empid = $empArr['empid'];
					$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' ORDER BY date ASC";
					$attQuery = mysql_query($attendance);

					$daysAttended = 0;//counter for days attended
					$noRepeat = null;
					
					//Computes 13th monthpay per month
					while($attDate = mysql_fetch_assoc($attQuery))
					{
						$dateExploded = explode(" ", $attDate['date']);
						$month = $dateExploded[0];
						$year = $dateExploded[2];

						if ($noRepeat != $month || $noRepeat == null)
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
											".$empArr['empid']."
										</td>
										<td>
											".$empArr['lastname'].", ".$empArr['firstname']."
										</td>
										<td>
											".$empArr['position']."
										</td>
										<td>
											".$month." ".$year."
										</td>
										<td>
											".numberExactFormat($thirteenthMonth, 2, '.')."
										</td>
									</tr>";
							$overallPayment += $thirteenthMonth;
						}
						
						$noRepeat = $month;
					}
				}
			if(mysql_num_rows($employeeQuery) == 0)	
			{
				Print "
						<tr bgcolor='#E74C3C'>
							<td colspan='5'>
								No 13th month pay report as of the moment.
							</td>
						</tr>
						";
			}	

			Print "	<tr>
						<td colspan='3'>
						</td>
						<td>
							Grand Total
						</td>
						<td>
							".numberExactFormat($overallPayment, 2, '.')."
						</td>
					</tr>
					</table>";
			}
			else if($period == 'year')
			{
				Print "	<div class='pull-down'>
						<div class='col-md-10 col-md-offset-1'>
							<button class='btn btn-default'>
								Print ".$printButton."
							</button>
							<table class='table table-bordered pull-down'>
							
							<tr>
								<td colspan='5'>
									13th Month pay report for ".$printButton."
								</td>	
							</tr>
							<tr>
								<td>
									Employee ID
								</td>
								<td>
									Name
								</td>
								<td>
									Position
								</td>
								<td>
									Year
								</td>
								<td>
									Amount
								</td>
							</tr>";
				$employeeQuery = mysql_query($employee);
				$overallPayment = 0;
				while($empArr = mysql_fetch_assoc($employeeQuery))
				{
					$empid = $empArr['empid'];
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
											".$empArr['empid']."
										</td>
										<td>
											".$empArr['lastname'].", ".$empArr['firstname']."
										</td>
										<td>
											".$empArr['position']."
										</td>
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
				if(mysql_num_rows($employeeQuery) == 0)	
				{
					Print "
							<tr bgcolor='#E74C3C'>
								<td colspan='5'>
									No 13th month pay report as of the moment.
								</td>
							</tr>
							";
				}	

				Print "	<tr>
							<td colspan='3'>
							</td>
							<td>
								Grand Total
							</td>
							<td>
								".numberExactFormat($overallPayment, 2, '.')."
							</td>
						</tr>
						</table>";
			}
			?>
			</div>

			
	</div>
	<form id="dynamicForm" method="post">
		<input type="hidden" name="chosenDate">
	</form>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function periodChange(period) {
			window.location.assign("reports_overall_13thmonthpay.php?req=<?php Print $require?>&site=<?php Print $site?>&period="+period+"&position=<?php Print $position?>");
		}

		function requirementChange(req) {
			window.location.assign("reports_overall_13thmonthpay.php?req="+req+"&site=<?php Print $site?>&period=<?php Print $period?>&position=<?php Print $position?>");
		}

		function positionChange(position) {
			window.location.assign("reports_overall_13thmonthpay.php?req=<?php Print $require?>&site=<?php Print $site?>&period=<?php Print $period?>&position="+position);
		}
		function weekDates(date) {
			document.getElementsByName('chosenDate')[0].value = date;
			document.getElementById('dynamicForm').submit();
		}

	</script>
</body>
</html>