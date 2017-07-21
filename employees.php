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
	<div class="container-fluid">

		<?php
		require_once('directives/modals/addEmployee.php');
		require_once('directives/modals/editEmployee.php');
		?>
		
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
		<div class="container">
			<div class="row">
				<div class="col-md-5 col-md-offset-1 pull-down">
					<div class="input-group">
						<input type="text" class="form-control">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
						</span>
					</div>
				</div>
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-4 col-md-pull-1 pull-down text-right">
<<<<<<< HEAD
					Filter employees by:
					<div class="btn-group">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Position <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="#">Foreman</a></li>
							<li><a href="#">Leadman</a></li>
							<li><a href="#">Time Keeper</a></li>
							<li><a href="#">Operator</a></li>
							<li><a href="#">Carpenter</a></li>
							<li><a href="#">Mason</a></li>
							<li><a href="#">Labor</a></li>
							<li><a href="#">Welder</a></li>
							<li><a href="#">Painter</a></li>
							<li><a href="#">Electrician</a></li>
							<li><a href="#">Plumber</a></li>
							<li><a href="#">Office Staff</a></li>
						</ul>
					</div>
					<div class="btn-group">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Site <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="#">Muralla</a></li>
							<li><a href="#">Zooey Main</a></li>
							<li><a href="#">Teressa </a></li>
							<li><a href="#">Camalig</a></li>
							<li><a href="#">Marilao</a></li>
							<li><a href="#">Sta. Maria</a></li>
							<li><a href="#">Balagtas</a></li>
							<li><a href="#">La Union</a></li>
							<li><a href="#">Kaybiga</a></li>
							<li><a href="#">Max steel</a></li>
							<li><a href="#">Zooey Lawang Bato</a></li>
							<li><a href="#">Pedro Gil</a></li>
							<li><a href="#">Batangas</a></li>
							<li><a href="#">Tagaytay</a></li>
							<li><a href="#">Carmona</a></li>
							<li><a href="#">Paliparan</a></li>
							<li><a href="#">Laguna</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-2 col-md-pull-1 pull-down pull-left">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee">Add new Employee</button>
				</div>
			</div>
		</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row jumbotron">
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-bordered" style="background-color:white;">
					<tr>
						<td>Employee ID</td>
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
						<td>
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#editEmployee" id="editEmployee">Edit details</button>
							<a type="button" class="btn btn-default" href="viewemployee.php">View details</a>
						</td>
					</tr>
					<tr>
						<td>1</td>
						<td>Trial Employee entry</td>
						<td>Position</td>
						<td>Placeholder</td>
						<td>
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#editEmployee" id="editEmployee">Edit details</button>
							<a type="button" class="btn btn-default" href="viewemployee.php">View details</a>
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
		document.getElementById("employees").setAttribute("class", "active");
	</script>

</body>
</html>

