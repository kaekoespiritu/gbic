<!DOCTYPE html>
<?php
require_once('directives/db.php');
include('directives/session.php');
  date_default_timezone_set('Asia/Hong_Kong');

$date = (isset($_SESSION['payrollDate']) ? $_SESSION['payrollDate'] : strftime("%B %d, %Y")); // Gets the payroll date if admin didn't finish the payroll for the week
// $date = "November 07, 2018";
// $date = "October 10, 2018";
  // $date = "May 9, 2018";
// $date = "July 11, 2018";
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
	<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
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

		<div class="col-md-9 col-lg-9 col-md-offset-2 col-lg-offset-2">
			<?php

			$counter = 0;

			$site_box = "SELECT location FROM site WHERE active = '1'";
			$site_box_query = mysql_query($site_box);

			//check for early cutoff
			if(isset($_SESSION['earlyCutoff']))
			{
				echo "<script>console.log('".strtotime($_SESSION['earlyCutoff'])." - ".$_SESSION['earlyCutoff']."')</script>";// start
				echo "<script>console.log('".strtotime($_SESSION['payrollDate'])." - ".$_SESSION['payrollDate'] ."')</script>";// end
				$daysCount = strtotime($_SESSION['earlyCutoff']) - strtotime($_SESSION['payrollDate']);
				$cutoffDays = abs($daysCount/(60 * 60 * 24));
				
				$cutoffArr = array();// Array for checking the attendance

				$start = $_SESSION['earlyCutoff'];
				$end = $_SESSION['payrollDate'];
				for($cutoffCount = 0; $cutoffCount <= $cutoffDays; $cutoffCount++)
				{
					array_push($cutoffArr, date('F d, Y', strtotime('+'.$cutoffCount.' day', strtotime($start))));
				}

				
			}

			while($row = mysql_fetch_assoc($site_box_query))
			{
				$site = mysql_real_escape_string($row['location']);

				$day1 = date('F d, Y', strtotime('-1 day', strtotime($date)));
				$day2 = date('F d, Y', strtotime('-2 day', strtotime($date)));
				$day3 = date('F d, Y', strtotime('-3 day', strtotime($date)));
				$day4 = date('F d, Y', strtotime('-4 day', strtotime($date)));
				$day5 = date('F d, Y', strtotime('-5 day', strtotime($date)));
				$day6 = date('F d, Y', strtotime('-6 day', strtotime($date)));
				$day7 = date('F d, Y', strtotime('-7 day', strtotime($date)));

				if(isset($_SESSION['earlyCutoff']))
					$days = $cutoffArr;
				else
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

						$employees = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1'";
						$empCheckerQuery = mysql_query($employees);
						
						$siteCheckerBool = false;

						$empNum = mysql_num_rows($empCheckerQuery);// gets the number of employees in the query
						$count = 1;// counter for number of loops
						$checkerBuilder = "";
						if($empNum != 0)
						{
							$siteCheckerBool = true;
							$checkerBuilder = " AND (";
							while($empArr = mysql_fetch_assoc($empCheckerQuery))
							{
								$employeeId = $empArr['empid'];
								$checkerBuilder .= " empid = '".$employeeId."' ";

								if($empNum != $count)
									$checkerBuilder .= " OR ";

								$count++;
							}
							$checkerBuilder .= ")";
						}
						
						if($siteCheckerBool)//if site has employees
						{
							//Check if overall attendance for a certain site is done
							$attendanceChecker = "SELECT * FROM attendance WHERE date = '$checkDay' $checkerBuilder";
							// if($checkDay == "March 9, 2018")
								$attendanceQuery = mysql_query($attendanceChecker);

							// if($attendanceQuery)
							// {
								$attNum = mysql_num_rows($attendanceQuery);
								
								if($attNum == 0)
								{
									$attendanceStatus = 0;
								}
								else
								{
									$checker = null;
									$noWorkBool = false;// Boolean for no work
									while($attRow = mysql_fetch_assoc($attendanceQuery))
									{
										if($attRow['attendance'] == 3)
											$noWorkBool = true;
										if($attRow['attendance'] != 0 || $noWorkBool)//0 is for no input
										{
											$checker++;//counter
										}
									}
									if($checker == $attNum)//check if number of attendance and the counter are the same
									{
										$attendanceStatus++;//Trigger for completing the attendance for the site
										
									}
								}
							// }
						}
					}
				}
				$weekComplete = false; // boolean to check if attendance is complete for the whole week
			
				if(isset($_SESSION['earlyCutoff']))//dito
				{
					if($attendanceStatus >= $cutoffDays)
						$weekComplete = true;
				}
				if($attendanceStatus >= 7)
				{
					$weekComplete = true;
				}

				if($counter == 0 && $empNum != 0)
				{
					Print '<div class="row">';
				}

				$site_num = mysql_real_escape_string($row['location']);
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
				$siteChecker = "SELECT p.empid FROM payroll AS p LEFT OUTER JOIN employee AS e ON p.empid = e.empid WHERE e.site = '$site_num' AND p.date = '$date' AND e.employment_status = '1'";
				$checkerQuery = mysql_query($siteChecker) or die(mysql_error());

				$siteEmpNum = mysql_num_rows($checkerQuery); // gets the number of emp that has finished payroll
				if($employee_num == $siteEmpNum)
				{
					$siteBool = true;//site is finish with payroll
				}
				if($employee_num != 0)
				{
					/* If location is long, font-size to smaller */
					if(strlen($row['location'])>=16)
					{
						if(!$weekComplete)
						{
							Print '	<a href="payroll_table.php?position=null&site='. $row['location'] .'" style="color: white !important; text-decoration: none !important; pointer-events:none; cursor:not-allowed;" disabled>
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
							Print '	<a href="payroll_table.php?position=null&site='. $row['location'] .'" style="color: white !important; text-decoration: none !important; ">
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
							Print '	<a href="payroll_table.php?position=null&site='. $row['location'] .'" style="color: white !important;  text-decoration: none !important; pointer-events:none; cursor:not-allowed;" disabled> 
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
							Print '	<a href="payroll_table.php?position=null&site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
									<div class="sitebox">
									
										<span class="autofit">'
											. $row['location'] .'<br>';
							if($siteBool)
							{
								Print		'<span class="glyphicon glyphicon-ok"></span>';
							}
							

							Print			'<br>Employees: '. $employee_num .
										'</span>

									</div>
								</a>';
						}
						
					}
					$counter++;
					// Print "<script>console.log('".mysql_num_rows($site_box_query)."')</script>";
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