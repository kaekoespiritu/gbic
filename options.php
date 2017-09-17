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
						<td>
							<select id="Monday" class="form-control" disabled>
								<option value="open">Open</option>
								<option value="close">Close</option>
							</select>
						</td>
						<td>
							<select id="Tuesday" class="form-control" disabled>
								<option value="open">Open</option>
								<option value="close">Close</option>
							</select>
						</td>
						<td>
							<select id="Wednesday" class="form-control" disabled>
								<option value="open">Open</option>
								<option value="close">Close</option>
							</select>
						</td>
						<td>
							<select id="Thursday" class="form-control" disabled>
								<option value="open">Open</option>
								<option value="close">Close</option>
							</select>
						</td>
						<td>
							<select id="Friday" class="form-control"  disabled>
								<option value="open">Open</option>
								<option value="close">Close</option>
							</select>
						</td>
						<td>
							<select id="Saturday" class="form-control" disabled>
								<option value="open">Open</option>
								<option value="close">Close</option>
							</select>
						</td>
						<td>
							<select id="Sunday" class="form-control" disabled>
								<option value="open">Open</option>
								<option value="close">Close</option>
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
				<div class="panel-body">
					<a href="" class="btn btn-primary" onclick="save()">Save changes</a>
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

	<div class="col-md-10 col-md-offset-1">
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

		function save()
		{
			confirm("Are you sure you want to save this new open/close payroll schedule?");
		}

		function triggerInput(dayOfWeek)
		{
			var checkbox = document.getElementsByName('checkboxes'), i;
			var checkboxlength = document.querySelectorAll('input[type=checkbox]').length;

			// Enable dropdown when checkbox is selected
			if(document.getElementById(dayOfWeek+"BOX").checked==true)
			{
				var cellCHECK = document.getElementById(dayOfWeek);
				cellCHECK.removeAttribute('disabled');
			}

			// Disabled dropdown when checkbox is deselected
			if(document.getElementById(dayOfWeek+"BOX").checked==false)
			{
				var cellUNCHECK = document.getElementById(dayOfWeek);
				cellUNCHECK.setAttribute('disabled', '');
			}

			// Checking if 2 checkboxes are active
			if(document.querySelectorAll('input[type=checkbox]:checked').length === 2)
			{
				// Set default select option to close
				var changeDefault = document.getElementById(dayOfWeek);
				changeDefault.options[1].setAttribute('selected','selected');

				 for(i = 0; i <= checkboxlength; i++)
				 {
				 	if(checkbox[i].checked===false)
				 	{
				 		checkbox[i].setAttribute('disabled', 'disabled');	
				 	}
				    
				 }
			}

			// Checking if only 1 checkbox is active
			if(document.querySelectorAll('input[type=checkbox]:checked').length === 1)
			{
				for(var i = 0; i <= checkboxlength; i++)
				{
					console.log(checkbox[i]);
				    if(checkbox[i].checked===false)
				    {
				    	checkbox[i].removeAttribute('disabled');
				    }   
				}
			}
		}
	</script>
</div>
</body>
</html>
