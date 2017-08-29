<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
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

	<div class="col-md-10 col-md-offset-1 pull-down">

		<div class="panel panel-default">
			<a data-toggle="collapse" href="#collapseChangePayroll">
				<div class="panel-heading">
					<h3 class="panel-title">Change opening and closing payroll</h3>
				</div>
			</a>
			<div id="collapseChangePayroll" class="panel-collapse collapse">
				<table class="table">
					<tr>
						<td>Monday</td>
						<td>Tuesday</td>
						<td>Wednesday</td>
						<td>Thursday</td>
						<td>Friday</td>
						<td>Saturday</td>
						<td>Sunday</td>
					</tr>
					<tr>
						<td><input type="text" placeholder="---" disabled></td>
						<td>CLOSE</td>
						<td>OPEN</td>
						<td><input type="text" placeholder="---" disabled></td>
						<td><input type="text" placeholder="---" disabled></td>
						<td><input type="text" placeholder="---" disabled></td>
						<td><input type="text" placeholder="---" disabled></td>
					</tr>
					<tr>
						<td><input type="checkbox"></td>
						<td><input type="checkbox" checked></td>
						<td><input type="checkbox" checked></td>
						<td><input type="checkbox"></td>
						<td><input type="checkbox"></td>
						<td><input type="checkbox"></td>
						<td><input type="checkbox"></td>
					</tr>
				</table>
				<div class="panel-body">
					<a href="" class="btn btn-primary">Save changes</a>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<a data-toggle="collapse" href="#collapseManageAccounts">
				<div class="panel-heading">
					<h3 class="panel-title">Manage accounts</h3>
				</div>
			</a>
			<div id="collapseManageAccounts" class="panel-collapse collapse">
			<div class="panel-body col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Administrator</h3>
					</div>
					<div class="panel-body">
						Add account
					</div>
				</div>
			</div>
			<div class="panel-body col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Employee</h3>
					</div>
					<div class="panel-body">
						Add account
					</div>
				</div>
			</div>
			<div class="panel-body">
					<a href="" class="btn btn-primary">Save changes</a>
				</div>
		</div>
		</div>

	</div>

	<div class="row">
		<div class="col-md-6">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Site management</h3>
				</div>
				<div class="panel-body">
					Add sites
					Edit sites
					Delete sites (?)
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Position management</h3>
				</div>
				<div class="panel-body">
					Add position
					Edit position
					Delete position (?)
				</div>
			</div>
		</div>

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
