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
						<li><a href='reports_individual_loans.php?type=Loans&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Loans</a></li>
						<li>Individual Old Vale Report for [NAME], [POSITION] at [SITE]</li>
					</ol>
				</div>

				<div class="form-inline">
					<h4>Select view</h4>
					<select onchange="periodChange(this.value)" class="form-control">
						<?php 
							if($period == "week")
								Print "<option value='week' selected>Weekly</option>";
							else
								Print "<option value='week'>Weekly</option>";
							if($period == "month")
								Print "<option value='month'selected>Monthly</option>";
							else
								Print "<option value='month'>Monthly</option>";
							if($period == "year")
								Print "<option value='year' selected>Yearly</option>";
							else
								Print "<option value='year'>Yearly</option>";
						?>
					</select>
					<h4>Select period</h4>
					<select class="form-control">
						<option>Sample date</option>
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="pull-down">
				<button class="btn btn-default">
					Print Weekly
				</button>
				<table class="table table-bordered pull-down">
					<tr>
						<td colspan="4">
							Date: [START] to [END]
						</td>
					</tr>
					<tr>
						<td>
							Date
						</td>
						<td>
							Loaned Amount
						</td>
						<td>
							Balance to Pay
						</td>
						<td>
							Approved By:
						</td>
					</tr>
					<tr>
						<td>
							[DATE]
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
						<td>
							[APPROVED BY]
						</td>
					</tr>
				</table>
				</div>
			</div>

			<div class="col-md-4">
				<div class="pull-down">
				<button class="btn btn-default">
					Print Monthly
				</button>
				<table class="table table-bordered pull-down">
					<tr>
						<td colspan="4">
							Date: [START] to [END]
						</td>
					</tr>
					<tr>
						<td>
							Date
						</td>
						<td>
							Loaned Amount
						</td>
						<td>
							Balance to Pay
						</td>
						<td>
							Approved By:
						</td>
					</tr>
					<tr>
						<td>
							[DATE]
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
						<td>
							[APPROVED BY]
						</td>
					</tr>
				</table>
				</div>
			</div>

			<div class="col-md-4">
				<div class="pull-down">
				<button class="btn btn-default">
					Print Yearly
				</button>
				<table class="table table-bordered pull-down">
					<tr>
						<td colspan="4">
							Date: [START] to [END]
						</td>
					</tr>
					<tr>
						<td>
							Date
						</td>
						<td>
							Loaned Amount
						</td>
						<td>
							Balance to Pay
						</td>
						<td>
							Approved By:
						</td>
					</tr>
					<tr>
						<td>
							[DATE]
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
						<td>
							[APPROVED BY]
						</td>
					</tr>
				</table>
				</div>
			</div>
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