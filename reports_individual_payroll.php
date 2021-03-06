<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

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
		header("location:reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null");
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
						<li><a href='reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Payroll Report for <?php Print $empArr['firstname']." ".$empArr['lastname']." | ".$empArr['position']." at ". $empArr['site']?></li>
					</ol>
				</div>
			</div>

			<div class="form-inline">
				<h4>Select period</h4>
				<select class="form-control" id="dd_payrollDate" onchange="payrollDateChange(this.value)">
					<option hidden>Select date</option>
					<?php
						$payrollDates = "SELECT date FROM payroll WHERE empid = '$empid'";
						$payrollDateQuery = mysql_query($payrollDates);

						$earlyCuttoff = '';// for printable
						if(mysql_num_rows($payrollDateQuery))//check if there's payroll
						{
							$cutoffBool = false;// Boolean for the suceeding week after the initial cutoff
							$cutoffClearPlaceholderBool = false;
							$cutoffInitialDate = '';// Placeholder for the start of the suceeding date after the cutoff
							$selectionArr = array();
							while($payrollDateArr = mysql_fetch_assoc($payrollDateQuery))
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

								if(isset($_POST['dateChange']))
								{
									if($_POST['dateChange'] == $payDay)
									{
										array_push($selectionArr, $payDay."-".$payrollStartDate."-".$payrollEndDate."-selected");
									}
									else
									{
										array_push($selectionArr, $payDay."-".$payrollStartDate."-".$payrollEndDate);
									}
								}
								else
								{
									array_push($selectionArr, $payDay."-".$payrollStartDate."-".$payrollEndDate);
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
							$revSelectionArr = array_reverse($selectionArr);// Reverse array
							foreach($revSelectionArr as $selection)
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
						else
						{
							Print "<option>No payroll date available</option>";
						}
					?>
				</select>
			</div>

			<div class="pull-down">
				
				<button class='btn btn-primary' id='printButton' onclick="printPayroll()">Print Payroll</button>
				
				<button class="btn btn-primary" id="printPayslip" onclick="printPayslip()">
					Print Payslip
				</button>
				<?php
				if(isset($_POST['dateChange']))//if admin chooses a date on the period dropdown
				{


					Print "
						<table class='table table-bordered pull-down reports-table'>
						<tr>
							<td colspan='6' rowspan='2'>";
						
							if($empArr['complete_doc'] == 1)
								Print "Complete Requirements";
							else
								Print "Without Requirements";
						
					Print "	</td>
								<td colspan='20' rowspan='2' class='vertical-align'>
									PAYROLL
								</td>
							</tr>";
							
					$chosenDate = $_POST['dateChange'];
					$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$chosenDate' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
					$payrollQuery = mysql_query($payroll);
					$printBool = false;//disable print button if there's no data retrieved
					if(mysql_num_rows($payrollQuery))
					{
						$printBool = true;
						$payrollArr = mysql_fetch_assoc($payrollQuery);
						
						$endDate = date('F d, Y', strtotime('-1 day', strtotime($payrollArr['date'])));
						$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

						// Check for early cutoff 
						$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$chosenDate' LIMIT 1";
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
							$suceedingCutoffPayroll = date('F d, Y', strtotime('-14 day', strtotime($chosenDate)));

							$suceedingCutoffCheck = "SELECT * FROM early_payroll WHERE start = '$suceedingCutoffPayroll' LIMIT 1";
							$suceedingCutoffQuery = mysql_query($suceedingCutoffCheck);
							if(mysql_num_rows($suceedingCutoffQuery) > 0)
							{
								$cutoffArr = mysql_fetch_assoc($suceedingCutoffQuery);
								$startDate = date('F d, Y', strtotime('+1 day', strtotime($cutoffArr['end'])));;// Get the end payroll of the cutoff to get the start of the current payroll
								$earlyCuttoff = $startDate;//Pass the start of payroll to the printables
							}
						}

						//Gets the actual holiday num
						if($payrollArr['reg_holiday_num'] > 1)
						{
							// $holidayRegChecker = "SELECT * FROM holiday AS h INNER JOIN attendance AS a ON h.date = a.date WHERE a.empid = '$empid' AND a.attendance = '2' AND h.type = 'regular'";
							// $holidayRegQuery = mysql_query($holidayRegChecker);
							// $regHolidayNum = mysql_num_rows($holidayRegQuery);
							$regHolidayNum = $payrollArr['reg_holiday_num'];
						}
						else if($payrollArr['reg_holiday_num'] == 1)
						{
							$regHolidayNum = 1;
						}
						else
						{
							$regHolidayNum = 0;
						}

						Print "
							<input type='hidden' id='payrollDate' value='".$payrollArr['date']."'>
							<tr>
							<tr>
								<td colspan='26' bgcolor='#AAB7B8'>
									<strong>
										Period: ".$startDate." - ".$endDate."
									</strong>
								</td>
							</tr>
							</tr>
						<tr>
							<td>
								Rate
							</td>
							<td>
								# of days
							</td>
							<td>
								O.T.
							</td>
							<td>
								# of hours
							</td>
							<td>
								Allow.
							</td>
							<td>
								COLA
							</td>
							<td>
								Sun
							</td>
							<td>
								D
							</td>
							<td>
								hrs
							</td>
							<td>
								N.D.
							</td>
							<td>
								#
							</td>
							<td>
								Reg. Hol
							</td>
							<td>
								#
							</td>
							<td>
								Spe. Hol
							</td>
							<td>
								#
							</td>
							<td>
								X All.
							</td>
							<td>
								SSS
							</td>
							<td>
								Philhealth
							</td>
							<td>
								PagIBIG
							</td>
							<td>
								Old vale
							</td>
							<td>
								vale
							</td>
							<td>
								SSS loan
							</td>
							<td>
								P-ibig loan
							</td>
							<td>
								Ins.
							</td>
							<td>
								tools
							</td>
							<td colspan='2'>
								Total Salary
							</td>
							
						</tr>
						<tr>
							<td><!-- Rate -->
								".$payrollArr['rate']."
							</td>
							<td><!-- # of days -->
								".$payrollArr['num_days']."
							</td>
							<td><!-- OT -->
								".$payrollArr['overtime']."
							</td>
							<td><!-- # of hours -->
								".$payrollArr['ot_num']."
							</td>
							<td><!-- Allow -->
								".$payrollArr['allow']."
							</td>
							<td><!-- COLA -->
								".($payrollArr['cola']/$payrollArr['allow_days'])."
							</td>
							<td><!-- Sun -->
								".$payrollArr['sunday_rate']."
							</td>
							<td><!-- D -->
								".$payrollArr['sunday_att']."
							</td>
							<td><!-- hrs -->
								".$payrollArr['sunday_hrs']."
							</td>
							<td><!-- ND -->
								".$payrollArr['nightdiff_rate']."
							</td>
							<td><!-- # -->
								".$payrollArr['nightdiff_num']."
							</td>
							<td><!-- Reg.hol -->
								".$payrollArr['reg_holiday']."
							</td>
							<td><!-- # -->
								".$regHolidayNum."
							</td>
							<td><!-- Spe. hol -->
								".$payrollArr['spe_holiday']."
							</td>
							<td><!-- # -->
								".$payrollArr['spe_holiday_num']."
							</td>
							<td><!-- X.All -->
								".($payrollArr['x_allowance'] + $payrollArr['x_allow_weekly'] + ($payrollArr['x_allow_daily'] * $payrollArr['allow_days']))."
							</td>
							<td><!-- SSS -->
								".$payrollArr['sss']."
							</td>
							<td><!-- Philhealth -->
								".$payrollArr['philhealth']."
							</td>
							<td><!-- Pagibig -->
								".$payrollArr['pagibig']."
							</td>
							<td><!-- Old vale -->
								".$payrollArr['old_vale']."
							</td>
							<td><!-- vale -->
								";
								$payrollDay = date('F d, Y', strtotime('+1 day', strtotime($endDate)));
								
								$payrollOutstandingSql = "SELECT total_salary FROM payroll WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') < STR_TO_DATE('$payrollDay', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";

								$payrollOutstandingQuery = mysql_query($payrollOutstandingSql);
								$payrollOutstanding = 0.00;
								
								if(mysql_num_rows($payrollOutstandingQuery))
								{
									$outStandingCheck = mysql_fetch_assoc($payrollOutstandingQuery);
									if($outStandingCheck['total_salary'] < 0.00){
										$payrollOutstanding = abs($outStandingCheck['total_salary']);
										$payrollOutstanding += $payrollArr['new_vale'];
									}
									else {
										$payrollOutstanding = $payrollArr['new_vale'];
									}
								}
								else
								{
									$payrollOutstanding = $payrollArr['new_vale'];
								}

							Print 
								$payrollOutstanding."
							<td><!-- sss loan -->
								".$payrollArr['loan_sss']."
							</td>
							<td><!-- pagibig loan -->
								".$payrollArr['loan_pagibig']."
							</td>
							<td><!-- insurance -->
								".$payrollArr['insurance']."
							</td>
							<td><!-- tools -->
								".$payrollArr['tools_paid']."
							</td>
							<td><!-- Total Salary -->
								";

								if($payrollArr['total_salary'] > 0)
								{
									Print numberExactFormat($payrollArr['total_salary'],2,".", true);
								}
								else
								{
									Print "0.00";
								}
								Print "
							</td>
						</tr>
						<br>
						";
						
					}
					else
					{
					Print "	<tr>
								<tr>
									<td colspan='24' bgcolor='#E74C3C'>
										No payroll record
									</td>
								</tr>
							</tr>";
					}
				}
				?>
				
				</table>
			</div>
		</div>

	</div>

	<input type="hidden" id="printBool" value="<?php Print $printBool?>">
	<form id="dateChangeForm" method="POST" action="reports_individual_payroll.php?empid=<?php Print $empid?>">
		<input type="hidden" id="dateChange" name="dateChange">
	</form>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		$( document ).ready(function() {
    	
    		var bool = $('#printBool').val();
    		if(bool != 1) {
    			$('#printButton').attr('disabled','disabled');
    			$('#printPayslip').attr('disabled','disabled');
    		}
    		

		});

		function printPayroll() {
			var payrollDate = document.getElementById('payrollDate').value;
			window.location.assign("print_individual_payroll.php?empid=<?php Print $empid?>&date="+payrollDate+"&cutoff=<?php Print $earlyCuttoff?>");
		}

		function printPayslip() {
			var payrollDate = document.getElementById('dd_payrollDate').value;
			window.location.assign("print_individual_payslip.php?empid=<?php Print $empid?>&date="+payrollDate+"&cutoff=<?php Print $earlyCuttoff?>");
		}

		function payrollDateChange(date) {
			document.getElementById('dateChange').value = date;
			document.getElementById('dateChangeForm').submit();
		}

		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
    		
	</script>
</body>
</html>