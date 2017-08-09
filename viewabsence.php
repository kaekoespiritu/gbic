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
					<li><a href="applications.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Absence Applications</a></li>
					<li class="active">Check details</li>
				</ol>
			<h2 class="text-left">Miguelito Joselito Dela Cruz</h2>
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
			<br>
			<table class="table table-bordered table-condensed" style="background-color:white;">
				<tr>
					<td>Wednesday, Aug. 2</td>
					<td>Thursday, Aug. 3</td>
					<td>Friday, Aug. 4</td>
				</tr>
				<tr>
					<td>ABSENT</td>
					<td>ABSENT</td>
					<td>ABSENT</td>
				</tr>
			</table>
			<div class="panel">
				<div class="well well-sm"><h3>Total days absent: 3</h3></div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<form>
							<div class="row">
							<div class="col-md-4 text-left">
								<h3>Reason for absence</h3>
								<textarea class="form-control" rows="2"></textarea>
								<br><br>
								</div>
								<div class="col-md-6">
								<h3>Actions</h3>
								<a class="btn btn-success">Approve absence</a> 
								<a class="btn btn-danger" disabled="disabled">Employee went AWOL</a>
								</div>
								<div class="col-md-2 pull-down">
								<a class="btn btn-primary pull-down">Save changes</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>	
	</div>
</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
</script>
<script rel="javascript" src="js/dropdown.js"></script>


</div>
</body>
</html>
