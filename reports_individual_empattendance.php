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
						<li><a href='reports_individual_attendance.php?type=Attendance&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Attendance</a></li>
						<li>Individual Weekly Attendance Report for [NAME], [POSITION] at [SITE]</li>
					</ol>
				</div>
			</div>

			<div class="form-inline">
				<h4>Select Period for Attendance viewing</h4>
				<select class="form-control">
						<option selected>Select date period</option>
						<option>[DATE]</option>
						<option>[DATE]</option>
						<option>[DATE]</option>
					</select>
			</div>
		</div>

		<button class="btn btn-default pull-down">
			Print Attendance
		</button>

		<div class="pull-down col-md-12 overflow">
			<table class="table table-bordered pull-down">
				<tr>
					<td colspan="49">
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
					<td colspan="7">
						Sunday
					</td>
					<td colspan="7">
						Monday
					</td>
					<td colspan="7">
						Tuesday
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