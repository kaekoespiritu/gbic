<!DOCTYPE html>
<?php
if(!isset($_GET['site']))
{
	header("location:payroll_login.php");
}


include('directives/db.php');
include('directives/session.php');
  date_default_timezone_set('Asia/Hong_Kong');
  $site = $_GET['site'];//Change this to dynamic by getting data from PayrollSite.php


// $date = (isset($_SESSION['payrollDate']) ? $_SESSION['payrollDate'] : strftime("%B %d, %Y")); // Gets the payroll date if admin didn't finish the payroll for the week
$date = "October 10, 2018";
// $date = "May 9, 2018";
// $date = "July 11, 2018";


$dayToday = date('l, F d, Y', time());
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link href="css/multiple-select.css" rel="stylesheet"/>
</head>
<body style="font-family: Quicksand;" onload="printCheck()">

<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<div class="row pull-down">
	<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
		<ol class="breadcrumb text-left">
			<li><a href="payroll_site.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a></li>
			<li class="active">Position</li>
			<?php
			Print '
				<button class="btn btn-primary pull-right" id="printButton" onclick="printPayroll(\''.$date.'\',\''.$site.'\')">Print Payroll sheet</button>';
			?>
		</ol>
	</div>
	</div>

	<div class="row">
	<h2>Payroll for this week</h2>
	<h3>Today is <?php 
					
					echo $dayToday; ?></h3>
	<h4>Open: Tuesday | Close: Wednesday</h4>
	<h4>At site <?Print $site?></h4>
	</div>

	<div class="container">
		<h3>Choose a position</h3>

		<div class="col-md-9 col-lg-9 col-md-offset-2 col-lg-offset-2">
			<?php

			$counter = 0;

			$position_box = "SELECT position FROM job_position WHERE active = '1'";
			$position_box_query = mysql_query($position_box);

			$payrollPrintBool = true;
			while($row = mysql_fetch_assoc($position_box_query))
			{

				$position_num = $row['position'];
				$num_employee = "SELECT * FROM employee WHERE position = '$position_num' AND site = '$site' AND employment_status = '1'";
				$employee_query = mysql_query($num_employee);
				$employee_num = 0;

				if($employee_query)
				{
					$employee_num = mysql_num_rows($employee_query);
				}
				// check if all employees are done in the site
				$positionBool = false;
				$positionEmpNum = 0;
				$positionChecker = "SELECT p.empid FROM payroll AS p LEFT OUTER JOIN employee AS e ON p.empid = e.empid WHERE e.site = '$site' AND e.position = '$position_num' AND p.date = '$date'";
				
				$checkerQuery = mysql_query($positionChecker) or die(mysql_error());

				$positionEmpNum = mysql_num_rows($checkerQuery); // gets the number of emp that has finished payroll
				if($employee_num == $positionEmpNum)
				{
					$positionBool = true;//site is finish with payroll
				}
				else
				{
					$payrollPrintBool = false;//Print function will be disabled
				}

				if($counter == 0 && $employee_num != 0)
				{
					Print '<div class="row">';
				}

				if($employee_num != 0)
				{
					/* If location is long, font-size to smaller */
					if(strlen($row['position'])>=16)
					{
						Print '	<a href="payroll_table.php?position='. $row['position'] .'&site='. $site .'" style="color: white !important; text-decoration: none !important;">
									<div class="sitebox">
										<span class="smalltext">'
											. $row['position'] .'</span><br>';
						if($positionBool)
							Print  			'<span class="glyphicon glyphicon-ok"></span>';
							
							Print			'<span><br>Employees: '. $employee_num .
										'</span>
									</div>
								</a>';
					}
					else
					{
						Print '	<a href="payroll_table.php?position='. $row['position'] .'&site='. $site .'" style="color: white !important; text-decoration: none !important;">
									<div class="sitebox">
										<span class="autofit">'
											. $row['position'] .'<br>';
						if($positionBool)
							Print  			'<span class="glyphicon glyphicon-ok">';
						

							Print 			'</span><br>Employees: '. $employee_num .
										'</span>
									</div>
								</a>';
					}
					$counter++;
					if($counter == 5)
					{
						Print '</div>';	
						$counter = 0;
					}
				}
			}
			if($payrollPrintBool)
			{
				Print "<input type='hidden' id='printBool' value='check'>";
			}
			?>
		</div>
	</div>
</body>
<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	function printCheck() {
		var check = document.getElementById('printBool');
		var printButton = document.getElementById('printButton').disabled
		if(check) 
			printButton.disabled = false;
		else
			printButton.disabled = true;

	}

	function printPayroll(date, site) {
		window.location.assign("print_payroll.php?site="+site+"&date="+date);

	}
	document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");
</script>
</html>
















