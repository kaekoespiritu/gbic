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
		<div class="row"><br>
			<div class="row text-center">
				<ol class="breadcrumb text-left">
					<li><a href="employees.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a></li>
					<li class="active">Edit employee details</li>
				</ol>
			</div>
		</div>
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
							<div class="dropdown">
								<select class="form-control" aria-labelledby="dropdownMenu1">
									<option hidden>Select a status</option>
									<option>Single</option>
									<option>Married</option>
									<option>Divorced</option>
									<option>Separated</option>
									<option>Widowed</option>
								</select>
							</div>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3">
							<label for="contact">Date of Hire</label>
						</div>
						<div class="col-md-9">
							<input type="text" size="10" style="width:150px" class="form-control" id="dtpkr_editEmployee" placeholder="month-day-year">
						</div>
						<div class="col-md-12 pull-down">
							<button type="button" class="btn btn-primary pull-down">Save changes</button>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<h4 class="modal-title">Job details</h4><hr>
					<div class="row">
						<div class="col-md-5">
							<label for="position" class="text-right">Position</label>
						</div>
						<div class="col-md-5">
							<div class="dropdown">
								<select class="form-control" aria-labelledby="dropdownMenu1">
									<option hidden>Select a position</option>
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
						</div>
					</div><br>

					<div class="row">
						<div class="col-md-5">
							<label for="position" class="text-right">Site</label>
						</div>
						<div class="col-md-5">
							<div class="dropdown">
								<select class="form-control">
									<option hidden>Select a site</option>
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
					</div><br> 

					<div class="row">
						<div class="col-md-5">
							<label for="rate">Rate per day</label>
						</div>
						<div class="col-md-5">
							<input type="text" class="form-control" id="rate">
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-5">
							<label for="allowance">Allowance</label>
						</div>
						<div class="col-md-5">
							<input type="text" class="form-control" id="allowance">
						</div>
					</div>
					<div class="row">
						<h4 class="modal-title"><br>Contributions</h4><hr>

						<div class="row">
								<div class="col-md-5">
									<label style="font-weight: 700" for="sss">SSS</label>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" id="sss">
								</div>
						</div><br>
						<div class="row">
							<div class="col-md-5">
									<label style="font-weight: 700" for="philhealth" class="text-left">PhilHealth</label>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" id="philhealth">
								</div>
						</div><br>
						<div class="row">
							<div class="col-md-5">
								<label for="pagibig">Pag-IBIG</label>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control" id="pagibig">
							</div>
							<div class="col-md-10 col-md-offset-1 pull-down text-center well well-sm">
								* SSS & PhilHealth contributions are automatically computed.
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("employees").setAttribute("class", "active");
		$( "#dtpkr_editEmployee" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 10) 
			}
		});
		</script>
		<script rel="javascript" src="js/dropdown.js"></script>


	</div>
</body>
</html>