<!DOCTYPE html>
<?php
include('directives/db.php');
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

	<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
	<div class="row pull-down">
		<div class="row">
			<div class="col-md-4 col-md-offset-2" style="border-right: 1px solid black;">
				<h2 id='holidayTitle'>
					<?php 
						if($holidayNum != 0)
						{
								$holidayRow = mysql_fetch_assoc($holidayQuery);
								$_SESSION['holidayName'] = $holidayRow['holiday'];
								$_SESSION['holidayDate'] = $holidayRow['date'];
								$_SESSION['holidayType'] = $holidayRow['type'];
								$holidayName = $holidayRow['holiday'];
								$holidayType = $holidayRow['type'];
								Print  $holidayName . " attendance log";
						}
						else if(isset($_POST['holidaySubmit']))
						{
							
							$holidayName = $_POST['holidayName'];
							$holidayType = $_POST['holidayType'];
							
							if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
							{
								
								if($_SESSION['holidayName'] !== $holidayName)
								{
									
									$_SESSION['holidayName'] = $holidayName;
								}
								else
								{
									
									$holidayName = $_SESSION['holidayName'];
								}
								if($_SESSION['holidayType'] !== $holidayType)
								{
									
									$_SESSION['holidayType'] = $holidayType;
								}
								else
								{
									
									$holidayType = $_SESSION['holidayType'];
								}
							}
							else
							{	
								
								$_SESSION['holidayName'] = $holidayName;
								$_SESSION['holidayType'] = $holidayType;
								$_SESSION['holidayDate'] = $date;

							}
							

							Print  $holidayName . " attendance log";
						}
						else if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
						{

							if($_SESSION['holidayDate'] == $date)
							{

								$holidayName = $_SESSION['holidayName'];
								Print  $holidayName . " attendance log";
							}
							else if($day == "Sunday")
							{
								Print "Sunday attendance log";
							}
							else
							{
								Print "Daily attendance log";
							}
							
						}
						else if($day == "Sunday")
						{
							Print "Sunday attendance log";
						}
						else
						{
							Print "Daily attendance log";
						}
					?>
				</h2>
				<br>
				<div class="col-md-6 col-md-offset-3">
					<form>
					
						<input name="txt_attendance" type="text" size="10" class="form-control" value = <?php
					if(isset($_SESSION['date']))
					{
						$date = $_SESSION['date'];
						Print "'". $date ."'";
					}
					else
					{
						$date = strftime("%B %d, %Y");
						Print '""';
					}
					?> id="dtpkr_attendance" placeholder="mm-dd-yyyy" required>
					<br>
					<div id="dynamicForm">
					<?php 
						if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
						{
							if($_SESSION['holidayDate'] == $date)
							{
								if($_SESSION['holidayType'] == "regular")
								{
									Print "<h4>Regular Holiday</h4>";
								}
								else
								{
									Print "<h4>Special Holiday</h4>";
								}
								Print "	<a href='holiday_query.php?date=".$date."' class='btn btn-danger btn-sm pull-down'  id='cancel'>
										Cancel
									</a>";	
							}
							else
							{
								Print "<button id='holiday' class='btn btn-primary' onclick='Holiday(this)'>Holiday?</button>";
							}
							
						}
						else
						{
							Print "<button id='holiday' class='btn btn-primary' onclick='Holiday(this)'>Holiday?</button>";
						}
					?>	
					</div>
					
					</form>
					<form method = "post" id="holidayForm" action = "">
						<?php
							if(isset($_SESSION['holidayDate']))
							{
								if($_SESSION['holidayDate'] == $date)
								{
									if(isset($_SESSION['holidayName']))
									{	
										Print "<input type='hidden' id='holidayName' value='".$_SESSION['holidayName']."'name='holidayName'>";
									}
									else
									{
										Print "<input type='hidden' id='holidayName' name='holidayName'>";
									}
									if(isset($_SESSION['holidayType']))
									{
										Print "<input type='hidden' id='holidayType' value='".$_SESSION['holidayType']."'name='holidayType'>";
									}
									else
									{
										Print "<input type='hidden' id='holidayType' name='holidayType'>";
									}
								}
								else if(isset($_SESSION['holidayType']) && isset($_SESSION['holidayName']))
								{
									Print "<input type='hidden' id='holidayName' value='".$_SESSION['holidayName']."' name='holidayName'>";
									Print "<input type='hidden' id='holidayType' value='".$_SESSION['holidayType']."' name='holidayType'>";
								}
							}
							else
							{
								Print "<input type='hidden' id='holidayName' name='holidayName'>";
								Print "<input type='hidden' id='holidayType' name='holidayType'>";
							}

		
						?>
						<input type='hidden' name='holidaySubmit'>
					</form>
				</div>
			</div>
			<div class="col-md-4">
				<button class="btn btn-success col-md-pull-4" onclick="printAll()">
					Print attendance sheet for all sites
				</button>
				<h4><br>--- OR ---<br><br></h4>
				<!-- DROPDOWN checkbox for selected site -->
				<form method = "post" action = "print_selected_site.php">
					<div class="col-md-6">
						<select multiple="multiple" class="text-left">
							<?php
							$site = "SELECT location FROM site WHERE active = '1'";
							$site_query = mysql_query($site);
							while($row_site = mysql_fetch_assoc($site_query))
							{
								Print '<option name="selectedSite[]" value="'. $row_site['location'] .'"> '. $row_site['location'] .'</option>';
							}
							?>
						</select>
					</div>
					<div class="col-md-5">
						<input type="submit" value = "Print site" name="checkbox_submit" class="btn btn-success">
					</div>
				</form>
			</div>
		</div>
	</div>


	<div class="container">
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
					Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
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
					Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
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
<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
<script src="js/multiple-select.js"></script>
<script src="js/attendance.js"></script>

	
</body>
</html>
