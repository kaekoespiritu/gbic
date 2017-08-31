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
					<?php
					$site_name = $_GET['site'];
					Print '<li class="active">Employee attendance sheet for '. $site_name .'</li>';
					?>
					
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
							Print "	
							<tr id=\"". $row_employee['empid'] ."\">
								<td>
									". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
								</td>
								<td>
									". $row_employee['position'] ."
								</td>
								<!-- Time In -->
								<td>
									<input type='text' class='timein timepicker form-control input-sm' name='timein[]'>
								</td> 
								<!-- Time Out-->
								<td>
									<input type='text' class='timeout timepicker form-control input-sm' name='timeout[]'>
								</td> 
								<!-- Working Hours -->
								<td>
									<input type='text' placeholder='--'' class='form-control input-sm workinghours' name='workinghrs[]' disabled>
								</td> 
								<!-- Overtime -->
								<td>
									<input type='text' placeholder='--' class='form-control input-sm overtime' name='othrs[]' disabled>
								</td> 
								<!-- Undertime -->
								<td>
									<input type='text' placeholder='--' class='form-control input-sm undertime' name='undertime[]' disabled>
								</td> 
								<td>
									<a class='btn btn-sm btn-primary remarks' onclick='remarks(\"". $row_employee['empid'] ."\")'>Remarks</a>
								</td>
								<td>
									<a class='btn btn-sm btn-danger absent' onclick='absent(\"". $row_employee['empid'] ."\")'>Absent</a>
								</td>
							</tr>
							";
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

		function remarks(id)
		{
			// show modal here to input for remarks
		}

		function absent(id)
		{
			
			var mainRow = document.getElementById(id); // Get row to be computed

			// change color of row to shade of red

		}

		function getHour(time)
		{
			console.log("getHour: " + time);
			if(time)
			{
			var hour = time.split(":"); // Split hour + min + AM/PM
			var min = hour[1].split(" "); // Split min + AM/PM
			var diff; // Determine if AM/PM

			if(min[1] == "PM" && hour != 12)
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

	function getMin(time)
	{
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

	function computeTime(row, timeinhour,timeinmin,timeouthour,timeoutmin)
	{
		console.log("Time in: " + timeinhour + ":" + timeinmin + " Time out: " + timeouthour + ":" + timeoutmin);
		if(timeinhour && timeouthour)
		{
			var workinghours = timeouthour - timeinhour;
			var workingmins;
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

			// WORKING HOURS
			if(workingmins == 0)
			{
				row.querySelector('.workinghours').value = workinghours + " hours";
			}
			else
			{
				row.querySelector('.workinghours').value = workinghours + " hours, " + workingmins + " mins";	
			}

			// OVERTIME if Working Hours exceed 8
			if(workinghours > 8 && workingmins == 0)
			{
				row.querySelector('.overtime').value = workinghours - 8 + " hours";
			}
			else if (workinghours > 8)
			{
				row.querySelector('.overtime').value = workinghours - 8 + " hours, " + workingmins + " mins";
			}

			// UNDERTIME if Working Hours don't reach 8
			if(workinghours < 8 && workingmins == 0)
			{
				row.querySelector('.undertime').value = (workinghours - 8)*-1 + " hours";
			}
			else if(workinghours < 8)
			{
				row.querySelector('.undertime').value = (workinghours - 8)*-1 + " hours, " + workingmins + " mins";
			}

			// change color of row to shade of green
			
		}
}

function timeIn(id)
{
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

	function timeOut(id)
	{
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