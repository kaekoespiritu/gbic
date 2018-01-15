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

		<h3 class="pull-down">Overall Contribution Report for employees at [SITE]</h3>
		
		<!--TODO: Make accordion style here to view WEEKLY/MONTHLY/YEARLY-->
		<table class="table table-bordered pull-down">
			<button class="btn btn-default pulldown">
				Print Weekly
			</button>
			<tr>
				<td colspan="11">
					Weekly Expenses
				</td>	
			</tr>
			<tr>
				<td rowspan="2">
					Name
				</td>
				<td rowspan="2">
					Position
				</td>
				<td rowspan="2">
					Site
				</td>
				<td rowspan="2">
					Salary
				</td>
				<td colspan="2">
					SSS
				</td>
				<td colspan="2">
					PagIBIG
				</td>
				<td colspan="2">
					PhilHealth
				</td>
				<td rowspan="2">
					Total
				</td>
			</tr>
			<tr>
				<td>
					Employee
				</td>
				<td>
					Employer
				</td>
				<td>
					Employee
				</td>
				<td>
					Employer
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
					[NAME]
				</td>
				<td>
					[POSITION]
				</td>
				<td>
					[SITE]
				</td>
				<td>
					[SALARY]
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
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
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					Grand Total
				</td>
				<td>
					$$$
				</td>
			</tr>
		</table>

		<table class="table table-bordered pull-down">
			<button class="btn btn-default pulldown">
				Print Monthly
			</button>
			<tr>
				<td colspan="11">
					Monthly Expenses
				</td>	
			</tr>
			<tr>
				<td rowspan="2">
					Name
				</td>
				<td rowspan="2">
					Position
				</td>
				<td rowspan="2">
					Site
				</td>
				<td rowspan="2">
					Salary
				</td>
				<td colspan="2">
					SSS
				</td>
				<td colspan="2">
					PagIBIG
				</td>
				<td colspan="2">
					PhilHealth
				</td>
				<td rowspan="2">
					Total
				</td>
			</tr>
			<tr>
				<td>
					Employee
				</td>
				<td>
					Employer
				</td>
				<td>
					Employee
				</td>
				<td>
					Employer
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
					[NAME]
				</td>
				<td>
					[POSITION]
				</td>
				<td>
					[SITE]
				</td>
				<td>
					[SALARY]
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
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
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					Grand Total
				</td>
				<td>
					$$$
				</td>
			</tr>
		</table>

		<table class="table table-bordered pull-down">
			<button class="btn btn-default pulldown">
				Print Yearly
			</button>
			<tr>
				<td colspan="11">
					Yearly Expenses
				</td>	
			</tr>
			<tr>
				<td rowspan="2">
					Name
				</td>
				<td rowspan="2">
					Position
				</td>
				<td rowspan="2">
					Site
				</td>
				<td rowspan="2">
					Salary
				</td>
				<td colspan="2">
					SSS
				</td>
				<td colspan="2">
					PagIBIG
				</td>
				<td colspan="2">
					PhilHealth
				</td>
				<td rowspan="2">
					Total
				</td>
			</tr>
			<tr>
				<td>
					Employee
				</td>
				<td>
					Employer
				</td>
				<td>
					Employee
				</td>
				<td>
					Employer
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
					[NAME]
				</td>
				<td>
					[POSITION]
				</td>
				<td>
					[SITE]
				</td>
				<td>
					[SALARY]
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
				<td>
					$$$
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
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				<td>
					Grand Total
				</td>
				<td>
					$$$
				</td>
			</tr>
		</table>

	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>