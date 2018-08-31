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
$insurance = $employee_info['insurance'];
$contributions = $employee_info['complete_doc'];

?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css" type="text/css">
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
							<label for="lname">Last name</label>
						</div>
						<div class="col-md-9 col-lg-9">
							<input type="text" autocomplete="off" class="form-control" id="lname" name = "lastname" value="<?php Print "$lastname"?>" disabled>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="fname">First name</label>
						</div>
						<div class="col-md-9 col-lg-9">
							<input type="text" autocomplete="off" class="form-control" id="fname" name = "firstname" value="<?php Print "$firstname"?>" disabled>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="address">Address</label>
						</div>
						<div class="col-md-9 col-lg-9">
							<input type="text" autocomplete="off" class="form-control" name = "address" placeholder="<?php Print "$address"?>" id="address">
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3 col-lg-3">
							<label for="contact">Contact number</label>
						</div>
						<div class="col-md-5 col-lg-5">
							<input type="number" autocomplete="off" class="form-control" name = "contactnum" placeholder="<?php Print "$contactnum"?>" id="contact">
						</div>
						<div class="col-md-1 col-lg-1">
							<label for="contact">Date of Birth</label>
						</div>
						<div class="col-md-3 col-lg-3">
							<input type="text" class="form-control" autocomplete="off" name = "dob" placeholder="<?php Print "$dob"?>" id="dtpkr_dob">
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
							<input type="text" size="10" style="width:150px" autocomplete="off" name = "datehired" class="form-control" placeholder="<?php Print "$datehired"?>" id="dtpkr_datehired" >
						</div>
					</div>

					<div class="row pull-down">
						<div class="col-md-4 col-lg-4">
							<label for="emergency">Emergency contact:</label>	
						</div>
						<div class="col-md-8 col-lg-8">									
							<input name="emergencyContact" type="text" autocomplete="off" placeholder="<?php Print $emergency?>" class="form-control">
						</div>
					</div>

					<div class="row pull-down">
						<div class="col-md-4 col-lg-4">
							<label for="characterReference" class="no-wrap">Character Reference:</label>
						</div>
						<div class="col-md-8 col-lg-8">
							<input name="characterReference" type="text" autocomplete="off" placeholder="<?php Print $reference?>" class="form-control">
						</div>
					</div>

					<div class="row pull-down">
						<div class="col-md-4 col-lg-4">
							<label for="cola" class="no-wrap">COLA:</label>
						</div>
						<div class="col-md-4 col-lg-4">
							<input name="cola" type="text" autocomplete="off" placeholder="<?php Print $cola?>" class="form-control">
						</div>
					</div>
					<div class="row pull-down">
						<div class="col-md-4 col-lg-4">
							<label for="insurance" class="no-wrap">Insurance:</label>
						</div>
						<div class="col-md-4 col-lg-4">
							<input name="insurance" type="text" autocomplete="off" placeholder="<?php Print $insurance?>" class="form-control">
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
									<option disabled value="" selected>Select a position</option>
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
									<option disabled value="" selected>Select a site</option>
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

					<div class="row">
						<div class="col-md-5 col-lg-5">
							<label for="rate">Rate Per Day</label>
						</div>
						<div class="col-md-5 col-lg-5">
							<input name="rate"  type="text" autocomplete="off" onchange="salaryDecimal()" placeholder="<?php Print $rate?>" class="form-control" id="rate">
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
						if($sssEE != 0 || $contributions == 1)
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
												<input name="sssEE" type="text" autocomplete="off" placeholder="'.$sssEE.'" class="form-control" id="sssEE">
											</div>
											<div class="col-md-1 col-lg-1">
												<label for="sss_er">ER:</label>
											</div>
											<div class="col-md-4 col-lg-4">
												<input name="sssER" type="text" autocomplete="off" placeholder="'.$sssER.'" class="form-control" id="sssER">
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
												<input name="sssEE" type="text" autocomplete="off" placeholder="No document" class="form-control" id="sssEE" readonly>
											</div>
												<div class="col-md-1 col-lg-1">
												<label for="sss_er">ER:<label>
											</div>
											<div class="col-md-4 col-lg-4">
												<input name="sssER" type="text" autocomplete="off" placeholder="No document" class="form-control" id="sssER" readonly>
											</div>
										</div>
									</div>
								</div>';
						?>

						<?php
						if($philhealthEE != 0  || $contributions == 1)
							Print '
								<div class="col-md-1 col-lg-12 pull-down">
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
												<input name="philhealthEE" placeholder="'.$philhealthEE.'" type="text" autocomplete="off" class="form-control" id="philhealthEE">
											</div>
											<div class="col-md-1 col-lg-1">
												<label for="philhealth_er">ER:</label>
											</div>
											<div class="col-md-4 col-lg-4">
												<input name="philhealthER" placeholder="'.$philhealthER.'" type="text" autocomplete="off" class="form-control" id="philhealthER">
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
										<input name="philhealthEE" placeholder="No document" type="text" autocomplete="off" class="form-control" id="philhealthEE" readonly>
									</div>
									<div class="col-md-1 col-lg-1">
										<label for="philhealth_er">ER:</label>
									</div>
									<div class="col-md-4 col-lg-4">
										<input name="philhealthER" placeholder="No document" type="text" class="form-control" autocomplete="off" id="philhealthER" readonly>
									</div>
								</div>
							</div>
						</div>';
						?>
						
						<?php
						if($pagibigEE != 0  || $contributions == 1)
							Print  '
								<div class="col-md-1 col-lg-12 pull-down">
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
												<input name="pagibigEE" placeholder="'.$pagibigEE.'" type="text" autocomplete="off" class="form-control" id="pagibigEE">
											</div>
											<div class="col-md-1 col-lg-1">
												<label for="pagibig_er">ER:</label>
											</div>
											<div class="col-md-4 col-lg-4">
												<input name="pagibigER" placeholder="'.$pagibigER.'" type="text" autocomplete="off" class="form-control" id="pagibigER">
											</div>
										</div>
									</div>
								</div>';
						else
							Print  '
								<div class="col-md-1 col-lg-12 pull-down">
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
												<input name="pagibigEE" placeholder="No document" type="text" autocomplete="off" class="form-control" id="pagibigEE" readonly>
											</div>
												<div class="col-md-1 col-lg-1">
												<label for="pagibig_er">ER:</label>
											</div>
											<div class="col-md-4 col-lg-4">
												<input name="pagibigER" placeholder="No document" type="text" autocomplete="off" class="form-control" id="pagibigER" readonly>
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
<script rel="javascript" src="js/jquery-ui/external/jquery/jquery.js"></script>
<script rel="javascript" src="js/jquery-ui/jquery-ui.js"></script>
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
			dateFormat: 'MM dd, yy',
			showAnim: 'blind',
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			}
		});
		$("#dtpkr_dob").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			}
		});

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