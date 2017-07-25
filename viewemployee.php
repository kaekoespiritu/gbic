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
<body style="font-family: QuicksandMed;">
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
		<div class="row text-center pull-down">
			<ol class="breadcrumb text-left">
				<li><a href="employees.php">Employees</a></li>
				<li class="active">View employee details</li>
			</ol>

			<div class="row">
			<div class="col-md-6">
			<h2>Miguel Joselito Dela Cruz</h2>
			</div>
			<div class="col-md-6">
			<a href="#" class="btn btn-primary">View historical payslip</a>
			</div>
			</div>
		</div>

		<div class="row pull-down">
			<div class="horizontal">
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
						</div> 
						<div class="row">
							<div class="col-md-3">
								<label>Contact number</label>
							</div>
							<div class="col-md-3 text-left">
								<span>09123456789</span>
							</div>
						</div> 
						<div class="row">
							<div class="col-md-3">
								<label for="contact">Civil Status</label>
							</div>
							<div class="col-md-3 text-left">
								<span>Single</span>
							</div>
						</div> 
						<div class="row">
							<div class="col-md-3">
								<label>Date of Birth</label>
							</div>
							<div class="col-md-3 text-left">
								<span>Aug. 12, 1898</span>
							</div>
							</div> 
						<div class="row">
							<div class="col-md-3">
								<label for="contact">Date of Hire</label>
							</div>
							<div class="col-md-3 text-left">
								<span> July 14, 2014 </span>
							</div>
						</div>
						<div class="row">
						<h4 class="modal-title">Loans</h4><hr>
						</div>
						<div class="row">
							<div class="col-md-3">
								<label for="contact">SSS</label>
							</div>
							<div class="col-md-3 text-left">
								<span>1,500php</span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<label for="contact">Pag-IBIG</label>
							</div>
							<div class="col-md-3 text-left">
								<span>2,000php</span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<label for="contact">Vale</label>
							</div>
							<div class="col-md-3 text-left">
								<span>250php</span>
							</div>
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
						</div> 

						<div class="row">
							<div class="col-md-6">
								<label for="position" class="text-right">Site</label>
							</div>
							<div class="col-md-6">
								<span>Muralla</span>
							</div>
						</div> 


						<div class="row">
							<div class="col-md-6">
								<label for="rate">Rate per day</label>
							</div>
							<div class="col-md-6">
								<span>399</span>
							</div>
						</div> 
						<div class="row">
							<div class="col-md-6">
								<label for="allowance">Allowance</label>
							</div>
							<div class="col-md-6">
								<span>100</span>
							</div>
						</div>
						<div class="row">
							<h4 class="modal-title"> Contributions</h4><hr>

							<div class="row">
								<div class="row">
									<div class="col-md-6">
										<label for="pagibig">SSS *</label>
									</div>
									<div class="col-md-6">
										<span>299</span>
									</div>
								</div> 
								<div class="row">
									<div class="col-md-6">
										<label for="pagibig">PhilHealth *</label>
									</div>
									<div class="col-md-6">
										<span>299</span>
									</div>
								</div> 
								<div class="row">
									<div class="col-md-6">
										<label for="pagibig">Pag-IBIG</label>
									</div>
									<div class="col-md-6">
										<span>299</span>
									</div>
								</div> 
								<div class="col-md-8 col-md-offset-2 text-center pull-down well well-sm">
									* SSS and PhilHealth contributions are automatically computed based on employee's base pay.
								</div>
							</div>
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
		document.getElementById("employees").setAttribute("class", "active");
	</script>
	<script rel="javascript" src="js/dropdown.js"></script>
	
</div>
</body>
</html>
