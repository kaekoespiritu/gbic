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
						<!-- <a class="btn btn-primary pull-right" href="reports_overall_13thmonthpay_deduction.php?site=<?php //Print $site?>&position=<?php //Print $position?>">
 -->						
 						<a class="btn btn-primary pull-right" onclick="submitForm()">
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
			<form method='post' action='reports_overall_13thmonthpay_deduction.php' id='formMonthPay'>
				<input type="hidden" name="site" value="<?php Print $site?>">
				<input type="hidden" name="position" value="<?php Print $position?>">
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
			echo "<script>console.log($employee)</script>";
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

					$startThirteenthDate = '';
					$noRemainderBool = false;
					if(mysql_num_rows($thirteenthCheckQuery) == 1)
					{

						$thirteenthCheckArr = mysql_fetch_assoc($thirteenthCheckQuery);
						$pastThirteenthDate = "AND STR_TO_DATE(date, '%M %e, %Y ') >= STR_TO_DATE('".$thirteenthCheckArr['to_date']."', '%M %e, %Y ')";
						$thirteenthRemainder = $thirteenthCheckArr['amount'] - $thirteenthCheckArr['received'];
						$thirteenthRemainder = abs($thirteenthRemainder);// makes the value absolute

						// display in the duration of 13th month pay of employee
						$pastToDateThirteenthPay = $thirteenthCheckArr['to_date'];// Beggining date
						$thirteenthBool = false;
						$remainderBool = true;// displays the remainder

					}
					else
					{
						$pastThirteenthDate = "AND STR_TO_DATE(date, '%M %e, %Y ') >= STR_TO_DATE('".$empArr['datehired']."', '%M %e, %Y ')";
						$noRemainderBool = true;
					}
					$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
					$attQuery = mysql_query($attendance);
					
					$daysAttended = 0;//counter for days attended
					$noRepeat = null;
					//adds the 13th month pay remainder if there is
					$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

					if($remainderBool)
					{
						if($thirteenthRemainder != 0)
						{
							$printBool = true;//enable printable
							$addedPay = $thirteenthRemainder;// Previous payable

							$remainderBool = false;
						}
					}

					$arrayChecker = array(); // Set array to check if there is duplicate dates
					$finalDate = strftime("%B %d, %Y");;
					$finalCounter = 0;// counter to get the final date
					$finalLoop = mysql_num_rows($attQuery);// max loop
					//Computes 13th monthpay per month
					while($attDate = mysql_fetch_assoc($attQuery))
					{
						$finalCounter++;// Increment each loop
						if($thirteenthBool)
						{
							$pastToDateThirteenthPay = $attDate['date'];
							$thirteenthBool = false;
						}

						$dateExploded = explode(" ", $attDate['date']);
						$month = $dateExploded[0];
						$year = $dateExploded[2];


						if($noRepeat != $month.$year  || $noRepeat == null)
						{
							$attMonth = "SELECT * FROM attendance WHERE empid = '$empid' AND (date LIKE '$month%' AND date LIKE '%$year') $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
							$attMonthQuery = mysql_query($attMonth);

							$thirteenthMonth = 0;
							$daysAttended = 0;

							$checkBool = true;
							$checkCounter = 0;
							$checkCount = mysql_num_rows($attMonthQuery);
							//Computes 13th month per day of the month
							while($attArr = mysql_fetch_assoc($attMonthQuery))
							{ 
								// Checks if date is already in the array. if it is then skip the computation for this date
								if(!in_array($attArr['date'], $arrayChecker))
								{
									array_push($arrayChecker, $attArr['date']);// Push date inside the array 
									$date = $attArr['date'];
									$day = date('l', strtotime($date));// check what day of the week

									$workHrs = $attArr['workhours'];

									$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
									$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

									if(mysql_num_rows($holidayCheckQuery) == 0 && $day != "Sunday")
									{
										if($attArr['attendance'] == '2')//check if student is present
										{
											if($attArr['workhours'] >= 8)//check if employee attended 8hours
											{
												$daysAttended++;
											}
											else
											{
												$daysAttended += ($attArr['workhours']/8);
											}
										}
									}
								}	
							}
							$thirteenthMonth = ($daysAttended * $empArr['rate']) / 12; 
							$printBool = true;//enable printable
							$overallPayment += $thirteenthMonth;
						}
						
						$noRepeat = $month.$year;
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
								<input type='hidden' name='empid[]' value='".$empid."'>
								<input type='hidden' name='name[]' value='".$empArr['lastname'].", ".$empArr['firstname']."'>
								<input type='hidden' name='position[]' value='".$empArr['position']."'>
								<input type='hidden' name='startDate[]' value='".$pastToDateThirteenthPay."'>
								<input type='hidden' name='endDate[]' value='".$finalDate."'>
								<input type='hidden' name='payment[]' value='".$overallPayment."'>
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
		</form>
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

		function submitForm() {
			document.getElementById('formMonthPay').submit();
		}
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