<!DOCTYPE html>
<?php
include('session.php');
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

		<!-- ADD EMPLOYEE MODAL -->
		<div class="modal fade" id="addEmployee" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-7">
							<h4 class="modal-title text-right">Add employee</h4>
						</div>
						<div class="col-md-5">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<form class="horizontal">
							<div class="row">
								<div class="col-md-6">
									<h4 class="modal-title">Personal Information</h4><hr>
									<div class="row">
										<div class="col-md-3">
											<label for="fname">First name</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="fname">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="lname">Last name</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="lname">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="address">Address</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="address">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Contact number</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="contact">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<h4 class="modal-title">Job details</h4><hr>
									<div class="row">
										<div class="col-md-5">
											<label for="position" class="text-right">Position</label>
										</div>
										<div class="col-md-4">
											<div class="dropdown">
												<button class="btn btn-default dropdown-toggle pull-left" type="button" id="position" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
													Select a position
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
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
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-5">
											<label for="rate">Rate per day</label>
										</div>
										<div class="col-md-4">
											<input type="text" class="form-control" id="rate">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5">
											<label for="allowance">Allowance</label>
										</div>
										<div class="col-md-4">
											<input type="text" class="form-control" id="allowance">
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<h4 class="modal-title"><br>Contributions</h4><hr><br>

									<div class="row">
										<div class="col-md-1">
											<label for="pagibig">PagIBIG</label>
										</div>
										<div class="col-md-2">
											<input type="text" class="form-control" id="pagibig">
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary">Save</button>
					</div>
				</div>
			</div>
		</div>

		<!-- EDIT EMPLOYEE MODAL -->
		<div class="modal fade" id="editEmployee" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-7">
							<h4 class="modal-title text-right">Edit employee details</h4>
						</div>
						<div class="col-md-5">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<form class="horizontal">
							<div class="row">
								<div class="col-md-6">
									<h4 class="modal-title">Personal Information</h4><hr>
									<div class="row">
										<div class="col-md-3">
											<label for="fname">First name</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="fname">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="lname">Last name</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="lname">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="address">Address</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="address">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Contact number</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="contact">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Age</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="contact">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Civil Status</label>
										</div>
										<div class="col-md-9">
											<div class="btn-group" data-toggle="buttons">
												<label class="btn btn-primary active">
												<input type="checkbox" autocomplete="off" checked> Single
												</label>
												<label class="btn btn-primary">
													<input type="checkbox" autocomplete="off"> Married
												</label>
												<label class="btn btn-primary">
													<input type="checkbox" autocomplete="off"> Divorced
												</label>
												<label class="btn btn-primary">
													<input type="checkbox" autocomplete="off"> Separated
												</label>
												<label class="btn btn-primary">
													<input type="checkbox" autocomplete="off"> Widowed
												</label>
											</div>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Date of Hire</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="contact">
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<h4 class="modal-title">Job details</h4><hr>
									<div class="row">
										<div class="col-md-5">
											<label for="position" class="text-right">Position</label>
										</div>
										<div class="col-md-4">
											<div class="dropdown">
												<button class="btn btn-default dropdown-toggle pull-left" type="button" id="position" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
													Select a position
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
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
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-5">
											<label for="rate">Rate per day</label>
										</div>
										<div class="col-md-4">
											<input type="text" class="form-control" id="rate">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5">
											<label for="allowance">Allowance</label>
										</div>
										<div class="col-md-4">
											<input type="text" class="form-control" id="allowance">
										</div>
									</div>
									<div class="row">
										<h4 class="modal-title"><br>Contributions</h4><hr><br>

										<div class="row">
											<div class="col-md-5">
												<label for="pagibig">PagIBIG</label>
											</div>
											<div class="col-md-4">
												<input type="text" class="form-control" id="pagibig">
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>

		<!-- NAVIGATION BAR -->
		<?php
			require_once("nav.php");
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
				<div class="col-md-4 pull-down pull-right">
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
						<td><button type="button" class="btn btn-default" data-toggle="modal" data-target="#editEmployee" id="editEmployee">Edit details</button></td>
					</tr>
					<tr>
						<td>1</td>
						<td>Trial Employee entry</td>
						<td>Position</td>
						<td>Placeholder</td>
						<td>ACTION BUTTONS HERE...</td>
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

