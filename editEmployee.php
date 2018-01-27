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
	$salary = $employee_info['salary'];
	$rate = $employee_info['rate'];
	$allowance = $employee_info['allowance'];
	$site = $employee_info['site'];
	$sss = $employee_info['sss'];
	$philhealth = $employee_info['philhealth'];
	$pagibig = $employee_info['pagibig'];
	$empid = $employee_info['empid'];
	$emergency = $employee_info['emergency'];
	$reference = $employee_info['reference'];
	
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
	<div class="col-md-10 col-md-offset-1">
		<div class="row"><br>
			<div class="row text-center">
				<ol class="breadcrumb text-left">
					<li><a href='employees.php?site=<?php Print "$site"?>&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a></li>
					<li class="active">Edit employee details</li>
					<h4 style="float:right;">Employee ID: <?php Print "$empid"?></h4>
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
							<input type="text" class="form-control" id="fname" name = "firstname" value="<?php Print "$firstname"?>" disabled>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3">
							<label for="lname">Last name</label>
						</div>
						<div class="col-md-9">
							<input type="text" class="form-control" id="lname" name = "lastname" value="<?php Print "$lastname"?>" disabled>
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3">
							<label for="address">Address</label>
						</div>
						<div class="col-md-9">
							<input type="text" class="form-control" name = "address" placeholder="<?php Print "$address"?>" id="address">
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3">
							<label for="contact">Contact number</label>
						</div>
						<div class="col-md-5">
							<input type="text" class="form-control" name = "contactnum" placeholder="<?php Print "$contactnum"?>" id="contact">
						</div>
						<div class="col-md-1">
							<label for="contact">Date of Birth</label>
						</div>
						<div class="col-md-3">
							<input type="text" class="form-control" name = "dob" placeholder="<?php Print "$dob"?>" id="dtpkr_dob">
						</div>
					</div><br>
					<div class="row">
						<div class="col-md-3">
							<label for="contact">Civil Status</label>
						</div>
						<div class="col-md-9">
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
						<div class="col-md-3">
							<label for="contact">Date of Hire</label>
						</div>
						<div class="col-md-9">
							<input type="text" size="10" style="width:150px" name = "datehired" class="form-control" placeholder="<?php Print "$datehired"?>" id="dtpkr_datehired" >
						</div>
					</div>

					<div class="row pull-down">
								<div class="col-md-4">
									<label for="emergency">Emergency contact:</label>	
								</div>
								<div class="col-md-8">									
									<input name="emergencyContact" type="text" placeholder="<?php Print $emergency?>"class="form-control">
								</div>
							</div>

							<div class="row pull-down">
								<div class="col-md-4">
									<label for="emergency" class="no-wrap">Character Reference:</label>
								</div>
								<div class="col-md-8">
									<input name="characterReference" type="text" placeholder="<?php Print $reference?>" class="form-control">
								</div>
							</div>

						<div class="col-md-4 col-md-offset-3 pull-down">
							<input type='submit' class='btn btn-primary pull-down' value='Save Changes'>
						</div>
						<div class="col-md-1 pull-down">
							<input type='button' onclick="terminateEmployee('<?php Print $empid?>')" class='btn btn-danger pull-down' value='Terminate Employee'>
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
						<div class="col-md-5">
							<label for="position" class="text-right">Site</label>
						</div>
						<div class="col-md-5">
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

					<div class="row">
						<div class="col-md-5">
							<label for="rate">Monthly Salary</label>
						</div>
						<div class="col-md-5">
							<input name="salary"  type="text" class="form-control" id="monthlysalary" onkeyup="monthlySalary()" placeholder="<?php Print "$salary"?>" onchange="salaryDecimal()">
						</div>
					</div><br>

					<div class="row">
						<div class="col-md-5">
							<label for="rate">Rate Per Day</label>
						</div>
						<div class="col-md-5">
							<input name="rate"  type="text" placeholder="<?php Print "$rate"?>" class="form-control" id="rate" readonly>
						</div>
					</div><br>

					<div class="row">
						<div class="col-md-5">
							<label for="allowance">Allowance</label>
						</div>
						<div class="col-md-5">
							<input type="text" name="allowance" placeholder="<?php Print "$allowance"?>" class="form-control" onchange="allowanceDecimal()" id="allowance">
						</div>
					</div>
					<div class="row">
						<h4 class="modal-title"><br>Contributions</h4><hr>
						<!-- /////////////////////////// -->
						<div class="col-md-4">
							<label for="sss">SSS</label>
						</div>
						<div class="col-md-8">
							<?php
							if($sss != 0)
								Print  '
										<div class="col-md-12">
											<input type="checkbox" name="sssCheckbox" id="sssCheckbox" onchange="sssCheckboxFunc()" checked>
										</div>
										<div class="col-md-6">
											EE:
											<input name="sss" type="text" placeholder="'.$sss.'" class="form-control" id="sss">
										</div>
										<div class="col-md-6">
											ER:
											<input name="sss" type="text" placeholder="'.$sss.'" class="form-control" id="sss">
										</div>';
							else
								Print  '
										<div class="col-md-12">
											<input type="checkbox" name="sssCheckbox" id="sssCheckbox" onchange="sssCheckboxFunc()">
										</div>
										<div class="col-md-6">
											EE:
											<input name="sss" type="text" placeholder="No document" class="form-control" id="sss" readonly>
										</div>
										<div class="col-md-6">
											ER:
											<input name="sss" type="text" placeholder="No document" class="form-control" id="sss" readonly>
										</div>';
							?>
						</div>
						<div class="col-md-5">
							<label for="philhealth">Philhealth</label>
						</div>
						<div class="col-md-6">
							<?php
							if($philhealth != 0)
								Print '<div class="col-md-12">
										<input type="checkbox" name="philhealthCheckbox" id="philhealthCheckbox" onchange="philhealthCheckboxFunc()" checked>
										</div>
										<div class="col-md-6">
										EE:
										<input name="philhealth" placeholder="'.$philhealth.'" type="text" class="form-control" id="philhealth">
										</div>
										<div class="col-md-6">
										ER:
										<input name="philhealth" placeholder="'.$philhealth.'" type="text" class="form-control" id="philhealth">
										</div>';
							else
								Print '
										<div class="col-md-12">
										<input type="checkbox" name="philhealthCheckbox" id="philhealthCheckbox" onchange="philhealthCheckboxFunc()">
										</div>
										<div class="col-md-6">
										EE:
									   <input name="philhealth" placeholder="No document" type="text" class="form-control" id="philhealth" readonly>
									   </div>
									   <div class="col-md-6">
									   ER:
									   <input name="philhealth" placeholder="No document" type="text" class="form-control" id="philhealth" readonly>
									   </div>';
							?>
						</div>
						<div class="col-md-5">
							<label for="pagibig">Pag-IBIG</label>
						</div>
						<div class="col-md-6">
							<?php
							if($pagibig != 0)
								Print  '<div class="col-md-12">
										<input type="checkbox" name="pagibigCheckbox" id="pagibigCheckbox" onchange="pagibigCheckboxFunc()" checked>
										</div>
										<div class="col-md-6">
										EE:
										<input name="pagibig" placeholder="'.$pagibig.'" type="text" class="form-control" id="pagibig">
										</div>
										<div class="col-md-6">
										ER:
										<input name="pagibig" placeholder="'.$pagibig.'" type="text" class="form-control" id="pagibig">
										</div>';
							else
								Print  '<input type="checkbox" name="pagibigCheckbox" id="pagibigCheckbox" onchange="pagibigCheckboxFunc()">
										<input name="pagibig" placeholder="No document" type="text" class="form-control" id="pagibig" readonly>';
							?>
						</div>

						<div class="col-md-10 col-md-offset-1 pull-down text-center well well-sm">
						<!-- /////////////////////////// -->
						
							
								* SSS contribution is automatically computed.
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
	var sssRow = document.getElementById('sss');
	if(sss.checked == true)
	{
		console.log('checked');
		sssRow.readOnly = false;
		sssRow.placeholder = "";
		changedRate();
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

		function monthlySalary(salary){
			var salary = document.getElementById('monthlysalary').value;
			if(salary != ""){
				var dailyRate = document.getElementById('rate');
				dailyRate.setAttribute('value',(salary/25).toFixed(2));
				changedRate();
			}
		}

		function salaryDecimal(){
			console.log('s')
			var salary = document.getElementById('monthlysalary');
			var value = salary.value;
			var decimal = parseInt(value).toFixed(2);
			if(value != ""){
				console.log(salary);
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

		function changedRate() {
			var monthly = document.getElementById('monthlysalary').value;
			if(monthly == "")
				monthly = document.getElementById('monthlysalary').placeholder;

			var monthlySalary = monthly;
			var sssContribution = 0;
			var philhealthContribution = 0;

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
			else if(monthly >= 4250 && monthly <= 4749.9 )
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
			else if(monthly >= 6750 && monthly <= 7249.9 )
			sssContribution = 254.30;
			//7250 ~ 7749.9 = 272.50
			else if(monthly >= 7250 && monthly <= 7749.9 )
			sssContribution = 272.50;
			//7750 ~ 8249.9 = 290.70
			else if(monthly >= 7750 && monthly <=  8249.9 )
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

			var sssCheck = document.getElementById('sssCheckbox');
			// var philhealthCheck = document.getElementById('philhealthCheck');

			if(sssCheck.checked){
				document.getElementById('sss').value = sssContribution;
				
			}

			// if(philhealthCheck.checked){
   //  			document.getElementById('philhealth').value = philhealthContribution;
			// }
			
        	
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