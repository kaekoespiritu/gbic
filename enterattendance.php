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
					<li class="active">Employee attendance sheet for [SITE NAME]</li>
				</ol>
			</div>
			
			<!-- Attendance table -->
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
						<td colspan="2">Actions</td>
					</tr>
					
					<?php
					$site = $_GET['site'];
					$employees = "SELECT * FROM employee WHERE site = '$site'";
					$employees_query = mysql_query($employees);
					if($employees_query)
					{
						while($row_employee = mysql_fetch_assoc($employees_query))
						{
							Print "	<tr>
										<td>
											". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
										</td>
										<td>
											". $row_employee['position'] ."
										</td>
									<!-- Time In -->
										<td>
											<input type='text' class='timein timepicker form-control input-sm' onchange='timein(\"". $row_employee['empid'] ."\")' name='timein[]'>
										</td> 
									<!-- Time Out-->
										<td>
											<input type='text' class='timeout timepicker form-control input-sm' onchange='timeout(\"". $row_employee['empid'] ."\")' name='timeout[]'>
										</td> 
									<!-- Working Hours -->
										<td>
											<input type='text' placeholder='--'' class='form-control input-sm' name='workinghrs[]' disabled>
										</td> 
									<!-- Overtime -->
										<td>
											<input type='text' placeholder='--' class='form-control input-sm' name='othrs[]' disabled>
										</td> 
									<!-- Undertime -->
										<td>
											<input type='text' id='underTime' placeholder='--' class='form-control input-sm' name='undertime[]' disabled>
										</td> 
										<td>
											<a class='btn btn-sm btn-primary' onclick='remarks(\"". $row_employee['empid'] ."\")'>Remarks</a>
										</td>
										<td>
											<a class='btn btn-sm btn-danger' onclick='absent(\"". $row_employee['empid'] ."\")'>Absent</a>
										</td>
									</tr>";
						}
					}
					?>
					
					
				</table>
			</div>
		</div>
	</div>

			<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

			<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
			<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
			<script src="js/timepicker/jquery.timepicker.min.js"></script>
			<script rel="javascript" src="js/bootstrap.min.js"></script>
			<script>
				document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");

				function remarks(id){

				}
				function absent(id){

				}

				$(document).ready(function(){
					localStorage.clear();	
					$('input.timein').timepicker({
						timeFormat: 'hh:mm p',
						dynamic: false,
						scrollbar: false,
						dropdown: false
					});

					$('input.timeout').timepicker({
						timeFormat: 'hh:mm p',
						dynamic: false,
						scrollbar: false,
						dropdown: false
					});	

					$('#timeIn').change(function()
					{
							var timein = $(this).val(); // Get String value
							var hour = timein.split(":"); // Split hour + min + AM/PM
							var min = hour[1].split(" "); // Split min + AM/PM
							var diff; // Determine if AM/PM

							if(min[1] == "PM" && hour != 12)
							{
								diff = 12; // Add 12hrs if PM
								var timeinhour = parseInt(hour[0],10) + diff;
								localStorage.setItem("timeInHour", parseInt(hour[0],10)+diff);
							}
							else
							{
								localStorage.setItem("timeInHour", parseInt(hour[0]));
							}
							
							// Change strings to integers
							var timeinmin = parseInt(min[0],10);
							localStorage.setItem("timeInMin", parseInt(min[0]));

							// Computation for Working Hours, Undertime and Overtime
							if(localStorage.getItem("timeOutHour") && localStorage.getItem("timeOutMin"))
							{
								var workinghours = localStorage.getItem("timeOutHour") - localStorage.getItem("timeInHour");
								var workingmins = localStorage.getItem("timeOutMin") - localStorage.getItem("timeInMin");
								
								// WORKING HOURS
								if(workingmins == 0)
								{
									$("#workHours").attr("value", workinghours + " hours");
								}
								else
								{
									$("#workHours").attr("value", workinghours + " hours, " + workingmins + " mins");	
								}

								// OVERTIME if Working Hours exceed 8
								if(workinghours > 8 && workingmins == 0)
								{
									$("#overTime").attr("value", workinghours - 8 + " hours");
								}
								else
								{
									$("#overTime").attr("value", "0 hours");
								}

								// UNDERTIME if Working Hours don't reach 8
								alert(boom);
								if(workinghours < 8)
								{
									$("#underTime").attr("value", (workinghours - 8)*-1 + " hour");
									alert(yea);
								}
								else
								{
									$("#underTime").attr("value","0 hour");
									alert(no);
								}
							}
						});

					// TIME OUT INPUT FIELD
					$('#timeOut').change(function()
					{
						var timeout = $('#timeOut').val();
						var hour = timeout.split(":");
						var min = hour[1].split(" ");
						var diff;

						if(min[1] == "PM" && hour[0] != 12)
						{
							diff = 12;
							var timeouthour = parseInt(hour[0],10) + diff;
							localStorage.setItem("timeOutHour", parseInt(hour[0],10)+diff);
						}
						else
						{
							localStorage.setItem("timeOutHour", parseInt(hour[0]));
						}

							// Change strings to integers
							var timeoutmin = parseInt(min[0],10);
							localStorage.setItem("timeOutMin", parseInt(min[0]));
							
							// Computation for Working Hours, Undertime and Overtime
							if(localStorage.getItem("timeInHour") && localStorage.getItem("timeInMin"))
							{
								var workinghours = localStorage.getItem("timeOutHour") - localStorage.getItem("timeInHour");
								var workingmins = localStorage.getItem("timeOutMin") - localStorage.getItem("timeInMin");

								// WORKING HOURS
								if(workingmins == 0)
								{
									$("#workHours").attr("value", workinghours + " hours");
								}
								else
								{
									$("#workHours").attr("value", workinghours + " hours, " + workingmins + " mins");	
								}

								// OVERTIME if Working Hours exceed 8
								if(workinghours > 8 && workingmins == 0)
								{
									$("#overTime").attr("value", workinghours - 8 + " hours");
								}
								else
								{
									$("#overTime").attr("value", "0 hours");
								}

								// UNDERTIME if Working Hours don't reach 8
								if(workinghours < 8)
								{
									$("#underTime").attr("value", (workinghours - 8)*-1 + " hour");
									alert(yea);
								}
								else
								{
									$("#underTime").attr("value","0 hour");
									alert(no);
								}

								// RESET OVERTIME & UNDERTIME on CHANGE
								
							}
						});

				});
			</script>
		</body>
		</html>











