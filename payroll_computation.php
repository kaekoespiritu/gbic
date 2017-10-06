<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
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

	<div class="col-md-10 col-md-offset-1 pull-down">
		<ol class="breadcrumb text-left" style="margin-bottom: 0px">

			<li><a href="payroll_table.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Payroll</a></li>
			<li class="active">Computation for [NAME], [POSITION] from [SITE]</li>

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
						<th>Days / Hours</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<!-- Rate per day -->
					<tr>
						<td>Rate per day</td>
						<td># of days</td>
						<td>###</td>
					</tr>
					<!-- Allowance -->
					<tr>
						<td>Allowance</td>
						<td># of hours</td>
						<td>###</td>
					</tr>
					<!-- Overtime -->
					<tr>
						<td>Overtime</td>
						<td># of hours</td>
						<td>###</td>
					</tr>
					<!-- Night Differential -->
					<tr>
						<td>Night Differential</td>
						<td># of hours</td>
						<td>###</td>
					</tr>
					<!-- Sunday Rate -->
					<tr>
						<td>Sunday Rate</td>
						<td># of hours</td>
						<td>###</td>
					</tr>
					<!-- Holiday Rate -->
					<tr>
						<td>Holiday Rate (Regular/Special)</td>
						<td># of hours</td>
						<td>###</td>
					</tr>
					<tr style="font-family: QuicksandMed;">
						<td colspan="2" class="active">Subtotal</td>
						<td>###</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Contributions -->
		<div class="col-md-6 text-left">
			<h3>Contributions</h3>
			<table class="table">
				<thead>
					<tr>
						<td>SSS</td>
						<td>###</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>PhilHealth</td>
						<td>###</td>
					</tr>
					<tr>
						<td>PagIBIG</td>
						<td>###</td>
					</tr>
					<tr class="active" style="font-family: QuicksandMed;">
						<td>Subtotal</td>
						<td>###</td>
					</tr>
				</tbody>
			</table>

			<!-- Loans -->
			<h3>Loans</h3>
			<table class="table">
				<thead>
					<tr>
						<td>Vale</td>
						<td>###</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>SSS</td>
						<td>###</td>
					</tr>
					<tr>
						<td>PagIBIG</td>
						<td>###</td>
					</tr>
					<tr class="active" style="font-family: QuicksandMed;">
						<td>Subtotal</td>
						<td>###</td>
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
			  	<div class="col-md-4">
			  		<h4><span class="glyphicon glyphicon-plus" style="color:green;"></span> Total Earnings: ###</h4>
			  	</div>
			    <div class="col-md-4">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Contributions: ###</h4>
			    </div>
			    <div class="col-md-4">
			    	<h4><span class="glyphicon glyphicon-minus" style="color:red;"></span> Total Loans: ###</h4>
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
