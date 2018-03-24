	<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');
?>

<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">

	<style>
	body
	{
		background: #fafafa;
	  	text-align: center;
	  	height:100% !important;
	}

	</style>
<script src="js/jquery.min.js"></script>

</head>
<body style="font-family: Quicksand;">

<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>
	
	<div class="container pull-down">
		<table class="table table-bordered table-responsive" style="color: white;">
			<tr>
				<td style="background-color:#AA6F38">
					<h4>Today is<br></h4>
					<h3>
						<?php 
						date_default_timezone_set('Asia/Hong_Kong');
						$date = date('l\<\b\\r\>F d, Y', time());
						echo $date; ?>
					</h3>
				</td>
				<td style="background-color: #236068">
					<?php
					$emp_query = "SELECT * FROM employee";
					$employee_query = mysql_query($emp_query);
					$employees = mysql_num_rows($employee_query);
					?>
					<h1 class="text-center"><?php Print "$employees"?></h1>
					<h4 class="text-center">Total Employees</h4>
				</div>
			</td>
			<td style="background-color: #AA4038">
				<h3>Today's
					<br>Attendance Status:<br>
					<i>
						<?php
							if(isset($_SESSION['completeAtt']))
								Print "Complete!";
							else
								Print "Incomplete!";
						?>
						
					</i>
				</h3>
			</td>
		</tr>
	</table>
			<!-- TODO: Change this alert to modal -->
			<?php
			$awol = "SELECT * FROM awol_employees";
			$awolQuery = mysql_query($awol);
			$awolCount = mysql_num_rows($awolQuery);
			if($awolCount > 0)
			{

				// Call modal to show  

				Print "<script>
						$(document).ready(function(){
							$('#awolNumber').html(\"".$awolCount."\");
							$('#show').modal('show');
						});
					</script>";
				
				Print "
					<a href='applications.php'>
							<div class='panel panel-danger'>
								<div class='panel-heading'>
									<h3 class='panel-title'>ABSENCE NOTICE: There are ".$awolCount." employee(s) absent for a week.</h3>
								</div>
							</div>
						</a>";
			}
			?>
	
</div>

<!-- SITES -->
<?php
$query = "SELECT location FROM site WHERE active = '1'";
$site_query = mysql_query($query);

$cycles = 0;
while($row = mysql_fetch_assoc($site_query))
{

	if($cycles == 0 || $cycles == 4)
	{
		$emp_location = $row['location'];
		$employee_find = "SELECT * FROM employee WHERE site = '$emp_location' AND employment_status = '1' ";
		$employee_find_query = mysql_query($employee_find);
		$employee_num = 0;
		if($employee_find_query)
		{
			$employee_num = mysql_num_rows($employee_find_query);
		}
		Print "<a data-toggle='modal' href='#shortcut' onclick='shortcut(\"".$row['location']."\")''><div class='col-md-2 col-md-offset-1 card card-1'>
				<h4 class='sitename' id='".$row['location']."'>".$row['location']."</h4>	
				Employees deployed: ".$employee_num."
			   </div></a>";

	if($cycles == 4)
	{
		$cycles = 1;
	}
	else
	{
		++$cycles;
	}
}
else
{
	$emp_location = $row['location'];
	$employee_find = "SELECT * FROM employee WHERE site = '$emp_location' AND employment_status = '1' ";
	$employee_find_query = mysql_query($employee_find);
	$employee_num = 0;
	if($employee_find_query)
	{
		$employee_num = mysql_num_rows($employee_find_query);
	}
	Print "<a data-toggle='modal' href='#shortcut' onclick='shortcut(\"".$row['location']."\")''><div class='col-md-2 card card-1'>
			<h4 class='sitename' id='".$row['location']."'>".$row['location']."</h4>
			Employees deployed: ".$employee_num."	
		   </div></a>";
++$cycles;
}

}
?>

<!-- MODAL -->
<div class="modal fade" id="shortcut" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6 <?php Print $attendanceAccess?>">
						<a id="attendanceLink" class="btn btn-primary btn-lg col-md-12 <?php Print $attendanceAccess?>">
						<img src="Images/attendance.png" class="center-block">Attendance</a>
					</div>
					<div class="col-md-6  <?php Print $employeesTab?>">
						<a id="employeesLink" class="btn btn-primary btn-lg col-md-12  <?php Print $employeesTab?>">
						<img src="Images/engineer.png" class="center-block"> Employees</a>
					</div>
					<div class="pull-down col-md-12">
						<h4 class="text-center">Click on the options above to view details for <span id="addSiteName"></span>.</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal for showing AWOL employees -->
	<div class="modal fade" id="show" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h4>
					Absence Notice: There are <span id="awolNumber"></span> employee(s) that have accumulated 7 days of absences and are now pending for AWOL.
					</h4>
					<br/>
					<h5><i>Click anywhere to close this notification.</i></h5>
				</div>
			</div>
		</div>
	</div>	


</div>
<script>
	// Change main row color to Home
	document.getElementById("home").setAttribute("style", "background-color: #10621e;");

	
    function loadAwol(num){
    	console.log('asd');
		// $('#awolNumber').val(num);
		// $('#show').modal('show');
	}

	function shortcut(sitename) {
		// Calling links to change
		var attendance = document.getElementById('attendanceLink');
		var employees = document.getElementById('employeesLink');
		var reports = document.getElementById('reportsLink');

		// Change name of modal to name of appropriate site
		var span = document.getElementById('addSiteName').innerHTML = sitename;

		console.log(sitename);

		// Changing links accordingly
		attendance.setAttribute("href", "enterattendance.php?site="+sitename+"&position=null"); 
		employees.setAttribute("href", "employees.php?site="+sitename+"&position=null"); 
		//reports to be added soon
	}
</script>
<script rel="javascript" src="js/jquery.min.js"></script>
<script src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
</body>
</html>

