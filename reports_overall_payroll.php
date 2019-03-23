<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$location = $_GET['site'];
	$req = $_GET['req'];//requirements
	$site = "SELECT * FROM site WHERE location = '$location'";
	$siteQuery = mysql_query($site);

	if(mysql_num_rows($siteQuery) == 0)
	{
		header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
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
<body style="font-family: Quicksand;" onload="enableDropdown()">

	<div class="container-fluid">

		<?php
		require_once("directives/nav.php");
		?>

		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_overall_earnings.php?type=Earnings&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Overall Payroll Report for <?php Print $location?></li>
					</ol>
				</div>
			</div>

		<div class="row">
			<div class="col-md-3 col-lg-3 col-md-offset-3 col-lg-offset-3">
				<h4>Step 1: Select Requirements</h4>
				<select onchange="payrollRequirements(this.value)" class="form-control" id="step1">
					<?php 
					if($req == 'null')
						Print "<option selected>-- All / With / Without --</option>";
					if($req == 'all')
						Print "<option value='all' selected>All</option>";
					else
						Print "<option value='all'>All</option>";
					if($req == 'withReq')
						Print "<option value='withReq' selected>With Requirements</option>";
					else
						Print "<option value='withReq'>With Requirements</option>";
					if($req == 'withOReq')
						Print "<option value='withOReq' selected>W/o Requirements</option>";
					else
						Print "<option value='withOReq'>W/o Requirements</option>";

					?>
				</select>
			</div>
			<div class="col-md-3 col-lg-3">
					<h4>Step 2: Select Payroll Dates</h4>
					<select onchange="payrollDates(this.value)" class="form-control" id="step2" disabled>
						<option hidden>Select date</option>
						<?php
						// $payrollDays = "SELECT DISTINCT date FROM Payroll WHERE site = '' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";//gets non repeatable dates
						$payrollDays = "SELECT DISTINCT date FROM payroll p INNER JOIN employee e ON p.empid = e.empid WHERE e.site = '$location' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
						$payrollDaysQuery = mysql_query($payrollDays);

						$earlyCuttoff = '';
						if(mysql_num_rows($payrollDaysQuery))
						{
							$cutoffBool = false;// Boolean for the suceeding week after the initial cutoff
							$cutoffClearPlaceholderBool = false;
							$cutoffInitialDate = '';// Placeholder for the start of the suceeding date after the cutoff
							$selectionArr = array();
							while($PdaysOptions = mysql_fetch_assoc($payrollDaysQuery))
							{
								$payDay = $PdaysOptions['date'];
								$endDate = date('F d, Y', strtotime('-1 day', strtotime($PdaysOptions['date'])));
								$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

								// Check for early cutoff 
								$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$payDay' LIMIT 1";
								$cutoffQuery = mysql_query($cutoffCheck);
								if(mysql_num_rows($cutoffQuery) > 0)
								{
									$cutoffArr = mysql_fetch_assoc($cutoffQuery);
									$startDate = $cutoffArr['start'];
									$endDate = $cutoffArr['end'];

									$cutoffInitialDate = date('F d, Y', strtotime('+1 day', strtotime($cutoffArr['end'])));
								}

								if($cutoffBool == true)
								{
									$startDate = $cutoffInitialDate;
									$cutoffClearPlaceholderBool = true;// This is to reset the placeholder
									$cutoffBool = false;// Reset the cutoffBoolean
								}

								if(isset($_POST['payrollDate']))
								{
									if($_POST['payrollDate'] == $payDay)
									{
										array_push($selectionArr, $payDay."-".$startDate."-".$endDate."-selected");
									}
									else
									{
										array_push($selectionArr, $payDay."-".$startDate."-".$endDate);
									}
								}
								else
								{
									array_push($selectionArr, $payDay."-".$startDate."-".$endDate);
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
							Print "<option>No payroll date</option>";
						}
						?>
					</select>
			</div>
    	</div>
    </div>


			<?php 

				if(isset($_POST['payrollDate'])) {
					$date = $_POST['payrollDate'];
					$endDate = date('F d, Y', strtotime('-1 day', strtotime($date)));
					$startDate = date('F d, Y', strtotime('-7 day', strtotime($date)));
					// Check for early cutoff 
					$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$date' LIMIT 1";
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
						$suceedingCutoffPayroll = date('F d, Y', strtotime('-14 day', strtotime($date)));

						$suceedingCutoffCheck = "SELECT * FROM early_payroll WHERE start = '$suceedingCutoffPayroll' LIMIT 1";
						$suceedingCutoffQuery = mysql_query($suceedingCutoffCheck);
						if(mysql_num_rows($suceedingCutoffQuery) > 0)
						{
							$cutoffArr = mysql_fetch_assoc($suceedingCutoffQuery);
							$startDate = date('F d, Y', strtotime('+1 day', strtotime($cutoffArr['end'])));;// Get the end payroll of the cutoff to get the start of the current payroll
							$earlyCuttoff = $startDate;//Pass the start of payroll to the printables
						}
					}

					

					Print '<div class="row pull-down">
					<div class="col-md-1 col-lg-12 pull-down">
								<a class="btn btn-default" id="printPayroll" href="print_overall_payroll.php?site='.$location.'&date='.$_POST['payrollDate'].'&req='.$req.'&cutoff='.$earlyCuttoff.'">
									Print Payroll
								</a>
								<a class="btn btn-default" id="printPayslip" onclick="printPayslips()">
									Print Payslips
								</a>
								</div>
							</div>';
					if($req == 'all')
						$reqMessage = "All ".$location." employees";
					else if($req == 'withReq')
						$reqMessage = $location." W/ Requirements";
					else if($req == 'withOReq')
						$reqMessage = $location." W/o Requirements";
					Print '<div class="row">
							<div class="col-md-1 col-lg-12 overflow">
							<table class="table table-bordered pull-down">
								<tr>
									<td colspan="6">
										'.$reqMessage .'
									</td>
									<td colspan="23" rowspan="2" class="vertical-align">
										PAYROLL
									</td>
								</tr>
								<tr>
									<td colspan="6">
										Date covered: '.$startDate.' - '.$endDate.'
									</td>
								</tr>
								<tr>
									<td>
										#
									</td>
									<td>
										Name
									</td>
									<td>
										Position
									</td>
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
									<td>
										Total Salary
									</td>
								</tr>';
							
							

							if($req == 'all')
							{
								$employee = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' ORDER BY lastname ASC, position ASC";
							}
							else if($req == 'withReq')
							{
								$employee = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' AND complete_doc = '1'ORDER BY lastname ASC, position ASC";
							}
							else if($req == 'withOReq')
							{
								$employee = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' AND complete_doc = '0'ORDER BY lastname ASC, position ASC";
							}

						$printBool = 1;
						$dataBool = true;//Boolean if there is data for the payroll
						$employeeQuery = mysql_query($employee);
						if(mysql_num_rows($employeeQuery) >= 1)
						{
							$color = "#ECF0F1";//for alternating color
							$rowNum = 1;
							
							while($employeeArr = mysql_fetch_assoc($employeeQuery))
							{
								$emplid = $employeeArr['empid'];
								$payroll = "SELECT * FROM payroll WHERE date = '$date' AND empid='$emplid'";
								$payrollQuery = mysql_query($payroll);
								if(mysql_num_rows($payrollQuery) >= 1)
								{
									$dataBool = false;
									$payrollArr = mysql_fetch_assoc($payrollQuery);

									//Gets the actual holiday num
									if($payrollArr['reg_holiday_num'] > 1)
									{
										// $holidayRegChecker = "SELECT * FROM holiday AS h INNER JOIN attendance AS a ON h.date = a.date WHERE a.empid = '$emplid' AND a.attendance = '2' AND h.type = 'regular'";
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

									$color = ($rowNum % 2 == 0 ? "#ECF0F1" : "#FDFEFE");//alternating color

									Print '	<tr bgcolor="'.$color.'">
												<td><!-- # -->
													'.$rowNum.'
												</td>
												<td align="left"><!-- Name -->
													'.$employeeArr['lastname'].', '.$employeeArr['firstname'].'
												</td>
												<td><!-- Position -->
													'.$employeeArr['position'].'
												</td>
												<td><!-- Rate -->
													'.$payrollArr['rate'].'
												</td>
												<td><!-- # of days -->
													'.$payrollArr['num_days'].'
												</td>
												<td><!-- OT -->
													'.$payrollArr['overtime'].'
												</td>
												<td><!-- # of hours -->
													'.$payrollArr['ot_num'].'
												</td>
												<td><!-- Allow -->
													'.$payrollArr['allow'].'
												</td>
												<td><!-- COLA -->
													'.($payrollArr['cola']/$payrollArr['allow_days']).'
												</td>
												<td><!-- Sun -->
													'.$payrollArr['sunday_rate'].'
												</td>
												<td><!-- D -->
													'.$payrollArr['sunday_att'].'
												</td>
												<td><!-- hrs -->
													'.$payrollArr['sunday_hrs'].'
												</td>
												<td><!-- ND -->
													'.$payrollArr['nightdiff_rate'].'
												</td>
												<td><!-- # -->
													'.$payrollArr['nightdiff_num'].'
												</td>
												<td><!-- Reg.hol -->
													'.$payrollArr['reg_holiday'].'
												</td>
												<td><!-- # -->
													'.$regHolidayNum.'
												</td>
												<td><!-- Spe. hol -->
													'.$payrollArr['spe_holiday'].'
												</td>
												<td><!-- # -->
													'.$payrollArr['spe_holiday_num'].'
												</td>
												<td><!-- X.All -->
													'.($payrollArr['x_allowance'] + $payrollArr['x_allow_weekly'] + ($payrollArr['x_allow_daily'] * $payrollArr['allow_days'])).'
												</td>
												<td><!-- SSS -->
													'.$payrollArr['sss'].'
												</td>
												<td><!-- Philhealth -->
													'.$payrollArr['philhealth'].'
												</td>
												<td><!-- Pagibig -->
													'.$payrollArr['pagibig'].'
												</td>
												<td><!-- Old vale -->
													'.$payrollArr['old_vale'].'
												</td>
												<td><!-- vale -->
													';

													$payrollDay = date('F d, Y', strtotime('+1 day', strtotime($endDate)));
													
													$payrollOutstandingSql = "SELECT total_salary FROM payroll WHERE empid = '$emplid' AND STR_TO_DATE(date, '%M %e, %Y') < STR_TO_DATE('$payrollDay', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";

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

													Print $payrollOutstanding.'
												</td>
												<td><!-- sss loan -->
													'.$payrollArr['loan_sss'].'
												</td>
												<td><!-- pagibig loan -->
													'.$payrollArr['loan_pagibig'].'
												</td>
												<td><!-- insurance -->
													'.$payrollArr['insurance'].'
												</td>
												<td><!-- tools -->
													'.$payrollArr['tools_paid'].'
												</td>
												<td><!-- Total Salary -->
													';
													if($payrollArr['total_salary'] > 0)
													{
														Print numberExactFormat($payrollArr['total_salary'],2,".", true);
													}
													else
													{
														Print '0';
													}
																Print '
												</td>
											</tr>';
										
										$rowNum++;//increment the row number
								}
									
							}


								
						}
							
						
						else
						{
							$printBool = 0;
							$dataBool = false;
							Print '	<tr>
										<td colspan="27">
											<h4>No Payroll data at the moment</h4>
										</td>
									</tr>';
						}

						if($dataBool)
						{
							$printBool = 0;
							Print '	<tr>
										<td colspan="27">
											<h4>No Payroll data at the moment</h4>
										</td>
									</tr>';
						}
						Print '</table>';
						
			}
			else
			{
				Print "<h4 class='pull-down-more col-md-1 col-lg-12'>Select Requirements and Date to view Payroll report.</h4>";
			}
			?>
			</div>

	</div>
</div>
	<form id="dynamicForm" method="POST" action="reports_overall_payroll.php?req=<?php Print $req?>&site=<?php Print $location?>">
		<input type="hidden" name="payrollDate" id="payrollDate">
	</form>

	<input type="hidden" id="printButton" value="<?php Print $printBool?>">
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		$( document ).ready(function() {
		   	if($('#printButton').val() == 1)
		   	{
		   		$('#printPayslip').removeClass('disabletotally');
		   		$('#printPayroll').removeClass('disabletotally');
		   	}
		   	else
		   	{
		   		$('#printPayslip').addClass('disabletotally');
		   		$('#printPayroll').addClass('disabletotally');
		   	}	
		});
	</script>
	<script>

		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function payrollRequirements(req) {
			window.location.assign("reports_overall_payroll.php?req="+req+"&site=<?php Print $location?>");
		}

		function payrollDates(date) {
			document.getElementById('payrollDate').value = date;
			document.getElementById('dynamicForm').submit();
		}

		function enableDropdown(item) {
			// check if step1 has a selection
			// enable dropdown if step1 has a selected value
			var item = document.getElementById('step1');
			var step1 = item.options[item.selectedIndex].text;
			if(step1 !== '-- All / With / Without --') {
				document.getElementById('step2').disabled = false;
			}
		}

		function printPayslips() {
			var req = document.getElementById('step1').value;
			var date = document.getElementById('step2').value;

			window.location.assign("print_overall_payslip.php?req="+req+"&date="+date+"&site=<?php Print $location?>&cutoff=<?php Print $earlyCuttoff?>");
		}
	</script>
</body>
</html>