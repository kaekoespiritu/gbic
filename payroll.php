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
<body style="font-family: QuicksandBold;">
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
-->
<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>


	<div class="jumbotron pull-down">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 pull-down">
			<h2 class="text-center">
				Only authorized personnel may access the payroll.<br>
				Please enter the system password to continue.</h2>
				</div>
				<div class="col-md-4 col-md-offset-4 pull-down">
				<input type="password" class="form-control" id="payrollpass" placeholder="Password">
				<button class="btn btn-primary pull-down">Submit</button>
				</div>
			</div>
		</div>

		<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
			document.getElementById("payroll").setAttribute("class", "active");
		</script>


	</div>
</body>
</html>
