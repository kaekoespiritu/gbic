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

		<h3 class="pull-down">PagIBIG Contribution Report for Name, Position at Site</h3>

		<div class="pull-down">
			<button class="btn btn-default">
				Print Payroll
			</button>
			<table class="table table-bordered pull-down">
				<tr>
					<td colspan="3">
						Name, Position at Site (with/without requirements)
					</td>
				</tr>
				<tr>
					<td rowspan="2">
						Week
					</td>
					<td colspan="2">
						Pag-IBIG
					</td>
				</tr>
				<tr>
					<td>
						Employee
					</td>
					<td>
						Employer
					</td>
				</tr>
				<tr>
					<td>
						Dec. 1-7
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
						Total
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