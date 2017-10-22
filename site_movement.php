<!DOCTYPE html>
<?php
include('directives/session.php');
?>
<html>
	<head>
		<title>Payroll</title>
		<!-- Company Name: Green Built Industrial Corporation -->

		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="css/style.css" type="text/css">
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

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li>
						<a href="employees.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a>
					</li>
					<li class="active">Moving employees to new site</li>
				</ol>
			</div>
		</div>

		<!-- Table of vacant employees-->
		<div class="col-md-10 col-md-offset-1">
			<div class="pull-left">
				<a data-target="#changeSite" data-toggle="modal" class="btn btn-default">
					<span class="glyphicon glyphicon-arrow-down"></span> Change site for selected employees
				</a>
			</div>

			<table class="table table-bordered pull-down">
				<thead>
				<tr>
					<td>Select</td>
					<td>Employee ID</td>
					<td>Name</td>
					<td>Position</td>
					<td>Previous Site</td>
					<td>New Site</td>
				</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<input type="checkbox" value="">
						</td>
						<td>2017-123123123</td>
						<td>Miguelito Joselito Dela Cruz</td>
						<td>Mason</td>
						<td>Muralla</td>
						<td>
							<select class="form-control input-sm">
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- MODAL -->
			<div class="modal fade bs-example-modal-sm" role="dialog" id="changeSite">
			  <div class="modal-dialog modal-sm" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11">Change to new site</h4>
				        <button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <div class="modal-body">
			     	<select class="form-control input-sm">
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							  <option>SITE NAME</option>
							</select>
			     	</div>
			     	<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary">Save changes</button>
				      </div>
			    </div>
			  </div>
			</div>
	 	
	 	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
			document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
		</script>
	 	
	 </div>
	</body>
</html>
