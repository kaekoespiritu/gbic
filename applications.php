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
							<input placeholder="Search for an old employee" id="search_text" class="form-control">
							<div id="search_result" class="searchresult-rehire-employee"></div>
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
						<div class="col-md-7">
							<h4 class="modal-title text-right">Rehire old employee</h4>
						</div>
						<div class="col-md-5">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<form class="horizontal" method="POST" action="logic_rehire_employee.php">
							<div class="row">
								<div class="col-md-6">
									<input type="hidden" name="empid" id="employeeID">
									<h4 class="modal-title">Personal Information</h4><br>
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
											<input name="txt_addDOB" type="text" placeholder="mm-dd-yyyy" class="form-control" id="dtpkr_dob" reaodnly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3">
											<label for="contact">Civil Status</label>
										</div>
										<div class="col-md-9">
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
										<div class="col-md-3">
											<label for="contact">Date of Hire</label>
										</div>
										<div class="col-md-9">
											<input name="txt_addDateHired" type="text" size="10" style="width:150px" class="form-control" id="dtpkr_datehired" placeholder="mm-dd-yyyy" required>
										</div>
									</div>

									<div class="row pull-down">
										<div class="col-md-4">
											<label for="emergency">Emergency contact:</label>	
										</div>
										<div class="col-md-8">									
											<input name="txt_emergencyContact" id="emergencyContact" type="text" class="form-control" >
										</div>
									</div>

									<div class="row pull-down">
										<div class="col-md-4">
											<label for="emergency" class="no-wrap">Character Reference:</label>
										</div>
										<div class="col-md-8">
											<input name="txt_characterReference" id="characterRef" type="text" class="form-control">
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
										<div class="col-md-5">
											<label for="position" class="text-right">Site</label>
										</div>
										<div class="col-md-5">
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

									<div class="row">
										<div class="col-md-5">
											<label for="rate">Monthly Salary</label>
										</div>
										<div class="col-md-5">
											<input name="txt_addMonthlySalary" autocomplete="off" type="text" class="form-control" id="monthlysalary" onchange="salaryDecimal()" onkeyup="monthlySalary()" required>
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
											<div class="col-md-5">
												<label for="sss">SSS</label>
											</div>
											<div class="col-md-4">
												<input type="checkbox" id="sssCheckbox" onchange="sssCheckboxFunc()">
												<input name="txt_addSSS" type="text" class="form-control" id="sss">
											</div>
											<div class="col-md-5">
												<label for="philhealth">Philhealth</label>
											</div>
											<div class="col-md-4">
												<input type="checkbox" id="philhealthCheckbox" onchange="philhealthCheckboxFunc()">
												<input name="txt_addPhilhealth" type="text" class="form-control" id="philhealth">
											</div>
											<div class="col-md-5">
												<label for="pagibig">Pag-IBIG</label>
											</div>
											<div class="col-md-4">
												<input type="checkbox" id="pagibigCheckbox" onchange="pagibigCheckboxFunc()">
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
								<input type="submit" name="add_submit" id="add_submit" class="btn btn-primary" value="Rehire Employee">
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
	<script rel="javascript" src="js/accounting.min.js"></script>
	<script>
		$("#dtpkr_datehired").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			maxDate:(0),
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 10) 
			}
		});
		$("#dtpkr_dob").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			maxDate:(0),
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 10) 
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
			var monthly = parent.querySelector('.toModalMonthly').value;
			var rate = parent.querySelector('.toModalRate').value;
			var allowance = parent.querySelector('.toModalAllowance').value;
			
			//Government Documents
			var sss = parent.querySelector('.toModalSss').value;
			var pagibig = parent.querySelector('.toModalPagibig').value;
			var philhealth = parent.querySelector('.toModalPhilhealth').value;

			

			var htmlPosition = "<option value='"+position+"' selected>"+position+"</option>";
			var htmlSite = "<option value='"+site+"' selected>"+site+"</option>";
			var htmlCivil = "<option value='"+civilstatus+"' selected>"+civilstatus+"</option>";

			var sssRow = document.getElementById('sss');
			var pagibigRow = document.getElementById('pagibig');
			var philhealthRow = document.getElementById('philhealth');
			if(sss != ""){
				document.getElementById('sssCheckbox').checked = true;
				sssRow.value = sss;
				sssRow.placeholder = "";

			}
			else {
				sssRow.placeholder = "No document";
				sssRow.readOnly = true;
				sssRow.value = "";
			}
			if(pagibig != ""){
				document.getElementById('pagibigCheckbox').checked = true;
				pagibigRow.value = pagibig;
			}
			else {
				pagibigRow.placeholder = "No document";
				pagibigRow.readOnly = true;
				pagibigRow.value = "";
			}
			if(philhealth != ""){
				document.getElementById('philhealthCheckbox').checked = true;
				philhealthRow.value = philhealth;
			}
			else {
				philhealthRow.placeholder = "No document";
				philhealthRow.readOnly = true;
				philhealthRow.value = "";
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
			document.getElementById('monthlysalary').value = accounting.formatNumber(monthly, 2, ",");
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
		    $('#search_result').html(data);
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

		/* AUTOMATED COMPUTATION FOR SSS BASED ON MONTHLY SALARY */
function sssbox() {
		var monthly = document.getElementById('monthlysalary').value;
		var sssContribution = 0;

		monthly = monthly.replace( /,/g, "");//removes commas

		if(monthly >= 1000 && monthly <= 1249.9)
			sssContribution = 36.30;
		//1250 ~ 1749.9 = 54.50
		else if(monthly >= 1250 && monthly <= 1749.9)
			sssContribution = 54.50;
		//1750 ~ 2249.9 = 72.70
		else if(monthly >= 1750 && monthly <= 2249.9)
			sssContribution = 72.70;
		//2250 ~ 2749.9 = 90.80
		else if(monthly >= 2250 && monthly <= 2749.9)
			sssContribution = 90.80;
		//2750 ~ 3249.9 = 109.0
		else if(monthly >= 2750 && monthly <= 3249.9)
			sssContribution = 109.00;
		//3250 ~ 3749.9 = 127.20
		else if(monthly >= 3250 && monthly <= 3749.9)
			sssContribution = 127.20;
		//3750 ~ 4249.9 = 145.30
		else if(monthly >= 3750 && monthly <= 4249.9)
			sssContribution = 145.30;
		//4250 ~ 4749.9 = 163.50
		else if(monthly >= 4250 && monthly <= 4749.9)
			sssContribution = 163.50;
		//4750 ~ 5249.9 = 181.70
		else if(monthly >= 4750 && monthly <= 5249.9)
			sssContribution = 181.70;
		//5250 ~ 5749.9 = 199.80
		else if(monthly >= 5250 && monthly <= 5749.9)
			sssContribution = 199.80;
		//5750 ~ 6249.9 = 218.0
		else if(monthly >= 5750 && monthly <= 6249.9)
			sssContribution = 218.00;
		//6250 ~ 6749.9 = 236.20
		else if(monthly >= 6250 && monthly <= 6749.9)
			sssContribution = 236.20;
		//6750 ~ 7249.9 = 254.30
		else if(monthly >= 6750 && monthly <= 7249.9)
			sssContribution = 254.30;
		//7250 ~ 7749.9 = 272.50
		else if(monthly >= 7250 && monthly <= 7749.9)
			sssContribution = 272.50;
		//7750 ~ 8249.9 = 290.70
		else if(monthly >= 7750 && monthly <=  8249.9)
			sssContribution = 290.70;
		//8250 ~ 8749.9 = 308.80
		else if(monthly >= 8250 && monthly <= 8749.9)
			sssContribution = 308.80;
		//8750 ~ 9249.9 = 327.0
		else if(monthly >= 8750 && monthly <= 9249.9 )
			sssContribution = 327.00;
		//9250 ~ 9749.9 = 345.20
		else if(monthly >= 9250 && monthly <= 9749.9)
			sssContribution = 345.20;
		//9750 ~ 10249.9 = 363.30
		else if(monthly >= 9750 && monthly <= 10249.9)
			sssContribution = 363.30;
		//10250 ~ 10749.9 = 381.50
		else if(monthly >= 10250 && monthly <=  10749.9)
			sssContribution = 381.50;
		//10750 ~ 11249.9 = 399.70
		else if(monthly >= 10750 && monthly <= 11249.9)
			sssContribution = 399.70;
		//11250 ~ 11749.9 = 417.80
		else if(monthly >= 11250 && monthly <= 11749.9)
			sssContribution = 417.80;
		//11750 ~ 12249.9 = 436.0
		else if(monthly >= 11750 && monthly <= 12249.9)
			sssContribution = 436.00;
		//12250 ~ 12749.9 = 454.20
		else if(monthly >= 12250 && monthly <= 12749.9)
			sssContribution = 454.20;
		//12750 ~ 13249.9 = 472.30
		else if(monthly >= 12750 && monthly <= 13249.9)
			sssContribution = 472.30;
		//13250 ~ 13749.9 = 490.50
		else if(monthly >= 13250 && monthly <= 13749.9)
			sssContribution = 490.50;
		//13750 ~ 14249.9 = 508.70
		else if(monthly >= 13750 && monthly <= 14249.9 )
			sssContribution = 508.70;
		//14250 ~ 14749.9 = 526.80
		else if(monthly >= 14250 && monthly <= 14749.9)
			sssContribution = 526.80;
		//14750 ~ 15249.9 = 545.0
		else if(monthly >= 14750 && monthly <= 15249.9 )
			sssContribution = 545.00;
		//15250 ~ 15749.9 = 563.20
		else if(monthly >= 15250 && monthly <= 15749.9)
			sssContribution = 563.20;
		//15750 ~ higher = 581.30
		else if(monthly >= 15750)
			sssContribution = 581.30;

		sssContribution = sssContribution.toFixed(2);
		console.log(sssContribution);
		document.getElementById('sss').value = sssContribution;
}
function monthlySalary(salary){
	var salary = document.getElementById('monthlysalary').value;
	var dailyRate = document.getElementById('rate');
	dailyRate.value= (salary/25).toFixed(2);
	if(document.getElementById('sssCheckbox').checked == true){
		sssbox();
	}
}
function salaryDecimal(){
	var salary = document.getElementById('monthlysalary');
	var value = document.getElementById('monthlysalary').value;
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
	var sssRow = document.getElementById('sss');
	if(sss.checked == true)
	{
		console.log('checked');
		sssbox();
		sssRow.readOnly = false;
		sssRow.placeholder = "";
	}
	else
	{
		console.log('unchecked');
		sssRow.placeholder = "No document";
		sssRow.readOnly = true;
		sssRow.value = "";
	}
}
function philhealthCheckboxFunc() {
	var philhealth = document.getElementById('philhealthCheckbox');
	var philhealthRow = document.getElementById('philhealth');
	if(philhealth.checked == true)
	{
		philhealthRow.readOnly = false;
		philhealthRow.required = true;
		philhealthRow.placeholder = "";
	}
	else
	{
		philhealthRow.placeholder = "No document";
		philhealthRow.readOnly = true;
		philhealthRow.required = false;
		philhealthRow.value = "";
	}
}
function pagibigCheckboxFunc() {
	var pagibig = document.getElementById('pagibigCheckbox');
	var pagibigRow = document.getElementById('pagibig');
	if(pagibig.checked == true)
	{
		pagibigRow.readOnly = false;
		pagibigRow.required = true;
		pagibigRow.placeholder = "";
	}
	else
	{
		pagibigRow.placeholder = "No document";
		pagibigRow.readOnly = true;
		pagibigRow.required = false;
		pagibigRow.value = "";
	}
}
	</script>

</body>
</html>
<!--
      changeMonth: true,
      changeYear: true