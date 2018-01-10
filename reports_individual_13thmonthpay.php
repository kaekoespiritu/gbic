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

		<div class="col-md-4">
			<table class="table table-bordered pull-down">
			<tr>
				<td colspan="2">
					Name, Position at Site
				</td>
			</tr>
			<tr>
				<td>
					Week
				</td>
				<td>
					13th Month
				</td>
			</tr>
			<tr>
				<td>
					Dec. 1 - 7
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
			</tr>
			</table>
		</div>

		<div class="col-md-4">
			<table class="table table-bordered pull-down">
			<tr>
				<td colspan="2">
					Name, Position at Site
				</td>
			</tr>
			<tr>
				<td>
					Month
				</td>
				<td>
					13th Month
				</td>
			</tr>
			<tr>
				<td>
					December
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
			</tr>
			</table>
		</div>

		<div class="col-md-4">
			<table class="table table-bordered pull-down">
			<tr>
				<td colspan="2">
					Name, Position at Site
				</td>
			</tr>
			<tr>
				<td>
					Year
				</td>
				<td>
					13th Month
				</td>
			</tr>
			<tr>
				<td>
					2017
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