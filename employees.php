<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">

<<<<<<< HEAD
		<!-- ADD EMPLOYEE MODAL -->
		<div class="modal fade" id="addEmployee" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-7">
							<h4 class="modal-title text-right">Add new Employee</h4>
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
											<label for="firstname">First name</label>
										</div>
										<div class="col-md-9">
											<input type="text" name="txtFirstNameAdd" class="form-control" id="fname">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="lname">Last name</label>
										</div>
										<div class="col-md-9">
											<input type="text" name="txtFirstNameAdd" class="form-control" id="lname">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="address">Address</label>
										</div>
										<div class="col-md-9">
											<input type="text" name="txtFirstNameAdd" class="form-control" id="address">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Contact number</label>
										</div>
										<div class="col-md-5">
											<input type="text" name="txtFirstNameAdd" class="form-control" id="contact">
										</div>
										<div class="col-md-1">
											<label for="contact">Age</label>
										</div>
										<div class="col-md-3">
											<input type="text" name="txtFirstNameAdd" class="form-control" id="contact">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Civil Status</label>
										</div>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Single</label>
												</div>
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Married</label>
												</div>
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Divorced</label>
												</div>
												<div class="col-md-6">
												<input type="radio" autocomplete="off">
												<label>Separated</label>
												</div>
												<div class="col-md-6">
												<input type="radio" autocomplete="off">
												<label>Widowed</label>
												</div>
											</div>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
										
											<label for="contact">Date of Hire</label>
										</div>
										<div class="col-md-9">
										
											<input type="text" name="txtFirstNameAdd" size="10" style="width:130px" class="form-control" id="dtpkr_addEmployee" placeholder="month-day-year">
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
												<button class="btn btn-default dropdown-toggle pull-left" type="button" id="position" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">													Select a position
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
														<li value="">-Select position-</li>
<!-- POSITION -->								<?php
													$position_query = "SELECT position FROM job_position";
													$query = mysql_query($position_query);
													while($row = mysql_fetch_assoc($query))
													{
														Print "<li value='".$row['position']."'>".$row['position']."</a></li>";
													}
												?>
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
												<label for="pagibig">Pag-IBIG</label>
											</div>
											<div class="col-md-4">
												<input type="text" class="form-control" id="pagibig">
											</div><br><br><br>
											<div class="col-md-8 col-md-offset-2 text-center well">
											* SSS and PhilHealth contributions are automatically computed based on employee's base pay.
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
										<div class="col-md-5">
											<input type="text" class="form-control" id="contact">
										</div>
										<div class="col-md-1">
											<label for="contact">Age</label>
										</div>
										<div class="col-md-3">
											<input type="text" class="form-control" id="contact">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Civil Status</label>
										</div>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Single</label>
												</div>
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Married</label>
												</div>
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Divorced</label>
												</div>
												<div class="col-md-6">
												<input type="radio" autocomplete="off">
												<label>Separated</label>
												</div>
												<div class="col-md-6">
												<input type="radio" autocomplete="off">
												<label>Widowed</label>
												</div>
											</div>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Date of Hire</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" style="width:130px" id="dtpkr_editEmployee" placeholder="placeholder of real date">
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
												<button class="btn btn-default dropdown-toggle pull-left" type="button" id="position" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
												<label for="pagibig">Pag-IBIG</label>
											</div>
											<div class="col-md-4">
												<input type="text" class="form-control" id="pagibig">
											</div><br><br><br>
											<div class="col-md-8 col-md-offset-2 text-center well">
											* SSS and PhilHealth contributions are automatically computed based on employee's base pay.
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

=======
		<?php
		require_once('directives/modals/addEmployee.php');
		require_once('directives/modals/editEmployee.php');
		?>
		
>>>>>>> caa0fdfd9ece514ecf3e628b9cd6f8701e243517
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-1 pull-down">
					<div class="input-group">
						<input type="text" class="form-control">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
						</span>
					</div>
				</div>
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-5 col-md-pull-1 pull-down text-right">
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
				<div class="col-md-1 col-md-pull-1 pull-down pull-left">
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

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	
	<script>
		document.getElementById("employees").setAttribute("class", "active");

<<<<<<< HEAD
<<<<<<< HEAD
    $( "#dtpkr_addEmployee" ).datepicker({
 		changeMonth: true,
      	changeYear: true,
      	dateFormat: 'mm-dd-yy',
      	showAnim: 'fadeIn',
      	defaultDate: new Date(),
      	beforeShow: function(){    
           $(".ui-datepicker").css('font-size', 10) 
       }
    });
   
	   $( "#dtpkr_editEmployee" ).datepicker({
 		changeMonth: true,
      	changeYear: true,
      	dateFormat: 'mm-dd-yy',
      	showAnim: 'fadeIn'
    });
 
=======
=======
>>>>>>> caa0fdfd9ece514ecf3e628b9cd6f8701e243517
		$( "#dtpkr_addEmployee" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			defaultDate: new Date()
		});

		$( "#dtpkr_editEmployee" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind'
		});

<<<<<<< HEAD
>>>>>>> caa0fdfd9ece514ecf3e628b9cd6f8701e243517
=======
>>>>>>> caa0fdfd9ece514ecf3e628b9cd6f8701e243517
		
	</script>

</body>
</html>
<!--
      changeMonth: true,
      changeYear: true