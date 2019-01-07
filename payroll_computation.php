<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');

$empid = $_POST['empid'];
$date = $_POST['date'];

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$employeeQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($employeeQuery);
$site = $empArr['site'];
$position = $empArr['position'];

$payroll = "SELECT * FROM payroll WHERE date = '$date' AND empid = '$empid'";
$payrollQuery = mysql_query($payroll);
$payrollArr = mysql_fetch_assoc($payrollQuery);

$day1 = $date;
$day2 = date('F d, Y', strtotime('-1 day', strtotime($date)));
$day3 = date('F d, Y', strtotime('-2 day', strtotime($date)));
$day4 = date('F d, Y', strtotime('-3 day', strtotime($date)));
$day5 = date('F d, Y', strtotime('-4 day', strtotime($date)));
$day6 = date('F d, Y', strtotime('-5 day', strtotime($date)));
$day7 = date('F d, Y', strtotime('-6 day', strtotime($date)));
$daySeven = date('F d, Y', strtotime('-7 day', strtotime($date)));

$weekArr = array($day1, $day2, $day3, $day4, $day5, $day6, $day7);

$payrollOutstandingSql = "SELECT total_salary FROM payroll WHERE empid = '$empid' AND date != '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
$payrollOutstandingQuery = mysql_query($payrollOutstandingSql);

$payrollOutstanding = 0;

while($outStandingCheck = mysql_fetch_assoc($payrollOutstandingQuery))
{
	if($outStandingCheck['total_salary'] < 0.00)
		$payrollOutstanding = $outStandingCheck['total_salary'] ;
}


?>

<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: Quicksand;">

<div class="container-fluid">

	<?php
	require("directives/nav.php");
	?>
<!-- ?site=Tagaytay&position=Carpenter&empid=2010-0903761 -->
	<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">
		<ol class="breadcrumb text-left" style="margin-bottom: 0px">

			<li>
				<?php
					Print "<button onclick='EditPayroll(\"".$empid."\")'  class='btn btn-primary'>";
				?>
					<span class="glyphicon glyphicon-arrow-left"></span>Edit Payroll
				</button>
			</li>
			<li class="active">
				<?php
				Print "Computation for ".$empArr['lastname'].", ".$empArr['firstname']." the ". $empArr['position']." from ". $empArr['site'];
				?>
			</li>
			<a class="btn btn-success pull-right" href="payroll_table.php?position=<?php Print $position?>&site=<?php Print $site?>">Choose Next Employee</a>

		</ol>
	</div>

	<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">

		<!-- Earnings -->
		<div class="col-md-6 col-lg-6 text-left">
			<div>
				<h3>Earnings<a class="btn btn-warning pull-right" onclick="editEarnings()" id="editEarnings">Edit Earnings</a></h3>
				
			</div>
			<table class="table">
				<thead>
					<tr>
						<th>Type</th>
						<th>Amount</th>
						<th>Days / Hours</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<!-- Rate per day -->
					<?php
						
						$numDays = $payrollArr['num_days']." Day(s)";

						$ratePerDaySub = 0;
							$ratePerDaySub = $payrollArr['num_days'];//for computation
							$numDaysArr = explode('.', $ratePerDaySub);
							if(count($numDaysArr) == 2)
							{
								if($numDaysArr[1] == 0)
									$ratePerDaySub = $numDaysArr[0];
							}
							else
								$ratePerDaySub = $numDaysArr[0];

							$ratePerDayDisp = $ratePerDaySub." Day(s)";// for display
						// }
						$subTotalRatePerDay = $ratePerDaySub * $empArr['rate'];
						Print "<script>console.log('ratePerDaySub: ". $ratePerDaySub." | dailyRate: ".numberExactFormat($empArr['rate'],2,'.', true)."')</script>";//dito
						$totalRatePerDay = $subTotalRatePerDay;//for the Subtotal of Earnings

						if($subTotalRatePerDay == 0)
							$subTotalRatePerDay = "--";
						else
							$subTotalRatePerDay = numberExactFormat($subTotalRatePerDay, 2, '.', true);
						if($numDays == 0)
							$numDays = "--";
					?>
					<tr>
						<td>Rate per day</td>
						<td><?php Print $empArr['rate']?></td>
						<td id="rateDays"><?php Print $ratePerDayDisp?></td>
						<td><?php Print $subTotalRatePerDay?></td>
					</tr>

					<!-- Allowance -->
					<?php

						$allowDays =  $payrollArr['allow_days'];

						$subTotalAllowance = $empArr['allowance'] * $allowDays;
						$totalAllowance = $subTotalAllowance;//for the Subtotal of Earnings

						if($subTotalAllowance == 0)
							$subTotalAllowance = "--";
						else
							$subTotalAllowance = numberExactFormat($subTotalAllowance, 2, '.', true);

						if($allowDays == 0)
							$allowDays = "--";
						else
							$allowDays = $allowDays." Day(s)";
						
					?>
					<tr>
						<td>Allowance</td>
						<td><?php Print $empArr['allowance']?></td>
						<td id="allowDays"><?php Print $allowDays?></td>
						<td><?php Print $subTotalAllowance?></td>
					</tr>

					<!-- Extra Allowance -->

					<?php
					$xAllowance = 0;
					if($payrollArr['x_allowance'] != 0)
					{
					Print "
							<tr>
								<td>Extra Allowance</td>
								<td>".numberExactFormat($payrollArr['x_allowance'], 2, '.', true)."</td>
								<td>--</td>
								<td>".numberExactFormat($payrollArr['x_allowance'], 2, '.', true)."</td>
							</tr>
							";
					$xAllowance = $payrollArr['x_allowance'];
					}
					
					?>
					<!-- Extra Allowance Daily -->

					<?php
					$xAllowanceDaily = 0;
					$overallXAllowDaily = 0;
					if($payrollArr['x_allow_daily'] != 0)
					{
						$overallXAllowDaily = $allowDays * $payrollArr['x_allow_daily'];
					Print "
							<tr>
								<td>Extra Allowance</td>
								<td>".numberExactFormat($payrollArr['x_allow_daily'], 2, '.', true)."</td>
								<td>".$allowDays."</td>
								<td>".numberExactFormat($overallXAllowDaily, 2, '.', true)."</td>
							</tr>
							";
					}
					
					?>
					<!-- Extra Allowance Weekly -->

					<?php
					$xAllowanceWeekly = 0;
					if($payrollArr['x_allow_weekly'] != 0)
					{
					Print "
							<tr>
								<td>Extra Allowance</td>
								<td>".numberExactFormat($payrollArr['x_allow_weekly'], 2, '.', true)."</td>
								<td>--</td>
								<td>".numberExactFormat($payrollArr['x_allow_weekly'], 2, '.', true)."</td>
							</tr>
							";
					$xAllowanceWeekly = $payrollArr['x_allow_weekly'];
					}
					
					?>
					<!-- Overtime -->
					<?php
						$subTotalOvertime = $payrollArr['ot_num']*$payrollArr['overtime'];
						$totalOvertime = $subTotalOvertime;//for the Subtotal of Earnings
						$ot_num = $payrollArr['ot_num']." Hour(s)";
						if($subTotalOvertime == 0)
							$subTotalOvertime = "--";
						else
							$subTotalOvertime = numberExactFormat($subTotalOvertime, 2, '.', true);
						if($ot_num == 0)
							$ot_num = "--";
					?>
					<tr>
						<td>Overtime</td>
						<td><?php Print $payrollArr['overtime']?></td>
						<td id="otDays"><?php Print $ot_num?></td>
						<td><?php Print $subTotalOvertime?></td>
					</tr>
					<!-- Night Differential -->
					<?php
						$subTotalNightDifferential = $payrollArr['nightdiff_rate'] * $payrollArr['nightdiff_num'];
						$totalNightDifferential = $subTotalNightDifferential;//for the Subtotal of Earnings
						$nightdiffNum = $payrollArr['nightdiff_num']." Hour(s)";
						if($subTotalNightDifferential == 0)
							$subTotalNightDifferential = "--";
						else
							$subTotalNightDifferential = numberExactFormat($subTotalNightDifferential, 2, '.', true);
						if($nightdiffNum == 0)
							$nightdiffNum = "--";
					?>
					<tr>
						<td>Night Differential</td>
						<td><?php Print $payrollArr['nightdiff_rate']?></td>
						<td id="ndDays"><?php Print $nightdiffNum?></td>
						<td><?php Print $subTotalNightDifferential?></td>
					</tr>
					<!-- Sunday Rate -->
					<?php

						$sundayHrs = $payrollArr['sunday_hrs'];
						$sundayHoursComp = 0;
						if($sundayHrs == 0)
							$sundayHrs = "--";
						else
						{
							// $sundayArr = explode('.', $sundayHrs);
							// if(count($sundayArr) > 1)//if it has minutes
							// {
							// 	if($sundayArr[1] == 0)//no minutes
							// 	{
							// 		$sundayHoursComp = $sundayArr[0];
							// 		$sundayHrs =  $sundayArr[0]." Hour(s)";
							// 	}
							// 	else
							// 	{
							// 		$sundayMinComp = $sundayArr[1]/60;
							// 		$sundayHoursComp = $sundayArr[0] + $sundayMinComp;
							// 		$sundayHrs =  $sundayArr[0]." Hour(s) ".$sundayArr[1]." min(s)";	
							// 	}
							// }
							// else
							// {
								$sundayHoursComp = $sundayHrs;
								$sundayHrs =  $sundayHrs." Hour(s)";
							// }
						}

						$subTotalSundayRate = $payrollArr['sunday_rate'] * $payrollArr['sunday_hrs'];
						$totalSundayRate = $subTotalSundayRate;//for the Subtotal of Earnings
						
						if($subTotalSundayRate == 0)
							$subTotalSundayRate = "--";
						else
							$subTotalSundayRate = numberExactFormat($subTotalSundayRate, 2, '.', true);
							
					?>
					<tr>
						<td>Sunday Rate</td>
						<td><?php Print $payrollArr['sunday_rate']?></td>
						<td id="sunDays"><?php Print $sundayHrs?></td>
						<td><?php Print $subTotalSundayRate?></td>
					</tr>
					<!-- Regular Holiday Rate -->
					<?php

						if($payrollArr['reg_holiday_num'] > 1)
						{
							// $holidayRegChecker = "SELECT DISTINCT h.date AS Holiday_Date, h.holiday AS Holiday_name FROM holiday AS h, attendance AS a WHERE STR_TO_DATE(a.date, '%M %e, %Y') BETWEEN DATE_SUB(STR_TO_DATE('$date', '%M %e, %Y'), INTERVAL 7 DAY) AND STR_TO_DATE('$date', '%M %e, %Y') AND DATEDIFF(STR_TO_DATE(h.date, '%M %e, %Y'), STR_TO_DATE(a.date, '%M %e, %Y')) >= 1 AND a.empid = '$empid' AND a.attendance = '2' AND h.type = 'regular'";
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

						$subTotalRegularHolidayRate = ($payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday']) ;

						$totalRegularHolidayRate = $subTotalRegularHolidayRate;//for the Subtotal of Earnings
						$regHolNum = $regHolidayNum." Day(s)";
						if($subTotalRegularHolidayRate == 0)
							$subTotalRegularHolidayRate = "--";
						else
							$subTotalRegularHolidayRate = numberExactFormat($subTotalRegularHolidayRate, 2, '.', true);
						if($regHolNum == 0)
							$regHolNum = "--";

					?>
					<tr>
						<td>Regular Holiday</td>
						<td><?php Print numberExactFormat($payrollArr['reg_holiday'], 2, '.', true) ?></td>
						<td id="regHolDays"><?php Print $regHolNum?></td>
						<td><?php Print $subTotalRegularHolidayRate?></td>
					</tr>
					<!-- Special Holiday Rate -->
					<?php
						if($payrollArr['spe_holiday_num'] > 0)
							$subTotalSpecialHolidayRate = ($payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday']);
						else
							$subTotalSpecialHolidayRate = 0;
						$totalSpecialHolidayRate = $subTotalSpecialHolidayRate;//for the Subtotal of Earnings
						$speHolNum = $payrollArr['spe_holiday_num']." Day(s)";
						if($subTotalSpecialHolidayRate == 0)
							$subTotalSpecialHolidayRate = "--";
						else
							$subTotalSpecialHolidayRate = numberExactFormat($subTotalSpecialHolidayRate, 2, '.', true);
						if($speHolNum == 0)
							$speHolNum = "--";
						
					?>
					<tr>
						<td>Special Holiday</td>
						<td><?php Print numberExactFormat($payrollArr['spe_holiday'], 2, '.', true)?></td>
						<td id="speHolDays"><?php Print $speHolNum?></td>
						<td><?php Print $subTotalSpecialHolidayRate?></td>
					</tr>
					<!-- COLA -->
					<?php
						$totalCola = $payrollArr['cola'];
						if($totalCola == 0)
							$subTotalCola = "--";
						else
							$subTotalCola = numberExactFormat($totalCola, 2, '.', true);


						if($payrollArr['cola'] != 0)
						{
							$currentCola = $payrollArr['cola']/$allowDays;
							Print "
								<tr>
									<td>COLA</td>
									<td>".$currentCola."</td>
									<td>".$allowDays."</td>
									<td>".$subTotalCola."</td>
								</tr>
							";
						}
					?>

					<?php
						$totalEarnings = $totalRegularHolidayRate + $totalSpecialHolidayRate + $totalSundayRate + $totalNightDifferential + $totalAllowance + $totalOvertime + $totalRatePerDay + $xAllowance + $totalCola + $overallXAllowDaily + $xAllowanceWeekly;
							Print "<script>console.log('payroll_computation.php - totalRegularHolidayRate: ".abs($totalRegularHolidayRate)." | totalSpecialHolidayRate: ".abs($totalSpecialHolidayRate)." | totalSundayRate: ".abs($totalSundayRate)." | totalNightDifferential: ".$totalNightDifferential." | totalAllowance: ".$totalAllowance." | totalOvertime: ".$totalOvertime." | totalRatePerDay: ".$totalRatePerDay." | xAllowance: ".$xAllowance." | totalCola: ".$totalCola. "')</script>";"')</script>";

					?>
					<tr style="font-family: QuicksandMed;">
						<td colspan="2" class="active">Subtotal</td>
						<td class="active"></td>
						<td class="active"><?php Print numberExactFormat($totalEarnings, 2, '.', true)?></td>

					</tr>
				</tbody>
			</table>

			<!-- Tools -->
			<h3>Tools</h3>
			<table class='table'>
				<thead>
					<tr>
						<th colspan="3">Name</th>
						<th>Quantity</th>
						<th>Cost</th>
					</tr>
				</thead>
				<tbody>
			<?php
				$tools = "SELECT * FROM tools WHERE empid = '$empid' AND date = '$date'";
				$toolsQuery = mysql_query($tools);
				$toolSubTotal = 0;
				$Notools = true;// if theres no tools
				$displayToolSubTotal = null;
				if(mysql_num_rows($toolsQuery) > 0)
				{
					$Notools = false;
					while($toolArr = mysql_fetch_assoc($toolsQuery))
					{
						$toolSubTotal += $toolArr['cost'];
						Print "
							<tr>
								<td colspan='3'>".$toolArr['tools']."</td>
								<td>".$toolArr['quantity']."</td>
								<td>".$toolArr['cost']."</td>
							</tr>
							";
					}
				}

				
				
				if($Notools)
				{
					Print "	<tr>
								<td>No Tools</td>
								<td colspan='3'></td>
								<td>--</td>
							</tr>";
				}

				if($toolSubTotal == 0)
				{
					$displayToolSubTotal = "--";
				}
				else
				{
					$displayToolSubTotal = numberExactFormat($toolSubTotal, 2, '.', true);
				}
				if($payrollArr['tools_paid'] != 0)
				{
					$displayToolPayed = numberExactFormat($payrollArr['tools_paid'], 2, '.', true);
				}
				else if($payrollArr['tools_paid'] == 0)//if employee didnot input any amount to pay
				{
					$displayToolPayed = numberExactFormat($toolSubTotal, 2, '.', true);
				}
				else
				{
					$displayToolPayed = "--";
				}

				$prevPayCheck = "SELECT * FROM payroll WHERE empid = '$empid' AND date <> '$date' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
				$prevPayQuery = mysql_query($prevPayCheck) or die (mysql_error());

					//Previous payable
					if(mysql_num_rows($prevPayQuery) > 0)
					{
						$prevPayArr = mysql_fetch_assoc($prevPayQuery);
						if($prevPayArr['tools_outstanding'] > 0)
						Print 
							"<tr>
								<td>Previous Payable</td>
								<td colspan='3' ></td>
								<td>".$prevPayArr['tools_outstanding']."</td>
							</tr>";
					}
					$toolsSubTotal = "--";
					if($payrollArr['tools_paid'] != 0)//Tools paid
					{
						Print 
							"<tr>
								<td>Amount Paid</td>
								<td colspan='3' ></td>
								<td>".$displayToolPayed."</td>
							</tr>";
						$toolsSubTotal = numberExactFormat($payrollArr['tools_paid'], 2, '.', true);
					}
					if($payrollArr['tools_outstanding'] != 0)//outstanding Payable
					{
						Print 
							"<tr>
								<td>Outstanding Payable</td>
								<td colspan='3' ></td>
								<td>".numberExactFormat($payrollArr['tools_outstanding'], 2, '.', true)."</td>
							</tr>";
					}
					?>
					<tr style='font-family: QuicksandMed;''>
						<td class='active'>Subtotal</td>
						<td colspan='3' class='active'></td>
						<td class='active'><?php Print $toolsSubTotal?></td>
					</tr>
				</tbody>
			</table>
			
		</div>

		<!-- Contributions -->
		<div class="col-md-6 col-lg-6 text-left">
			<h3>Contributions</h3>
			<table class="table">
				<?php 
					$contributions = $payrollArr['pagibig']+$payrollArr['philhealth']+$payrollArr['sss']+$payrollArr['tax']+$payrollArr['insurance'];
				?>
				<thead>
					<tr>
						<td>TAX</td>
						<td>
							<?php 
							if($payrollArr['tax'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['tax'], 2, '.', true);
							?>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>SSS</td>
						<td>
							<?php 
							if($payrollArr['sss'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['sss'], 2, '.', true);
							?>
						</td>
					</tr>
					<tr>
						<td>PhilHealth</td>
						<td>
							<?php 
							if($payrollArr['philhealth'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['philhealth'], 2, '.', true);
							?>
						</td>
					</tr>
					<tr>
						<td>PagIBIG</td>
						<td>
							<?php 
							if($payrollArr['pagibig'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['pagibig'], 2, '.', true);
							?>
						</td>
					</tr>
					<tr>
						<td>Insurance</td>
						<td>
							<?php 
							if($payrollArr['insurance'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['insurance'], 2, '.', true);
							?>
						</td>
					</tr>
					<tr class="active" style="font-family: QuicksandMed;">
						<td>Subtotal</td>
						<td><?php Print numberExactFormat($contributions, 2, '.', true)?></td>
					</tr>
				</tbody>
			</table>

			<!-- Loans -->
			<?php 
			if(mysql_num_rows($payrollOutstandingQuery) > 0)
				$totalLoans = $payrollArr['loan_pagibig'] + $payrollArr['loan_sss'] + $payrollArr['old_vale'] + $payrollArr['new_vale'] + abs($payrollOutstanding);
			else
				$totalLoans = $payrollArr['loan_pagibig'] + $payrollArr['loan_sss'] + $payrollArr['old_vale'] + $payrollArr['new_vale'];
			?>
			<h3>Loans</h3>
			<table class="table">
				<thead>
					<?php
					if(mysql_num_rows($payrollOutstandingQuery) > 0)
					{
						Print "<tr>
								<td>Outstanding Payroll</td>
								<td>
									".( abs($payrollOutstanding) != 0 ? numberExactFormat(abs($payrollOutstanding), 2, '.', true) : '--' )."
								</td>
						</tr>";
					}
					else
					{
						Print "<tr>
								<td>Outstanding Payroll</td>
								<td>
									--
								</td>
						</tr>";
					}
					?>
					<tr>
						<td>New Vale</td>
						<td>
							<?php 
							if($payrollArr['new_vale'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['new_vale'], 2, '.', true);
							?>
						</td>
					</tr>
					<tr>
						<td>Old Vale</td>
						<td>
							<?php 
							if($payrollArr['old_vale'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['old_vale'], 2, '.', true);
							?>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>SSS</td>
						<td>
							<?php 
							if($payrollArr['loan_sss'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['loan_sss'], 2, '.', true);
							?>
						</td>
					</tr>
					<tr>
						<td>PagIBIG</td>
						<td>
							<?php 
							if($payrollArr['loan_pagibig'] == 0)
								Print "--";
							else
								Print numberExactFormat($payrollArr['loan_pagibig'], 2, '.', true);
							?>
						</td>
					</tr>
					<tr class="active" style="font-family: QuicksandMed;">
						<td>Subtotal</td>
						<td>
							<?php 
							if($totalLoans  == 0)
								Print "--";
							else
								Print numberExactFormat($totalLoans, 2, '.', true);
							?>
						</td>
					</tr>
			</table>
		</div>

		<!-- Overall Computation -->
		<div class="col-md-1 col-lg-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">
			    <h3 style="margin:0px">Overall Computation</h3>
			  </div>
			  <div class="panel-body text-center">
			  	<div class="col-md-3 col-lg-3">
			  		<h4><span class="glyphicon glyphicon-plus" style="color:green;"></span> Total Earnings:<br>
			  			<b>
			  				<?php Print numberExactFormat($totalEarnings, 2, '.', true)?>
			  			</b>
			  		</h4>
			  	</div>
			    <div class="col-md-3 col-lg-3">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Contributions:<br>
			    		<strong>
			    			<?php Print numberExactFormat($contributions, 2, '.', true) ?>
			    		</strong>
			    	</h4>
			    </div>
			    <div class="col-md-3 col-lg-3">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Loans:
			    	<br>
			    		<strong>
			    			<?php Print numberExactFormat($totalLoans, 2, '.', true) ?>
			    		</strong>
			    	</h4>
			    </div>
			    <div class="col-md-3 col-lg-3">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Tools:
			    	<br> 
			    		<b>
			    			<?php Print numberExactFormat($payrollArr['tools_paid'], 2, '.', true) ?>
			    		</b>
			    	</h4>
			    </div>
			    <?php
			    		Print "<script>console.log('logic_payroll - totalEarnings: ".abs($totalEarnings)." | contributions: ".abs($contributions)." | totalLoans: ".abs($totalLoans)." | tools_paid: ".abs($payrollArr['tools_paid'])."')</script>";
			    	$grandTotal = abs($totalEarnings) - abs($contributions) - abs($totalLoans) - abs($payrollArr['tools_paid']) - abs($payrollOutstanding);
			    	
			    	$grandTotal = $grandTotal;
			    ?>
			    <div class="col-md-1 col-lg-12">
			    	<h3><u>Grand total: <?php Print numberExactFormat($grandTotal, 2, '.', true) ?></u></h3>
				</div>
			  </div>
			</div>
		</div>
	</div>
	<div id="tempDiv">
	</div>
<script rel="javascript" src="js/jquery-ui/external/jquery/jquery.js"></script>
<script>
	
	function numberVerify(e) {
	    if (/^[\d\.]+$/.test(String.fromCharCode(e.keyCode))) {
	        return true;
	    } else {
	        e.preventDefault();
	        return false;
	    }
	}

	document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");

	function saveEarnings() {
		//Gets the value inside the earnings
		var rateDays = document.getElementById('rateDays').querySelector('.numberVerify');
		var otDays = document.getElementById('otDays').querySelector('.numberVerify');
		var ndDays = document.getElementById('ndDays').querySelector('.numberVerify');
		var sunDays = document.getElementById('sunDays').querySelector('.numberVerify');
		var regHolDays = document.getElementById('regHolDays').querySelector('.numberVerify');
		var speHolDays = document.getElementById('speHolDays').querySelector('.numberVerify');
		var allowDays = document.getElementById('allowDays').querySelector('.numberVerify');

		var form = document.createElement('form');
		form.setAttribute('action', 'logic_payroll_update_earnings.php?date=<?php Print $date?>&empid=<?php Print $empid?>');
		form.setAttribute('method', 'post');
		form.setAttribute('id', 'tempForm');

		form.appendChild(rateDays);
		form.appendChild(otDays);
		form.appendChild(ndDays);
		form.appendChild(sunDays);
		form.appendChild(regHolDays);
		form.appendChild(speHolDays);
		form.appendChild(allowDays);
		console.dir(form);

		document.getElementById('tempDiv').appendChild(form);

		var con = confirm("Are you sure you want to save this alteration?");
		if(con) {
			document.getElementById('tempForm').submit();
		}

		
		// 
		// console.log(ndDays);
		// console.log(sunDays);
		// console.log(speHolDays);
		// console.log(allowDays);

	}

	function editEarnings() {
		function getValue(val) {
			if(val == "--" || val == "") {// If has no value
				return 0;
			}
			else {// if it has hours / days
				var splitValue = val.split(" ");
				return splitValue[0];
			}
		}
		var editButton = document.getElementById('editEarnings');
		editButton.innerHTML = "Save Earnings";
		editButton.setAttribute('onclick', 'saveEarnings()');
		editButton.classList.remove("btn-warning");
		editButton.classList.add("btn-info");

		//Gets the value inside the earnings
		var rateDays = document.getElementById('rateDays');
		var otDays = document.getElementById('otDays');
		var ndDays = document.getElementById('ndDays');
		var sunDays = document.getElementById('sunDays');
		var regHolDays = document.getElementById('regHolDays');
		var speHolDays = document.getElementById('speHolDays');
		var allowDays = document.getElementById('allowDays');

		var rateVal = getValue(rateDays.innerHTML);
		var otVal = getValue(otDays.innerHTML);
		var ndVal = getValue(ndDays.innerHTML);
		var sunVal = getValue(sunDays.innerHTML);
		var regHolVal = getValue(regHolDays.innerHTML);
		var speHolVal = getValue(speHolDays.innerHTML);
		var allowVal = getValue(allowDays.innerHTML);

		rateDays.innerHTML = "<input type='text' name='rateDays' onkeypress='return numberVerify(event)' class='numberVerify' maxlength='5' size='5' value='"+rateVal+"' > Day(s)";
		otDays.innerHTML = "<input type='text' name='otDays' onkeypress='return numberVerify(event)' class='numberVerify' maxlength='5' size='5' value='"+otVal+"'> Hour(s)";
		ndDays.innerHTML = "<input type='text' name='ndDays' onkeypress='return numberVerify(event)' class='numberVerify' maxlength='5' size='5' value='"+ndVal+"'> Hour(s)";
		sunDays.innerHTML = "<input type='text' name='sunDays' onkeypress='return numberVerify(event)' class='numberVerify' maxlength='5' size='5' value='"+sunVal+"'> Hour(s)";
		regHolDays.innerHTML = "<input type='text' name='regHolDays' onkeypress='return numberVerify(event)' class='numberVerify' maxlength='5' size='5' value='"+regHolVal+"'> Day(s)";
		speHolDays.innerHTML = "<input type='text' name='speHolDays' onkeypress='return numberVerify(event)' class='numberVerify' maxlength='5' size='5' value='"+speHolVal+"'> Day(s)";
		allowDays.innerHTML = "<input type='text' name='allowDays' onkeypress='return numberVerify(event)' class='numberVerify' maxlength='5' size='5' value='"+allowVal+"'> Day(s)";

	}

	function EditPayroll(id){
		var res = confirm("Editing the payroll will remove previously inputted data for this employee. Are you sure you want to proceed?");
		if(res)
			window.location.assign('logic_payroll_backPayroll.php?e='+id);
	}

</script>
<script rel="javascript" src="js/jquery.min.js"></script>
</body>
</html>
