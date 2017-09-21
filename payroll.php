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
			<h2 class="text-left">Miguelito Joselito Dela Cruz</h2>
			<div class="row">
				<div class="col-md-8 text-left" style="word-break: keep-all">
					<h4><b style="font-family: QuickSandMed">Employee ID:</b> 2014-1352845</h4>
					<h4><b style="font-family: QuickSandMed">Position:</b> Mason </h4>
					<h4><b style="font-family: QuickSandMed">Address:</b> 97 Waco St. Greenheights Village, Quezon City</h4>
					<h4><b style="font-family: QuickSandMed">Contact Number:</b> 09123456789</h4>
				</div>
				<div class="col-md-4 text-right">
					<h4><span class="glyphicon glyphicon-ok"></span> PhilHealth documents</h4>
					<h4><span class="glyphicon glyphicon-remove"></span> Pag-IBIG documents</h4>
					<h4><span class="glyphicon glyphicon-ok"></span> SSS documents</h4>
				</div>
			</div>
		</div>
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-bordered table-condensed" style="background-color:white;">
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
					<td>Time In:  8:00AM </td>
					<td>Time Out:  5:00PM </td>
					<td>Time In:  8:00AM </td>
					<td>Time Out:  5:00PM </td>
					<td>Time In:  8:00AM </td>
					<td>Time Out:  5:00PM </td>
					<td>Time In:  8:00AM </td>
					<td>Time Out:  5:00PM </td>
					<td>Time In:  8:00AM </td>
					<td>Time Out:  5:00PM </td>
					<td>Time In:  8:00AM </td>
					<td>Time Out:  5:00PM </td>
					<td>Time In:  8:00AM </td>
					<td>Time Out:  5:00PM </td>
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
