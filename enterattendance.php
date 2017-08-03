<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
?>
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

		<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li><a href="attendance.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a></li>
					<li class="active">Employee attendance</li>
					<a href="#" class="btn btn-primary" style="float:right;">View site attendance record</a>
				</ol>
			</div>
			<div class="col-md-5 col-md-offset-1 text-left">
				<h3>Miguelito Joselito Dela Cruz</h3>
			</div>
			<div class="col-md-5">
				<h3>Site: Somewhere | 
				Position: Something</h3>
			</div>
		</div>

		<div class="col-md-10 col-md-offset-1 pull-down">
		<form>
			<h3>Time in: <input type="text" class="timein" name="timein">
			Time out: <input type="text" class="timeout" name="timeout"><br><br>
			Remarks: <input type="text"></h3>
			</form>
		</div>

		<div class="col-md-10 col-md-offset-1 pull-down">
		<button class="btn btn-primary">Next employee</button>
		</div>


	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script rel="javascript" src="js/dropdown.js"></script>
	<script>
		document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
		$(document).ready(function(){
			$('input.timein').timepicker({
				timeFormat: 'h:m p',
				dynamic: false,
				scrollbar: false,
				dropdown: true
			});
			$('input.timeout').timepicker({
				timeFormat: 'h:m p',
				dynamic: false,
				scrollbar: false,
				dropdown: true
			});
		});
	</script>
</body>
</html>
<!--
      changeMonth: true,
      changeYear: true