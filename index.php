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
					$emp_query = "SELECT * FROM employee WHERE employment_status = 1";
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
	<div>
		<h2 align="left">
			Notifications :
		</h2>
	</div>
			<!-- TODO: Change this alert to modal -->
			<?php
			$notifBool = true;// Boolean for notification for displaying "No notifications"
			// Notification for AWOL Employees
			$awol = "SELECT * FROM awol_employees awol INNER JOIN employee emp ON emp.empid = awol.empid ORDER BY emp.lastname ASC, emp.firstname ";
			$awolQuery = mysql_query($awol);
			$awolCount = mysql_num_rows($awolQuery);
			if($awolCount > 0)
			{
				$notifBool = false;// disable display of "No notification"
				if($awolCount > 2)
				{
					$awolNum = $awolCount / 2;
					$awolNum = round($awolNum);// Rounds off the result of awol num
					$appendAwolQuery1 = "LIMIT 0, ".$awolNum;
						$appendAwolQuery2 = "LIMIT ".$awolNum.", ".($awolCount+1);

					$awol1 = $awol;
					$awol2 = $awol;
					$awol1 .= $appendAwolQuery1;
					$awol2 .= $appendAwolQuery2;


					$awolQuery1 = mysql_query($awol1);
					$awolQuery2 = mysql_query($awol2);
				}
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
									<h3>ABSENCE NOTICE: Employees that accumulated 7 DAYS of absences: </h3>
									<div class='row'>";

				if($awolCount > 2)// Separate in 2 columns
				{
					Print			"<div class='col-lg-8 col-lg-offset-2'>";
					// 1st Column
					Print				"<div class='col-lg-6'>
											<ul align='left'>";
									while($awolArr1 = mysql_fetch_assoc($awolQuery1))
									{	
										Print "<li>".$awolArr1['lastname'].", ".$awolArr1['firstname']."(".$awolArr1['position'].") - [".$awolArr1['site']."] </li>";
									}

					Print				"	</ul>
										</div>";
					// 2nd Column
					Print				"<div class='col-lg-6'>
											<ul align='left'>";
									while($awolArr2 = mysql_fetch_assoc($awolQuery2))
									{	
										Print "<li>".$awolArr2['lastname'].", ".$awolArr2['firstname']."(".$awolArr2['position'].") - [".$awolArr2['site']."] </li>";
									}

					Print				"	</ul>
										</div>";
					Print 			"</div>";
				}		
				else
				{
					Print			"<div class='col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3'>
										<ul align='left'>";
									while($awolArr = mysql_fetch_assoc($awolQuery))
									{	
										Print "<li>".$awolArr['lastname'].", ".$awolArr['firstname']."(".$awolArr['position'].") - [".$awolArr['site']."] </li>";
									}

					Print			"	</ul>
									</div>";
				}		
				

				Print			"	</div>
								</div>
							</div>
						</a>";
			}

			// Notification for employees that accumulated 4 consecutive absences
			$absence = "SELECT * FROM absence_notif ab INNER JOIN employee emp ON emp.empid = ab.empid ORDER BY emp.lastname ASC, emp.firstname ";
			$absenceQuery = mysql_query($absence);
			$absenceCount = mysql_num_rows($absenceQuery);
			if($absenceCount > 0)
			{
				$notifBool = false;// disable display of "No notification"
				if($absenceCount > 3)
				{
					$absenceNum = $absenceCount / 2;
					$absenceNum = round($absenceNum);// Rounds off the result of awol num
					$appendAbsenceQuery1 = "LIMIT 0, ".$absenceNum;
					$appendAbsenceQuery2 = "LIMIT ".$absenceNum.", ".($absenceCount+1);

					$absence1 = $absence;
					$absence2 = $absence;
					$absence1 .= $appendAbsenceQuery1;
					$absence2 .= $appendAbsenceQuery2;

					$absenceQuery1 = mysql_query($absence1);
					$absenceQuery2 = mysql_query($absence2);
				}
				
				Print "
						<div class='panel panel-danger'>
							<div class='panel-heading'>
								<h3>ABSENCE NOTICE: Employees that accumulated 4 DAYS of absences:
									<span>
										<input type='button' class='btn btn-danger pull-right' onclick='clearAbsenceRecord()' value='OK'>
									</span>
								</h3>
								<div class='row'>";

				if($absenceCount > 3)// Separate in 2 columns
				{
					Print			"<div class='col-lg-8 col-lg-offset-2'>";
					// 1st Column
					Print				"<div class='col-lg-6'>
											<ul align='left'>";
									while($absenceArr1 = mysql_fetch_assoc($absenceQuery1))
									{	
										Print "<li>".$absenceArr1['lastname'].", ".$absenceArr1['firstname']."(".$absenceArr1['position'].") - [".$absenceArr1['site']."] </li>";
									}

					Print				"	</ul>
										</div>";
					// 2nd Column
					Print				"<div class='col-lg-6'>
											<ul align='left'>";
									while($absenceArr2 = mysql_fetch_assoc($absenceQuery2))
									{	
										Print "<li>".$absenceArr2['lastname'].", ".$absenceArr2['firstname']."(".$absenceArr2['position'].") - [".$absenceArr2['site']."] </li>";
									}

					Print				"	</ul>
										</div>";
					Print 			"</div>";
				}		
				else
				{
					Print			"<div class='col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3'>
										<ul align='left'>";
									while($absenceArr = mysql_fetch_assoc($absenceQuery))
									{	
										Print "<li>".$absenceArr['lastname'].", ".$absenceArr['firstname']."(".$absenceArr['position'].") - [".$absenceArr['site']."] </li>";
									}

					Print				"</ul>
									</div>";
				}		
				

				Print			"	</div>
								</div>
							</div>";
			}

			// Notification for 13th month pay tenure
			$tenureChecker = "SELECT * FROM employee WHERE employment_status = '1'";
			$tenureQuery = mysql_query($tenureChecker) or die(mysql_error());
			$tenureArrWithReq = array();
			$tenureArrWithOReq = array();
			while($empArr = mysql_fetch_assoc($tenureQuery))
			{
				
				if($empArr['complete_doc'] == '1')// Complete Req
				{
					$dateToday = strtotime('now');
					$dateHired = strtotime('+6 month', strtotime($empArr['datehired']));
					$dateHiredLimit = strtotime('+7 month', strtotime($empArr['datehired']));
					if($dateToday >= $dateHired && $dateToday <= $dateHiredLimit)// Check if employee exceeded 6 months of tenure but dismisses the notif if the tenure entered 7months
					{
						$toArr = $empArr['lastname'].', '.$empArr['firstname'].'('.$empArr['position'].') - ['.$empArr['site'].']('.date('F j, Y',$dateHired).')';
						array_push($tenureArrWithReq, $toArr);
					}
				}
				else if($empArr['complete_doc'] == '0') // Incomplete Req
				{
					$dateToday = strtotime('now');
					$dateHired = strtotime('+5 month', strtotime($empArr['datehired']));
					$dateHiredLimit = strtotime('+6 month', strtotime($empArr['datehired']));
					if($dateToday >= $dateHired && $dateToday <= $dateHiredLimit)// Check if employee exceeded 5 months of tenure but dismisses the notif if the tenure entered 6months
					{
						$toArr = $empArr['lastname'].', '.$empArr['firstname'].'('.$empArr['position'].') - ['.$empArr['site'].']('.date('F j, Y',$dateHired).')';
						array_push($tenureArrWithOReq, $toArr);
					}
				}
			}
			// $tenureArrWithReq = array($tenureArrWithReq);
			// $tenureArrWithOReq = array($tenureArrWithOReq);
			if(!empty($tenureArrWithReq) || !empty($tenureArrWithOReq))
			{
				$notifBool = false;// disable display of "No notification"
				Print "		<div class='panel panel-warning'>
								<div class='panel-heading'>
									<h3>13th Month pay notice: </h3>
									<div class='row'>";

									if(!empty($tenureArrWithReq))// With req
									{
										Print "<h3 class='col-md-12'>Employees with Complete Requirements that stayed in the company for 6 Months</h3>
											<div class='col-md-10 col-md-offset-1'>";
										$wReqCount = count($tenureArrWithReq);
										$wReqHalf = $wReqCount / 2;
										$wReqHalf = round($wReqHalf);
										$loopCounter = 1; 
										$wReqArr1 = array();// 1st column
										$wReqArr2 = array();// 2nd column
										foreach($tenureArrWithReq as $tenureReq)
										{
											if($wReqCount > 2)
											{
												if($loopCounter >= $wReqHalf)
													array_push($wReqArr1, $tenureReq);
												else
													array_push($wReqArr2, $tenureReq);
											$loopCounter++;// Increment loop counter
											}
											else
												array_push($wReqArr1, $tenureReq);
										}

										if($wReqCount > 2)
										{
											Print "	<div class='col-md-6'>
														<ul>";
												foreach($wReqArr1 as $withReq)
												{
													Print "<li align='left'>".$withReq."</li>";
												}
											Print "		</ul>
													</div>";
											Print "	<div class='col-md-6'>	
														<ul>";
												foreach($wReqArr2 as $withReq)
												{
													Print "<li align='left'>".$withReq."</li>";
												}
											Print "		</ul>
													</div>";
										}
										else
										{
											Print "<ul>";
											foreach($wReqArr1 as $withReq)
												{
													Print "<li align='left'>".$withReq."</li>";
												}
											Print "</ul>";
										}

										Print "</div>";
									}
									if(!empty($tenureArrWithOReq)) //Without req
									{
										Print "<div class='col-md-10 col-md-offset-1'>
											<h3 class='col-md-12'>Employees with No/Incomplete Requirements that stayed in the company for 5 Months</h3>";
										$wOReqCount = count($tenureArrWithOReq);
										$wOReqHalf = $wOReqCount / 2;
										$wOReqHalf = round($wOReqHalf);
										$loopCounter = 1; 
										$wOReqArr1 = array();// 1st column
										$wOReqArr2 = array();// 2nd column
										foreach($tenureArrWithOReq as $tenureWOReq)
										{
											if($wOReqCount > 2)
											{
												if($loopCounter >= $wOReqHalf)
													array_push($wOReqArr1, $tenureWOReq);
												else
													array_push($wOReqArr2, $tenureWOReq);
											$loopCounter++;// Increment loop counter
											}
											else
												array_push($wOReqArr1, $tenureWOReq);
										}
										Print "<div class='row'>";
										if($wOReqCount > 2)
										{
											Print "	<div class='col-md-6 smalltext'>
														<ul>";
												foreach($wOReqArr1 as $withWOReq)
												{
													Print "<li align='left'>".$withWOReq."</li>";
												}
											Print "		</ul>
													</div>";
											Print "	<div class='col-md-6'>	
														<ul>";
												foreach($wOReqArr2 as $withWOReq)
												{
													Print "<li align='left'>".$withWOReq."</li>";
												}
											Print "		</ul>
													</div>";
										}
										else
										{
											Print "	<div class='col-md-6 col-md-offset-3'>";
											Print "<ul>";
											foreach($wOReqArr1 as $withWOReq)
												{
													Print "<li align='left'>".$withWOReq."</li>";
												}
											Print "</ul>";
											Print "</div>";
										}
										Print "</div>";
										Print "</div>";
									}
				Print			"	</div>
								</div>
							</div>";
			}
				
			if($notifBool)
				Print "	<div class='panel panel-info'>
							<div class='panel-heading'>
								<h3>No notifications</h3>
							</div>
						</div>";
			?>
	<div>
		<h2 align='left' >
			Sites :
		</h2>
	</div>
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
		$emp_location = mysql_real_escape_string($row['location']);
		$employee_find = "SELECT * FROM employee WHERE site = '$emp_location' AND employment_status = '1' ";
		$employee_find_query = mysql_query($employee_find);
		$employee_num = 0;
		if($employee_find_query)
		{
			$employee_num = mysql_num_rows($employee_find_query);
		}
		Print "<a data-toggle='modal' href='#shortcut' onclick='shortcut(\"".mysql_real_escape_string($row['location'])."\")''><div class='col-md-2 col-lg-2 col-md-offset-1 col-lg-offset-1 card card-1'>
				<h4 class='sitename' id='".mysql_real_escape_string($row['location'])."'>".mysql_real_escape_string($row['location'])."</h4>	
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
	$emp_location = mysql_real_escape_string($row['location']);
	$employee_find = "SELECT * FROM employee WHERE site = '$emp_location' AND employment_status = '1' ";
	$employee_find_query = mysql_query($employee_find);
	$employee_num = 0;
	if($employee_find_query)
	{
		$employee_num = mysql_num_rows($employee_find_query);
	}
	Print "<a data-toggle='modal' href='#shortcut' onclick='shortcut(\"".mysql_real_escape_string($row['location'])."\")''><div class='col-md-2 col-lg-2 card card-1'>
			<h4 class='sitename' id='".mysql_real_escape_string($row['location'])."'>".mysql_real_escape_string($row['location'])."</h4>
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
					<div class="col-md-6 col-lg-6 <?php Print $attendanceAccess?>">
						<a id="attendanceLink" class="btn btn-primary btn-lg col-md-1 col-lg-12 <?php Print $attendanceAccess?>">
						<img src="Images/attendance.png" class="center-block">Attendance</a>
					</div>
					<div class="col-md-6 col-lg-6  <?php Print $employeesTab?>">
						<a id="employeesLink" class="btn btn-primary btn-lg col-md-1 col-lg-12  <?php Print $employeesTab?>">
						<img src="Images/engineer.png" class="center-block"> Employees</a>
					</div>
					<div class="pull-down col-md-1 col-lg-12">
						<h4 class="text-center">Click on the options above to view details for <span id="addSiteName"></span>.</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


</div>
<script>
	// Change main row color to Home
	document.getElementById("home").setAttribute("style", "background-color: #10621e;");

	function clearAbsenceRecord() {
		window.location.assign('logic_clear_absence.php')
	}

	function shortcut(sitename) {
		// Calling links to change
		var attendance = document.getElementById('attendanceLink');
		var employees = document.getElementById('employeesLink');
		var reports = document.getElementById('reportsLink');

		// Change name of modal to name of appropriate site
		var span = document.getElementById('addSiteName').innerHTML = sitename;

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

