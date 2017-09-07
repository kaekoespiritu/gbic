<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');
date_default_timezone_set('Asia/Manila');
	if(isset($_SESSION['date']))
	{
		$date = $_SESSION['date'];
	}
	else
	{
		$date = strftime("%B %d, %Y");
	}
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
<body style="font-family: Quicksand">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>
		<?php
		require_once('directives/modals/siteAttendance.php');

		?>

		<div class="row" style="z-index: 101">
			<!-- BREAD CRUMBS -->
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<!-- TODO: If Sunday/Holiday attendance is selected, change link and name -->
					<li><a href="attendance.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a></li>
					<?php
					$site_name = $_GET['site'];
					Print '<li class="active">Employee attendance sheet for '. $site_name .' on '. $date .'</li>';
					?>
					<button class="btn btn-success pull-right" onclick="save()">Save Changes</button>
				</ol>
			</div>
			
			<!-- Attendance table -->
			<form id="form" method="post" action="logic_attendance.php?site=<?php Print $site_name;?>">
		<div class="col-md-10 col-md-offset-1 pull-down">
			<table class="table table-condensed table-bordered" style="background-color:white;">
				<tr>
					<td>Name</td>
					<td>Position</td>
					<td>Time In</td>
					<td>Time Out</td>
					<td>Working Hours</td>
					<td>Overtime</td>
					<td>Undertime</td>
					<td>Night Differential</td>
					<td colspan="2">Actions</td>
				</tr>
				
				<?php
				$site = $_GET['site'];
				$employees = "SELECT * FROM employee WHERE site = '$site' ORDER BY lastname";
				$employees_query = mysql_query($employees);

				$dateChecker = "SELECT date FROM attendance WHERE date = '$date'";
				$dateCheckerQuery = mysql_query($dateChecker);

				if($dateCheckerQuery)//Checks if there is already an attendance made for that specific date
				{
					$dateRows = mysql_num_rows($dateCheckerQuery);
				}	
				
				// theres already an data on that specific day on the database
				$counter = 0;
				if(!empty($dateRows))
				{
					if($dateRows >= 1)
					{	
						while($row_employee = mysql_fetch_assoc($employees_query))
						{
							$empidChecker = $row_employee['empid'];
							$empCheck = "SELECT * FROM attendance WHERE date = '$date' AND empid = '$empidChecker' ";
							$empCheckQuery = mysql_query($empCheck);
							$empRow = mysql_fetch_assoc($empCheckQuery);
						// Attendance Check
							if($empRow['attendance'] == 0)//No input
							{
								Print 	"<tr id=\"". $row_employee['empid'] ."\">
											<!-- Employee ID -->
												<td class='empName'>
													". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
												</td>
											<!-- Position -->
												<td>
													". $row_employee['position'] ."
												</td>
											<!-- Time In -->
												<td>
													<input type='text' value='". $empRow['timein'] ."' class='timein timepicker form-control input-sm' name='timein[".$counter."]'>
												</td>
											<!-- Time Out-->
												<td>
													<input type='text' class='timeout timepicker form-control input-sm' value='' name='timeout[".$counter."]'>
												</td>
											<!-- Working Hours -->
												<td>
													<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
													<input type='hidden' class='workinghoursH'  name='workinghrs[".$counter."]' >
												</td>
											<!-- Overtime -->
												<td>
													<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
													<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
												</td>
											<!-- Undertime -->
												<td>
													<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
													<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
												</td>
											<!-- Night Differential --> 
												<td>
													<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
													<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
												</td>
											<!-- Attendance Status -->
												<input type='hidden' name='attendance[".$counter."]' value='' class='attendance'>";


							}
							else if($empRow['attendance'] == 1)//Absent
							{
								Print 	"<tr id=\"". $row_employee['empid'] ."\" class='danger'>
											<!-- Employee ID -->
												<td class='empName'>
													". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
												</td>
											<!-- Position -->
												<td>
													". $row_employee['position'] ."
												</td>
											<!-- Time In -->
												<td>
													<input type='text' placeholder='ABSENT' class='timein timepicker form-control input-sm' value='' name='timein[".$counter."]'>
												</td> 
											<!-- Time Out-->
												<td>
													<input type='text' class='timeout timepicker form-control input-sm' placeholder='ABSENT' value='' name='timeout[".$counter."]'>
												</td>
											<!-- Working Hours -->
												<td>
													<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
													<input type='hidden' class='workinghoursH'  name='workinghrs[".$counter."]' >
												</td>
											<!-- Overtime -->
												<td>
													<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
													<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
												</td>
											<!-- Undertime -->
												<td>
													<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
													<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
												</td>
											<!-- Night Differential --> 
												<td>
													<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
													<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
												</td>
											<!-- Attendance Status -->
												<input type='hidden' name='attendance[".$counter."]' value='ABSENT' class='attendance'>";
							}
							else if($empRow['attendance'] == 2)//Present
							{
								Print 	"<tr id=\"". $row_employee['empid'] ."\" class='success'>
											<!-- Employee ID -->
												<td class='empName'>
													". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
												</td>
											<!-- Position -->
												<td>
													". $row_employee['position'] ."
												</td>
											<!-- Time In -->
												<td>
													<input type='text' class='timein timepicker form-control input-sm' value='". $empRow['timein'] ."' name='timein[".$counter."]'>
												</td>
											<!-- Time Out-->
												<td>
													<input type='text' class='timeout timepicker form-control input-sm' value='". $empRow['timeout'] ."' name='timeout[".$counter."]'>
												</td>";
							// Working hours
								if($empRow['workhours'] <= 5)
								{
									Print "<!-- Working Hours -->
										<td>
											<input type='text' placeholder='--'' value='". $empRow['workhours'] ." hrs/HALFDAY' class='form-control input-sm workinghours' disabled>
											<input type='hidden' class='workinghoursH' value='". $empRow['workhours'] ." hrs/HALFDAY' name='workinghrs[".$counter."]' >
										</td>";
								}
								else
								{
									Print "<!-- Working Hours -->
										<td>
											<input type='text' placeholder='--'' value='". $empRow['workhours'] ." hours' class='form-control input-sm workinghours' disabled>
											<input type='hidden' class='workinghoursH' value='". $empRow['workhours'] ." hours' name='workinghrs[".$counter."]' >
										</td>";
								}
							// Overtime
								if($empRow['overtime'] != 0)
								{
									Print "<!-- Overtime -->
										<td>
											<input type='text' placeholder='--' class='form-control input-sm overtime' value='". $empRow['overtime'] ." hours'  disabled>
											<input type='hidden' class='overtimeH' value='". $empRow['overtime'] ." hours' name='othrs[".$counter."]' >
										</td>";
								}
								else
								{
									Print "<!-- Overtime -->
									<td>
										<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
										<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
									</td>";
								}
							// Undertime
								if($empRow['undertime'] != 0)
								{
									Print "<!-- Undertime -->
										<td>
											<input type='text' placeholder='--' value='". $empRow['undertime'] ." hours' class='form-control input-sm undertime'  disabled>
											<input type='hidden' class='undertimeH' value='". $empRow['undertime'] ." hours' name='undertime[".$counter."]'>
										</td>";
								}
								else
								{
									Print "<!-- Undertime -->
										<td>
											<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
											<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
										</td>";
								}
							// NightDiff
								if($empRow['nightdiff'] != 0)
								{
									Print "<!-- Night Differential --> 
										<td>
											<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='". $empRow['nightdiff'] ." hours' disabled>
											<input type='hidden' class='nightdiffH' value='". $empRow['nightdiff'] ." hours' name='nightdiff[".$counter."]' >
										</td>";
								}
								else
								{
									Print "<!-- Night Differential --> 
										<td>
											<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
											<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
										</td>";
								}
							// Attendance Status
								Print "<!-- Attendance Status -->
									<input type='hidden' name='attendance[".$counter."]' value='PRESENT' class='attendance'>";
							}
							Print 	
								"<input type='hidden' name='empid[".$counter."]' value=". $row_employee['empid'] .">";	
						
						// REMARKS	
							if(isset($empRow['remarks']))
							{
								Print "<!-- Remarks Input --> 
									<input type='hidden' value='". $empRow['remarks'] ."' name='remarks[".$counter."]' class='hiddenRemarks'>";
							}
							else
							{
								Print "<!-- Remarks Input --> 
									<input type='hidden' name='remarks[".$counter."]' class='hiddenRemarks'>";
							}

								Print "<!-- Remarks Button --> 
								<td>
									<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\")'>Remarks</a>
								</td>
								<td>
									<a class='btn btn-sm btn-danger absent' onclick='absent(\"". $row_employee['empid'] ."\")'>Absent</a>
								</td>
							</tr>
								";
								$counter++;
						}
					}
				}
				// if there is no data on the specific day on the database
				else if($employees_query)
				{
					
					while($row_employee = mysql_fetch_assoc($employees_query))
					{

						Print 	"	
							<tr id=\"". $row_employee['empid'] ."\">
								<input type='hidden' name='empid[".$counter."]' value=". $row_employee['empid'] .">
								<td class='empName'>
									". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
								</td>
								<td>
									". $row_employee['position'] ."
								</td>
								<!-- Time In -->
								<td>
									<input type='text' class='timein timepicker form-control input-sm' value='' name='timein[".$counter."]'>
								</td> 
								<!-- Time Out-->
								<td>
									<input type='text' class='timeout timepicker form-control input-sm' value='' name='timeout[".$counter."]'>
								</td> 
								<!-- Working Hours -->
								<td>
									<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
									<input type='hidden' class='workinghoursH'  name='workinghrs[".$counter."]' >
								</td> 
								<!-- Overtime -->
								<td>
									<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
									<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
								</td> 
								<!-- Undertime -->
								<td>
									<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
									<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
								</td>
								<!-- Night Differential --> 
								<td>
									<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
									<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
								</td>
								<!-- Remarks Input --> 
									<input type='hidden' name='remarks[".$counter."]' class='hiddenRemarks'>

								<!-- Attendance Status -->
									<input type='hidden' name='attendance[".$counter."]' class='attendance'>
								<!-- Remarks Button --> 
								<td>
									<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\")'>Remarks</a>
								</td>
								<td>
									<a class='btn btn-sm btn-danger absent' onclick='absent(\"". $row_employee['empid'] ."\")'>Absent</a>
								</td>
							</tr>
								";
								$counter++;
					}
				}
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
	<script>

	document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");

		$(document).ready(function(){
			console.log("jQuery comes in!");
			$('input.timein').timepicker({
				timeFormat: 'hh:mm p',
				dynamic: false,
				scrollbar: false,
				dropdown: false
			});
			$('input.timein').change(function(){
				var id = $(this).parent().parent().attr('id');
				console.log(id);
				timeIn(id);
			});
			$('input.timeout').timepicker({
				timeFormat: 'hh:mm p',
				dynamic: false,
				scrollbar: false,
				dropdown: false
			});
			$('input.timeout').change(function(){
				var id = $(this).parent().parent().attr('id');
				console.log(id);
				timeOut(id);
			});
		});

		//Submit the form
		function save() {
			var a = confirm("Are you sure you want to save this attendance? All of the blank will be absent.")
			if(a)
			{
				document.getElementById('form').submit();
			}
		}

		function remarks(id) {	
			// show modal here to input for remarks
			var mainRow = document.getElementById(id);
			if(mainRow.querySelector('.hiddenRemarks').value != null)
			{
				var input = mainRow.querySelector('.hiddenRemarks').value;
				document.getElementById('remark').value = input;
			}
			else
			{
				document.getElementById('remark').value = "";
			}
			var empName = mainRow.querySelector('.empName').innerHTML.trim();
			var modal = document.getElementById('dito').innerHTML = "Remarks for " + empName;
			document.getElementById('saveRemarks').setAttribute('onclick', "saveRemarks(\""+ id +"\")");
			console.log(modal);
			
		}

		// Transfer content to hidden input field
		function saveRemarks(id) {
			var remarks = document.getElementById('remark').value;
			var hiddenRemarks = document.getElementById(id).querySelector('.hiddenRemarks').setAttribute('value', remarks);

		}

		function absent(id) {
			
			var mainRow = document.getElementById(id); // Get row to be computed

			// change color of row to shade of red
			mainRow.classList.add('danger');
			// set attendance status to ABSENT
			mainRow.querySelector('.attendance').value = "ABSENT";
			// add text ABSENT to time in and time out
			mainRow.querySelector('.timein').placeholder = "ABSENT";
			mainRow.querySelector('.timeout').placeholder = "ABSENT";
			mainRow.querySelector('.timein').value = "";
			mainRow.querySelector('.timeout').value = "";
			mainRow.querySelector('.workinghours').value = "";
			mainRow.querySelector('.overtime').value = "";
			mainRow.querySelector('.undertime').value = "";
			//for hidden rows
			mainRow.querySelector('.workinghoursH').value = "";
			mainRow.querySelector('.overtimeH').value = "";
			mainRow.querySelector('.undertimeH').value = "";
		}

		function getHour(time) {
			console.log("getHour: " + time);
			if(time)
			{
			var hour = time.split(":"); // Split hour + min + AM/PM
			var min = hour[1].split(" "); // Split min + AM/PM
			var diff; // Determine if AM/PM

			if(min[1] == "AM" && parseInt(hour[0],10) == 12)
			{
				hr = 24;
				return hr;
			}
			if(min[1] == "PM" && parseInt(hour[0],10) != 12)
			{
				diff = 12; // Add 12hrs if PM
				var hr = parseInt(hour[0],10) + diff;
				return hr;
			}
			else
			{
				var hr = parseInt(hour[0]);
				return hr;
			}

		}	
		else
		{
			return 0;
		}		
	}

	function getMin(time) {
		console.log("getMin: " + time);
		if(time)
		{
			var hour = time.split(":"); // Split hour + min + AM/PM
			var min = hour[1].split(" "); // Split min + AM/PM

			var mins = parseInt(min[0],10);
			return mins;
		}
		else
		{
			return 0;
		}
	}

	function computeTime(row, timeinhour,timeinmin,timeouthour,timeoutmin) {
		console.log("Time in: " + timeinhour + ":" + timeinmin + " Time out: " + timeouthour + ":" + timeoutmin);

		row.querySelector('.attendance').value = "";
		// Verifies that time in and time out input fields have value
		if(timeinhour && timeouthour)
		{	
			//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
			var workinghours;
			var workingmins;

			// If time is 12AM
			if(timeinhour == 0)
			{
				workinghours = timeouthour;
			}
			else
			{
				workinghours = timeouthour - timeinhour;
				//alert(workinghours);
			}

			// MORNING SHIFT
			if(workinghours >= 1)
			{
			// Computing minutes
				//alert("dayshift");
				if(timeinmin > timeoutmin)
				{
					workingmins = timeinmin - timeoutmin;
				}
				if(timeoutmin > timeinmin)
				{
					workingmins = timeoutmin - timeinmin;
				}
				if(timeinmin === timeoutmin)
				{
					workingmins = 0;
				}
			
			// Computing lunchbreak
				if(timeinhour <= 11 && timeouthour >= 12)
				{
					workinghours = workinghours - 1;
				}
				//alert(workinghours);
				//set the attendance status to PRESENT
				row.querySelector('.attendance').value = "PRESENT";
			// WORKING HOURS
				if(workinghours <= 5)//HALF DAY
				{
					row.querySelector('.workinghours').value = workinghours + " hrs/HALFDAY";
					row.querySelector('.workinghoursH').value = workinghours + " hrs/HALFDAY";
				}
				else if(workingmins == 0)
				{
					row.querySelector('.workinghours').value = workinghours + " hours";
					row.querySelector('.workinghoursH').value = workinghours + " hours";
				}
				else
				{
					row.querySelector('.workinghours').value = workinghours + " hours, " + workingmins + " mins";	
					row.querySelector('.workinghoursH').value = workinghours + " hours, " + workingmins + " mins";
				}

			// OVERTIME if Working Hours exceed 8
				if(workinghours > 8 && workingmins == 0)
				{
					row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hours";
					row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hours";
				}
				else if (workinghours > 8)
				{
					row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
					row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
				}
				else
				{
					row.querySelector('.overtime').value = "";
					row.querySelector('.overtimeH').value = "";
				}

			// UNDERTIME if Working Hours don't reach 8
				if(workinghours < 8 && workingmins == 0)
				{
					row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hours";
					row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hours";
				}
				else if(workinghours < 8)
				{
					row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
					row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
				}
				else
				{
					row.querySelector('.undertime').value = "";
					row.querySelector('.undertimeH').value = "";
				}

				// NIGHT DIFF if Working Hours is in between 10pm - 6am
			// 10 is 10pm and 18 is 6pm
			//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
				//timeinhour -= 12;
				//timeouthour += 12;
				var nightdiff = "";
				//nightdiff MORNING
				if(((timeinhour <= 0 && timeouthour >= 6) || (timeinhour >= 0 || timeouthour <= 6)) 
						&& ((timeinhour <= 0 && timeouthour <= 6)|| (timeinhour >= 0 || timeouthour >= 6)))
				{
					//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
					//alert("yeah");
				//posibility: attendance within NightDiff
				//------------------------- MORNING --------------------------------------
					if(timeinhour >= 0 && timeouthour <= 6)
					{
						nightdiff = timeinhour - timeouthour;
						//alert("possibility : 1");
					}
				//posibility: NightDiff is within attendance
					else if(timeinhour < 0 && timeouthour > 6)
					{
						var NDin = timeinhour;
						var NDout = timeouthour - 6;
						var workhrs = timeinhour - timeouthour;

						nightdiff = ((Math.abs(NDin) + Math.abs(NDout)) - Math.abs(workhrs));
						//alert("possibility : 2");
					}
				//posibility: attendance exceeds NightDiff
					else if(timeinhour < 6 && timeouthour > 6)
					{
						nightdiff = timeinhour - 6;
						//alert("possibility : 3");
					}
				//posibility: attendance > NightDiff
					else if(timeinhour <= 0 && timeouthour > 0)
					{
						//alert("timeinhour: "+timeinhour +" timeouthour: "+timeouthour);
						nightdiff = timeouthour; 
						//alert("possibility : 4");
					}
				//------------------------- NIGHT --------------------------------------
					else if(timeinhour >= 22 && timeouthour <= 6)
					{
						nightdiff = timeinhour - timeouthour;
						//alert("possibility : 5");
					}
				//posibility: NightDiff is within attendance
					else if(((timeouthour <= 24) && (timeouthour > 22)) && timeinhour < 22  )
					{
						// var NDin = timeinhour - 6;
						// var NDout = timeouthour - 22;
						// var workhrs = Math.abs(timeinhour) - Math.abs(timeouthour);
						// alert("1-"+NDin);
						// alert("2-"+NDout);
						// alert("3-"+Math.abs(workhrs));
						// workhrs = Math.abs(workhrs);

						// nightdiff = (Math.abs(NDin) - Math.abs(NDout)) - Math.abs(workhrs);
						// alert("4-"+nightdiff);

						var NDout 
						if(timeouthour == 24)
						{
							NDout = 2;
						}
						else
						{
							NDout = timeouthour - 24;
						}
						nightdiff = Math.abs(NDout);
						//alert("possibility : 6");
					}
				//posibility: attendance > NightDiff
					// else if(timeinhour <= 18 && timeouthour > 18)
					// {
					// 	nightdiff = timeouthour - 18; 
					// 	alert("possibility : 8");
					// }
					else
					{
						//alert("possibility : 9");
						nightdiff = "";
					}
					if(Number.isInteger(nightdiff))
					{
					   	nightdiff = Math.abs(nightdiff);		
					}
					
				}
				
				if(nightdiff != "")
				{
					//alert("yeah1");
					row.querySelector('.nightdiff').value = nightdiff + " hours";
					row.querySelector('.nightdiffH').value = nightdiff + " hours";
				}
				else
				{
					//alert("yeah");
					row.querySelector('.nightdiff').value = "";
					row.querySelector('.nightdiffH').value = "";
				}
				// If absent was initially placed, changed to success
				if(row.classList.contains('danger'))
				{
					row.classList.remove('danger');
					row.classList.add('success');
				}
				else
				{
					row.classList.add('success');
				}

			}

		// NIGHT SHIFT (timeout-timein is negative)
			else
			{
			// Night differential starts at 10pm - 6am
				console.log("Time in: " + timeinhour + ":" + timeinmin + " Time out: " + timeouthour + ":" + timeoutmin);
				console.log("Working hours: " + workinghours + " Working mins: " + workingmins);
				// sets the attendance status to PRESENT
				row.querySelector('.attendance').value = "PRESENT";
				// TIME IN: 22-12 = 10
				// TIME OUT: 6 + 12 = 18
				// RESULT 8
				//alert("before: "+timeinhour);
				timeinhour -= 12;
				//alert("after: "+timeinhour);
				//alert("before: "+timeouthour);
				timeouthour += 12;
				//alert("after: "+timeouthour);
				//alert("nightshift");
				workinghours = timeouthour - timeinhour;
				if(workinghours < 1)
				{
					workinghours *= -1;
				}
				//alert(workinghours);
				//alert("timein: "+timeinhour + " timeout: " + timeouthour);
			// Computing minutes
				if(timeinmin > timeoutmin)
				{
					workingmins = timeinmin - timeoutmin;
				}
				if(timeoutmin > timeinmin)
				{
					workingmins = timeoutmin - timeinmin;
				}
				if(timeinmin === timeoutmin)
				{
					workingmins = 0;
				}
				
			// Computing lunchbreak for nightshift
				if(timeinhour <= 2 && timeouthour >= 3)
				{
					workinghours = workinghours - 1;
				}

			// WORKING HOURS
				if(workinghours <= 5)//HALF DAY
				{
					row.querySelector('.workinghours').value = workinghours + " hrs/HALFDAY";
					row.querySelector('.workinghoursH').value = workinghours + " hrs/HALFDAY";
				}
				else if(workingmins == 0)
				{
					row.querySelector('.workinghours').value = workinghours + " hours";
					row.querySelector('.workinghoursH').value = workinghours + " hours";
				}
				else
				{
					row.querySelector('.workinghours').value = workinghours + " hours, " + workingmins + " mins";	
					row.querySelector('.workinghoursH').value = workinghours + " hours, " + workingmins + " mins";	
				}
			// OVERTIME if Working Hours exceed 8
				if(workinghours > 8 && workingmins == 0)
				{
					row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hours";
					row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hours";
				}
				else if (workinghours > 8)
				{
					row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
					row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
				}
				else
				{
					row.querySelector('.overtime').value = "";
					row.querySelector('.overtimeH').value = "";
				}

			// UNDERTIME if Working Hours don't reach 8
				if(workinghours < 8 && workingmins == 0)
				{
					row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hours";
					row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hours";
				}
				else if(workinghours < 8)
				{
					row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
					row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
				}
				else
				{
					row.querySelector('.undertime').value = "";
					row.querySelector('.undertimeH').value = "";
				}
			// If absent was initially placed, changed to success
				if(row.classList.contains('danger'))
				{
					row.classList.remove('danger');
					row.classList.add('success');
				}
				else
				{
					row.classList.add('success');
				}
			// NIGHT DIFF if Working Hours is in between 10pm - 6am
			// 10 is 10pm and 18 is 6pm
			//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
			var nightdiff = "";
				if(((timeinhour <= 10 && timeouthour >= 18) || (timeinhour >= 10 || timeouthour <= 18)) 
						&& ((timeinhour <= 10 && timeouthour <= 18)|| (timeinhour >= 10 || timeouthour >= 18)))
				{
					//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
					
				//posibility: attendance within NightDiff
					if(timeinhour >= 10 && timeouthour <= 18)
					{
						nightdiff = timeinhour - timeouthour;
						//alert("possibility : 2");
					}
				//posibility: NightDiff is within attendance
					else if(timeinhour < 10 && timeouthour > 18)
					{
						var NDin = timeinhour - 10;
						var NDout = timeouthour - 18;
						var workhrs = timeinhour - timeouthour;

						nightdiff = ((Math.abs(NDin) + Math.abs(NDout)) - Math.abs(workhrs));
						//alert("possibility : 4");
					}
				//posibility: attendance exceeds NightDiff
					else if(timeinhour < 18 && timeouthour > 18)
					{
						nightdiff = timeinhour - 18;
						//alert("possibility : 3");
					}
				//posibility: attendance > NightDiff
					else if(timeinhour <= 10 && timeouthour > 10)
					{
						nightdiff = timeouthour - 10; 
						//alert("possibility : 1");
					}
					else
					{
						nightdiff = "";
					}
					if(Number.isInteger(nightdiff))
					{
					   	nightdiff = Math.abs(nightdiff);		
					}
				}
				if(nightdiff != "")
				{
					
					row.querySelector('.nightdiff').value = nightdiff + " hours";
					row.querySelector('.nightdiffH').value = nightdiff + " hours";
				}
				else
				{
					
					row.querySelector('.nightdiff').value = "";
					row.querySelector('.nightdiffH').value = "";
				}
				
				
				
			}

		}
		else
		{

			row.querySelector('.workinghours').value = "";
			row.querySelector('.overtime').value = "";
			row.querySelector('.undertime').value = "";
			row.querySelector('.nightdiff').value = "";
			row.querySelector('.timein').placeholder = "";
			row.querySelector('.timeout').placeholder = "";
			//for hidden rows
			row.querySelector('.workinghoursH').value = "";
			row.querySelector('.overtimeH').value = "";
			row.querySelector('.undertimeH').value = "";
			row.querySelector('.nightdiffH').value = "";
			row.querySelector('.attendance').value = "";

			if(row.classList.contains('danger'))
			{
				row.classList.remove('danger');
				row.classList.add('');
			}
			else if(row.classList.contains('success'))
			{
				row.classList.remove('success');
				row.classList.add('');
			}
		}
	}	

	function timeIn(id) {
		var mainRow = document.getElementById(id); // Get row to be computed
		var timein = mainRow.querySelector('.timein').value; // Get time in value

		// Function call to get time
		var timeinhour = getHour(timein);
		var timeinmin = getMin(timein);

		var timeout = mainRow.querySelector('.timeout').value; // Get time out value

		// Function call to get time
		var timeouthour = getHour(timeout);
		var timeoutmin = getMin(timeout);

		// Function call to compute for working hours, undertime and overtime
		computeTime(mainRow, timeinhour,timeinmin,timeouthour,timeoutmin);

	}

	function timeOut(id) {
		var mainRow = document.getElementById(id); // Get row to be computed
		var timein = mainRow.querySelector('.timein').value; // Get time in value

		// Function call to get time
		var timeinhour = getHour(timein);
		var timeinmin = getMin(timein);

		var timeout = mainRow.querySelector('.timeout').value; // Get time out value

		// Function call to get time
		var timeouthour = getHour(timeout);
		var timeoutmin = getMin(timeout);
		
		// Function call to compute for working hours, undertime and overtime
		computeTime(mainRow, timeinhour,timeinmin,timeouthour,timeoutmin);

	}	
</script>
</body>
</html>