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

		<h3 class="pull-down">Overall Loans Report for employees at [SITE]</h3>

		<div>
			<button class="btn btn-default pull-down">
				Print [PERIOD]
			</button>

			<table class="table table-bordered pull-down">
				<tr>
					<td colspan="3">
						Period: Weekly/Monthly/Yearly
					</td>
					<td colspan="5" rowspan="2">
						Loan Type Report
					</td>
					<td rowspan="3">
						Balance
					</td>
				</tr>
				<tr>
					<td colspan="3">
						Date: [START] to [END]
					</td>
				</tr>
				<tr>
					<td>
						Name
					</td>
					<td>
						Site
					</td>
					<td>
						Position
					</td>
					<td>
						Date
					</td>
					<td>
						SSS
					</td>
					<td>
						PagIBIG
					</td>
					<td>
						Old Vale
					</td>
					<td>
						New Vale
					</td>
				</tr>
				<tr>
					<td>
						[NAME]
					</td>
					<td>
						[SITE]
					</td>
					<td>
						[POSITION]
					</td>
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
						$$$
					</td>
					<td>
						$$$
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