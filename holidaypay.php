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

	<div class="row">
		<form>
			<div class="col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-1 pull-down">
			<label for="holiday"><h2>What kind of holiday?</h2></label>
				<div class="form-group">
					<label class="radio-inline">
						<input type="radio" name="holidayregular" id="regular" value="regular"> Regular
					</label>
					<label class="radio-inline">
						<input type="radio" name="holidayspecial" id="special" value="special"> Special
					</label>
				</div>
			</div>
			<div class="col-md-5 col-lg-5 pull-down">
				<div class="form-group">
					<label for="holidayname"><h2>Name of the Holiday</h2></label>
					<input type="text" class="form-control" id="holidayname" placeholder="Ex. Independence Day">
				</div>
			</div>
		</form>
	</div>
</div>
<div class="col-md-4 col-lg-4 col-md-offset-1 col-lg-offset-1 pull-down">
	<div class="input-group">
		<input type="text" class="form-control">
		<span class="input-group-btn">
			<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
		</span>
	</div>
</div>
<div class="row">
<!-- FILTER EMPLOYEE BY POSITION -->
<div class="col-md-5 col-lg-5 col-md-pull-1 text-right pull-down">
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
<div class="col-md-1 col-lg-1 col-md-pull-1 text-right pull-down">
	<a class="btn btn-primary" href="enterattendance.php">Start input of attendance</a>
</div>
</div>

<div class="row pull-down">
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
				<table class="table table-bordered table-condensed" style="background-color:white;">
					<tr>
						<td>ID</td>
						<td>Name</td>
						<td>Position</td>
						<td>Site</td>
						<td>Actions</td>
					</tr>
					<tr>
						<td>1</td>
						<td>Trial Employee entry</td>
						<td>Position</td>
						<td>Placeholder</td>
						<td><label><input type="checkbox"> Present</label></td>
					</tr>
					<tr>
						<td>1</td>
						<td>Trial Employee entry</td>
						<td>Position</td>
						<td>Placeholder</td>
						<td><label><input type="checkbox"> Present</label></td>
					</tr>
				</table>
			</div>	
		</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
</script>


</div>
</body>
</html>
