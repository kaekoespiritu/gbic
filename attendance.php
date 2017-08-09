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
			<div class="col-md-5 col-md-offset-1">
			<button class="btn btn-success">Print attendance sheet for all sites</button>
			</div>
			<div class="col-md-4">
					Print only:
					<div class="btn-group">
						<select class="form-control">
							<option hidden>Site</option>

							<option value="Muralla">Muralla</option>
							<option value="ZooeyMain">Zooey Main</option>
							<option value="Teressa">Teressa</option>
							<option value="Camalig">Camalig</option>
							<option value="Marilao">Marilao</option>
							<option value="StaMaria">Sta. Maria</option>
							<option value="Batangas">Balagtas</option>
							<option value="LaUnion">La Union</option>
							<option value="Kaybiga">Kaybiga</option>
							<option value="MaxSteel">Max steel</option>
							<option value="ZooeyLawangBato">Zooey Lawang Bato</option>
							<option value="PedroGil">Pedro Gil</option>
							<option value="Batangas">Batangas</option>
							<option value="Tagaytay">Tagaytay</option>
							<option value="Carmona">Carmona</option>
							<option value="Paliparan">Paliparan</option>
							<option value="Laguna">Laguna</option>
						</select>
					</div>
					<button class="btn btn-success">Print site attendance</button>
				</div>
			</div>
			</div>

			<!-- TODO: Sites to have max characters of 12 -->
			<div class="row">
			<h3>Sites</h3>
			<div class="col-md-8 col-md-offset-2">
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Muralla<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Zooey Main<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Teressa<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Camalig<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Marilao<br><br>Employees: ##</a>
			</div>
			<div class="col-md-8 col-md-offset-2" style="margin-top:3px">
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Sta. Maria<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Balagtas<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">La Union<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Kaybiga<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Max steel<br><br>Employees: ##</a>
			</div>
			<div class="col-md-8 col-md-offset-2" style="margin-top:3px">
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Lawang Bato<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Pedro Gil<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Batangas<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Tagaytay<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Carmona<br><br>Employees: ##</a>
			</div>
			<div class="col-md-8 col-md-offset-2" style="margin-top:3px">
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Paliparan<br><br>Employees: ##</a>
			<a href="enterattendance.php" class="btn btn-primary btn-lg">Laguna<br><br>Employees: ##</a>
			</div>
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
