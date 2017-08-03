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

	<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
			<div class="row pull-down">
			<h2>Daily attendance log<br><br></h2>
			<button class="btn btn-default">Print attendance sheet for all sites</button>
			</div>

			<div class="row">
			<h3>Sites</h3>
			<div class=" col-md-8 col-md-offset-2">
			<table class="table">
				<tr>
					<td>
						<a href="enterattendance.php" class="btn btn-primary"><h4>Site name</h4>
						Employees: ##	</a>
					</td>
					<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
					<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
										<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
										<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="" class="btn btn-primary"><h4>Site name</h4>
						Employees: ##	</a>
					</td>
					<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
					<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
										<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
										<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="" class="btn btn-primary"><h4>Site name</h4>
						Employees: ##	</a>
					</td>
					<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
					<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
										<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
										<td>
						<a href="enterattendance.php" class="btn btn-primary">
						<h4>Site name</h4>
						Employees: ##	
					</a>
					</td>
				</tr>
			</table>
			</div>
			</div>

</div>


<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
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
