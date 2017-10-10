<!DOCTYPE html>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">

		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- Modal pop up s-->
		<?php
		require_once("directives/modals/addLoan.php");
		?>

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li>
						<a href="loans_view.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Viewing loans</a>
					</li>
					<li class="active">Loan History</li>
				</ol>
			</div>
		</div>

		<div class="row col-md-10 col-md-offset-1">
			<div class="text-left">
				<h3>Name of Employee here</h3>
				<h4>Position at Site</h4>
			</div>


			<table class="table table-bordered pull-down">
				<tr>
					<td>Date</td>
					<td>Loan Amount</td>
					<td>Amount Paid</td>
				</tr>
				<tr>
					<td>DATE DATE</td>
					<td>AMOUNT AMOUNT</td>
					<td>AMOUNT AMOUNT</td>
				</tr>
			</table>
		</div>

	</div>
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		// Setting active color of menu to Employees
		document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>
