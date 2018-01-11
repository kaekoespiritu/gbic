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

		<h3 class="pull-down">Overall PagIBIG Contribution Report for employees at [SITE]</h3>

		<div class="col-md-4">
			<button class="btn btn-default pull-down">
				Print Weekly
			</button>
			<table class="table table-bordered pull-down">
				<tr>
					<td colspan="6">
						Weekly PagIBIG Contribution of employees
					</td>
				</tr>
				<tr>
					<td>
						Week
					</td>
					<td>
						Name
					</td>
					<td>
						Position
					</td>
					<td>
						Site
					</td>
					<td>
						Employee
					</td>
					<td>
						Employer
					</td>
				</tr>
				<tr>
					<td>
						Dec. 1 - 7
					</td>
					<td>
						[NAME]
					</td>
					<td>
						[POSITION]
					</td>
					<td>
						[SITE]
					</td>
					<td>
						$$$
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						Total
					</td>
					<td>
						$$$
					</td>
				</tr>
			</table>
		</div>

		<div class="col-md-4">
			<button class="btn btn-default pull-down">
				Print Monthly
			</button>
			<table class="table table-bordered pull-down">
				<tr>
					<td colspan="6">
						Monthly PagIBIG Contribution of employees
					</td>
				</tr>
				<tr>
					<td>
						Month
					</td>
					<td>
						Name
					</td>
					<td>
						Position
					</td>
					<td>
						Site
					</td>
					<td>
						Employee
					</td>
					<td>
						Employer
					</td>
				</tr>
				<tr>
					<td>
						December
					</td>
					<td>
						[NAME]
					</td>
					<td>
						[POSITION]
					</td>
					<td>
						[SITE]
					</td>
					<td>
						$$$
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						Total
					</td>
					<td>
						$$$
					</td>
				</tr>
			</table>
		</div>

		<div class="col-md-4">
			<button class="btn btn-default pull-down">
				Print Yearly
			</button>
			<table class="table table-bordered pull-down">
				<tr>
					<td colspan="6">
						Yearly PagIBIG Contribution of employees
					</td>
				</tr>
				<tr>
					<td>
						Year
					</td>
					<td>
						Name
					</td>
					<td>
						Position
					</td>
					<td>
						Site
					</td>
					<td>
						Employee
					</td>
					<td>
						Employer
					</td>
				</tr>
				<tr>
					<td>
						2017
					</td>
					<td>
						[NAME]
					</td>
					<td>
						[POSITION]
					</td>
					<td>
						[SITE]
					</td>
					<td>
						$$$
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						Total
					</td>
					<td>
						$$$
					</td>
				</tr>
			</table>
		</div>

	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>