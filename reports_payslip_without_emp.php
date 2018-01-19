<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');
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
						<li><a href='reports_payslip_without.php?type=Payslip&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Payslip</a></li>
						<li>Individual Payslip for [Name], [Position] at [Site]</li>
					</ol>
				</div>
			</div>

		<div class="pull-down">
			
				
			<div class="col-md-6 col-md-offset-3">
				<button class="btn btn-default">
					Print Payslip
				</button>
				<table class="table table-bordered pull-down">
				<tr>
					<td colspan="4">
						Date Covered: [Date]
					</td>	
				</tr>
				<tr>
					<td colspan="4">
						[Last Name], [First Name] [Middle Name]
					</td>
				</tr>
				<tr>
					<td colspan="4" class="success">
						To be summed up
					</td>
				</tr>
				<tr>
					<td>
						Type
					</td>
					<td>
						Amount
					</td>
					<td>
						Days
					</td>
					<td>
						Total
					</td>
				</tr>
				<tr>
					<td>
						Rate
					</td>
					<td>
						$$$
					</td>
					<td>
						#
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						Overtime
					</td>
					<td>
						$$$
					</td>
					<td>
						#
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						Allowance
					</td>
					<td>
						$$$
					</td>
					<td>
						#
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						COLA
					</td>
					<td>
						$$$
					</td>
					<td>
						#
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						Sunday Rate
					</td>
					<td>
						$$$
					</td>
					<td>
						#
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						Night Differential
					</td>
					<td>
						$$$
					</td>
					<td>
						#
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						Regular Holiday
					</td>
					<td>
						$$$
					</td>
					<td>
						#
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						Special Holiday
					</td>
					<td>
						$$$
					</td>
					<td>
						#
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td colspan="4" class="danger">
						To be deducted
					</td>
				</tr>
				<tr>
					<td colspan="2">
						SSS
					</td>
					<td colspan="2">
						$$$
					</td>
				</tr>
				<tr>
					<td colspan="2">
						PhilHealth
					</td>
					<td colspan="2">
						$$$
					</td>
				</tr>
				<tr>
					<td colspan="2">
						Pag-ibig
					</td>
					<td colspan="2">
						$$$
					</td>
				</tr>
				<tr>
					<td colspan="2">
						Old Vale
					</td>
					<td colspan="2">
						$$$
					</td>
				</tr>
				<tr>
					<td colspan="2">
						New Vale
					</td>
					<td colspan="2">
						$$$
					</td>
				</tr>
				<tr>
					<td colspan="3">
						Grand Total
					</td>
					<td>
						$$$
					</td>
				</tr>
				</table>
			</div>

		</div>

	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function periodChange(period) {
			window.location.assign('reports_individual_13thmonthpay.php?empid=<?php Print $empid?>&per='+period);
		}
	</script>
</body>
</html>