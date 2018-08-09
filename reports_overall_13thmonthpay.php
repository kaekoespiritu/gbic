<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$site = $_GET['site'];
	$require = $_GET['req'];
	$position = $_GET['position'];

	//Checks if site in HTTP is altered by user manually
	$siteChecker = "SELECT * FROM site WHERE location = '$site'";
	//Checks if position in HTTP is altered by user manually 
	$positionChecker = "SELECT * FROM job_position WHERE position = '$position' AND active = '1'";
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
		case "all":break;
		case "withReq":break;
		case "withOReq":break;
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


		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_overall_earnings.php?type=Earnings&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Overall 13th Month Pay Report for <?php Print $site?></li>
						<a class="btn btn-primary pull-right" href="reports_overall_13thmonthpay_deduction.php?site=<?php Print $site?>&position=<?php Print $position?>">

							Give 13th Month Pay
						</a>
					</ol>
				</div>
			</div>

		<div class="form-inline">
			<div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
			<h4>Filters:</h4>
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
			<a class="btn btn-danger" href="reports_overall_13thmonthpay.php?position=all&req=all&site=<?php Print $site?>">Clear Filters</a>
		</div>


		<!-- <div class='pull-down'> -->
		<div class='col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1'>
			<button class='btn btn-primary pull-down' onclick="print13thMonth()">
				Print 13th Month pay report
			</button>
			<table class='table table-bordered pull-down'>

			
			<tr>
				<td colspan='5'>
					<?php Print $site ?> 13th Month Pay Report
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
					From - To date
				</td>
				<td>
					13th MonthPay Amount
				</td>
			</tr>
	<?php
	//Filters
		$appendQuery = "";
		if($position != "all")
		{
			$appendQuery .= " AND position = '$position' ";
		}
		if($require != "all")
		{
			$req = ($require == "withReq" ? 1 : 0);
			$appendQuery .= " AND complete_doc = '$req' ";
		}


		$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' $appendQuery ORDER BY lastname ASC, firstname ASC";
		$empQuery = mysql_query($employee) or die(mysql_error());

		if(mysql_num_rows($empQuery) != 0)
		{
			$overall13thMonth = 0;//Accumulate all the 13th monthpay
			while($empArr = mysql_fetch_assoc($empQuery))
			{
				$empid = $empArr['empid'];

				$oneThreeMonthBool = false;//for print button
				$thirteenthBool = true;// boolean for giving the "from to" date in the 13th month
				$remainderBool = false; // boolean for displaying the remainder once

				$printBool = false;//printable disabled

				//Check if employee have already received past 13th month pay
				$thirteenthChecker = "SELECT * FROM thirteenth_pay WHERE empid = '$empid' ORDER BY STR_TO_DATE(from_date, '%M %e, %Y ') DESC LIMIT 1";
				$thirteenthCheckQuery = mysql_query($thirteenthChecker) or die (mysql_error());
				$pastThirteenthDate = "";
				$thirteenthRemainder = 0;

				if(mysql_num_rows($thirteenthCheckQuery) == 1)
				{

					$thirteenthCheckArr = mysql_fetch_assoc($thirteenthCheckQuery);
					$pastThirteenthDate = "AND STR_TO_DATE(date, '%M %e, %Y ') >= STR_TO_DATE('".$thirteenthCheckArr['to_date']."', '%M %e, %Y ')";
					$thirteenthRemainder = $thirteenthCheckArr['amount'] - $thirteenthCheckArr['received'];
					$thirteenthRemainder = abs($thirteenthRemainder);// makes the value absolute

					// display in the duration of 13th month pay of employee
					$pastToDateThirteenthPay = $thirteenthCheckArr['to_date'];
					$thirteenthBool = false;
					$remainderBool = true;// displays the remainder
				}

					

				$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";

				
				$payrollQuery = mysql_query($payrollDate) or die(mysql_error());
				$dateLength = mysql_num_rows($payrollQuery);

				//adds the 13th month pay remainder if there is
				$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

				

				//counter to get the last date
				$loopMax = mysql_num_rows($payrollQuery);
				$loopCounter = 0;
				
				$finalDate = $date = strftime("%B %d, %Y");
				//Evaluates the attendance and compute the 13th monthpay
				while($payDateArr = mysql_fetch_assoc($payrollQuery))
				{
					//Gets the last date
					$loopCounter++;
					if($loopCounter == $loopMax)
						$finalDate = $payDateArr['date'];

					if($thirteenthBool)
					{
						$pastToDateThirteenthPay = date('F d, Y', strtotime('-6 day', strtotime($payDateArr['date'])));
						$thirteenthBool = false;
					}
					$endDate = $payDateArr['date'];
					$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));
					

					$attendance = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
					$attQuery = mysql_query($attendance);

					$daysAttended = 0;//counter for days attended
					//Computes the 13th month
					while($attArr = mysql_fetch_assoc($attQuery))
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

					$printBool = true;//enable printable

					$overallPayment += $thirteenthMonth;
				}
				Print "
					<tr>
						<td>
							".$empid."
						</td>
						<td align='left'>
							".$empArr['lastname'].", ".$empArr['firstname']."
						</td>
						<td>
							".$empArr['position']."
						</td>
						<td>
							".$pastToDateThirteenthPay." - ".$finalDate." 
						</td>
						<td>
							".numberExactFormat($overallPayment, 2, '.', true)."
						</td>
					</tr>";
				$overall13thMonth += $overallPayment;
			}
			Print "
					<tr>
						<td colspan='3'>
						</td>
						<td>
							Grand Total
						</td>
						<td>
							".numberExactFormat($overall13thMonth, 2, '.', true)."
						</td>
					</tr>";
		}
		?>
		</table>
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

		function requirementChange(req) {
			window.location.assign("reports_overall_13thmonthpay.php?req="+req+"&site=<?php Print $site?>&position=<?php Print $position?>");
		}

		function positionChange(position) {
			window.location.assign("reports_overall_13thmonthpay.php?req=<?php Print $require?>&site=<?php Print $site?>&position="+position);
		}

		function weekDates(date) {
			document.getElementsByName('chosenDate')[0].value = date;
			document.getElementById('dynamicForm').submit();
		}

		function print13thMonth() {
			window.location.assign("print_overall_13thmonth.php?req=<?php Print $require?>&site=<?php Print $site?>&position=<?php Print $position?>");
		}

	</script>
</body>
</html>