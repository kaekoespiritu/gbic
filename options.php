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
						<td><input type="checkbox" name="checkboxes[]" id="MondayBOX" onclick="triggerInput('Monday')"></td>
						<td><input type="checkbox" name="checkboxes[]" id="TuesdayBOX" onclick="triggerInput('Tuesday')"></td>
						<td><input type="checkbox" name="checkboxes[]" id="WednesdayBOX" onclick="triggerInput('Wednesday')"></td>
						<td><input type="checkbox" name="checkboxes[]" id="ThursdayBOX" onclick="triggerInput('Thursday')"></td>
						<td><input type="checkbox" name="checkboxes[]" id="FridayBOX" onclick="triggerInput('Friday')"></td>
						<td><input type="checkbox" name="checkboxes[]" id="SaturdayBOX" onclick="triggerInput('Saturday')"></td>
						<td><input type="checkbox" name="checkboxes[]" id="SundayBOX" onclick="triggerInput('Sunday')"></td>
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
				var day = dayOfWeek+"BOX";
				// Getting input field
				var cellCHECK = document.getElementById(day);
				console.log(cellCHECK);
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

				console.log("Number of checks: " + document.querySelectorAll(':checked').length);
				// Checking if 2 checkboxes are active
				if(document.querySelectorAll(':checked').length == 2)
				{
					var checkboxes = document.getElementsByTagName("INPUT");
 
				  for(var i = 0; i <= checkboxes; i++)
				  {
				    if(checkboxes[i].type=="checkbox" && checkboxes[i].checked == false)
				    checkboxes[i].disabled=true;
				  }
				}
				else if(document.querySelectorAll(':checked').length/2 == 1)
				{

					var checkboxes = document.getElementsByTagName("INPUT");

					for(var i = 0; i <= checkboxes; i++)
					{
					    
					    if(checkboxes[i].type=="checkbox" && checkboxes[i].hasAttribute("disabled")==true)
					    {
					    	checkboxes[i].disabled=false;	
					    	checkboxes[i].checked=false;
					    }
					    
					  	console.log("lalala  "+checkboxes[i]);
					  }
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
		}
	</script>
</div>
</body>
</html>
