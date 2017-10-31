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
	require_once("directives/nav.php");
	?>
<!-- ?site=Tagaytay&position=Carpenter&empid=2010-0903761 -->
	<div class="col-md-10 col-md-offset-1 pull-down">
		<ol class="breadcrumb text-left" style="margin-bottom: 0px">

			<li><a href="payroll.php?site=<?php Print $empArr['site']?>&position=<?php Print $empArr['position']?>&empid=<?php Print $empid?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Payroll</a></li>
			<li class="active">Computation for <?php Print $empArr['lastname'].", ".$empArr['firstname']." the ". $empArr['position']." from ". $empArr['site']?>
			</li>

		</ol>
	</div>

	<div class="col-md-10 col-md-offset-1">

		<!-- Earnings -->
		<div class="col-md-6 text-left">
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
						$subTotalRatePerDay = $payrollArr['num_days'] * $empArr['rate'];
						$totalRatePerDay = $subTotalRatePerDay;//for the Subtotal of Earnings
						$numDays = $payrollArr['num_days']." Day(s)";
						if($subTotalRatePerDay == 0)
							$subTotalRatePerDay = "--";
						else
							$subTotalRatePerDay = number_format($subTotalRatePerDay, 2, '.', ',');
						if($numDays == 0)
							$numDays = "--";
					?>
					<tr>
						<td>Rate per day</td>
						<td><?php Print $empArr['rate']?></td>
						<td><?php Print $numDays?></td>
						<td><?php Print $subTotalRatePerDay?></td>
					</tr>
					<!-- Allowance -->
					<?php
						$subTotalAllowance = $empArr['allowance']*$payrollArr['num_days'];
						$totalAllowance = $subTotalAllowance;//for the Subtotal of Earnings
						if($subTotalAllowance == 0)
							$subTotalAllowance = "--";
						else
							$subTotalAllowance = number_format($subTotalAllowance, 2, '.', ',');
					?>
					<tr>
						<td>Allowance</td>
						<td><?php Print $empArr['allowance']?></td>
						<td><?php Print $numDays?></td>
						<td><?php Print $subTotalAllowance?></td>
					</tr>
					<!-- Overtime -->
					<?php
						$subTotalOvertime = $payrollArr['ot_num']*$payrollArr['overtime'];
						$totalOvertime = $subTotalOvertime;//for the Subtotal of Earnings
						$ot_num = $payrollArr['ot_num']." Hour(s)";
						if($subTotalOvertime == 0)
							$subTotalOvertime = "--";
						else
							$subTotalOvertime = number_format($subTotalOvertime, 2, '.', ',');
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
						$subTotalNightDifferential = $payrollArr['nightdiff_rate']*$payrollArr['nightdiff_num'];
						$totalNightDifferential = $subTotalNightDifferential;//for the Subtotal of Earnings
						$nightdiffNum = $payrollArr['nightdiff_num']." Hour(s)";
						if($subTotalNightDifferential == 0)
							$subTotalNightDifferential = "--";
						else
							$subTotalNightDifferential = number_format($subTotalNightDifferential, 2, '.', ',');
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
						$subTotalSundayRate = $payrollArr['ot_num']*$payrollArr['overtime'];
						$totalSundayRate = $subTotalSundayRate;//for the Subtotal of Earnings
						$sundayHrs = $payrollArr['sunday_hrs']." Hour(s)";
						if($subTotalSundayRate == 0)
							$subTotalSundayRate = "--";
						else
							$subTotalSundayRate = number_format($subTotalSundayRate, 2, '.', ',');
						if($sundayHrs == 0)
							$sundayHrs = "--";
					?>
					<tr>
						<td>Sunday Rate</td>
						<td><?php Print $payrollArr['sunday_rate']?></td>
						<td><?php Print $sundayHrs?></td>
						<td><?php Print $subTotalSundayRate?></td>
					</tr>
					<!-- Special Holiday Rate -->
					<?php
						$subTotalSpecialHolidayRate = $payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday'];
						$totalSpecialHolidayRate = $subTotalSpecialHolidayRate;//for the Subtotal of Earnings
						$regHolNum = $payrollArr['reg_holiday_num']." Day(s)";
						if($subTotalSpecialHolidayRate == 0)
							$subTotalSpecialHolidayRate = "--";
						else
							$subTotalSpecialHolidayRate = number_format($subTotalSpecialHolidayRate, 2, '.', ',');
						if($regHolNum == 0)
							$regHolNum = "--";
					
					?>
					<tr>
						<td>Regular Holiday</td>
						<td><?php Print $payrollArr['reg_holiday']?></td>
						<td><?php Print $regHolNum?></td>
						<td><?php Print $subTotalSpecialHolidayRate?></td>
					</tr>
					<!-- Regular Holiday Rate -->
					<?php
						$subTotalRegularHolidayRate = $payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday'];
						$totalRegularHolidayRate = $subTotalRegularHolidayRate;//for the Subtotal of Earnings
						$speHolNum = $payrollArr['spe_holiday_num']." Day(s)";
						if($subTotalRegularHolidayRate == 0)
							$subTotalRegularHolidayRate = "--";
						else
							$subTotalRegularHolidayRate = number_format($subTotalRegularHolidayRate, 2, '.', ',');
						if($speHolNum == 0)
							$speHolNum = "--";
						
					?>
					<tr>
						<td>Special Holiday</td>
						<td><?php Print $payrollArr['spe_holiday']?></td>
						<td><?php Print $speHolNum?></td>
						<td><?php Print $subTotalRegularHolidayRate?></td>
					</tr>
					<?php
						$totalEarnings = $totalRegularHolidayRate + $totalSpecialHolidayRate + $totalSundayRate + $totalNightDifferential + $totalAllowance + $totalOvertime + $totalRatePerDay;
						
					?>
					<tr style="font-family: QuicksandMed;">
						<td colspan="2" class="active">Subtotal</td>
						<td class="active"></td>
						<td class="active"><?php Print number_format($totalEarnings, 2, '.', ',')?></td>

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
						}
					}
					$toolsChecker = "SELECT * FROM payroll WHERE empid='$empid' AND date != '$date' ORDER BY date DESC LIMIT 1";
					$toolsCheckerQuery = mysql_query($toolsChecker);
					if(mysql_num_rows($toolsCheckerQuery) == 1)
					{
						$Notools = false;
						$outstandingChecker = mysql_fetch_assoc($toolsCheckerQuery);
						$toolSubTotal += $outstandingChecker['tools_outstanding'];
							Print "	<tr>
										<td>Outstanding Payable</td>
										<td colspan='3'></td>
										<td>".Print number_format($outstandingChecker['tools_outstanding'], 2, '.', ',')."</td>
									</tr>";
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
					$displayToolSubTotal = "--";
				else
					$displayToolSubTotal = number_format($toolSubTotal, 2, '.', ',');
			?>
			<!-- Rate per day -->
					<tr style='font-family: QuicksandMed;''>
						<td class='active'>Subtotal</td>
						<td colspan='3' class='active'></td>
						<td class='active'><?php Print $displayToolSubTotal?></td>
					</tr>
				</tbody>
			</table>
			
		</div>

		<!-- Contributions -->
		<div class="col-md-6 text-left">
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
								Print number_format($payrollArr['tax'], 2, '.', ',');
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
								Print number_format($payrollArr['sss'], 2, '.', ',');
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
								Print number_format($payrollArr['philhealth'], 2, '.', ',');
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
								Print number_format($payrollArr['pagibig'], 2, '.', ',');
							?>
						</td>
					</tr>
					<tr class="active" style="font-family: QuicksandMed;">
						<td>Subtotal</td>
						<td><?php Print number_format($contributions, 2, '.', ',')?></td>
					</tr>
				</tbody>
			</table>

			<!-- Loans -->
			<?php $totalLoans = $payrollArr['loan_pagibig'] + $payrollArr['loan_pagibig'] + $payrollArr['loan_sss'] + $payrollArr['old_vale'] + $payrollArr['new_vale'];
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
								Print number_format($payrollArr['new_vale'], 2, '.', ',');
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
								Print number_format($payrollArr['old_vale'], 2, '.', ',');
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
								Print number_format($payrollArr['loan_sss'], 2, '.', ',');
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
								Print number_format($payrollArr['loan_pagibig'], 2, '.', ',');
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
								Print number_format($totalLoans, 2, '.', ',');
							?>
						</td>
					</tr>
			</table>
		</div>

		<!-- Overall Computation -->
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">
			    <h3 style="margin:0px">Overall Computation</h3>
			  </div>
			  <div class="panel-body text-center">
			  	<div class="col-md-3">
			  		<h4><span class="glyphicon glyphicon-plus" style="color:green;"></span> Total Earnings:<br>
			  			<b>
			  				<?php Print number_format($totalEarnings, 2, '.', ',')?>
			  			</b>
			  		</h4>
			  	</div>
			    <div class="col-md-3">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Contributions:<br>
			    		<strong>
			    			<?php Print number_format($contributions, 2, '.', ',') ?>
			    		</strong>
			    	</h4>
			    </div>
			    <div class="col-md-3">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Loans:
			    	<br>
			    		<strong>
			    			<?php Print number_format($totalLoans, 2, '.', ',') ?>
			    		</strong>
			    	</h4>
			    </div>
			    <div class="col-md-3">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Tools:
			    	<br> 
			    		<b>
			    			<?php Print number_format($toolSubTotal, 2, '.', ',') ?>
			    		</b>
			    	</h4>
			    </div>
			    <?php
			    	$grandTotal = abs($totalEarnings) - abs($contributions) - abs($totalLoans) - abs($toolSubTotal);
			    	$grandTotal = abs($grandTotal);
			    ?>
			    <div class="col-md-12">
			    	<h3><u>Grand total: <?php Print number_format($grandTotal, 2, '.', ',') ?></u></h3>
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
