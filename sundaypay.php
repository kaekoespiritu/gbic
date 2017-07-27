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

	<div class="container pull-down">
			<div class="col-md-4 col-md-offset-1 pull-down">
				<div class="input-group">
					<input type="text" class="form-control">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
					</span>
				</div>
			</div>
			<!-- FILTER EMPLOYEE BY POSITION -->
			<div class="col-md-5 col-md-pull-1 text-right pull-down">
				Filter by:
				<div class="btn-group">
					<select class="form-control">
						<option hidden>Position</option>
						<option value="Foreman">Foreman</option>
						<option value="Leadman">Leadman</option>
						<option value="TimeKeeper">Time Keeper</option>
						<option value="Operator">Operator</option>
						<option value="Carpenter">Carpenter</option>
						<option value="Mason">Mason</option>
						<option value="Labor">Labor</option>
						<option value="Welder">Welder</option>
						<option value="Painter">Painter</option>
						<option value="Electrician">Electrician</option>
						<option value="Plumber">Plumber</option>
						<option value="OfficeStaff">Office Staff</option>
					</select>
				</div>
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
			</div>
			<div class="col-md-1 col-md-pull-1 text-right pull-down">
				<a class="btn btn-primary" href="payroll.php">Save changes</a>
			</div>
		</div>
	</div>

	<div class="row pull-down">
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-bordered table-condensed" style="background-color:white;">
				<tr>
					<td>ID</td>
					<td>Name</td>
					<td>Position</td>
					<td>Site</td>
					<td>Sunday Attendance</td>
				</tr>
				<tr>
					<td>1</td>
					<td>Trial Employee entry</td>
					<td>Position</td>
					<td>Placeholder</td>
					<td><input type="checkbox"></td>
				</tr>
				<tr>
					<td>1</td>
					<td>Trial Employee entry</td>
					<td>Position</td>
					<td>Placeholder</td>
					<td><input type="checkbox"></td>
				</tr>
			</table>
		</div>	
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("payroll").setAttribute("class", "active");
	</script>
	<script rel="javascript" src="js/dropdown.js"></script>


</div>
</body>
</html>
