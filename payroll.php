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
					<h4><span class="glyphicon glyphicon-check"></span> PhilHealth documents</h4>
					<h4>Has PagIBIG documents</h4>
					<h4>Has SSS documents</h4>
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
						<div class="col-md-2 col-md-offset-3">
							<h4 class="text-left">Loans</h4>
							<div class="form-group">
								<label class="control-label col-md-3" for="sss">SSS</label>
								<div class="col-md-9">
									<input type="text" id="sss" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3" for="pagibig">Pag-IBIG</label>
								<div class="col-md-9">
									<input type="text" id="pagibig" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<h4 class="text-left">Deductions</h4>
							<div class="form-group">
								<label class="control-label col-md-3" for="tax">Tax</label>
								<div class="col-md-9">
									<input type="text" id="tax" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3" for="tools">Tools</label>
								<div class="col-md-9">
									<input type="text" id="tools" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<h4 class="text-left">Vale</h4>
								<h4 class="text-left">1,000 PHP</h4>
								<button type="button" class="btn btn-success col-md-6" data-toggle="modal" data-target="#addVale">Add new vale</button>
								<button type="button" class="btn btn-danger col-md-6" data-toggle="modal" data-target="#deductVale">Deduct old vale</button>
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
				        <input type="text" placeholder="Add as new vale">
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
				        <input type="text" placeholder="Deduct from old vale">
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
</script>


</div>
</body>
</html>
