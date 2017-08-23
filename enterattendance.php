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
	<link rel="stylesheet" href="js/timepicker/jquery.timepicker.min.css">

</head>
<body style="font-family: Quicksand">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>
		<?php
		require_once('directives/modals/siteAttendance.php');

		?>

		<div class="row" style="z-index: 101">
			<!-- BREAD CRUMBS -->
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
				<!-- TODO: If Sunday/Holiday attendance is selected, change link and name -->
					<li><a href="attendance.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a></li>
					<li class="active">Employee attendance at [SITE NAME]</li>
					<a data-toggle="modal" data-target="#siteAttendance" class="btn btn-primary" style="float:right;">View site attendance record</a>
				</ol>
			</div>
			<!-- EMPLOYEE INFORMATION -->
			<div class="col-md-10 col-md-offset-1">
				<div class="row">
					<h2 class="text-center">Miguelito Joselito Dela Cruz</h2>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6 text-left" style="word-break: keep-all">
						<h4><b style="font-family: QuickSandMed">Employee ID:</b> 2014-1352845</h4>
						<h4><b style="font-family: QuickSandMed">Date of hire:</b> July 14, 2014 </h4>
					</div>
					<div class="col-md-6 text-left">
						<h4><b style="font-family: QuickSandMed">Address:</b> 97 Waco St. Greenheights Village, Quezon City</h4>
						<h4><b style="font-family: QuickSandMed">Contact Number:</b> 09123456789</h4>
					</div>
				</div>
			</div>

		</div>



		<!-- ATTENDANCE FORM -->
		<div class="col-md-10 col-md-offset-1 pull-down">
		<hr>
			<h4 class="pull-down">Attendace form</h4>
			<form>
				<button class="btn btn-success">Morning Shift (8AM-5PM)</button>
				<button class="btn btn-success">Afternoon Shift (8AM-5PM)</button>
				<button class="btn btn-success">Night Shift (8AM-5PM)</button>
				<button class="btn btn-danger">Absent</button>
				<div class="col-md-6 text-right">
				<h4>Time in: <input type="text" id="timeIn" class="timein timepicker" name="timein"><br><br>
					Time out: <input type="text" id="timeOut" class="timeout timepicker" name="timeout">
					<br><br>
					Remarks: <input type="text"></h4>
					</div>
					<div class="col-md-6 text-left">
					<h4>Working hours: <input type="text" id="workHours" placeholder="--" disabled><br><br>
					Overtime: <input type="text" id="overTime" placeholder="--" disabled><br><br>
					Undertime: <input type="text" id="underTime" placeholder="--" disabled></h4>
					</div>
				</form>
			</div>

			<div class="col-md-10 col-md-offset-1 pull-down">
				<button class="btn btn-primary">Next employee</button>
			</div>
		</div>

		<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="js/timepicker/jquery.timepicker.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script rel="javascript" src="js/dropdown.js"></script>
		<script>
			document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");

			$(document).ready(function(){
				$('input.timein').timepicker({
					timeFormat: 'hh:mm p',
					dynamic: false,
					scrollbar: false,
					dropdown: false
				});
				$('input.timeout').timepicker({
					timeFormat: 'hh:mm p',
					dynamic: false,
					scrollbar: false,
					dropdown: false
					
				});	
/*					$("#timeIn").keypress(function(){
							var timein = $('#timeIn').val();
							if($('#timeOut').val()  && $('#timeIn').val())
							{
								var timeout = $('#timeOut').val(); 
								alert(timein);
								alert(timeout);
							}
					});		
					$("#timeOut").keypress(function(){
							var timeout = $('#timeOut').val();
							if($('#timeOut').val() && $('#timeIn').val())
							{
								var timein = $('#timeIn').val(); 
								alert(timein);
								alert(timeout);
							}
					});		*/

					if($("#timeIn").val() && $("timeOut").val()){
						var timein = $('#timeIn').val();
						var timeout = $('#timeOut').val();
						alert("Time in: "+timein + " | Time out: " + timeout);
					}
			
			});
		</script>
	</body>
	</html>











