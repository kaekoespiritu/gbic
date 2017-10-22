<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');
	date_default_timezone_set('Asia/Manila');
	$site_name = $_GET['site'];
	if(isset($_SESSION['date']))
	{
		$date = $_SESSION['date'];
	}
	else
	{
		$date = strftime("%B %d, %Y");
	}
	if(!isset($_GET['position']))
	{
		header("location:enterattendance.php?site=".$site_name."&position=null");
	}
	if(isset($_SESSION['holidayDate']))
	{
		if($_SESSION['holidayDate'] == $date)
		{
			$holidayType = $_SESSION['holidayType']; 
			$holidayName = $_SESSION['holidayName'];
		}
	}

	$day = date('l', strtotime($date));

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
					<?php
					
					Print '<li class="active">Employee attendance sheet for '. $site_name .' on '. $date .'</li>';
					?>
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
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-5 pull-right">
					Filter by:
					<!-- POSITION DROPDOWN -->
					<div class="btn-group">
						<select class="form-control" id="position" onchange="position()">
							<option hidden>Position</option>
							<?php
							$position = "SELECT position FROM job_position WHERE active = '1'";
							$position_query = mysql_query($position);


							while($row_position = mysql_fetch_assoc($position_query))
							{
								$positionReplaced = str_replace('/+/', ' ', $_GET['position']);
								$position = mysql_real_escape_string($row_position['position']);
								if($position == $positionReplaced)
								{
									Print '<option value="'. $position .'" selected="selected">'. $position .'</option>';
								}
								else
								{
									Print '<option value="'. $position .'">'. $position .'</option>';
								}
							}
							?>
						</select>
					</div>
					<!-- END OF POSITION DROPDOWN -->
					
					<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filter</button>
				</div>
			
			<!-- Attendance table -->
			<form id="form" method="post" action="logic_attendance.php?site=<?php Print $site_name;?>">
		<div class="col-md-12">
			<table class="table table-condensed table-bordered" style="background-color:white;">
				<tr>
					<td style='width:200px !important;'>Name</td>
					<td>Position</td>
					<td>Time In</td>
					<td>Time Out</td>
					<td>After break Time In</td>
					<td>After break Time Out</td>
					<td>Working Hours</td>
					<td>Overtime</td>
					<td>Undertime</td>
					<td>Night Differential</td>
					<td colspan="3">Actions</td>
				</tr>
				<?php
				require "directives/attendance/enter_attendance.php";

				attendance();
				?>
				


			</table>
		</div>
			</form>
			
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
	<script src="js/enterAttendance.js"></script>
	<script>
	// Clear Filter
	function clearFilter() {
		window.location.assign("enterattendance.php?site=<?php Print $site_name ?>&position=null");
	}
		// Position Filter 
	function position() {
		if(document.URL.match(/position=([0-9]+)/))
		{
			var arr = document.URL.match(/position=([0-9]+)/)
			var positionUrl = arr[1];
			if(positionUrl)
			{
				localStorage.setItem("counter", 0);
			}
			else if(localStorage.getItem('counter') > 2)
			{
				localStorage.clear();
			}
		}
		var position = document.getElementById("position").value;
		var positionReplaced = position.replace(/\s/g , "+");
		localStorage.setItem("glob_position", positionReplaced);
		window.location.assign("enterattendance.php?site=<?php Print $site_name ?>&position="+positionReplaced);
	}

	</script>
</body>
</html>