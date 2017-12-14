<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');

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
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li><a href="employees.php?site=null&position=null" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a></li>
					<li class="active">Absence Notifications</li>
					<a href="" class="btn btn-success pull-right" data-toggle="modal" data-target="#rehireEmployee"> Rehire Employee</a>
				</ol>
			</div>
			
		</div>

		<!-- Modal for searching old employees -->
		<div class="modal fade" id="rehireEmployee" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Rehire an Employee</h4>
				    </div>
			    	<div class="modal-body">
						<h4>To rehire an employee, search for their name below:</h4>
						<div class="form-group col-md-10 col-md-offset-1" style="float:none">
							<input placeholder="Search for an old employee" class="form-control">
						</div>
						<button class="btn btn-primary" data-toggle="modal" data-target="#oldEmployee">Rehire Employee</button>
					</div>
			    </div>
			</div>
		</div>

		<!-- Modal for selecting old employees -->
		<div class="modal fade" id="oldEmployee" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-7">
							<h4 class="modal-title text-right">Rehire old employee</h4>
						</div>
						<div class="col-md-5">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<form class="horizontal" method="POST" action="logic_add_employee.php">
							<div class="row">
								<div class="col-md-6">
									<h4 class="modal-title">Personal Information</h4><hr>

									<div class="row">
										<div class="col-md-3">
											<label for="fname">First name</label>
										</div>
										<div class="col-md-9">
											<input name="txt_addFirstName" type="text" class="form-control" id="fname" readonly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3">
											<label for="lname">Last name</label>
										</div>
										<div class="col-md-9">
											<input name="txt_addLastName" type="text" class="form-control" id="lname" readonly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3">
											<label for="address">Address</label>
										</div>
										<div class="col-md-9">
											<input name="txt_addAddress" type="text" class="form-control" id="address" required>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3">
											<label for="contact">Contact number</label>
										</div>
										<div class="col-md-4">
											<input name="txt_addContactNum" type="text" class="form-control" id="contact" required>
										</div>

										<div class="col-md-1">
											<label for="contact">Date of Birth</label>
										</div>
										<div class="col-md-4">
											<input name="txt_addDOB" type="text" placeholder="mm-dd-yyyy" class="form-control" id="dtpkr_addDOB" reaodnly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3">
											<label for="contact">Civil Status</label>
										</div>
										<div class="col-md-9">
											<div class="dropdown">
												<select name="txt_addCivilStatus" class="form-control" aria-labelledby="dropdownMenu1" required>
													<option value="Single">Single</option>
													<option value="Married">Married</option>
													<option value="Divorced">Divorced</option>
													<option value="Separated">Separated</option>
													<option value="Widowed">Widowed</option>
												</select>
											</div>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3">
											<label for="contact">Date of Hire</label>
										</div>
										<div class="col-md-9">
											<input name="txt_addDateHired" type="text" size="10" style="width:150px" class="form-control" id="dtpkr_addEmployee" placeholder="mm-dd-yyyy" required>
										</div>
									</div>

									<div class="row pull-down">
										<div class="col-md-4">
											<label for="emergency">Emergency contact:</label>	
										</div>
										<div class="col-md-8">									
											<input name="txt_emergencyContact" type="text" class="form-control" required>
										</div>
									</div>

									<div class="row pull-down">
										<div class="col-md-4">
											<label for="emergency" class="no-wrap">Character Reference:</label>
										</div>
										<div class="col-md-8">
											<input name="txt_characterReference" type="text" class="form-control" required>
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
												<select name="dd_addPosition" class="form-control" aria-labelledby="dropdownMenu1" required>
													<option hidden>Select a position</option>
												<?php
												$query = "SELECT position FROM job_position WHERE active = '1'";
												$job_query = mysql_query($query);
												while($row = mysql_fetch_assoc($job_query))
												{
													Print '<option value="'.$row["position"].'">'.$row["position"].'</option>';
												}
												?>
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
												<select class="form-control" name="dd_site" required>
													<option hidden>Select a site</option>
												<?php
													$site_query = "SELECT location FROM site WHERE active = '1'";
													$location_query = mysql_query($site_query);
													while($row = mysql_fetch_assoc($location_query))
													{
														Print '<option value="'.$row["location"].'">'.$row["location"].'</option>';
													}
												?>
												</select>
											</div>
										</div>
									</div><br> 

									<div class="row">
										<div class="col-md-5">
											<label for="rate">Monthly Salary</label>
										</div>
										<div class="col-md-5">
											<input name="txt_addMonthlySalary"  type="text" class="form-control" id="monthlysalary" required>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-5">
											<label for="rate">Rate Per Day</label>
										</div>
										<div class="col-md-5">
											<input name="txt_addRatePerDay"  type="text" class="form-control" id="rate" readonly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-5">
											<label for="allowance">Allowance</label>
										</div>
										<div class="col-md-5">
											<input name="txt_addAllowance" type="text" class="form-control" id="allowance">
										</div>
									</div>

									<div class="row">
										<h4 class="modal-title"><br>Contributions</h4><hr>
										<div class="row">
											<div class="col-md-6 checkbox">
												<input id="sss" name="chkbox_addSSS" type="checkbox" onclick="sssbox()" value="SSS" >
												<label style="font-weight: 700" for="sss">SSS</label>
												<div id="txt_sssAppear" style="display:none;" class="col-md-8 col-md-offset-2">
													<input name="txt_sss" type="text" class="form-control" id="txt_sss">
												</div>
											</div>
											
											<div class="col-md-6 checkbox">
												<input name="chkbox_addPhilHealth" id="philhealthCheck" type="checkbox" onclick="philhealthbox()" value="PhilHealth" id="philhealth">
												
												<label style="font-weight: 700" for="philhealth">PhilHealth</label>
												<div id="txt_philhealthAppear" style="display:none;" class="col-md-8 col-md-offset-2">
													<input name="txt_philhealth" type="text" class="form-control" id="txt_philhealth">
												</div>
											</div>
										</div>

										<div class="row">
										<div class="col-md-5">
											<label for="pagibig">Pag-IBIG</label>
										</div>
										<div class="col-md-4">
											<input name="txt_addPagibig" type="text" class="form-control" id="pagibig">
										</div>

										<div class="col-md-10 col-md-offset-1 pull-down text-center well well-sm">
											* SSS contribution is automatically computed based on employee's monthly salary.
										</div>
									</div>
								</div>
							</div>

						</div>	
						<div class="modal-footer">
							<input type="submit" name="add_submit" id="add_submit" class="btn btn-primary" value="Add Employee">
						</div>			
					</form>
					</div>
					
				</div>
			</div>
		</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row pull-down">
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-bordered table-condensed" style="background-color:white;">
					<tr>
						<td>ID</td>
						<td>Name</td>
						<td>Position</td>
						<td>Site</td>
						<td>Days absent</td>
						<td>Status</td>
						<td>Actions</td>
					</tr>
					<?php
						$awol = "SELECT * FROM awol_employees";
						$awolQuery = mysql_query($awol);

						if(mysql_num_rows($awolQuery))
						{
						while($row = mysql_fetch_assoc($awolQuery))
						{
							$empid = $row['empid'];
							$employee = "SELECT * FROM employee WHERE empid = '$empid'";
							$employeeQuery = mysql_query($employee);
							$empInfo = mysql_fetch_assoc($employeeQuery);
							Print "
										<tr>
											<td>
												".$empid."
											</td>
											<td>"
												.$empInfo['lastname'].", ".$empInfo['firstname'].
										   "</td>
											<td>"
												.$empInfo['position'].
										   "</td>
											<td>"
												.$empInfo['site'].
										   "</td>
											<td>
												7
											</td>
											<td>"
												.$row['status'].
										   "</td>
											<td>
												<button class='btn btn-default' onclick='next(\"".$empid."\")'>
													Check details
												</button>
												<input type='hidden' name='empid' value='".$empid."'>
											</td>
										</tr>
									";
						}
					}
					else
					{
						Print 
						"
						<tr><td colspan='7'><h3>No records found.</h3></td></tr>
						";
					}
					?>
				</table>
				<div id="hidden_form_container"></div>
			</div>	
		</div>


	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		function saveChanges(){
			confirm("Note: After saving these changes, the loans you've entered will no longer be editable. Are you sure you want to save changes?");
		}
		document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
		
		function next(id) 
		{
	  	var form, input, newInput2;
	  	//Created a form
	  	form = document.createElement('form');
	  	form.action = 'absence_view.php';
	  	form.method = 'post';
	  	//Elements insite the form
	  	input = document.createElement('input');
	  	input.type = 'hidden';
	  	input.name = 'empid';
	  	input.value = id;

	  	//Insert inside the elements inside the form
	  	form.appendChild(input);
	  	document.getElementById('hidden_form_container').appendChild(form);
	  	//used DOM to submit the form
	  	form.submit();
		}	
	</script>
</body>
</html>
<!--
      changeMonth: true,
      changeYear: true