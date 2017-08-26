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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
			<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
			<ol class="breadcrumb text-left">
				<li><a href="employees.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a></li>
				<li class="active">Loan Applications</li>
			</ol>
			</div>
				<div class="col-md-4 col-md-offset-1">
					<div class="input-group">
						<input type="text" class="form-control">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
						</span>
					</div>
				</div>
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-5 col-md-pull-1 text-right">
					Filter by:
					<div class="btn-group">
						<select class="form-control">
							<option hidden>Position</option>
							<option value="Foreman">Foreman</option>
							<option value="Leadman">Leadman</option>
							<option value="TimeKeeper">Time Keeper</option>
							<option value="Operator">Operator</option>
							<option value="Carpenter">Carpenter</option>
							<option value="Mason">Mason</option>
							<option value="Labor">Labor</option>
							<option value="Welder">Welder</option>
							<option value="Painter">Painter</option>
							<option value="Electrician">Electrician</option>
							<option value="Plumber">Plumber</option>
							<option value="OfficeStaff">Office Staff</option>
						</select>
					</div>
					<div class="btn-group">
						<select class="form-control">
							<option hidden>Site</option>
							<option value="Muralla">Muralla</option>
							<option value="ZooeyMain">Zooey Main</option>
							<option value="Teressa">Teressa</option>
							<option value="Camalig">Camalig</option>
							<option value="Marilao">Marilao</option>
							<option value="StaMaria">Sta. Maria</option>
							<option value="Batangas">Balagtas</option>
							<option value="LaUnion">La Union</option>
							<option value="Kaybiga">Kaybiga</option>
							<option value="MaxSteel">Max steel</option>
							<option value="ZooeyLawangBato">Zooey Lawang Bato</option>
							<option value="PedroGil">Pedro Gil</option>
							<option value="Batangas">Batangas</option>
							<option value="Tagaytay">Tagaytay</option>
							<option value="Carmona">Carmona</option>
							<option value="Paliparan">Paliparan</option>
							<option value="Laguna">Laguna</option>
						</select>
					</div>
				</div>
				<div class="col-md-1 col-md-pull-1 text-right">
					<button class="btn btn-primary" onclick="saveChanges()">Save changes</button>
				</div>
			</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row pull-down">
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-bordered table-condensed" style="background-color:white;">
					<tr>
						<td>ID</td>
						<td>Name</td>
						<td>Position</td>
						<td>Site</td>
						<td colspan="3">Loans</td>
					</tr>
					<tr>
						<td>1</td>
						<td>Trial Employee entry</td>
						<td>Position</td>
						<td>Placeholder</td>
						<td><input type="checkbox" /> SSS <input type="text" id="sss" name="sss" disabled="disabled"/></td>
						<td><input type="checkbox" /> Pag-IBIG <input type="text" id="philhealth" name="philhealth" disabled="disabled"/></td>
						<td><input type="checkbox" /> Vale <input type="text" id="pagibig" name="pagibig" disabled="disabled"/></td>
					</tr>
					<tr>
						<td>1</td>
						<td>Trial Employee entry</td>
						<td>Position</td>
						<td>Placeholder</td>
						<td><input type="checkbox" /> SSS <input type="text" id="sss" name="sss" disabled="disabled"/></td>
						<td><input type="checkbox" /> Pag-IBIG <input type="text" id="philhealth" name="philhealth" disabled="disabled"/></td>
						<td><input type="checkbox" /> Vale <input type="text" id="pagibig" name="pagibig" disabled="disabled"/></td>
					</tr>
				</table>
			</div>	
		</div>


	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
	function saveChanges(){
		confirm("Note: After saving these changes, the loans you've entered will no longer be editable. Are you sure you want to save changes?");
	}
	document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>
<!--
      changeMonth: true,
      changeYear: true