<!DOCTYPE html>
<?php
include_once('directives/db.php');
include('directives/session.php');
  date_default_timezone_set('Asia/Hong_Kong');
  if(isset($_SESSION['date']))
	{
		$date = $_SESSION['date'];
	}
	else
	{
		$date = strftime("%B %d, %Y");
	}
	$day = date('l', strtotime($date));
	$holiday = "SELECT * FROM holiday WHERE date = '$date'";
	$holidayQuery = mysql_query($holiday);
	if($holidayQuery)
	{
		$holidayNum = mysql_num_rows($holidayQuery);
	}
	//Print "<script>alert('Name: ". $_SESSION['holidayName'] ."/ Type: ". $_SESSION['holidayType'] ."')</script>";
	// if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
	// {
	// 	Print "<script>alert('Name: ". $_SESSION['holidayName'] ."/ Type: ". $_SESSION['holidayType'] ."')</script>";
	// }
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
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
-->
<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li>
						<a href="employees.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a>
					</li>
					<li class="active">Choosing a site</li>
					<?php
						$pendingSites = "SELECT * FROM site WHERE active = 'pending'";
						$pendingQuery = mysql_query($pendingSites);
						
						$initialQuery = "SELECT * FROM employee WHERE site = ";

						$sites = "";//Store sites that are pending
						while($pendingArr = mysql_fetch_assoc($pendingQuery))
						{
							if($sites != "")
							{
								$sites .= " OR site = ";
							}
							$sites .= "'".$pendingArr['location']."'";
						}
						$emp = mysql_query($initialQuery.$sites);
						$empNum = 0;//Pre-set empNum just incase there is no pending site
						if($emp)
							$empNum = mysql_num_rows($emp);

						if($empNum > 0)
						{
							Print '<a class="btn btn-primary pull-right" href="site_movement.php?site=pending">Idle employees <span class="badge badge-light">'.$empNum.'</span></a>';
						}
						else
						{
							Print '<a class="btn btn-primary pull-right disabledtotally" disabled>Idle employees <span class="badge badge-light">0</span></a>';
						}

					?>
				</ol>
			</div>
		</div>

	<div class="container pull-down">
		<h3>Sites</h3>

		<div class="col-md-9 col-md-offset-2">
			<?php
			$attCounter = 0;//Attendance Completion Checker
			$counter = 0;//Counter for the While loop

			$site_box = "SELECT location FROM site WHERE active = '1'";
			$site_box_query = mysql_query($site_box);
			while($row = mysql_fetch_assoc($site_box_query))
			{
				$attendanceStatus = 0;
				$site = $row['location'];
				if($counter == 0)
				{
					Print '<div class="row">';
				}

				//Check if overall attendance for a certain site is done
				$attendanceChecker = "SELECT * FROM attendance WHERE date = '$date' AND site = '$site'";
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
							$attendanceStatus = 1;//Trigger for completing the attendance for the site
						}
					}
				}
				

				$site_num = $row['location'];
				$num_employee = "SELECT * FROM employee WHERE site = '$site_num' AND employment_status = '1'";
				$employee_query = mysql_query($num_employee);
				$employee_num = 0;

				if($employee_query)
				{
					$employee_num = mysql_num_rows($employee_query);
				}
				/* If location is long, font-size to smaller */
				if(strlen($row['location'])>=16)
				{
					Print '	<a href="site_movement.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
								<div class="sitebox">
									<span class="smalltext">'
										. $row['location'] .
									'</span>
									<br>
									<span class="checkmark" name="site" value="'.$attendanceStatus.'"></span>
									<br>
									<span>Employees: '. $employee_num .'</span>
								</div>
							</a>';
				}
				else
				{
					Print '	<a href="site_movement.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
								<div class="sitebox">
									<span class="autofit">'
										. $row['location'] .
									'<br>
									<span class="checkmark" name="site" value="'.$attendanceStatus.'"></span>
									<br>Employees: '. $employee_num .'
									</span>
								</div>
							</a>';
				}
				$counter++;
				if($counter == 5)
				{
					Print '</div>';	
					$counter = 0;
				}
				
				// Counter for completed attendance each site
				if($attendanceStatus == 1)
				{
					$attCounter++;
				}
			}
				//Attendance Completion Checker
				$siteChecker = "SELECT * FROM site WHERE active = '1'";
				$siteQuery = mysql_query($siteChecker);
				$siteNum = mysql_num_rows($siteQuery);
				if($siteNum == $attCounter)
				{
					$_SESSION['completeAtt'] = true;
				}
				else
				{
					unset($_SESSION['completeAtt']);
				}
			?>
</div>
</div>
<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
</script>
</div>
</body>
</html>