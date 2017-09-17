<!DOCTYPE html>
<?php
include('directives/session.php');
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

	<div class="row pull-down">
	<div class="col-md-10 col-md-offset-1">
			<ol class="breadcrumb text-left">
				<li><a href="payroll_position.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Position</a></li>
				<li class="active">Employees at SITENAME</li>
				<button class="btn btn-success pull-right" onclick="saveChanges()">Save changes</button>
			</ol>
			</div>
		<div class="col-md-10 col-md-offset-1">
			<h2 class="text-left">Miguelito Joselito Dela Cruz</h2>
			<hr>
			<div class="row">
				<div class="col-md-8 text-left" style="word-break: keep-all">
					<h4><b style="font-family: QuickSandMed">Employee ID:</b> 2014-1352845</h4>
					<h4><b style="font-family: QuickSandMed">Position:</b> Mason </h4>
					<h4><b style="font-family: QuickSandMed">Address:</b> 97 Waco St. Greenheights Village, Quezon City</h4>
					<h4><b style="font-family: QuickSandMed">Contact Number:</b> 09123456789</h4>
				</div>
				<div class="col-md-4 text-right">
					<h4>Has PhilHealth documents</h4>
					<h4>Has PagIBIG documents</h4>
					<h4>Has SSS documents</h4>
				</div>
			</div>
			<br>
			<table class="table table-bordered table-condensed" style="background-color:white;">
				<tr>
					<td colspan="2">Wednesday</td>
					<td colspan="2">Thursday</td>
					<td colspan="2">Friday</td>
					<td colspan="2">Saturday</td>
					<td colspan="2">Sunday</td>
					<td colspan="2">Monday</td>
					<td colspan="2">Tuesday</td>
				</tr>
				<tr>
					<td>Time In: <div class="well well-sm">8:00AM</div></td>
					<td>Time Out: <div class="well well-sm">5:00PM</div></td>
					<td>Time In: <div class="well well-sm">8:00AM</div></td>
					<td>Time Out: <div class="well well-sm">5:00PM</div></td>
					<td>Time In: <div class="well well-sm">8:00AM</div></td>
					<td>Time Out: <div class="well well-sm">5:00PM</div></td>
					<td>Time In: <div class="well well-sm">8:00AM</div></td>
					<td>Time Out: <div class="well well-sm">5:00PM</div></td>
					<td>Time In: <div class="well well-sm">8:00AM</div></td>
					<td>Time Out: <div class="well well-sm">5:00PM</div></td>
					<td>Time In: <div class="well well-sm">8:00AM</div></td>
					<td>Time Out: <div class="well well-sm">5:00PM</div></td>
					<td>Time In: <div class="well well-sm">8:00AM</div></td>
					<td>Time Out: <div class="well well-sm">5:00PM</div></td>
				</tr>
			</table>
			<div class="panel">
				<table class="table table-bordered table-responsive">
					<tr>
						<td>
							<h4>Total hours rendered: 54</h4>
						</td>
						<td>
							<h4>Total overtime: 0</h4>
						</td>
						<td>
							<h4>Total night differential: 0</h4>
						</td>
					</tr>
				</table>
				<div class="row">
					<form>
						<div class="col-md-3 col-md-offset-2">
							<h3 class="text-left">Loans</h3>
							<table>
								<tr>
									<td>SSS</td>
									<td><input type="text" for="sss" class="form-control"></td>
								</tr>
								<tr>
									<td>Pag-IBIG</td>
									<td><input type="text" for="pagibig" class="form-control"></td>
								</tr>
							</table>
						</div>
						<div class="col-md-3">
							<h3 class="text-left">Deductions</h3>
							<table>
								<tr>
									<td>Tax</td>
									<td><input type="text" for="tax" class="form-control"></td>
								</tr>
								<tr>
									<td>Tools</td>
									<td><input type="text" for="tools" class="form-control"></td>
								</tr>
							</table>
						</div>
						<div class="col-md-3">
							<h3 class="text-left">Vale</h3>
							<table>
								<tr>
									<td colspan="2">SAMPLE TEXT HERE...</td>
								</tr>
								<tr>
									<td><button class="btn btn-success">Add new vale</button></td>
									<td><button class="btn btn-danger">Deduct old vale</button></td>
								</tr>
							</table>
						</div>
					</form>
				</div>
				<button class="btn btn-primary pull-down">Next</button>
			</div>
		</div>	
	</div>
</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");
</script>


</div>
</body>
</html>
