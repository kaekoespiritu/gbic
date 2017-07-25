<!DOCTYPE html>
<?php
include('directives/db.php');
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: Quicksand;">
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
-->
<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<!-- EMPLOYEE DETAILS -->
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="row">
				<div class="col-md-6">
					<h2 style="font-family: QuicksandMed;"><br>Miguelito Joselito Dela Cruz</h2>
				</div>
				<div class="col-md-6 text-right" style="font-family: QuickSand">
				<h3><b style="font-family: QuickSandMed">Site:</b> Muralla</h3>
				<h3><b style="font-family: QuickSandMed">Position:</b> Mason</h3>
				</div>
			</div>
			<hr>

			<div class="row">
				<div class="col-md-8 text-left" style="word-break: keep-all">
					<h4><b style="font-family: QuickSandMed">Employee ID:</b> 2014-1352845</h4>
					<h4><b style="font-family: QuickSandMed">Date of hire:</b> July 14, 2014 </h4>
					<h4><b style="font-family: QuickSandMed">Address:</b> 97 Waco St. Greenheights Village, Quezon City</h4>
					<h4><b style="font-family: QuickSandMed">Contact Number:</b> 09123456789</h4>
				</div>
				<div class="col-md-4 text-right">
					<h4>Has PhilHealth documents</h4>
					<h4>Has PagIBIG documents</h4>
					<h4>Has SSS documents</h4>
				</div>
			</div>
			<hr>
		</div>
	</div>

	<!-- PAYROLL PAGE -->
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
		<form>
			<div class="row">
				<h3>Attendance tracking</h3>
				Time In <input type="text" class="timein">
				Time Out <input type="text" class="timeout">
				<button class="btn btn-primary">Absent</button><br><br>
				Time spent: <input type="text" placeholder="Hours rendered" disabled="disabled">
			</div>
			<div class="row">
				<h3>Deductions</h3>
				<table class="col-md-8 col-md-offset-2">
				<tr>
					<td>SSS</td>
					<td><input type="text"></td>
					<td>Vale</td>
					<td><input type="text"></td>
				</tr>
				<tr>
					<td>PagIBIG</td>
					<td><input type="text"></td>
					<td>Taxes</td>
					<td><input type="text"></td>
				</tr>
				<tr></tr>
				</table>
			</div>
</form>
			<button class="btn btn-primary pull-down">Next</button>
		</div>
	</div>

</div>


<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("payroll").setAttribute("class", "active");
</script>
<script rel="javascript" src="js/dropdown.js"></script>
<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
<script>
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
});
</script>

</div>
</body>
</html>
