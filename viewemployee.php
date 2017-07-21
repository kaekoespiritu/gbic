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
<body>
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
-->
<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<div class="col-md-10 col-md-offset-1">
		<div class="row text-left pull-down">
			<ol class="breadcrumb">
				<li><a href="employees.php">Employees</a></li>
				<li class="active">View employee details</li>
			</ol>

			<h2 class="text-center">Miguel Joselito Dela Cruz</h2>
		</div>

		<div class="row pull-down">
			<form class="horizontal">
				<div class="row">
					<div class="col-md-6">
						<h4 class="modal-title">Personal Information</h4><hr>
						<div class="row">
							<div class="col-md-3">
								<label>Address</label>
							</div>
							<div class="col-md-9 text-left">
								<span>97 Waco St. Greenheights Village, Quezon City</span>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-3">
								<label>Contact number</label>
							</div>
							<div class="col-md-3 text-left">
								<span>09123456789</span>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-3">
								<label for="contact">Civil Status</label>
							</div>
							<div class="col-md-3 text-left">
								<span>Single</span>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-3">
								<label>Age</label>
							</div>
							<div class="col-md-3 text-left">
								<span>29</span>
							</div>
							</div><br>
						<div class="row">
							<div class="col-md-3">
								<label for="contact">Date of Hire</label>
							</div>
							<div class="col-md-3 text-left">
								<span> July 14, 2014 </span>
							</div>
						</div>
						<br>
						<div class="row pull-down">
							<a href="#" class="btn btn-primary">View historical payslip</a>
						</div>
					</div>

					<div class="col-md-6">
						<h4 class="modal-title">Job details</h4><hr>
						<div class="row">
							<div class="col-md-6">
								<label for="position" class="text-right">Position</label>
							</div>
							<div class="col-md-6">
								<span>Mason</span>
							</div>
						</div><br>

						<div class="row">
							<div class="col-md-6">
								<label for="rate">Rate per day</label>
							</div>
							<div class="col-md-6">
								<span>399</span>
							</div>
						</div><br>
						<div class="row">
							<div class="col-md-6">
								<label for="allowance">Allowance</label>
							</div>
							<div class="col-md-6">
								<span>100</span>
							</div>
						</div>
						<div class="row">
							<h4 class="modal-title"><br>Contributions</h4><hr>

							<div class="row">
								<div class="row">
									<div class="col-md-6">
										<label for="pagibig">SSS *</label>
									</div>
									<div class="col-md-6">
										<span>299</span>
									</div>
								</div><br>
								<div class="row">
									<div class="col-md-6">
										<label for="pagibig">PhilHealth *</label>
									</div>
									<div class="col-md-6">
										<span>299</span>
									</div>
								</div><br>
								<div class="row">
									<div class="col-md-6">
										<label for="pagibig">Pag-IBIG</label>
									</div>
									<div class="col-md-6">
										<span>299</span>
									</div>
								</div><br>
								<div class="col-md-8 col-md-offset-2 text-center well well-sm">
									* SSS and PhilHealth contributions are automatically computed based on employee's base pay.
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>


	</div>


	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("employees").setAttribute("class", "active");
	</script>
</div>
</body>
</html>
