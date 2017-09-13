<!DOCTYPE html>
<?php
include('directives/db.php');
include('directives/session.php');
  date_default_timezone_set('Asia/Hong_Kong');
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
<body style="font-family: Quicksand;">

<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<div class="row pull-down">
	<h2>Payroll for this week</h2>
	<h3>Today is <?php 
					date_default_timezone_set('Asia/Hong_Kong');
					$date = date('l, F d, Y', time());
					echo $date; ?></h3>
	<h4>Open: Tuesday | Close: Wednesday</h4>
	</div>

	<div class="container">
		<h3>Choose a site</h3>

		<div class="col-md-9 col-md-offset-2">
			<?php

			$counter = 0;

			$site_box = "SELECT location FROM site";
			$site_box_query = mysql_query($site_box);
			while($row = mysql_fetch_assoc($site_box_query))
			{

				if($counter == 0)
				{
					Print '<div class="row">';
				}

				$site_num = $row['location'];
				$num_employee = "SELECT * FROM employee WHERE site = '$site_num'";
				$employee_query = mysql_query($num_employee);
				$employee_num = 0;

				if($employee_query)
				{
					$employee_num = mysql_num_rows($employee_query);
				}
				/* If location is long, font-size to smaller */
				if(strlen($row['location'])>=16)
				{
					Print '	<a href="payrollposition.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
								<div class="sitebox">
									<span class="smalltext">'
										. $row['location'] .'</span><br><br><span>Employees: '. $employee_num .
									'</span>
								</div>
							</a>';
			}
			else
			{
				Print '	<a href="payrollposition.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
							<div class="sitebox">
								<span class="autofit">'
									. $row['location'] .'<br><br>Employees: '. $employee_num .
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
	?>
</div>
</div>
</body>
<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");
</script>
</html>