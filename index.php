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

	<style>
	body
	{
		background: #fafafa;
	  	text-align: center;
	  	height:100% !important;
	}

	</style>

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

	<a href="applications.php"><div class="panel panel-danger">
		<div class="panel-heading">
			<!-- WILL REDIRECT TO APPLICATIONS PAGE WITH FILTER SET TO PENDING APPROVAL AND 7 DAYS INCURRED ABSENCE-->
			<h3 class="panel-title">ABSENCE NOTICE: There are 5 employee(s) absent for a week.</h3>
		</div>
	</div></a>
</div>

<!-- SITES -->
<?php
$query = "SELECT location FROM site";
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
		Print "<div class='col-md-2 col-md-offset-1 card card-1'>
		<h4>".$row['location']."</h4>	
		Employees deployed: ".$employee_num."
	</div>";
			 		//Print "<script>alert('".$cycles."')</script>";
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
	Print "<div class='col-md-2 card card-1'>
	<h4>".$row['location']."</h4>
	Employees deployed: ".$employee_num."	
</div>";
++$cycles;
}

}
?>


</div>

<script>
	document.getElementById("home").setAttribute("style", "background-color: #10621e;");
</script>
<script rel="javascript" src="js/jquery.min.js"></script>
</body>
</html>
