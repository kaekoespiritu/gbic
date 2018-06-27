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
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css" type="text/css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 pull-down">
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
						<div class="form-group col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1" style="float:none">
							<input placeholder="Search for an old employee" id="search_text" class="form-control">
							<div id="search_result_rehire" class="search-result-rehire"></div>
						</div>
					</div>
			    </div>
			</div>
		</div>

		<!-- Modal for selecting old employees -->
		<div class="modal fade" id="oldEmployee" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-7 col-lg-7">
							<h4 class="modal-title text-right">Rehire old employee</h4>
						</div>
						<div class="col-md-5 col-lg-5">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<form class="horizontal" method="POST" action="logic_rehire_employee.php">
							<div class="row">
								<div class="col-md-6 col-lg-6">
									<input type="hidden" name="empid" id="employeeID">
									<h4 class="modal-title">Personal Information</h4><br>
									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="fname">First name</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<input name="txt_addFirstName" type="text" class="form-control" id="fname" readonly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="lname">Last name</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<input name="txt_addLastName" type="text" class="form-control" id="lname" readonly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="address">Address</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<input name="txt_addAddress" type="text" class="form-control" id="address">
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="contact">Contact number</label>
										</div>
										<div class="col-md-4 col-lg-4">
											<input name="txt_addContactNum" type="text" class="form-control" id="contact" required>
										</div>

										<div class="col-md-1 col-lg-1">
											<label for="contact">Date of Birth</label>
										</div>
										<div class="col-md-4 col-lg-4">
											<input name="txt_addDOB" type="text" placeholder="mm-dd-yyyy" class="form-control" id="dtpkr_dob" reaodnly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="contact">Civil Status</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<div class="dropdown">
												<select name="txt_addCivilStatus" id="civilstatus" class="form-control" aria-labelledby="dropdownMenu1" required>
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
										<div class="col-md-3 col-lg-3">
											<label for="contact">Date of Hire</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<input name="txt_addDateHired" type="text" size="10" style="width:150px" class="form-control" id="dtpkr_datehired" placeholder="mm-dd-yyyy" required>
										</div>
									</div>

									<div class="row pull-down">
										<div class="col-md-4 col-lg-4">
											<label for="emergency">Emergency contact:</label>	
										</div>
										<div class="col-md-8 col-lg-8">									
											<input name="txt_emergencyContact" id="emergencyContact" type="text" class="form-control" >
										</div>
									</div>

									<div class="row pull-down">
										<div class="col-md-4 col-lg-4">
											<label for="emergency" class="no-wrap">Character Reference:</label>
										</div>
										<div class="col-md-8 col-lg-8">
											<input name="txt_characterReference" id="characterRef" type="text" class="form-control">
										</div>
									</div>
								</div>

								<div class="col-md-6 col-lg-6">
									<h4 class="modal-title">Job details</h4><hr>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="position" class="text-right">Position</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<div class="dropdown">
												<select name="dd_addPosition" id="position" class="form-control" aria-labelledby="dropdownMenu1" required>
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
										<div class="col-md-5 col-lg-5">
											<label for="position" class="text-right">Site</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<div class="dropdown">
												<select class="form-control" name="dd_site" id="site" required>
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

									<!-- <div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="rate">Monthly Salary</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_addMonthlySalary" autocomplete="off" type="text" class="form-control" id="monthlysalary" onchange="salaryDecimal()" onkeyup="monthlySalary()" required>
										</div>
									</div><br> -->

									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="rate">Rate Per Day</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_addRatePerDay" onblur="salaryDecimal()" type="text" class="form-control" id="rate">
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="allowance">Allowance</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_addAllowance" type="text" class="form-control" id="allowance">
										</div>
									</div>

									<div class="row">
										<h4 class="modal-title"><br>Contributions</h4><hr>
										

										<div class="row">
											<!-- SSS -->
											<div class="col-md-12 col-lg-12">
												<div class="col-md-3 col-lg-3">
													<input type="checkbox" id="sssCheckbox" onchange="sssCheckboxFunc()">
													<label for="sss">SSS</label>
												</div>
												<div class="col-md-9 col-lg-9">
													<!-- <div class="form-inline"> -->
														<div class="row">
															<div class="col-md-1 col-lg-1">
																<label for="sss_ee">EE:</label>
															</div>
															<div class="col-md-4 col-lg-4">
																<input name="txt_addSSSEE" type="text" placeholder="No document" class="form-control" id="sssEE" readonly>
															</div>
															<div class="col-md-1 col-lg-1">
																<label for="sss_er">ER:</label>
															</div>
															<div class="col-md-4 col-lg-4">
																<input name="txt_addSSSER" type="text" placeholder="No document" class="form-control" id="sssER" readonly>
															</div>
														</div>
													<!-- </div> -->
												</div>
											</div>

											<!-- PhilHealth -->
											<div class="col-md-12 col-lg-12 pull-down">
												<div class="col-md-3 col-lg-3">
													<input type="checkbox" id="philhealthCheckbox" onchange="philhealthCheckboxFunc()">
													<label for="philhealth" class="nowrap">Philhealth</label>
												</div>
												<div class="col-md-9 col-lg-9">
													<div class="row">
														<div class="col-md-1 col-lg-1">
															<label for="philhealth_ee">EE:</label>
														</div>
														<div class="col-md-4 col-lg-4">
															<input name="txt_addPhilhealthEE" type="text" placeholder="No document" class="form-control" id="philhealthEE" readonly>
														</div>
														<div class="col-md-1 col-lg-1">
															<label for="philhealth_er">ER:</label>
														</div>
														<div class="col-md-4 col-lg-4">
															<input name="txt_addPhilhealthER" type="text" placeholder="No document" class="form-control" id="philhealthER" readonly>
														</div>
													</div>
												</div>
											</div>

											<!-- PagIBIG-->
											<div class="col-md-12 col-lg-12 pull-down">
												<div class="col-md-3 col-lg-3">
													<input type="checkbox" id="pagibigCheckbox" onchange="pagibigCheckboxFunc()">
													<label for="pagibig" class="nowrap">Pagibig</label>
												</div>
												<div class="col-md-9 col-lg-9">
													<div class="row">
														<div class="col-md-1 col-lg-1">
															<label for="pagibig_ee">EE:</label>
														</div>
														<div class="col-md-4 col-lg-4">
															<input name="txt_addPagibigEE" type="text" placeholder="No document" class="form-control" id="pagibigEE" readonly>
														</div>
														<div class="col-md-1 col-lg-1">
															<label for="pagibig_er">ER:</label>
														</div>
														<div class="col-md-4 col-lg-4">
															<input name="txt_addPagibigER" type="text" placeholder="No document" class="form-control" id="pagibigER" readonly>
														</div>
													</div>
												</div>
											</div>

											<div class="col-sm-10 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down text-center well well-sm">
												Note: Check boxes if employee has document for<br>SSS / PhilHealth / Pagibig.
											</div>
											<!-- <div class="col-md-5 col-lg-5">
												<label for="sss">SSS</label>
											</div>
											<div class="col-md-4 col-lg-4">
												<input type="checkbox" id="sssCheckbox" onchange="sssCheckboxFunc()">
												<input name="txt_addSSS" type="text" class="form-control" id="sss">
											</div>
											<div class="col-md-5 col-lg-5">
												<label for="philhealth">Philhealth</label>
											</div>
											<div class="col-md-4 col-lg-4">
												<input type="checkbox" id="philhealthCheckbox" onchange="philhealthCheckboxFunc()">
												<input name="txt_addPhilhealth" type="text" class="form-control" id="philhealth">
											</div>
											<div class="col-md-5 col-lg-5">
												<label for="pagibig">Pag-IBIG</label>
											</div>
											<div class="col-md-4 col-lg-4">
												<input type="checkbox" id="pagibigCheckbox" onchange="pagibigCheckboxFunc()">
												<input name="txt_addPagibig" type="text" class="form-control" id="pagibig">
											</div>

											<!-- <div class="col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 pull-down text-center well well-sm">
												* SSS contribution is automatically computed based on employee's monthly salary.
											</div> -->
										</div> 
									</div>
								</div>
							</div>	
							<div class="modal-footer">
								<input type="submit" name="add_submit" id="add_submit" class="btn btn-primary" value="Rehire Employee">
							</div>			
						</form>
					</div>
					
				</div>
			</div>
		</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row pull-down">
			<div class="col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
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
	<script rel="javascript" src="js/jquery-ui/external/jquery/jquery.js"></script>
	<script rel="javascript" src="js/jquery-ui/jquery-ui.min.js"></script>

	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script rel="javascript" src="js/accounting.min.js"></script>
	<script>
		$("#dtpkr_datehired").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			maxDate:(0),
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			}
		});
		$("#dtpkr_dob").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			maxDate:(0),
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			}
		});
		
		function sendToModal(id){
			var parent = document.getElementById(id);
			// Getting values from the searched employee

			//Personal Information
			var empid = parent.querySelector('.toModalEmpid').value;
			var firstname = parent.querySelector('.toModalFirstname').value;
			var lastname = parent.querySelector('.toModalLastname').value;
			var address = parent.querySelector('.toModalAddress').value;
			var civilstatus = parent.querySelector('.toModalCivilStatus').value;
			var contactnum = parent.querySelector('.toModalContactnum').value;
			var emergency = parent.querySelector('.toModalEmergency').value;
			var reference = parent.querySelector('.toModalReference').value;

			//Company Information
			var dob = parent.querySelector('.toModalDob').value;
			var datehired = parent.querySelector('.toModalHired').value;
			var position = parent.querySelector('.toModalPosition').value;
			var site = parent.querySelector('.toModalSite').value;
			// var monthly = parent.querySelector('.toModalMonthly').value;
			var rate = parent.querySelector('.toModalRate').value;
			var allowance = parent.querySelector('.toModalAllowance').value;
			
			//Government Documents
			var sssEE = parent.querySelector('.toModalSssEE').value;
			var sssER = parent.querySelector('.toModalSssER').value;
			var pagibigEE = parent.querySelector('.toModalPagibigEE').value;
			var pagibigER = parent.querySelector('.toModalPagibigER').value;
			var philhealthEE = parent.querySelector('.toModalPhilhealthEE').value;
			var philhealthER = parent.querySelector('.toModalPhilhealthER').value;

			var htmlPosition = "<option value='"+position+"' selected>"+position+"</option>";
			var htmlSite = "<option value='"+site+"' selected>"+site+"</option>";
			var htmlCivil = "<option value='"+civilstatus+"' selected>"+civilstatus+"</option>";

			var sssRowEE = document.getElementById('sssEE');
			var sssRowER = document.getElementById('sssER');
			var pagibigRowEE = document.getElementById('pagibigEE');
			var pagibigRowER = document.getElementById('pagibigER');
			var philhealthRowEE = document.getElementById('philhealthEE');
			var philhealthRowER = document.getElementById('philhealthER');
			if(sssEE != ""){
				document.getElementById('sssCheckbox').checked = true;
				sssRowEE.value = sssEE;
				sssRowEE.placeholder = "";
				sssRowER.value = sssER;
				sssRowER.placeholder = "";

			}
			else {
				sssRowEE.placeholder = "No document";
				sssRowEE.readOnly = true;
				sssRowEE.value = "";
				sssRowER.placeholder = "No document";
				sssRowER.readOnly = true;
				sssRowER.value = "";
			}
			if(pagibigEE != ""){
				document.getElementById('pagibigCheckbox').checked = true;
				pagibigRowEE.value = pagibigEE;
				pagibigRowEE.placeholder = "";
				pagibigRowER.value = pagibigER;
				pagibigRowER.placeholder = "";
			}
			else {
				pagibigRowEE.placeholder = "No document";
				pagibigRowEE.readOnly = true;
				pagibigRowEE.value = "";
				pagibigRowER.placeholder = "No document";
				pagibigRowER.readOnly = true;
				pagibigRowER.value = "";
			}
			if(philhealthEE != ""){
				document.getElementById('philhealthCheckbox').checked = true;
				philhealthRowEE.value = philhealthEE;
				philhealthRowEE.placeholder = "";
				philhealthRowER.value = philhealthER;
				philhealthRowER.placeholder = "";
			}
			else {
				philhealthRowEE.placeholder = "No document";
				philhealthRowEE.readOnly = true;
				philhealthRowEE.value = "";
				philhealthRowER.placeholder = "No document";
				philhealthRowER.readOnly = true;
				philhealthRowER.value = "";
			}

			// Move values to modal
			document.getElementById('employeeID').value = empid;
			document.getElementById('fname').value = firstname;
			document.getElementById('lname').value = lastname;
			document.getElementById('address').value = address;
			document.getElementById('dtpkr_dob').value = dob;
			document.getElementById('dtpkr_datehired').value = datehired;
			document.getElementById('contact').value = contactnum;
			document.getElementById('civilstatus').insertAdjacentHTML('afterbegin', htmlCivil);
			document.getElementById('position').insertAdjacentHTML('afterbegin', htmlPosition);
			document.getElementById('site').insertAdjacentHTML('afterbegin', htmlSite);
			document.getElementById('emergencyContact').value = emergency;
			document.getElementById('characterRef').value = reference;
			// document.getElementById('monthlysalary').value = accounting.formatNumber(monthly, 2, ",");
			document.getElementById('rate').value = accounting.formatNumber(rate, 2, ",");
			document.getElementById('allowance').value = accounting.formatNumber(allowance, 2, ",");
		}

		$(document).ready(function(){
		 function load_data(query)
		 {
		  $.ajax({
		   url:"livesearch_rehire.php",
		   method:"POST",
		   data:{
		   		query:query
		   	},
		   success:function(data)
		   {
		    $('#search_result_rehire').html(data);
		   }
		  });
		 }
		 $('#search_text').keyup(function(){
		  var search = $(this).val();
		  if(search != '')
		  {
		   load_data(search);
		  }
		  else
		  {
		   load_data();
		  }
		 });
		});

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


		function salaryDecimal(){
			var salary = document.getElementsByName('txt_addRatePerDay')[0];
			var value = salary.value;
			var decimal = parseInt(value).toFixed(2);
			salary.value=decimal;
		}
		function allowanceDecimal(){
			var allowance = document.getElementById('allowance');
			var value = document.getElementById('allowance').value;
			var decimal = parseInt(value).toFixed(2);
			allowance.value=decimal;
		}
		function pagibigDecimal(evt){
			var pagibig = document.getElementById('pagibig');
			var value = document.getElementById('pagibig').value;
			var decimal = parseInt(value).toFixed(2);
			pagibig.value=decimal;	

			// REGEX
			var theEvent = evt || window.event;
			var key = theEvent.keyCode || theEvent.which;
			key = String.fromCharCode( key );
			var regex = /[0-9]|\./;
			if( !regex.test(key) ) 
				 theEvent.returnValue = false;
			if(theEvent.preventDefault) 
				theEvent.preventDefault();
		}
		function sssCheckboxFunc() {
			var sss = document.getElementById('sssCheckbox');
			var sssEERow = document.getElementById('sssEE');
			var sssERRow = document.getElementById('sssER');
			if(sss.checked == true)
			{
				//Employee
				sssEERow.readOnly = false;
				sssEERow.placeholder = "";
				//Employer
				sssERRow.readOnly = false;
				sssERRow.placeholder = "";

				// sssbox();
			}
			else
			{
				//Employee
				sssEERow.placeholder = "No document";
				sssEERow.readOnly = true;
				sssEERow.value = "";
				//Employer
				sssERRow.placeholder = "No document";
				sssERRow.readOnly = true;
				sssERRow.value = "";
			}
		}
		function philhealthCheckboxFunc() {
			var philhealth = document.getElementById('philhealthCheckbox');
			var philhealthEERow = document.getElementById('philhealthEE');
			var philhealthERRow = document.getElementById('philhealthER');
			if(philhealth.checked == true)
			{
				//Employee
				philhealthEERow.readOnly = false;
				philhealthEERow.required = true;
				philhealthEERow.placeholder = "";
				//Employer
				philhealthERRow.readOnly = false;
				philhealthERRow.required = true;
				philhealthERRow.placeholder = "";
			}
			else
			{
				//Employee
				philhealthEERow.placeholder = "No document";
				philhealthEERow.readOnly = true;
				philhealthEERow.required = false;
				philhealthEERow.value = "";
				//Employer
				philhealthERRow.placeholder = "No document";
				philhealthERRow.readOnly = true;
				philhealthERRow.required = false;
				philhealthERRow.value = "";
			}
		}
		function pagibigCheckboxFunc() {
			var pagibig = document.getElementById('pagibigCheckbox');
			var pagibigEERow = document.getElementById('pagibigEE');
			var pagibigERRow = document.getElementById('pagibigER');
			if(pagibig.checked == true)
			{
				//Employee
				pagibigEERow.readOnly = false;
				pagibigEERow.required = true;
				pagibigEERow.placeholder = "";
				//Employer
				pagibigERRow.readOnly = false;
				pagibigERRow.required = true;
				pagibigERRow.placeholder = "";
			}
			else
			{
				//Employee
				pagibigEERow.placeholder = "No document";
				pagibigEERow.readOnly = true;
				pagibigEERow.required = false;
				pagibigEERow.value = "";
				//Employer
				pagibigERRow.placeholder = "No document";
				pagibigERRow.readOnly = true;
				pagibigERRow.required = false;
				pagibigERRow.value = "";
			}
		}
	</script>

</body>
</html>
<!--
      changeMonth: true,
      changeYear: true