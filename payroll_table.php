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
	<link rel="stylesheet" href="js/timepicker/jquery.timepicker.min.css">

</head>
<body style="font-family: QuicksandMed">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<div class="row" style="z-index: 101">
			<!-- BREAD CRUMBS -->
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li><a href="attendance.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a></li>
					<li class="active">Payroll for [POSITIONS] at [SITENAME] </li>
					<button class="btn btn-success pull-right" onclick="save()">Save Changes</button>
				</ol>

			</div>

			<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
				<div class="col-md-3 col-md-offset-1">
					<form method="post" action="" id="search_form">
						<div class="form-group">
							<input type="text" placeholder="Search" id="search_box" name="txt_search" onkeypress="enter(enter)" class="form-control">
						</div>
					</form>
				</div>
				</div>
				<!-- ACTION BUTTONS FOR FILTERS -->
				<!-- END OF ACTION BUTTONS FOR FILTERS-->
			
			<!-- Attendance table -->
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-condensed table-bordered" style="background-color:white;">
				<tr>
					<td>Employee ID</td>
					<td style='width:200px !important;'>Name</td>
					<td>Payroll status</td>
					<td>Has documents</td>
					<td>Has loans</td>
					<td>Has absences</td>
					<td colspan="2">Actions</td>
				</tr>
				<tr>
					<td>2017-123123</td>
					<td>Miguelito Joselito Dela Cruz</td>
					<td>Incomplete</td>
					<td>Yes, complete</td>
					<td>None</td>
					<td>None</td>
					<td><a class="btn btn-primary" href="payroll.php">Update</a></td>
					<td><a class="btn btn-primary" href="payroll.php">View</a></td>
				</tr>
				<tr class="success">
					<td>2017-123123</td>
					<td>Another name over here</td>
					<td>Complete</td>
					<td>Yes, complete</td>
					<td>Yes, SSS</td>
					<td>1 absence</td>
					<td><a class="btn btn-primary" href="payroll.php">Update</a></td>
					<td><a class="btn btn-primary" href="payroll.php">View</a></td>
				</tr>
			</table>
		</div>
			<!-- DUMMY MODAL FOR REMARKS -->
			<div class="modal fade" tabindex="-1" id="remarks" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="dito">Remarks for...</h4>
						</div>
						<div class="modal-body">
							<input class="form-control" id="remark">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveRemarks">Save changes</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

		</div>
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/timepicker/jquery.timepicker.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
	</script>
</body>
</html>