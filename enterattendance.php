<!DOCTYPE html>
<?php
	include('directives/session.php');
	include_once('directives/db.php');
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
	else
	{
		$filter_position = $_GET['position'];
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
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css" type="text/css">
	<link rel="stylesheet" href="js/timepicker/jquery.timepicker.min.css">
	<link rel="stylesheet" href="bower_components/bootstrap-toggle/css/bootstrap-toggle.min.css">

</head>
<body style="font-family: QuicksandMed">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<div class="row" style="z-index: 101">
			<!-- BREAD CRUMBS -->
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li><a href="attendance.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a></li>
					<?php
					
					Print '<li class="active">Employee attendance sheet for '. $site_name .' on '. $date .' ('.$day.')</li>';
					// if($day ==  "Sunday")
					// 	Print '<input type="hidden" id="isSunday">';
					?>

					<button class="btn btn-success pull-right" onclick="save()">Save Changes</button>
				</ol>

			</div>

			<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
				<div class="col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1">
					<form method="post" action="" id="search_form">
						<div class="form-group">
							<input type="text" placeholder="Search then press Enter" id="search_box" name="txt_search" onkeypress="enter(enter)" class="form-control">
						</div>
					</form>
				</div>
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-5 col-lg-5 pull-right">
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
			<form id="form" method="post" action="logic_attendance.php?site=<?php Print $site_name;?>&filter=<?php Print $filter_position;?>">
		<div class="col-md-1 col-lg-12">
			<table class="table table-condensed table-bordered" style="background-color:white;">
				<tr>
					<td>Status</td>
					<td style='width:200px !important;'>Name</td>
					<td>Position</td>
					<td colspan='2'>Auto</td>
					<td>Time In</td>
					<td>Time Out</td>
					<td>H.D. / Straight</td>
					<td>A.B. Time In</td>
					<td>A.B. Time Out</td>
					<td>N.S.</td>
					<td>Time In</td>
					<td>Time Out</td>
					<td>Working Hours</td>
					<td>Overtime</td>
					<td>Undertime</td>
					<td>Night Differential</td>
					<td colspan="4">Actions</td>
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
							<h4 class="modal-title" id="dito"></h4>
						</div>
						<div class="modal-body">
							<input class="form-control" id="remark"  maxlength="100"onkeyup="remarksListener(this.value)">
						</div>
						<div class="modal-footer">
							<h5 class="pull-left" >Characters left: &nbsp<span id="remarksCounter">100<span>&nbsp</h5>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveRemarks">Save changes</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<!-- DUMMY MODAL FOR EXTRA ALLOWANCE -->
			<div class="modal fade" tabindex="-1" id="XAllowanceModal" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="AllowDisplay"></h4>
						</div>
						<div class="modal-body">
							<center>
								<input class="form-control" onkeypress="validatenumber(event)" style="width:50%;"id="xAllowanceInput"  maxlength="20">
							</center>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveXAllow">Save changes</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<!-- DUMMY MODAL FOR EDIT ATTENDANCE -->
			<div class="modal fade" tabindex="-1" id="editAttendance" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="EditDisplay"></h4>
						</div>
						<div class="modal-body">
							<div class="row">
							<form class="form-inline">
								<div class="form-group">
									<span>Hour: </span>
									<input class="form-control small" onkeypress="validatenumber(event)" style="width:50%;" id="editHours"  maxlength="2">
								</div>
								<div class="form-group">
									<span>Minutes: </span>
									<input class="form-control small" onkeypress="validatenumber(event)" style="width:50%;"id="editMins"  maxlength="2">
								</div>
							</form>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" id="saveAttEdit">Save changes</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->




		</div>
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery-ui/external/jquery/jquery.js"></script>
	<script rel="javascript" src="js/jquery-ui/jquery-ui.js"></script>
	<script src="js/timepicker/jquery.timepicker.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script src="js/enterAttendance.js"></script>
	<script>

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

		document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
	// Clear Filter
	function clearFilter() {
		window.location.assign("enterattendance.php?site=<?php Print $site_name ?>&position=null");
	}
		// Position Filter 
	function position() {
		if(document.URL.match(/position=([0-9]+)/)) {

			var arr = document.URL.match(/position=([0-9]+)/)
			var positionUrl = arr[1];
			if(positionUrl) {

				localStorage.setItem("counter", 0);
			}
			else if(localStorage.getItem('counter') > 2) {

				localStorage.clear();
			}
		}
		var position = document.getElementById("position").value;
		var positionReplaced = position.replace(/\s/g , "+");
		localStorage.setItem("glob_position", positionReplaced);
		window.location.assign("enterattendance.php?site=<?php Print $site_name ?>&position="+positionReplaced);
	}

	function nightshift_ChkBox(id) {
		var mainRow = document.getElementById(id);//gets the row of the user checked

		if(mainRow.querySelector('.nightshiftChk').checked == true) {

			mainRow.querySelector('.timein3').disabled = false;
			mainRow.querySelector('.timeout3').disabled = false;

			// disable halfday checkbox
			mainRow.querySelector('.halfdayChk').disabled = true;

			// delete values to prepare for the 3rd timein and timeout
			mainRow.querySelector('.workinghours').value = "";
			mainRow.querySelector('.overtime').value = "";
			mainRow.querySelector('.undertime').value = "";
			mainRow.querySelector('.nightdiff').value = "";
			//for hidden rows
			mainRow.querySelector('.workinghoursH').value = "";
			mainRow.querySelector('.overtimeH').value = "";
			mainRow.querySelector('.undertimeH').value = "";
			mainRow.querySelector('.nightdiffH').value = "";

			mainRow.querySelector('.attendance').value = "";//reset the attendance status
			
			// If absent was initially placed, changed to success
			if(mainRow.classList.contains('danger'))
			{
				mainRow.classList.remove('danger');
			}
			else 
			{
				mainRow.classList.remove('success');
			}
		}
		else {

			// enable halfday checkbox
			mainRow.querySelector('.halfdayChk').disabled = false;

			mainRow.querySelector('.timein3').disabled = true;
			mainRow.querySelector('.timeout3').disabled = true;
			mainRow.querySelector('.timein3').value = '';
			mainRow.querySelector('.timeout3').value = '';
			timeIn(id);//call function to revert the results to just 4 inputs
		}


		
	}
	</script>
	<script src="bower_components/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
</body>
</html>




















