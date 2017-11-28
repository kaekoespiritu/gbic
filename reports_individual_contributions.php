<!DOCTYPE html>
<?php
include('directives/session.php');
?>
<html>
<head>
	<title>Payroll</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: Quicksand;">
	<div class="container-fluid">

		<?php
		require_once("directives/nav.php");
		?>

		<div class="container pull-down">
			<div class="col-md-12 pull-down">
				<h2>Individual Contributions Report</h2>
			</div>

			<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
			<div class="row">
				<div class="col-md-3 col-md-offset-1 pull-down">
					<div class="form-group">
						<input type="text" placeholder="Search" id="search_box" name="txt_search" onkeyup="enter(event)" class="form-control">
					</div>
				</div>

				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-7 pull-down text-right">
					Filter by:
					<!-- POSITION DROPDOWN -->
					<div class="btn-group">
						<select class="form-control" id="position" onchange="position()">
							<option hidden>Position</option>
							<option>Position here</option>
							<option>Position here</option>
							<option>Position here</option>
							<option>Position here</option>
						</select>
					</div>
					<!-- END OF POSITION DROPDOWN -->
					<!-- SITES DROPDOWN -->
					<div class="btn-group">
						<select class="form-control" id="site" onchange="site()">
							<option hidden>Site</option>
							<option>Sites here</option>
							<option>Sites here</option>
							<option>Sites here</option>
							<option>Sites here</option>
						</select>
					</div>
					<!-- END OF SITES DROPDOWN -->
					<button type="button" class="btn btn-danger text-right" onclick="clearFilter()">Clear Filters</button>
					View period:
					<!-- CHANGE PERIOD VIEW -->
					<div class="col-md-2 pull-right">
						<select class="form-control">
							<option>Weekly</option>
							<option>Monthly</option>
							<option>Yearly</option>
						</select>
					</div>
				</div>
				<!-- ACTION BUTTONS FOR FILTERS -->
				<!-- END OF ACTION BUTTONS FOR FILTERS-->
			</div>

			<!-- Table of employees -->
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<table class="table table-bordered table-condensed" style="background-color:white;">
						<tr>
							<th class='fixedWidth text-center'>Employee ID</th>
							<th class='text-center'>Name</th>
							<th class='text-center'>Position</th>
							<th class='text-center'>Site</th>
							<th class='text-center'>Actions</th>
						</tr>
						<tr>
							<td style='vertical-align: inherit'>1</td>
							<td style='vertical-align: inherit'>Name goes here</td>
							<td style='vertical-align: inherit'>Position goes here</td>
							<td style='vertical-align: inherit'>Site goes here</td>
							<td style='vertical-align: inherit'><button class="btn btn-default">Print / View</button></td>
						</tr>
					</table>
				</div>
			</div>

		</div>
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>
