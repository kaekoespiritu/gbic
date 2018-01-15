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

		<h3 class="pull-down">Weekly Attendance Report for [NAME], [POSITION] at [SITE]</h3>

		<div class="pull-down">
			<button class="btn btn-default">
				Print Attendance
			</button>
			<table class="table table-bordered pull-down">
				<tr>
					<td colspan="28">
						Weekly Time Record of Employee
					</td>
				</tr>
				<tr>
					<td colspan="7">
						Wednesday
					</td>
					<td colspan="7">
						Thursday
					</td>
					<td colspan="7">
						Friday
					</td>
					<td colspan="7">
						Saturday
					</td>
				</tr>
				<tr>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
					<td colspan="4">
						REGULAR DAY
					</td>
					<td colspan="2">
						OVERTIME
					</td>
					<td rowspan="3">
						REMARKS
					</td>
				</tr>
				<tr>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
					<td colspan="2">
						AM
					</td>
					<td colspan="2">
						PM
					</td>
					<td colspan="2">
						OT Hours
					</td>
				</tr>
				<tr>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
					<td>
						In
					</td>
					<td>
						Out
					</td>
				</tr>
				<tr>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						[REMARKS]
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						[REMARKS]
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						[REMARKS]
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						#
					</td>
					<td>
						[REMARKS]
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