<!DOCTYPE html>
<?php
include('directives/session.php');
if(isset($_GET['site']) && isset($_GET['position']))
{}
else
{
	header("location:payroll_login.php");
}
$site = $_GET['site'];
$position = $_GET['position'];
$empid = $_GET['empid'];
$date = strftime("%B %d, %Y");
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<style>
		.well
		{
			margin-bottom: 0px !important;
		}
	</style>
</head>
<body style="font-family: Quicksand;">
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
-->
<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<div class="row pull-down">
	<div class="col-md-10 col-md-offset-1">
		<ol class="breadcrumb text-left">

			<li><a href="payroll_table.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Table of Employees</a></li>
			<li class="active">[NAME OF EMPLOYEE] at [SITE NAME]</li>

			<button class="btn btn-success pull-right" style="margin-right:5px" onclick="saveChanges()">Save and compute <span class="glyphicon glyphicon-floppy-saved"></span></button>
		</ol>
	</div>
		<div class="col-md-10 col-md-offset-1">
			<?php
			$employee = "SELECT * FROM employee WHERE empid = '$empid'";
			$employeeQuery = mysql_query($employee);
			$empArr = mysql_fetch_assoc($employeeQuery);
			Print "
			<h2 class='text-left'>". $empArr['lastname'] .", ". $empArr['firstname'] ."</h2>
			<div class='row'>
				<div class='col-md-8 text-left' style='word-break: keep-all'>
					
						<h4>
							<b style='font-family: QuickSandMed'>
								Employee ID:
							</b>". $empArr['empid'] ."
						</h4>
						<h4>
							<b style='font-family: QuickSandMed'>
								Position:
							</b>". $empArr['position'] ."
						</h4>
						<h4>
							<b style='font-family: QuickSandMed'>
								Address:
							</b>". $empArr['address'] ."
						</h4>
						<h4>
							<b style='font-family: QuickSandMed'>
								Contact Number:
							</b>". $empArr['contactnum'] ."
						</h4>
				</div>";
				Print "
				<div class='col-md-4 text-right'>";
				if($empArr['philhealth'] != 0)//Phil Health Display
				{
					Print "<h4><span class='glyphicon glyphicon-ok'></span> PhilHealth documents</h4>";
				}
				else
				{
					Print "<h4><span class='glyphicon glyphicon-remove'></span> PhilHealth documents</h4>";
				}
				if($empArr['pagibig'] != 0)//Pagibig Display
				{
					Print "<h4><span class='glyphicon glyphicon-ok'></span> Pag-IBIG documents</h4>";
				}
				else
				{
					Print "<h4><span class='glyphicon glyphicon-remove'></span> Pag-IBIG documents</h4>";
				}
				if($empArr['sss'] != 0)//SSS Display
				{
					Print "<h4><span class='glyphicon glyphicon-ok'></span> SSS documents</h4>";
				}
				else
				{
					Print "<h4><span class='glyphicon glyphicon-remove'></span> SSS documents</h4>";
				}
				Print "</div>
				</div>";
			?>
		</div>
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-bordered table-condensed" style="background-color:white;">
				<?php
					$payrollDate = "SELECT * FROM attendance WHERE empid = '$empid' ORDER BY date ASC LIMIT 7";
					$payrollQuery = mysql_query($payrollDate);
					//Boolean for the conditions not to repeat just incase the employee does't attend sundays
					$monBool = true;
					$tueBool = true;
					$wedBool = true;
					$thuBool = true;
					$friBool = true;
					$satBool = true;
					$sunBool = true;
					//for absent dates
					$monAbsent = false;
					$tueAbsent = false;
					$wedAbsent = false;
					$thuAbsent = false;
					$friAbsent = false;
					$satAbsent = false;
					$sunAbsent = "No Sunday";
					while($dateRow = mysql_fetch_assoc($payrollQuery))
					{
						$day = date('l', strtotime($dateRow['date']));
						if($day == "Sunday" && $sunBool)
						{
							if($dateRow['attendance'] == 2)//Present
							{
								$sunTimeIn = $dateRow['timein'];
								$sunTimeOut = $dateRow['timeout'];
								$sunAbsent = "No Sunday";
							}
							$sunBool = false;
						}
						else if($day == "Monday" && $monBool)
						{
							if($dateRow['attendance'] == 2)//Present
							{
								$monTimeIn = $dateRow['timein'];
								$monTimeOut = $dateRow['timeout'];
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$monAbsent = true;
							}
							$monBool = false;
						}
						else if($day == "Tuesday" && $tueBool)//Tuesday
						{
							if($dateRow['attendance'] == 2)//Present
							{
								$tueTimeIn = $dateRow['timein'];
								$tueTimeOut = $dateRow['timeout'];
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$tueAbsent = true;
							}
							$tueBool = false;
						}
						else if($day == "Wednesday" && $wedBool)//Wednesday
						{
							if($dateRow['attendance'] == 2)//Present
							{
								$wedTimeIn = $dateRow['timein'];
								$wedTimeOut = $dateRow['timeout'];
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$wedAbsent = true;
							}
							$wedBool = false;
						}
						else if($day == "Thursday" && $thuBool)//Thursday
						{
							if($dateRow['attendance'] == 2)//Present
							{
								$thuTimeIn = $dateRow['timein'];
								$thuTimeOut = $dateRow['timeout'];
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$thuAbsent = true;
							}
							$thuBool = false;
						}
						else if($day == "Friday" && $friBool)//Friday
						{	
							if($dateRow['attendance'] == 2)//Present
							{
								$friTimeIn = $dateRow['timein'];
								$friTimeOut = $dateRow['timeout'];
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$friAbsent = true;
							}
							$friBool = false;
						}
						else if($day == "Saturday" && $satBool)//Saturday
						{
							if($dateRow['attendance'] == 2)//Present
							{
								$satTimeIn = $dateRow['timein'];
								$satTimeOut = $dateRow['timeout'];
							}
							else if($dateRow['attendance'] == 1)//Absent
							{
								$satAbsent = true;
							}
							$satBool = false;
						}					
					}
					$start_date = $date;
					$end_date = 'September 22, 2017';
					if ($end_date >= $start_date)
					{
					  for ($day = 0; $day < 7; $day++)
					  {
					    echo "<br />" . date("F j, Y", strtotime("$start_date +$day day"));
					    $yea = strtotime($start_date + $day);
					    echo $yea;
					  }
					}

				?>
				<tr>
					<td colspan="2">Wednesday</td>
					<td colspan="2">Thursday</td>
					<td colspan="2">Friday</td>
					<td colspan="2">Saturday</td>
					<td colspan="2">Sunday</td>
					<td colspan="2">Monday</td>
					<td colspan="2">Tuesday</td>
				</tr>
				<tr>
					<?php
						if(!$wedAbsent)
						{
							Print 	"	<td>Time In: ". $wedTimeIn ."</td>
										<td>Time Out: ". $wedTimeOut ."</td>";
						}
						else
						{
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$thuAbsent)
						{
							Print 	"	<td>Time In: ". $thuTimeIn ."</td>
										<td>Time Out: ". $thuTimeOut ."</td>";
						}
						else
						{
							
						}
						if(!$friAbsent != "absent")
						{
							Print 	"	<td>Time In: ". $friTimeIn ."</td>
										<td>Time Out: ". $friTimeOut ."</td>";
						}
						else
						{
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$satAbsent)
						{
							Print 	"	<td>Time In: ". $satTimeIn ."</td>
										<td>Time Out: ". $satTimeOut ."</td>";
						}
						else
						{
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if($sunAbsent != "No Sunday")
						{
							Print 	"	<td>Time In: ". $sunTimeIn ."</td>
										<td>Time Out: ". $sunTimeOut ."</td>";
						}
						else
						{
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$monAbsent)
						{
							Print 	"	<td>Time In: ". $monTimeIn ."</td>
										<td>Time Out: ". $monTimeOut ."</td>";
						}
						else
						{
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
						if(!$tueAbsent)
						{
							Print 	"	<td>Time In: ". $tueTimeIn ."</td>
										<td>Time Out: ". $tueTimeOut ."</td>";
						}
						else
						{
							Print 	"	<td colspan='2' class='danger'> Absent </td>";
						}
							
					?>
				</tr>
			</table>
		</div>
		<div class="col-md-10 col-md-offset-1">
			<div class="panel">
				<table class="table table-bordered table-responsive">
					<tr>
						<td style="background-color: peachpuff">
							<h4>Total hours rendered: 54</h4>
						</td>
						<td style="background-color: lemonchiffon">
							<h4>Total overtime: 0</h4>
						</td>
						<td style="background-color: powderblue">
							<h4>Total night differential: 0</h4>
						</td>
					</tr>
				</table>
				<div class="row">
					<form class="horizontal">
						<div class="col-md-2 col-md-offset-1">
							<h4>Loans</h4>
							<div class="form-group">
								<label class="control-label col-md-3" for="sss" >SSS</label>
								<div class="col-md-9">
									<input type="text" id="sss" class="form-control input-sm" placeholder="500PHP" onkeypress="validatenumber(event)">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3" for="pagibig">Pag-IBIG</label>
								<div class="col-md-9">
									<input type="text" id="pagibig" class="form-control input-sm" placeholder="250PHP" onkeypress="validatenumber(event)">
								</div>
							</div>
						</div>
						<div class="col-md-1">
							<h4 class="text-left">Vale</h4>
								<h5 class="text-left" style="white-space: nowrap;">1,000 PHP</h5>
								<div class="row">
									<button type="button" class="btn btn-success btn-sm col-md-12" data-toggle="modal" data-target="#addVale"><span class="glyphicon glyphicon-plus"></span> Add new</button>
									<button type="button" class="btn btn-danger btn-sm col-md-12" data-toggle="modal" data-target="#deductVale"><span class="glyphicon glyphicon-minus"></span> Deduct</button>
								</div>
						</div>
						<div class="col-md-3">
							<h4 class="text-left">Deductions</h4>
							<div class="form-group">
								<label class="control-label col-md-5" for="tax">Tax</label>
								<div class="col-md-7">
									<input type="text" id="tax" class="form-control input-sm" onkeypress="validatenumber(event)">
								</div>
								<label class="control-label col-md-5" for="sssContribution">SSS</label>
								<div class="col-md-7">
									<input type="text" id="sssContribution" class="form-control input-sm" onkeypress="validatenumber(event)">
								</div>
								<label class="control-label col-md-5" for="pagibigContribution" style="white-space: nowrap;">Pag-IBIG</label>
								<div class="col-md-7">
									<input type="text" id="pagibigContribution" class="form-control input-sm" disabled placeholder="No document">
								</div>
								<label class="control-label col-md-5" for="philhealth">PhilHealth</label>
								<div class="col-md-7">
									<input type="text" id="philhealth" class="form-control input-sm" onkeypress="validatenumber(event)">
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<h4 class="text-left">Tools</h4>
							<a class="btn btn-sm btn-primary col-md-1" onclick="addRow()"><span class="glyphicon glyphicon-plus"></span></a>
							<div class="form-group" id="toolform">
								<div id="1">
									<label class="control-label col-md-2" for="tools">Name</label>
									<div class="col-md-4">
										<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onkeypress="validateletter(event)">
									</div>
									<label class="control-label col-md-1" for="price">Price</label>
									<div class="col-md-4">
										<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)">
									</div>
								</div>
							</div>
							<!-- TEMPLATE -->
							<div class="form-group" id="template" style="display:none">
								<label class="control-label col-md-2" for="tools">Name</label>
								<div class="col-md-4">
									<input type="text" id="tools" name="toolname[]" class="form-control input-sm" onkeypress="validateletter(event)">
								</div>
								<label class="control-label col-md-1" for="price">Price</label>
								<div class="col-md-4">
									<input type="text" id="price" name="toolprice[]" class="form-control input-sm" onkeypress="validateprice(event)">
								</div>
							</div>
						</div>
					</form>
				</div>
				<br>
				<!-- MODALS -->
				<div class="modal fade" id="addVale">
				  <div class="modal-dialog modal-sm" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Add new vale</h4>
				      </div>
				      <div class="modal-body">
				      	Previous amount: 1,000 PHP
				      	<div class="row">
				      		<div class="col-md-8 col-md-offset-2">
				        		<input type="text" class="form-control" placeholder="Add as new vale" onkeypress="validatenumber(event)">
				    		</div>
				    	</div>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary">Add</button>
				      </div>
				    </div>
				  </div>
				</div>

				<div class="modal fade" id="deductVale">
				  <div class="modal-dialog modal-sm" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Deduct old vale</h4>
				      </div>
				      <div class="modal-body">
				      	Previous amount: 1,000 PHP
				      	<div class="row">
				      		<div class="col-md-8 col-md-offset-2">
				        		<input type="text" class="form-control col-md-4" placeholder="Deduct from old vale" onkeypress="validatenumber(event)">
				        	</div>
				        </div>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary">Deduct</button>
				      </div>
				    </div>
				  </div>
				</div>
			</div>
		</div>	
	</div>
</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");

var ct = 1;
function addRow(){
	ct++;
	var div1 = document.createElement('div');
	div1.id = ct;
	var delLink = '<div class="col-md-1" style="padding:0px"><button class="btn-sm btn btn-danger" onclick="deleteRow('+ ct +')"><span class="glyphicon glyphicon-minus"></span></button></div>';
	div1.innerHTML = delLink + document.getElementById('template').innerHTML;
	document.getElementById('toolform').appendChild(div1);
}

function deleteRow(eleId){
	var ele = document.getElementById(eleId);
	var parentEle = document.getElementById('toolform');
	parentEle.removeChild(ele);
	console.log(parentEle);
	console.log(ele);
	console.log(parentEle.children);
}

function validatenumber(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /[0-9]|\./;
	if( !regex.test(key) ) {
		 theEvent.returnValue = false;
	if(theEvent.preventDefault) 
		theEvent.preventDefault();
	}
}

function validateletter(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /^[a-zA-Z ]*$/;
	if( !regex.test(key) ) {
		 theEvent.returnValue = false;
	if(theEvent.preventDefault) 
		theEvent.preventDefault();
	}
}


function validateprice(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /^[0-9.,]+$/;
	if( !regex.test(key) ) {
		 theEvent.returnValue = false;
	if(theEvent.preventDefault) 
		theEvent.preventDefault();
	}
}

</script>

</div>
</body>
</html>
