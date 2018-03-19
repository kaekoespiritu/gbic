<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$dateToday = strftime("%B %d, %Y");

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
						<button class='btn btn-success pull-right' data-toggle="modal" data-target="#give13thmonthpay">
							Give 13th Month Pay
						</button>
						<button class='btn btn-danger pull-right' data-toggle="modal" data-target="#13thmonthhistory">
							13th Month Pay History
						</button>
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
				<button class="btn btn-primary" id="printButton" onclick="Print13thMonth()">
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
						$pastThirteenthDate = "AND STR_TO_DATE(date, '%M %e, %Y ') <= STR_TO_DATE(".$thirteenthCheckArr['to_date'].", '%M %e, %Y ')";
						$thirteenthRemainder = $thirteenthCheckArr['amount'] - $thirteenthCheckArr['received'];
						$thirteenthRemainder = abs($thirteenthRemainder);// makes the value absolute

						// display in the duration of 13th month pay of employee
						$pastToDateThirteenthPay = $thirteenthCheckArr['to_date'];
						$thirteenthBool = false;
						$remainderBool = true;// displays the remainder

						Print "<script>console.log('pastToDateThirteenthPay: ".$pastToDateThirteenthPay."')</script>";
					}

					if($period == "week")
					{
						

						$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
						$payrollQuery = mysql_query($payrollDate);
						$dateLength = mysql_num_rows($payrollQuery);

						//adds the 13th month pay remainder if there is
						$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

						if($remainderBool)
						{
							if($thirteenthRemainder != 0)
							{
								$printBool = true;//enable printable
								Print "
								<tr>
									<td>
										13th Month Pay remaining balance
									</td>
									<td>
										".numberExactFormat($thirteenthRemainder, 2, '.', true)."
									</td>
								</tr>";

								$remainderBool = false;

							}
							
						}

						//Evaluates the attendance and compute the 13th monthpay
						while($payDateArr = mysql_fetch_assoc($payrollQuery))
						{
							if($thirteenthBool)
							{

								$pastToDateThirteenthPay = date('F j, Y', strtotime('-6 day', strtotime($payDateArr['date'])));
								$thirteenthBool = false;
								Print "<script>console.log('pastToDateThirteenthPay: ".$pastToDateThirteenthPay."')</script>";
							}
							$endDate = $payDateArr['date'];
							$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));
							//Print "<script>console.log('".$endDate." - ".$startDate."')</script>";

							$attendance = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
							$attQuery = mysql_query($attendance);

							$daysAttended = 0;//counter for days attended
							//Computes the 13th month
							while($attArr = mysql_fetch_assoc($attQuery))
							{
								$date = $attArr['date'];

								//Print "<script>console.log('".$date."')</script>";
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
							//Print "<script>console.log('".$daysAttended."')</script>";
							$thirteenthMonth = ($daysAttended * $empArr['rate']) / 12; 

							$printBool = true;//enable printable
							Print "
									<tr>
										<td>
											".$startDate." - ".$endDate."
										</td>
										<td>
											".numberExactFormat($thirteenthMonth, 2, '.', true)."
										</td>
									</tr>";

							$overallPayment += $thirteenthMonth;
						}
					}
					else if($period == "month")
					{
						$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
						$attQuery = mysql_query($attendance);

						$daysAttended = 0;//counter for days attended
						$noRepeat = null;
						//adds the 13th month pay remainder if there is
						$overallPayment = ($thirteenthRemainder != 0 ? $overallPayment = $overallPayment : 0);

						if($remainderBool)
						{
							if($thirteenthRemainder != 0)
							{
								$printBool = true;//enable printable
								Print "
								<tr>
									<td>
										13th Month Pay remaining balance
									</td>
									<td>
										".numberExactFormat($thirteenthRemainder, 2, '.', true)."
									</td>
								</tr>";

								$remainderBool = false;

							}
							
						}
						//Computes 13th monthpay per month
						while($attDate = mysql_fetch_assoc($attQuery))
						{
							if($thirteenthBool)
							{

								$pastToDateThirteenthPay = $attDate['date'];
								$thirteenthBool = false;
								Print "<script>console.log('pastToDateThirteenthPay: ".$pastToDateThirteenthPay."')</script>";
							}
							$dateExploded = explode(" ", $attDate['date']);
							$month = $dateExploded[0];
							$year = $dateExploded[2];

							if ($noRepeat != $month.$year  || $noRepeat == null)
							{
								$attMonth = "SELECT * FROM attendance WHERE empid = '$empid' AND date LIKE '$month%' AND date LIKE '%$year' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
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
								$printBool = true;//enable printable
								Print "
										<tr>
											<td>
												".$month." ".$year."
											</td>
											<td>
												".numberExactFormat($thirteenthMonth, 2, '.', true)."
											</td>
										</tr>";
								$overallPayment += $thirteenthMonth;
							}
							
							$noRepeat = $month.$year;
						}


					}
					else if($period == "year")
					{
						$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
						$attQuery = mysql_query($attendance);

						$daysAttended = 0;//counter for days attended
						$noRepeat = null;
						//adds the 13th month pay remainder if there is
						$overallPayment = ($thirteenthRemainder != 0 ? $overallPayment = $overallPayment : 0);

						if($remainderBool)
						{
							if($thirteenthRemainder != 0)
							{
								$printBool = true;//enable printable
								Print "
								<tr>
									<td>
										13th Month Pay remaining balance
									</td>
									<td>
										".numberExactFormat($thirteenthRemainder, 2, '.', true)."
									</td>
								</tr>";

								$remainderBool = false;

							}
							
						}
						//Computes 13th monthpay per month
						while($attDate = mysql_fetch_assoc($attQuery))
						{
							if($thirteenthBool)
							{

								$pastToDateThirteenthPay = $attDate['date'];
								$thirteenthBool = false;
								Print "<script>console.log('pastToDateThirteenthPay: ".$pastToDateThirteenthPay."')</script>";
							}
							$dateExploded = explode(" ", $attDate['date']);
							$year = $dateExploded[2];

							if ($noRepeat != $year || $noRepeat == null)
							{
								$attYear = "SELECT * FROM attendance WHERE empid = '$empid' AND date LIKE '%$year' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
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
								$printBool = true;//enable printable
								Print "
										<tr>
											<td>
												".$yearBefore." - ".$year."
											</td>
											<td>
												".numberExactFormat($thirteenthMonth, 2, '.', true)."
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
						<?php Print numberExactFormat($overallPayment, 2, '.', true)?>
					</td>
				</tr>
				</table>
			</div>

		</div>

	</div>

	<!-- Modals -->
	<div class="modal fade" id="give13thmonthpay">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"><?php Print $empArr['lastname'].", ".$empArr['firstname']?>'s 13th Month Pay</h4>
	      </div>
	      <div class="modal-body">
	        <table class='table table-bordered'>
	        	<tr>
	        		<td>
	        			From - To Date
	        		</td>
	        		<td>
	        			Amount
	        		</td>
	        	</tr>
	        	<tr>
	        		<td>
	        			<?php Print $pastToDateThirteenthPay." - ".$dateToday?>
	        		</td>
	        		<td>
	        			<?php Print numberExactFormat($overallPayment, 2, '.', true)?>
	        		</td>
	        	</tr>
	        </table>

	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#enter13thmonthpay">Give 13th Month Pay</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="enter13thmonthpay">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body">
	      	<div class="row">
		      	<div class="col-md-6">
		      		<h4>13th Month Pay Amount:</h4>
		      			<b><?php Print numberExactFormat($overallPayment, 2, '.', true)?></b>
		      	</div>
		      	<div class="col-md-6">
		      		<h4>Amount to Give:</h4> <input type="number" id="amountToGive"><br>
		        	<input type="checkbox" onclick="copyAmount(<?php Print $overallPayment?>)"> Copy overall amount
		      	</div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary" onclick="give13thPay()">Give 13th Monthpay</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="13thmonthhistory">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"><?php Print $empArr['lastname'].", ".$empArr['firstname']?>'s 13th Month Pay History</h4>
	      </div>
	      <div class="modal-body">
	        <table class='table table-bordered'>
	        	<tr>
	        		<td>
	        			From Date
	        		</td>
	        		<td>
	        			To Date
	        		</td>
	        		<td>
	        			13th Month Pay Amount
	        		</td>
	        		<td>
	        			Amount given
	        		</td>
	        	</tr>
	        	<?php
	        		$thirteenthHist = "SELECT * FROM thirteenth_pay WHERE empid = '$empid' ORDER BY STR_TO_DATE(from_date, '%M %e, %Y') ASC";
	        		$thirteenthHistQuery = mysql_query($thirteenthHist) or die(mysql_error()) ;

	        		$histBool = false;//historical print disabled
	        		if(mysql_num_rows($thirteenthHistQuery) != 0)
	        		{
	        			$histBool = true;//historical print enabled
	        			while($histRow = mysql_fetch_assoc($thirteenthHistQuery))
	        			{
	        				Print "
		        				<tr>
					        		<td>
					        			".$histRow['from_date']."
					        		</td>
					        		<td>
					        			".$histRow['to_date']."
					        		</td>
					        		<td>
					        			".$histRow['amount']."
					        		</td>
					        		<td>
					        			".$histRow['received']."
					        		</td>
					        	</tr>
		        				";
	        			}
	        			
	        		}
	        		else
	        		{
	        			Print "
	        				<tr>
				        		<td colspan='4'>
				        			No 13th Month pay history as of the moment.
				        		</td>
				        	</tr>";
	        		}

	        	?>
	        </table>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" id="historyButton" onclick="printHistory()">Print</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Historical printable -->
	<input type="hidden" id="HistoricalPrint" value="<?php Print $histBool?>">
	<input type="hidden" id="Print" value="<?php Print $printBool?>">

	<input type="hidden" id="overallPayment" value="<?php Print $overallPayment?>">
	<input type="hidden" id="fromDate" value="<?php Print $pastToDateThirteenthPay?>">
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		$( document ).ready(function() {
		   	if($('#HistoricalPrint').val() == 1)
		   		$('#printButton').addClass('disabletotally');
		   	else
		   		$('#printButton').removeClass('disabletotally');

		   	if($('#Print').val() == 1)
		   		$('#historyButton').addClass('disabletotally');
		   	else
		   		$('#historyButton').removeClass('disabletotally');


		});

		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function copyAmount(amount) {
			amount = String(amount);
			var splitThirteenth = amount.split('.');
			var thirteenth = splitThirteenth[0]+"."+splitThirteenth[1].substring(0,2);
			document.getElementById("amountToGive").value = thirteenth;
		}

		function give13thPay() {
			var amount = document.getElementById("amountToGive").value;
			var thirteenth = document.getElementById("overallPayment").value;
			var fromDate = document.getElementById("fromDate").value;
			var splitThirteenth = thirteenth.split('.');
			thirteenth = splitThirteenth[0]+"."+splitThirteenth[1].substring(0,2);
			var a = confirm("Are you sure you want to give this employee's 13th month pay?")
			if(a) 
			{
				if(thirteenth > amount || amount == 0 || thirteenth == 0)
					alert("Please input proper amount.");
				else
				{
					console.log(amount+" = "+thirteenth+" = "+fromDate);
					window.location.assign("logic_reports_individual_13thmonth.php?empid=<?php Print $empid?>&amount="+amount+"&pay="+thirteenth+"&fromDate="+fromDate);
				}
				//	window.location.assign("");
			}
		}
		function periodChange(period) {
			window.location.assign('reports_individual_13thmonthpay.php?empid=<?php Print $empid?>&per='+period);
		}

		function Print13thMonth() {
			window.location.assign('print_individual_13thmonth.php?empid=<?php Print $empid?>&per=<?php Print $period?>');
		}

		function printHistory() {
			window.location.assign('print_individual_historical_13thmonth.php?empid=<?php Print $empid?>');
		}
	</script>
</body>
</html>