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
						<td><input type="text" class="form-control" placeholder="---" disabled id="Monday"></td>
						<td><input type="text" class="form-control" placeholder="---" disabled id="Tuesday"></td>
						<td><input type="text" class="form-control" placeholder="---" disabled id="Wednesday"></td>
						<td><input type="text" class="form-control" placeholder="---" disabled id="Thursday"></td>
						<td><input type="text" class="form-control" placeholder="---" disabled id="Friday"></td>
						<td><input type="text" class="form-control" placeholder="---" disabled id="Saturday"></td>
						<td><input type="text" class="form-control" placeholder="---" disabled id="Sunday"></td>
					</tr>
					<tr>
						<td><input type="checkBOX" name="checkboxes[]" id="MondayBOX" onchange="triggerInput('Monday')"></td>
						<td><input type="checkBOX" name="checkboxes[]" id="TuesdayBOX" onchange="triggerInput('Tuesday')"></td>
						<td><input type="checkBOX" name="checkboxes[]" id="WednesdayBOX" onchange="triggerInput('Wednesday')"></td>
						<td><input type="checkBOX" name="checkboxes[]" id="ThursdayBOX" onchange="triggerInput('Thursday')"></td>
						<td><input type="checkBOX" name="checkboxes[]" id="FridayBOX" onchange="triggerInput('Friday')"></td>
						<td><input type="checkBOX" name="checkboxes[]" id="SaturdayBOX" onchange="triggerInput('Saturday')"></td>
						<td><input type="checkBOX" name="checkboxes[]" id="SundayBOX" onchange="triggerInput('Sunday')"></td>
					</tr>
				</table>
				<div class="panel-body">
					<a href="" class="btn btn-primary">Save changes</a>
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

	<div class="row">
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

		function triggerInput(dayOfWeek)
		{

			if(document.getElementById(dayOfWeek+"BOX").checked==true)
			{
				// Getting input field
				var cellCHECK = document.getElementById(dayOfWeek);

				// Creating a select dropdown
				var selectList = document.createElement("select");
				selectList.setAttribute("id", dayOfWeek);
				
				// Replacing input field with select dropdown
				cellCHECK.parentNode.replaceChild(selectList, cellCHECK);

				// Creating a list of options
				var options = ["Open", "Close"];

				// Adding the options to the select
				for (var i = 0; i < options.length; i++)
				{
				    var option = document.createElement("option");
				    option.setAttribute("value", options[i]);
				    option.text = options[i];
				    selectList.appendChild(option);
				}

			}

			// Revert dropdown to input
			if(document.getElementById(dayOfWeek+"BOX").checked==false)
			{
				// Getting select dropdown
				var cellUNCHECK = document.getElementById(dayOfWeek);

				// Recreating input field
				var input = document.createElement("input");
				input.setAttribute('type', 'text');
				input.setAttribute('placeholder', '---');
				input.setAttribute('id', dayOfWeek);
				input.setAttribute('class','form-control');
				input.setAttribute('disabled', 'disabled');

				// Reverting changes
				cellUNCHECK.parentNode.replaceChild(input, cellUNCHECK);
			}

			console.log("Number of checkboxes: " + document.querySelectorAll('input[type=checkbox]').length);

				var checkbox = document.querySelectorAll('input[type=checkbox]'), i;
				var checkboxes = document.querySelectorAll('input[type=checkbox]').length;

				// Checking if 2 checkboxes are active
				if(document.querySelectorAll('input:checked').length === 2)
				{
					 for(i = 0; i <= checkboxes; i++)
					 {
					 	console.log("Looping through: " + checkbox[i] + " to get: " + checkbox[i].checked);
					 	console.log("Has disabled: " + checkbox[i].checked);
					 	if(checkbox[i].checked===false)
					 	{
					 		checkbox[i].setAttribute('disabled', 'disabled');	
					 	}
					    
					 }
				}

				if(document.querySelectorAll('input:checked').length === 1)
				{

					for(var i = 0; i <= checkboxes; i++)
					{
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
