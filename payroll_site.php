<!DOCTYPE html>
<?php
require_once('directives/db.php');
include('directives/session.php');
  date_default_timezone_set('Asia/Hong_Kong');

  //$date = $date = strftime("%B %d, %Y");
  //1st sample date
  // $date = "October 24, 2017";
  //2nd sample date
  $date = "October 31, 2017";
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
	<div class="col-md-10 col-md-offset-1">
		<ol class="breadcrumb text-left">
			<li><a href="payroll_login.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Logout of Payroll</a></li>
			<li class="active">Site</li>
		</ol>
	</div>
	</div>

	<div class="row">
	<h2>Payroll for this week</h2>
	<h3>Today is <?php 
					
		//$date = date('l, F d, Y', time());
		echo $date; 

		$payrollDay = "SELECT * FROM payroll_day";
		$payrollDayQuery = mysql_query($payrollDay);
		$PdayArr = mysql_fetch_assoc($payrollDayQuery);

	?></h3>

	<h4>Open: <?php Print $PdayArr['open']?> | Close: <?php Print $PdayArr['close']?></h4>
	</div>

	<div class="container">
		<h3>Choose a site</h3>

		<div class="col-md-9 col-md-offset-2">
			<?php

			$counter = 0;

			$site_box = "SELECT location FROM site WHERE active = '1'";
			$site_box_query = mysql_query($site_box);
			while($row = mysql_fetch_assoc($site_box_query))
			{
				$site = $row['location'];

				$day1 = $date;
				$day2 = date('F j, Y', strtotime('-1 day', strtotime($date)));
				$day3 = date('F j, Y', strtotime('-2 day', strtotime($date)));
				$day4 = date('F j, Y', strtotime('-3 day', strtotime($date)));
				$day5 = date('F j, Y', strtotime('-4 day', strtotime($date)));
				$day6 = date('F j, Y', strtotime('-5 day', strtotime($date)));
				$day7 = date('F j, Y', strtotime('-6 day', strtotime($date)));

				$days = array("$day1","$day2","$day3","$day4","$day5","$day6","$day7");
				
				$attendanceStatus = 0;
				foreach($days as $checkDay)
				{
					$day = date('l', strtotime($checkDay));//Gets the Day in the week of the date
					
					$holidayChecker = "SELECT * FROM holiday WHERE date = '$checkDay'";
					$holCheckerQuery = mysql_query($holidayChecker);
					
					//For holiday skip or increment Attendance status Because it is possible that no one set the attendance for that day
					if(mysql_num_rows($holCheckerQuery) > 0)//if they didnt do the attendance on holiday
					{
						$attendanceStatus++;
					}
					else if($day == "Sunday")//If there Sunday is not inputted
					{
						$attendanceStatus++;
					}
					else
					{
						//Check if overall attendance for a certain site is done
						$attendanceChecker = "SELECT * FROM attendance WHERE date = '$checkDay' AND site = '$site'";
						$attendanceQuery = mysql_query($attendanceChecker);
						if($attendanceQuery)
						{
							$attNum = mysql_num_rows($attendanceQuery);
							if($attNum == 0)
							{
								$attendanceStatus = 0;
							}
							else
							{
								$checker = null;
								while($attRow = mysql_fetch_assoc($attendanceQuery))
								{
									if($attRow['attendance'] != 0)//0 is for no input
									{
										$checker++;//counter
									}
								}
								if($checker == $attNum)//check if number of attendance and the counter are the same
								{
									++$attendanceStatus;//Trigger for completing the attendance for the site
								}
							}
						}
					}
					
				}
				//Print "<script>console.log('".$attendanceStatus."')</script>";
				$weekComplete = false; // boolean to check if attendance is complete for the whole week
				if($attendanceStatus >= 7)
				{
					$weekComplete = true;
				}
				//Print "<script>console.log('".$weekComplete." : ".$site."')</script>";

				if($counter == 0)
				{
					Print '<div class="row">';
				}

				$site_num = $row['location'];
				$num_employee = "SELECT * FROM employee WHERE site = '$site_num' AND employment_status = '1'";
				$employee_query = mysql_query($num_employee);
				$employee_num = 0;

				if($employee_query)
				{
					$employee_num = mysql_num_rows($employee_query);
				}

				// check if all employees are done in the site
				$siteBool = false;
				$siteEmpNum = 0;
				$siteChecker = "SELECT p.empid FROM payroll AS p LEFT OUTER JOIN employee AS e ON p.empid = e.empid WHERE e.site = '$site_num' AND p.date = '$date'";
				$checkerQuery = mysql_query($siteChecker) or die(mysql_error());

				$siteEmpNum = mysql_num_rows($checkerQuery); // gets the number of emp that has finished payroll
				
				if($employee_num == $siteEmpNum)
				{
					Print "<script>console.log('".$employee_num." == ".$siteEmpNum."')</script>";
					$siteBool = true;//site is finish with payroll
				}

				if($employee_num != 0)
				{
					/* If location is long, font-size to smaller */
					if(strlen($row['location'])>=16)
					{
						if(!$weekComplete)
						{
							Print '	<a href="payroll_position.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important; pointer-events:none; cursor:not-allowed;" disabled>
									<div class="sitebox" style="background-color:grey !important; ">
										<span class="smalltext">'
											. $row['location'] .'</span>
											<br>
												<span class="glyphicon glyphicon-ban-circle"></span>
											<br><span>Employees: '. $employee_num .
										'</span>
									</div>
								</a>';
						}
						else
						{
							Print '	<a href="payroll_position.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important; ">
									<div class="sitebox" >
										<span class="smalltext">'
											. $row['location'] .'</span>
											<br>';
							if($siteBool)
							Print  			'<span class="glyphicon glyphicon-ok"></span>';

							Print			'<br><span>Employees: '. $employee_num .
										'</span>
									</div>
								</a>';
						}
						
					}
					else
					{
						if(!$weekComplete)
						{
							Print '	<a href="payroll_position.php?site='. $row['location'] .'" style="color: white !important;  text-decoration: none !important; pointer-events:none; cursor:not-allowed;" disabled> 
									<div class="sitebox" style="background-color:grey !important; ">
										<span class="autofit">'
											. $row['location'] .'<br>
												<span class="glyphicon glyphicon-ban-circle"></span>
											<br>Employees: '. $employee_num .
										'</span>
									</div>
								</a>';
						}
						else
						{
							Print '	<a href="payroll_position.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
									<div class="sitebox">
									
										<span class="autofit">'
											. $row['location'] .'<br>';
						if($siteBool)
							Print		'<span class="glyphicon glyphicon-ok"></span>';

							Print			'<br>Employees: '. $employee_num .
										'</span>

									</div>
								</a>';
						}
						
					}
					$counter++;
					if($counter == 5)
					{
						Print '</div>';	
						$counter = 0;
					}
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