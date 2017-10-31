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
			<li class="active">Computation for <?php Print $empArr['lastname'].", ".$empArr['firstname']." the ". $empArr['position']." from ". $empArr['site']?></li>

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
					<tr>
						<td>Rate per day</td>
						<td><?php Print $empArr['rate']?></td>
						<td><?php Print $payrollArr['num_days']?></td>
						<td><?php Print number_format(($payrollArr['num_days']*$empArr['rate']), 2, '.', ',');?></td>
					</tr>
					<!-- Allowance -->
					<tr>
						<td>Allowance</td>
						<td><?php Print $empArr['allowance']?></td>
						<td><?php Print $payrollArr['num_days']?></td>
						<td><?php Print number_format(($empArr['allowance']*$payrollArr['num_days']), 2, '.', ',');?></td>
					</tr>
					<!-- Overtime -->
					<tr>
						<td>Overtime</td>
						<td><?php Print $payrollArr['overtime']?></td>
						<td><?php Print $payrollArr['ot_num']?></td>
						<td><?php Print number_format(($payrollArr['ot_num']*$payrollArr['overtime']), 2, '.', ',');?></td>
					</tr>
					<!-- Night Differential -->
					<tr>
						<td>Night Differential</td>
						<td><?php Print $payrollArr['nightdiff_rate']?></td>
						<td><?php Print $payrollArr['nightdiff_num']?></td>
						<td><?php Print number_format(($payrollArr['nightdiff_rate']*$payrollArr['nightdiff_num']), 2, '.', ',');?></td>
					</tr>
					<!-- Sunday Rate -->
					<tr>
						<td>Sunday Rate</td>
						<td><?php Print $payrollArr['sunday_rate']?></td>
						<td><?php Print $payrollArr['sunday_hrs']?></td>
						<td><?php Print number_format(($payrollArr['ot_num']*$payrollArr['overtime']), 2, '.', ',');?></td>
					</tr>
					<!-- Special Holiday Rate -->
					<tr>
						<td>Regular Holiday</td>
						<td><?php Print $payrollArr['reg_holiday']?></td>
						<td>#</td>
						<td>###</td>
					</tr>
					<!-- Regular Holiday Rate -->
					<tr>
						<td>Special Holiday</td>
						<td><?php Print $payrollArr['spe_holiday']?></td>
						<td>#</td>
						<td>###</td>
					</tr>
					<tr style="font-family: QuicksandMed;">
						<td colspan="2" class="active">Subtotal</td>
						<td></td>
						<td>###</td>

					</tr>
				</tbody>
			</table>
			<?php
				$tools = "SELECT * FROM tools WHERE empid = '$empid' AND date = '$date'";
				$toolsQuery = mysql_query($tools);
				if(mysql_num_rows($toolsQuery) > 0)
				{
					Print "	<h3>Tools</h3>
							<table class='table'>
								<thead>
									<tr>
										<th>Name</th>
										<th>Cost</th>
									</tr>
								</thead>
								<tbody>";
							
					$tools ="SELECT * FROM tools WHERE empid = '$empid' AND date = '$date'";
					$toolsQuery = mysql_query($tools);
					$toolSubTotal = 0;
					if(mysql_num_rows($toolsQuery) > 0)
					{
						while($toolArr = mysql_fetch_assoc($toolsQuery))
						{
							$toolSubTotal += $toolArr['cost'];
							Print "
								<tr>
									<td>".$toolArr['tools']."</td>
									<td>".$toolArr['cost']."</td>
								</tr>
								";
						}
					}
					$toolsChecker = "SELECT * FROM payroll WHERE empid='$empid' AND date != '$date' ORDER BY date DESC LIMIT 1";
					$toolsCheckerQuery = mysql_query($toolsChecker);
					if(mysql_num_rows($toolsCheckerQuery) == 1)
					{
						$outstandingChecker = mysql_fetch_assoc($toolsCheckerQuery);
						$toolSubTotal += $outstandingChecker['tools_outstanding'];
							Print "	<tr>
										<td>Outstanding Payable</td>
										<td>".$outstandingChecker['tools_outstanding']."</td>
									</tr>";
					}
							
							Print 	"<!-- Rate per day -->
									<tr style='font-family: QuicksandMed;''>
										<td class='active'>Subtotal</td>
										<td>".number_format($toolSubTotal, 2, '.', ',')."</td>
									</tr>
								</tbody>
							</table>";
				}
			?>
			
		</div>

		<!-- Contributions -->
		<div class="col-md-6 text-left">
			<h3>Contributions</h3>
			<table class="table">
				<?php 
					$contributions = $payrollArr['pagibig']+$payrollArr['philhealth']+$payrollArr['sss'];
				?>
				<thead>
					<tr>
						<td>SSS</td>
						<td><?php Print $payrollArr['sss'];?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>PhilHealth</td>
						<td><?php Print $payrollArr['philhealth'];?></td>
					</tr>
					<tr>
						<td>PagIBIG</td>
						<td><?php Print $payrollArr['pagibig'];?></td>
					</tr>
					<tr class="active" style="font-family: QuicksandMed;">
						<td>Subtotal</td>
						<td><?php Print $contributions?></td>
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
						<td><?php Print $payrollArr['new_vale']?></td>
					</tr>
					<tr>
						<td>Old Vale</td>
						<td><?php Print $payrollArr['old_vale']?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>SSS</td>
						<td><?php Print $payrollArr['loan_sss']?></td>
					</tr>
					<tr>
						<td>PagIBIG</td>
						<td><?php Print $payrollArr['loan_pagibig']?></td>
					</tr>
					<tr class="active" style="font-family: QuicksandMed;">
						<td>Subtotal</td>
						<td><?php Print $totalLoans ?></td>
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
			  				###
			  			</b>
			  		</h4>
			  	</div>
			    <div class="col-md-3">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Contributions:<br>
			    		<strong>
			    			<?php Print Print number_format($contributions, 2, '.', ',') ?>
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
			    <div class="col-md-12">
			    	<h3><u>Grand total: ###</u></h3>
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
