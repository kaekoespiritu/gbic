<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');

// if(!isset($_POST['empid']))
// {
// 	Print "<script>window.location.assign('payroll.login')</script>";
// }

$empid = $_POST['empid'];
$date = $_POST['date'];
// Print "<script>alert('".$empid."')</script>";
$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$employeeQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($employeeQuery);
$site = $empArr['site'];
$position = $empArr['position'];

$payroll = "SELECT * FROM payroll WHERE date = '$date' AND empid = '$empid'";
$payrollQuery = mysql_query($payroll);
$payrollArr = mysql_fetch_assoc($payrollQuery);

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

			<li><a href="logic_payroll_backPayroll.php?e=<?php Print $empid?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span>Edit Payroll</a></li>
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
			<h3>Earnings</h3>
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
						if(!empty($payrollArr['sunday_hrs']))
						{
							if($payrollArr['sunday_hrs'] >= 8)
							{
								$ratePerDaySub = $payrollArr['num_days'] - 1;
								$ratePerDaySub = abs($ratePerDaySub);
							}
							else
							{
								$ratePerDaySub = ($payrollArr['sunday_hrs']/8) - $payrollArr['num_days'];
								$ratePerDaySub = abs($ratePerDaySub);
							}
							$ratePerDayDisp = $ratePerDaySub." Day(s)";

						}
						else
						{
							$ratePerDaySub = $payrollArr['num_days'];//for computation
							$ratePerDayDisp = $payrollArr['num_days']." Day(s)";// for display
						}
						$subTotalRatePerDay = $ratePerDaySub * numberExactFormat($empArr['rate'],2,'.', true);
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
						<td><?php Print $ratePerDayDisp?></td>
						<td><?php Print $subTotalRatePerDay?></td>
					</tr>

					<!-- Allowance -->
					<?php
						$subTotalAllowance = $empArr['allowance'] * $payrollArr['num_days'];
							$totalAllowance = $subTotalAllowance;//for the Subtotal of Earnings

						// Print "<script>alert('".$totalAllowance." | ".$payrollArr['x_allowance']."')</script>";
						if($subTotalAllowance == 0)
							$subTotalAllowance = "--";
						else
							$subTotalAllowance = numberExactFormat($subTotalAllowance, 2, '.', true);
					?>
					<tr>
						<td>Allowance</td>
						<td><?php Print $empArr['allowance']?></td>
						<td><?php Print $numDays?></td>
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
						<td><?php Print $ot_num?></td>
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
						<td><?php Print $nightdiffNum?></td>
						<td><?php Print $subTotalNightDifferential?></td>
					</tr>
					<!-- Sunday Rate -->
					<?php
						$subTotalSundayRate = $payrollArr['sunday_rate'] * $payrollArr['sunday_hrs'];
						$totalSundayRate = $subTotalSundayRate;//for the Subtotal of Earnings
						$sundayHrs = $payrollArr['sunday_hrs']." Hour(s)";
						if($subTotalSundayRate == 0)
							$subTotalSundayRate = "--";
						else
							$subTotalSundayRate = numberExactFormat($subTotalSundayRate, 2, '.', true);
						if($sundayHrs == 0)
							$sundayHrs = "--";
					?>
					<tr>
						<td>Sunday Rate</td>
						<td><?php Print $payrollArr['sunday_rate']?></td>
						<td><?php Print $sundayHrs?></td>
						<td><?php Print $subTotalSundayRate?></td>
					</tr>
					<!-- Regular Holiday Rate -->
					<?php
						$regHolidayDays = $empArr['rate'] * $payrollArr['reg_holiday_num'];
						$subTotalRegularHolidayRate = ($payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday']) + $regHolidayDays;

						$totalSpecialHolidayRate = $subTotalRegularHolidayRate;//for the Subtotal of Earnings
						$regHolNum = $payrollArr['reg_holiday_num']." Day(s)";
						if($subTotalRegularHolidayRate == 0)
							$subTotalRegularHolidayRate = "--";
						else
							$subTotalRegularHolidayRate = numberExactFormat($subTotalRegularHolidayRate, 2, '.', true);
						if($regHolNum == 0)
							$regHolNum = "--";

					?>
					<tr>
						<td>Regular Holiday</td>
						<td><?php Print numberExactFormat($empArr['rate'] + $payrollArr['reg_holiday'], 2, '.', true) ?></td>
						<td><?php Print $regHolNum?></td>
						<td><?php Print $subTotalRegularHolidayRate?></td>
					</tr>
					<!-- Special Holiday Rate -->
					<?php
						$speHolidayDays = $empArr['rate'] * $payrollArr['spe_holiday_num'];
						$subTotalSpecialHolidayRate = ($payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday']) + $speHolidayDays;
						$totalRegularHolidayRate = $subTotalSpecialHolidayRate;//for the Subtotal of Earnings
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
						<td><?php Print  numberExactFormat($empArr['rate'] + $payrollArr['spe_holiday'], 2, '.', true)?></td>
						<td><?php Print $speHolNum?></td>
						<td><?php Print $subTotalSpecialHolidayRate?></td>
					</tr>
					<!-- COLA -->
					<?php
						$subTotalCola = $payrollArr['cola'];
						if($subTotalCola == 0)
							$subTotalCola = "--";
						else
							$subTotalCola = numberExactFormat($subTotalCola, 2, '.', true);

						if($payrollArr['cola'] != 0)
						{
							Print "
								<tr>
									<td>COLA</td>
									<td></td>
									<td></td>
									<td>".$subTotalCola."</td>
								</tr>
							";
						}
					?>

					<?php
						$totalEarnings = $totalRegularHolidayRate + $totalSpecialHolidayRate + $totalSundayRate + $totalNightDifferential + $totalAllowance + $totalOvertime + $totalRatePerDay + $xAllowance + $payrollArr['cola'];
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
						<th>Name</th>
						<td colspan='3'></td>
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
					$tools ="SELECT * FROM tools WHERE empid = '$empid' AND date = '$date'";
					$toolsQuery = mysql_query($tools);
					
					if(mysql_num_rows($toolsQuery) > 0)
					{
						$Notools = false;
						while($toolArr = mysql_fetch_assoc($toolsQuery))
						{
							$toolSubTotal += $toolArr['cost'];
							Print "
								<tr>
									<td>".$toolArr['tools']."</td>
									<td colspan='3'></td>
									<td>".$toolArr['cost']."</td>
								</tr>
								";
							//Print "<script>alert('".$toolArr['tools']."')</script>";
						}
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
					$contributions = $payrollArr['pagibig']+$payrollArr['philhealth']+$payrollArr['sss']+$payrollArr['tax'];
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
					<tr class="active" style="font-family: QuicksandMed;">
						<td>Subtotal</td>
						<td><?php Print numberExactFormat($contributions, 2, '.', true)?></td>
					</tr>
				</tbody>
			</table>

			<!-- Loans -->
			<?php 
				$totalLoans = $payrollArr['loan_pagibig'] + $payrollArr['loan_sss'] + $payrollArr['old_vale'] + $payrollArr['new_vale'];
			?>
			<h3>Loans</h3>
			<table class="table">
				<thead>
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
			    	$grandTotal = abs($totalEarnings) - abs($contributions) - abs($totalLoans) - abs($payrollArr['tools_paid']);
			    	$grandTotal = abs($grandTotal);
			    ?>
			    <div class="col-md-1 col-lg-12">
			    	<h3><u>Grand total: <?php Print numberExactFormat($grandTotal, 2, '.', true) ?></u></h3>
				</div>
			  </div>
			</div>
		</div>
	</div>

<script>
	document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");
</script>
<script rel="javascript" src="js/jquery.min.js"></script>
</body>
</html>
