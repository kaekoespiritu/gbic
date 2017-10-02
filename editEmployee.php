<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');

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
						<div class="col-md-12 pull-down">
							
							<input type='submit' class='btn btn-primary pull-down' value='Save Changes'>
							

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
								<select class="form-control" name="position" aria-labelledby="dropdownMenu1">
									<option hidden>Select a position</option>
									<?php
										$query = "SELECT position FROM job_position";
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
										$site_query = "SELECT location FROM site";
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
							<input name="salary"  type="text" class="form-control" id="monthlysalary" onkeyup="monthlySalary()" placeholder="<?php Print "$salary"?>" onblur="salaryDecimal()">
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
							<input type="text" name="allowance" placeholder="<?php Print "$allowance"?>" class="form-control" onblur="allowanceDecimal()" id="allowance">
						</div>
					</div>
					<div class="row">
						<h4 class="modal-title"><br>Contributions</h4><hr>

						<div class="row">
								<div class="col-md-5">
									<label style="font-weight: 700" for="sss">SSS</label>
								</div>
								<input type="checkbox" class="pull-left" name="sssCheck">
								<div class="col-md-4">
									<input type="text" class="form-control" name="sss" placeholder="<?php Print "$sss"?>" id="sss" readonly>
								</div>
						</div><br>
						<div class="row">
							<div class="col-md-5">
									<label style="font-weight: 700" for="philhealth" class="text-left">PhilHealth</label>
								</div>
								<input type="checkbox" class="pull-left" name="philhealthCheck">
								<div class="col-md-4">
									<input type="text" class="form-control" name="philhealth" placeholder="<?php Print "$philhealth"?>" id="philhealth" readonly>
								</div>
						</div><br>
						<div class="row">
							<div class="col-md-5">
								<label for="pagibig">Pag-IBIG</label>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control" name="pagibig" placeholder="<?php Print "$pagibig"?>" onblur="pagibigDecimal(event)" id="pagibig">
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
</form>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>

	<script>
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
			var dailyRate = document.getElementById('rate');
			dailyRate.setAttribute('value',(salary/25).toFixed(2));
			changedRate();
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
		}

		function changedRate() {
			var monthly = document.getElementById('monthlysalary').value;
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

			if(monthlySalary >= 1 && monthlySalary <= 8999.9)
	philhealthContribution = 200.00;
	//9000 ~ 9999.9 = 225
	else if(monthlySalary >= 9000 && monthlySalary <= 9999.9)
	philhealthContribution = 225.00;
	//10000 ~ 10999.9 = 250
	else if(monthlySalary >= 10000 && monthlySalary <= 10999.9)
	philhealthContribution = 250.00;
	//11000 ~ 11999.9 = 275
	else if(monthlySalary >= 11000 && monthlySalary <= 11999.9)
	philhealthContribution = 275.00;
	//12000 ~ 12999.9 = 300
	else if(monthlySalary >= 12000 && monthlySalary <= 12999.9)
	philhealthContribution = 300.00;
	//13000 ~ 13999.9 = 325
	else if(monthlySalary >= 13000 && monthlySalary <= 13999.9)
	philhealthContribution = 325.00;
	//14000 ~ 14999.9 = 350
	else if(monthlySalary >= 14000 && monthlySalary <= 14999.9)
	philhealthContribution = 350.00;
	//15000 ~ 15999.9 = 375
	else if(monthlySalary >= 15000 && monthlySalary <= 15999.9)
	philhealthContribution = 375.00;
	//16000 ~ 16999.9 = 400
	else if(monthlySalary >= 16000 && monthlySalary <= 16999.9)
	philhealthContribution = 400.00;
	//17000 ~ 17999.9 = 425
	else if(monthlySalary >= 17000 && monthlySalary <= 17999.9)
	philhealthContribution = 425.00;
	//18000 ~ 18999.9 = 450
	else if(monthlySalary >= 18000 && monthlySalary <= 18999.9)
	philhealthContribution = 450.00;
	//19000 ~ 19999.9 = 475
	else if(monthlySalary >= 19000 && monthlySalary <= 19999.9)
	philhealthContribution = 475.00;
	//20000 ~ 20999.9 = 500
	else if(monthlySalary >= 20000 && monthlySalary <= 20999.9)
	philhealthContribution = 500.00;
	//21000 ~ 21999.9 = 525
	else if(monthlySalary >= 21000 && monthlySalary <= 21999.9)
	philhealthContribution = 525.00;
	//22000 ~ 22999.9 = 550
	else if(monthlySalary >= 22000 && monthlySalary <= 22999.9)
	philhealthContribution = 550.00;
	//23000 ~ 23999.9 = 575
	else if(monthlySalary >= 23000 && monthlySalary <= 23999.9)
	philhealthContribution = 575.00;
	//24000 ~ 24999.9 = 600
	else if(monthlySalary >= 24000 && monthlySalary <= 24999.9)
	philhealthContribution = 600.00;
	//25000 ~ 25999.9 = 625
	else if(monthlySalary >= 25000 && monthlySalary <= 25999.9)
	philhealthContribution = 625.00;
	//26000 ~ 26999.9 = 650
	else if(monthlySalary >= 26000 && monthlySalary <= 26999.9 )
	philhealthContribution = 650.00;
	//27000 ~ 27999.9 = 675
	else if(monthlySalary >= 27000 && monthlySalary <= 27999.9)
	philhealthContribution = 675.00;
	//28000 ~ 28999.9 = 700
	else if(monthlySalary >= 28000 && monthlySalary <= 28999.9)
	philhealthContribution = 700.00;
	//29000 ~ 29999.9 = 725
	else if(monthlySalary >= 29000 && monthlySalary <= 29999.9)
	philhealthContribution = 725.00;
	//30000 ~ 30999.9 = 750
	else if(monthlySalary >= 30000 && monthlySalary <= 30999.9)
	philhealthContribution = 750.00;
	//31000 ~ 31999.9 = 775
	else if(monthlySalary >= 31000 && monthlySalary <= 31999.9)
	philhealthContribution = 775.00;
	//32000 ~ 32999.9 = 800
	else if(monthlySalary >= 32000 && monthlySalary <= 32999.9)
	philhealthContribution = 800.00;
	//33000 ~ 339999.9 = 825
	else if(monthlySalary >= 33000 && monthlySalary <= 339999.9)
	philhealthContribution = 825.00;
	//34000 ~ 349999.9 = 850
	else if(monthlySalary >= 34000 && monthlySalary <= 349999.9)
	philhealthContribution = 850.00
	//35000 ~ higher = 875
	else if(monthlySalary >= 35000)
	philhealthContribution = 875.00;			

				philhealthContribution = philhealthContribution.toFixed(2);
				sssContribution = sssContribution.toFixed(2);
				document.getElementById('sss').value = sssContribution;
        		document.getElementById('philhealth').value = philhealthContribution;
        	
		}
		
	function Editemp(id) {
	  	window.location.assign("logic_edit_employee.php?empid="+id);	
	}
	</script>


	</div>
</body>
</html>