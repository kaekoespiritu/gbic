<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');
if(!isset($_GET['site']) && !isset($_GET['site']) && !isset($_GET['site']))
{
	header("Location: applications.php?site=null&position=null&status=null");
}
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li><a href="employees.php?site=null&position=null" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a></li>
					<li class="active">Absence Notifications</li>
				</ol>
			</div>
			
		</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row pull-down">
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-bordered table-condensed" style="background-color:white;">
					<tr>
						<td>ID</td>
						<td>Name</td>
						<td>Position</td>
						<td>Site</td>
						<td>Days absent</td>
						<td>Status</td>
						<td>Actions</td>
					</tr>
					<?php
						$awol = "SELECT * FROM awol_employees";
						$awolQuery = mysql_query($awol);

						if(mysql_num_rows($awolQuery))
						{
						while($row = mysql_fetch_assoc($awolQuery))
						{
							$empid = $row['empid'];
							$employee = "SELECT * FROM employee WHERE empid = '$empid'";
							$employeeQuery = mysql_query($employee);
							$empInfo = mysql_fetch_assoc($employeeQuery);
							Print "
										<tr>
											<td>
												".$empid."
											</td>
											<td>"
												.$empInfo['lastname'].", ".$empInfo['firstname'].
										   "</td>
											<td>"
												.$empInfo['position'].
										   "</td>
											<td>"
												.$empInfo['site'].
										   "</td>
											<td>
												7
											</td>
											<td>"
												.$row['status'].
										   "</td>
											<td>
												<button class='btn btn-default' onclick='next(\"".$empid."\")'>
													Check details
												</button>
												<input type='hidden' name='empid' value='".$empid."'>
											</td>
										</tr>
									";
						}
					}
					else
					{
						Print 
						"
						<tr><td colspan='7'><h3>No records found.</h3></td></tr>
						";
					}
					?>
				</table>
				<div id="hidden_form_container"></div>
			</div>	
		</div>


	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		function saveChanges(){
			confirm("Note: After saving these changes, the loans you've entered will no longer be editable. Are you sure you want to save changes?");
		}
		document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
		
		function next(id) 
		{
	  	var form, input, newInput2;
	  	//Created a form
	  	form = document.createElement('form');
	  	form.action = 'absence_view.php';
	  	form.method = 'post';
	  	//Elements insite the form
	  	input = document.createElement('input');
	  	input.type = 'hidden';
	  	input.name = 'empid';
	  	input.value = id;

	  	//Insert inside the elements inside the form
	  	form.appendChild(input);
	  	document.getElementById('hidden_form_container').appendChild(form);
	  	//used DOM to submit the form
	  	form.submit();
		}	
	</script>
</body>
</html>
<!--
      changeMonth: true,
      changeYear: true