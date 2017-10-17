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
		<div class="col-md-10 col-md-offset-1 pull-down">
			<ol class="breadcrumb text-left">
				<li>
					<a href="options.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Options</a>
				</li>
				<li class="active">Moving employees from SITENAME to new site</li>
			</ol>
		</div>

		<!-- Table of vacant employees-->
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-bordered">
				<thead>
				<tr>
					<td>Employee ID</td>
					<td>Name</td>
					<td>Position</td>
					<td>Previous Site</td>
					<td>New Site</td>
				</tr>
				</thead>
				<tbody>
					<tr>
						<td>2017-123123123</td>
						<td>Miguelito Joselito Dela Cruz</td>
						<td>Mason</td>
						<td>Muralla</td>
						<td>
							<select class="form-control">
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
	 	
	 	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
			document.getElementById("adminOptions").setAttribute("style", "background-color: #10621e;");
		</script>
	 	
	 </div>
	</body>
</html>
