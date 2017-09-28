<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company id: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: Quicksand;">

	<div class="container-fluid">

		<!-- Navigation bar -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- Open/Close payroll options-->
		<div class="col-md-10 col-md-offset-1 pull-down">

			<div class="panel panel-default">
				<a data-toggle="collapse" href="#collapseChangePayroll">
					<div class="panel-heading">
						<h3 class="panel-title">Change opening and closing payroll</h3>
					</div>
				</a>
			
			<!-- Week table with checkbox and dropdown -->
				<div id="collapseChangePayroll" class="panel-collapse collapse">
					<form method="post" action="logic_options_payroll.php">
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
								<td>
									<select id="Monday" class="form-control" name="dropdown" onchange="swap(Monday)" disabled>
										<option value="" disabled selected>--</option>
										<option value="open" class="open">Open</option>
										<option value="close" class="close">Close</option>
									</select>
								</td>
								<td>
									<select id="Tuesday" class="form-control" name="dropdown" onchange="swap(Tuesday)" disabled>
										<option value="" disabled selected>--</option>
										<option value="open" class="open">Open</option>
										<option value="close" class="close">Close</option>
									</select>
								</td>
								<td>
									<select id="Wednesday" class="form-control" name="dropdown" onchange="swap(Wednesday)" disabled>
										<option value="" disabled selected>--</option>
										<option value="open" class="open">Open</option>
										<option value="close" class="close">Close</option>
									</select>
								</td>
								<td>
									<select id="Thursday" class="form-control" name="dropdown" onchange="swap(Thursday)" disabled>
										<option value="" disabled selected>--</option>
										<option value="open" class="open">Open</option>
										<option value="close" class="close">Close</option>
									</select>
								</td>
								<td>
									<select id="Friday" class="form-control" name="dropdown" onchange="swap(Friday)" disabled>
										<option value="" disabled selected>--</option>
										<option value="open" class="open">Open</option>
										<option value="close" class="close">Close</option>
									</select>
								</td>
								<td>
									<select id="Saturday" class="form-control" name="dropdown" onchange="swap(Saturday)" disabled>
										<option value="" disabled selected>--</option>
										<option value="open" class="open">Open</option>
										<option value="close" class="close">Close</option>
									</select>
								</td>
								<td>
									<select id="Sunday" class="form-control" name="dropdown" onchange="swap(Sunday)" disabled>
										<option value="" disabled selected>--</option>
										<option value="open" class="open">Open</option>
										<option value="close" class="close">Close</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><input type="checkBOX" name="checkboxes" id="MondayBOX" onchange="triggerInput('Monday')"></td>
								<td><input type="checkBOX" name="checkboxes" id="TuesdayBOX" onchange="triggerInput('Tuesday')"></td>
								<td><input type="checkBOX" name="checkboxes" id="WednesdayBOX" onchange="triggerInput('Wednesday')"></td>
								<td><input type="checkBOX" name="checkboxes" id="ThursdayBOX" onchange="triggerInput('Thursday')"></td>
								<td><input type="checkBOX" name="checkboxes" id="FridayBOX" onchange="triggerInput('Friday')"></td>
								<td><input type="checkBOX" name="checkboxes" id="SaturdayBOX" onchange="triggerInput('Saturday')"></td>
								<td><input type="checkBOX" name="checkboxes" id="SundayBOX" onchange="triggerInput('Sunday')"></td>
							</tr>
						</table>

						<!-- Hidden input fields to save for database use -->
						<input type="hidden" id="openPayroll" name="openPayroll">
						<input type="hidden" id="closePayroll" name="openPayroll">

						<!-- Save changes button -->
						<div class="panel-body">
							<a href="" class="btn btn-primary" onclick="save()">Save changes</a>
						</div>
					</form>
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


		<div class="col-md-10 col-md-offset-1">
			<div class="col-md-6">

				<!-- Site management -->
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

			<!-- Position Management -->
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
		// Changes color of active tab to admin options
		document.getElementById("adminOptions").setAttribute("style", "background-color: #10621e;");

		// Save function to database
		function save()
		{
			confirm("Are you sure you want to save this new open/close payroll schedule?");
		}


		// Checkbox to trigger dropdown
		function triggerInput(dayOfWeek)
		{
			var checkbox = document.getElementsByName('checkboxes'), i;
			var checkboxlength = document.querySelectorAll('input[type=checkbox]').length;
			var changeDefault = document.getElementById(dayOfWeek);

			// Enable dropdown when checkbox is selected
			if(document.getElementById(dayOfWeek+"BOX").checked==true)
			{
				var cellCHECK = document.getElementById(dayOfWeek);
				cellCHECK.removeAttribute('disabled');
				
				cellCHECK.options[0].removeAttribute('selected');
				cellCHECK.options[1].setAttribute('selected','');
			}

			// Disabled dropdown when checkbox is deselected
			if(document.getElementById(dayOfWeek+"BOX").checked==false)
			{
				var cellUNCHECK = document.getElementById(dayOfWeek);
				cellUNCHECK.setAttribute('disabled', '');

				if(changeDefault.options[2].hasAttribute('selected'))
				{
					changeDefault.options[2].removeAttribute('selected');	
					changeDefault.options[0].setAttribute('selected','');
				}
				else if(changeDefault.options[1].hasAttribute('selected'))
				{
					changeDefault.options[1].removeAttribute('selected');
					changeDefault.options[0].setAttribute('selected','');
				}
			}

			// Checking if 2 checkboxes are active
			if(document.querySelectorAll('input[type=checkbox]:checked').length === 2)
			{
				changeDefault.options[2].setAttribute('selected','');

				 for(i = 0; i <= checkboxlength; i++)
				 {

				 	if(!checkbox[i].checked)
				 	{
				 		checkbox[i].setAttribute('disabled', 'disabled');	
				 	}

				 	if(checkbox[i].checked==true)
				 	{
				 		console.log(changeDefault);
				 	}
				 }
			}

			// Checking if only 1 checkbox is active
			if(document.querySelectorAll('input[type=checkbox]:checked').length === 1)
			{		
				for(var i = 0; i <= checkboxlength; i++)
				{
				    if(!checkbox[i].checked)
				    {
				    	checkbox[i].removeAttribute('disabled');
				    }   
				}
			}
		}

		// Swapping elements after selecting a different dropdown option
		function swap(chosenDay) {
			var day = ['Monday', 'Tuesday','Wednesday','Thursday','Friday','Saturday'];		
			var first = "";	

			for(var a = 0; a < 7; a++)
			{
				if(chosenDay != day[a])
				{
					if(document.getElementById(day[a]).disabled == false)
					{	

						if(chosenDay.options[1].selected == true) // OPEN
						{
							document.getElementById(day[a]).options[2].selected = true; // CLOSE
							document.getElementById(day[a]).options[1].selected = false;
							chosenDay.options[1].selected = true;
						}
						else if(chosenDay.options[2].selected == true)
						{
							document.getElementById(day[a]).options[1].selected = true; // CLOSE
							document.getElementById(day[a]).options[2].selected = false;
							chosenDay.options[2].selected = true;
						}
					}
				}
			}
		}

	</script>
</div>
</body>
</html>
