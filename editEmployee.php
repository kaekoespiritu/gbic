<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');

$empid = $_GET['empid'];

$query = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
$employee_query = mysql_query($query);
$employee_info = mysql_fetch_assoc($employee_query);

$firstname = $employee_info['firstname'];
$lastname =  $employee_info['lastname'];
$address = $employee_info['address'];
$contactnum = $employee_info['contactnum'];
$dob = $employee_info['dob'];
$datehired = $employee_info['datehired'];
$civilstatus = $employee_info['civilstatus'];
$position = $employee_info['position'];
	// $salary = $employee_info['salary'];
$rate = $employee_info['rate'];
$allowance = $employee_info['allowance'];
$site = $employee_info['site'];
$sssEE = $employee_info['sss'];
$sssER = $employee_info['sss_er'];
$philhealthEE = $employee_info['philhealth'];
$philhealthER = $employee_info['philhealth_er'];
$pagibigEE = $employee_info['pagibig'];
$pagibigER = $employee_info['pagibig_er'];
$empid = $employee_info['empid'];
$emergency = $employee_info['emergency'];
$reference = $employee_info['reference'];
$cola = $employee_info['cola'];

?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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

	<?php
	Print "<form method='post' action='logic_edit_employee.php?empid=".$empid."'>";
	?>
	<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
		<div class="row"><br>
			<div class="row text-center">
				<ol class="breadcrumb text-left">
					<li><a href='employees.php?site=<?php Print "$site"?>&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a></li>
					<li class="active">Edit employee details</li>
					<input style="float:right; margin-left:2em" type='button' onclick="terminateEmployee('<?php Print $empid?>')" class='btn btn-danger' value='Terminate Employee'>
					<h4 style="float:right;">Employee ID: <?php Print "$empid"?></h4>
				</ol>
			</div>
		</div>
		<form class="horizontal">
			<div class="row">
				<div class="col-md-6 col-lg-6">
					<h4 class="modal-title">Personal Information</h4><hr>
					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="fname">First name</label>
						</div>
						<div class="col-md-9 col-lg-9">
							<input type="text" class="form-control" id="fname" name = "firstname" value="<?php Print "$firstname"?>" disabled>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="lname">Last name</label>
						</div>
						<div class="col-md-9 col-lg-9">
							<input type="text" class="form-control" id="lname" name = "lastname" value="<?php Print "$lastname"?>" disabled>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="address">Address</label>
						</div>
						<div class="col-md-9 col-lg-9">
							<input type="text" class="form-control" name = "address" placeholder="<?php Print "$address"?>" id="address">
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="contact">Contact number</label>
						</div>
						<div class="col-md-5 col-lg-5">
							<input type="number" class="form-control" name = "contactnum" placeholder="<?php Print "$contactnum"?>" id="contact">
						</div>
						<div class="col-md-1 col-lg-1">
							<label for="contact">Date of Birth</label>
						</div>
						<div class="col-md-3 col-lg-3">
							<input type="text" class="form-control" name = "dob" placeholder="<?php Print "$dob"?>" id="dtpkr_dob">
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="contact">Civil Status</label>
						</div>
						<div class="col-md-9 col-lg-9">
							<div class="dropdown">
								<select class="form-control" name = "civilstatus" aria-labelledby="dropdownMenu1">
									<option hidden>Select a status</option>
									<?php
									$status_query = "SELECT status FROM civil_status";
									$civilstatus_query = mysql_query($status_query);
									while($row = mysql_fetch_assoc($civilstatus_query))
									{
										if($civilstatus == $row['status'])
										{
											Print '<option selected="selected" value="'.$row["status"].'">'.$row["status"].'</option>';
										}
										else
										{
											Print '<option value="'.$row["status"].'">'.$row["status"].'</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
					</div><br>

					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="contact">Date of Hire</label>
						</div>
						<div class="col-md-9 col-lg-9">
							<input type="text" size="10" style="width:150px" name = "datehired" class="form-control" placeholder="<?php Print "$datehired"?>" id="dtpkr_datehired" >
						</div>
					</div>

					<div class="row pull-down">
						<div class="col-md-4 col-lg-4">
							<label for="emergency">Emergency contact:</label>	
						</div>
						<div class="col-md-8 col-lg-8">									
							<input name="emergencyContact" type="text" placeholder="<?php Print $emergency?>" class="form-control">
						</div>
					</div>

					<div class="row pull-down">
						<div class="col-md-4 col-lg-4">
							<label for="characterReference" class="no-wrap">Character Reference:</label>
						</div>
						<div class="col-md-8 col-lg-8">
							<input name="characterReference" type="text" placeholder="<?php Print $reference?>" class="form-control">
						</div>
					</div>

					<div class="row pull-down">
						<div class="col-md-4 col-lg-4">
							<label for="cola" class="no-wrap">COLA:</label>
						</div>
						<div class="col-md-4 col-lg-4">
							<input name="cola" type="text" placeholder="<?php Print $cola?>" class="form-control">
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
								<select class="form-control" name="position" aria-labelledby="dropdownMenu1">
									<option hidden>Select a position</option>
									<?php
									$query = "SELECT position FROM job_position WHERE active = '1'";
									$job_query = mysql_query($query);
									while($row = mysql_fetch_assoc($job_query))
									{
										if($position == $row['position'])
										{
											Print '<option selected="selected" value="'.$row["position"].'">'.$row["position"].'</option>';
										}
										else
										{
											Print '<option value="'.$row["position"].'">'.$row["position"].'</option>';
										}
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
								<select name="site" class="form-control">
									<option hidden>Select a site</option>
									<?php
									$site_query = "SELECT location FROM site WHERE active = '1'";
									$location_query = mysql_query($site_query);
									while($row = mysql_fetch_assoc($location_query))
									{
										if($site == $row['location'])
										{
											Print '<option selected="selected" value="'.$row["location"].'">'.$row["location"].'</option>';
										}
										else
										{
											Print '<option value="'.$row["location"].'">'.$row["location"].'</option>';
										}
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
							<input name="salary"  type="number" class="form-control" id="monthlysalary" onkeyup="monthlySalary()" placeholder="<?php //Print "$salary"?>" onchange="salaryDecimal()">
						</div>
					</div><br> -->

					<div class="row">
						<div class="col-md-5 col-lg-5">
							<label for="rate">Rate Per Day</label>
						</div>
						<div class="col-md-5 col-lg-5">
							<input name="rate"  type="text" onchange="salaryDecimal()" placeholder="<?php Print $rate?>" class="form-control" id="rate">
						</div>
					</div><br>

					<div class="row">
						<div class="col-md-5 col-lg-5">
							<label for="allowance">Allowance</label>
						</div>
						<div class="col-md-5 col-lg-5">
							<input type="number" name="allowance" placeholder="<?php Print "$allowance"?>" class="form-control" onchange="allowanceDecimal()" id="allowance">
						</div>
					</div>
					<div class="row">
						<h4 class="modal-title"><br>Contributions</h4><hr>
						<!-- /////////////////////////// -->
						<?php
						if($sssEE != 0)
							Print  '
						<div class="col-md-1 col-lg-12">
						<div class="col-md-3 col-lg-3">
						<input type="checkbox" name="sssCheckbox" id="sssCheckbox" onchange="sssCheckboxFunc()" checked>
						<label for="sss">SSS</label>
						</div>
						<div class="col-md-9 col-lg-9">
						<div class="row">
						<div class="col-md-1 col-lg-1">
						<label for="sss_ee">EE:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="sssEE" type="number" placeholder="'.$sssEE.'" class="form-control" id="sssEE">
						</div>
						<div class="col-md-1 col-lg-1">
						<label for="sss_er">ER:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="sssER" type="number" placeholder="'.$sssER.'" class="form-control" id="sssER">
						</div>
						</div>
						</div>
						</div>';
						else
							Print  '
						<div class="col-md-1 col-lg-12">
						<div class="col-md-3 col-lg-3">
						<input type="checkbox" name="sssCheckbox" id="sssCheckbox" onchange="sssCheckboxFunc()">
						<label for="sss">SSS</label>
						</div>
						<div class="col-md-9 col-lg-9">
						<div class="row">
						<div class="col-md-1 col-lg-1">
						<label for="sss_ee">EE:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="sssEE" type="number" placeholder="No document" class="form-control" id="sssEE" readonly>
						</div>
						<div class="col-md-1 col-lg-1">
						<label for="sss_er">ER:<label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="sssER" type="number" placeholder="No document" class="form-control" id="sssER" readonly>
						</div>
						</div>
						</div>
						</div>';
						?>

						<?php
						if($philhealthEE != 0)
							Print '<div class="col-md-1 col-lg-12 pull-down">
						<div class="col-md-3 col-lg-3">
						<input type="checkbox" name="philhealthCheckbox" id="philhealthCheckbox" onchange="philhealthCheckboxFunc()" checked>
						<label for="philhealth" class="nowrap">PhilHealth</label>
						</div>
						<div class="col-md-9 col-lg-9">
						<div class="row">
						<div class="col-md-1 col-lg-1">
						<label for="philhealth_ee">EE:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="philhealthEE" placeholder="'.$philhealthEE.'" type="number" class="form-control" id="philhealthEE">
						</div>
						<div class="col-md-1 col-lg-1">
						<label for="philhealth_er">ER:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="philhealthER" placeholder="'.$philhealthER.'" type="number" class="form-control" id="philhealthER">
						</div>
						</div>
						</div>
						</div>';
						else
							Print '
						<div class="col-md-1 col-lg-12 pull-down">
						<div class="col-md-3 col-lg-3">
						<input type="checkbox" name="philhealthCheckbox" id="philhealthCheckbox" onchange="philhealthCheckboxFunc()">
						<label for="philhealth" class="nowrap">PhilHealth</label>
						</div>
						<div class="col-md-9 col-lg-9">
						<div class="row">
						<div class="col-md-1 col-lg-1">
						<label for="philhealth_ee">EE:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="philhealthEE" placeholder="No document" type="text" class="form-control" id="philhealthEE" readonly>
						</div>
						<div class="col-md-1 col-lg-1">
						<label for="philhealth_er">ER:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="philhealthER" placeholder="No document" type="text" class="form-control" id="philhealthER" readonly>
						</div>
						</div>
						</div>
						</div>';
						?>
						
						<?php
						if($pagibigEE != 0)
							Print  '<div class="col-md-1 col-lg-12 pull-down">
						<div class="col-md-3 col-lg-3">
						<input type="checkbox" name="pagibigCheckbox" id="pagibigCheckbox" onchange="pagibigCheckboxFunc()" checked>
						<label for="pagibig" class="nowrap">Pagibig</label>
						</div>
						<div class="col-md-9 col-lg-9">
						<div class="row">
						<div class="col-md-1 col-lg-1">
						<label for="pagibig_ee">EE:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="pagibigEE" placeholder="'.$pagibigEE.'" type="text" class="form-control" id="pagibigEE">
						</div>
						<div class="col-md-1 col-lg-1">
						<label for="pagibig_er">ER:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="pagibigER" placeholder="'.$pagibigER.'" type="text" class="form-control" id="pagibigER">
						</div>
						</div>
						</div>
						</div>';
						else
							Print  '<div class="col-md-1 col-lg-12 pull-down">
						<div class="col-md-3 col-lg-3">
						<input type="checkbox" name="pagibigCheckbox" id="pagibigCheckbox" onchange="pagibigCheckboxFunc()">
						<label for="pagibig" class="nowrap">Pagibig</label>
						</div>
						<div class="col-md-9 col-lg-9">
						<div class="row">
						<div class="col-md-1 col-lg-1">
						<label for="pagibig_ee">EE:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="pagibigEE" placeholder="No document" type="text" class="form-control" id="pagibigEE" readonly>
						</div>
						<div class="col-md-1 col-lg-1">
						<label for="pagibig_er">ER:</label>
						</div>
						<div class="col-md-4 col-lg-4">
						<input name="pagibigER" placeholder="No document" type="text" class="form-control" id="pagibigER" readonly>
						</div>
						</div>
						</div>
						</div>';
						?>
					</div>

					<div class="col-sm-10 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down text-center well well-sm">
						Note: Check boxes if employee has document for<br>SSS / PhilHealth / Pagibig.
					</div>
					<div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
						<input type='submit' class='btn btn-primary pull-down' value='Save Changes'>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
</form>
<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>

<script>
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

				changedRate();
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

		document.getElementById("employees").setAttribute("style", "background-color: #10621e;");

		$("#dtpkr_datehired").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 10) 
			}
		});
		$("#dtpkr_dob").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 10) 
			}
		});

		// function monthlySalary(salary){
		// 	var salary = document.getElementById('monthlysalary').value;
		// 	if(salary != ""){
		// 		var dailyRate = document.getElementById('rate');
		// 		dailyRate.setAttribute('value',(salary/25).toFixed(2));
		// 		changedRate();
		// 	}
		// }

		function salaryDecimal(){
			var salary = document.getElementsByName('rate')[0];
			var value = salary.value;
			var decimal = parseInt(value).toFixed(2);
			if(value != ""){
				salary.value=decimal;
			}
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
		}

		// function changedRate() {
		// 	var monthly = document.getElementById('monthlysalary').value;
		// 	if(monthly == "")
		// 		monthly = document.getElementById('monthlysalary').placeholder;

		// 	var monthlySalary = monthly;
		// 	var sssContribution = 0;
		// 	var sssEmployer = 0;

		// 	if(monthly >= 1000 && monthly <= 1249.9){
		// 		sssContribution = 36.30;
		// 		sssEmployer = 83.70;
		// 	}
		// 	//1250 ~ 1749.9 = 54.50
		// 	else if(monthly >= 1250 && monthly <= 1749.9) {
		// 		sssContribution = 54.50;
		// 		sssEmployer = 120.50;
		// 	}
		// 	//1750 ~ 2249.9 = 72.70
		// 	else if(monthly >= 1750 && monthly <= 2249.9) {
		// 		sssContribution = 72.70;	
		// 		sssEmployer = 157.30;
		// 	}
		// 	//2250 ~ 2749.9 = 90.80
		// 	else if(monthly >= 2250 && monthly <= 2749.9) {
		// 		sssContribution = 90.80;
		// 		sssEmployer = 194.20;
		// 	}
		// 	//2750 ~ 3249.9 = 109.0
		// 	else if(monthly >= 2750 && monthly <= 3249.9){
		// 		sssContribution = 109.00;
		// 		sssEmployer = 231.00;
		// 	}
		// 	//3250 ~ 3749.9 = 127.20
		// 	else if(monthly >= 3250 && monthly <= 3749.9){
		// 		sssContribution = 127.20;
		// 		sssEmployer = 267.80;
		// 	}
		// 	//3750 ~ 4249.9 = 145.30
		// 	else if(monthly >= 3750 && monthly <= 4249.9){
		// 		sssContribution = 145.30;
		// 		sssEmployer = 304.70;
		// 	}
		// 	//4250 ~ 4749.9 = 163.50
		// 	else if(monthly >= 4250 && monthly <= 4749.9 ){
		// 		sssContribution = 163.50;
		// 		sssEmployer = 341.50;
		// 	}
		// 	//4750 ~ 5249.9 = 181.70
		// 	else if(monthly >= 4750 && monthly <= 5249.9){
		// 		sssContribution = 181.70;
		// 		sssEmployer = 378.30;
		// 	}
		// 	//5250 ~ 5749.9 = 199.80
		// 	else if(monthly >= 5250 && monthly <= 5749.9){
		// 		sssContribution = 199.80;
		// 		sssEmployer = 415.20;
		// 	}
		// 	//5750 ~ 6249.9 = 218.0
		// 	else if(monthly >= 5750 && monthly <= 6249.9){
		// 		sssContribution = 218.00;
		// 		sssEmployer = 452.00;
		// 	}
		// 	//6250 ~ 6749.9 = 236.20
		// 	else if(monthly >= 6250 && monthly <= 6749.9){
		// 		sssContribution = 236.20;
		// 		sssEmployer = 488.80;
		// 	}
		// 	//6750 ~ 7249.9 = 254.30
		// 	else if(monthly >= 6750 && monthly <= 7249.9){
		// 		sssContribution = 254.30;
		// 		sssEmployer = 525.70;
		// 	}
		// 	//7250 ~ 7749.9 = 272.50
		// 	else if(monthly >= 7250 && monthly <= 7749.9){
		// 		sssContribution = 272.50;
		// 		sssEmployer = 562.50;
		// 	}
		// 	//7750 ~ 8249.9 = 290.70
		// 	else if(monthly >= 7750 && monthly <=  8249.9 ){
		// 		sssContribution = 290.70;
		// 		sssEmployer = 599.30;
		// 	}
		// 	//8250 ~ 8749.9 = 308.80
		// 	else if(monthly >= 8250 && monthly <= 8749.9){
		// 		sssContribution = 308.80;
		// 		sssEmployer = 636.20;
		// 	}
		// 	//8750 ~ 9249.9 = 327.0
		// 	else if(monthly >= 8750 && monthly <= 9249.9){
		// 		sssContribution = 327.00;
		// 		sssEmployer = 673.00;
		// 	}
		// 	//9250 ~ 9749.9 = 345.20
		// 	else if(monthly >= 9250 && monthly <= 9749.9){
		// 		sssContribution = 345.20;
		// 		sssEmployer = 709.80;
		// 	}
		// 	//9750 ~ 10249.9 = 363.30
		// 	else if(monthly >= 9750 && monthly <= 10249.9){
		// 		sssContribution = 363.30;
		// 		sssEmployer = 746.70;
		// 	}
		// 	//10250 ~ 10749.9 = 381.50
		// 	else if(monthly >= 10250 && monthly <=  10749.9){
		// 		sssContribution = 381.50;
		// 		sssEmployer = 783.50;
		// 	}
		// 	//10750 ~ 11249.9 = 399.70
		// 	else if(monthly >= 10750 && monthly <= 11249.9){
		// 		sssContribution = 399.70;
		// 		sssEmployer = 820.30;
		// 	}
		// 	//11250 ~ 11749.9 = 417.80
		// 	else if(monthly >= 11250 && monthly <= 11749.9){
		// 		sssContribution = 417.80;
		// 		sssEmployer = 857.20;
		// 	}
		// 	//11750 ~ 12249.9 = 436.0
		// 	else if(monthly >= 11750 && monthly <= 12249.9){
		// 		sssContribution = 436.00;
		// 		sssEmployer = 894.00;
		// 	}
		// 	//12250 ~ 12749.9 = 454.20
		// 	else if(monthly >= 12250 && monthly <= 12749.9){
		// 		sssContribution = 454.20;
		// 		sssEmployer = 930.80;
		// 	}
		// 	//12750 ~ 13249.9 = 472.30
		// 	else if(monthly >= 12750 && monthly <= 13249.9){
		// 		sssContribution = 472.30;
		// 		sssEmployer = 967.70;
		// 	}
		// 	//13250 ~ 13749.9 = 490.50
		// 	else if(monthly >= 13250 && monthly <= 13749.9){
		// 		sssContribution = 490.50;
		// 		sssEmployer = 1004.5;
		// 	}
		// 	//13750 ~ 14249.9 = 508.70
		// 	else if(monthly >= 13750 && monthly <= 14249.9){
		// 		sssContribution = 508.70;
		// 		sssEmployer = 1041.30;
		// 	}
		// 	//14250 ~ 14749.9 = 526.80
		// 	else if(monthly >= 14250 && monthly <= 14749.9){
		// 		sssContribution = 526.80;
		// 		sssEmployer = 1070.20;
		// 	}
		// 	//14750 ~ 15249.9 = 545.0
		// 	else if(monthly >= 14750 && monthly <= 15249.9){
		// 		sssContribution = 545.00;
		// 		sssEmployer = 1135.00;
		// 	}
		// 	//15250 ~ 15749.9 = 563.20
		// 	else if(monthly >= 15250 && monthly <= 15749.9){
		// 		sssContribution = 563.20;
		// 		sssEmployer = 1171.80;
		// 	}
		// 	//15750 ~ higher = 581.30
		// 	else if(monthly >= 15750){
		// 		sssContribution = 581.30;
		// 		sssEmployer = 1208.70;
		// 	}


		// 	sssContribution = sssContribution.toFixed(2);
		// 	sssEmployer = sssEmployer.toFixed(2);
		// 	var sssCheck = document.getElementById('sssCheckbox');

		// 	if(sssCheck.checked){
		// 		document.getElementById('sssEE').value = sssContribution;
		// 		document.getElementById('sssER').value = sssEmployer;

		// 	}

		// }
		
		function Editemp(id) {
			window.location.assign("logic_edit_employee.php?empid="+id);	
		}

		function terminateEmployee(id) {

			var con = confirm("Are you sure you want to terminate this employee?");
			if(con) {
				window.location.assign("logic_terminate_employee.php?empid="+id);
			}
		}
	</script>


</div>
</body>
</html>