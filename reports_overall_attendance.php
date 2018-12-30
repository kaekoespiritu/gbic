<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');
	include("pagination/reports_individual_function.php");//For pagination

	if(isset($_GET['type']) && isset($_GET['period']))
	{
		// Allow only these types
		if($_GET['type'] != "Attendance")
			Print "<script>window.location.assign('index.php')</script>";

		// Allow only these periods
		switch($_GET['period'])
		{
			case "Weekly": break;
			case "Monthly": break;
			case "Yearly": break;
			default: Print "<script>window.location.assign('index.php')</script>";
		}
	}
	else
	{
		Print "<script>window.location.assign('index.php')</script>";
	}
	//for pagination
	$statement = "";
	$period = $_GET['period'];
	$reportType = $_GET['type'];

	//Search bar
	$search = "";
	if(isset($_GET['search']))
	{
		if($_GET['search'] != "" || $_GET['search'] != null)
		{
			$search = $_GET['search'];
		}
	}
?>
<html>
<head>
	<title>Payroll</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">

	<!-- For pagination -->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />
</head>
<body style="font-family: Quicksand;">
	<div class="container-fluid">

		<?php
		require_once("directives/nav.php");
		?>

		<div class="container pull-down">
			<div class="col-md-1 col-lg-12 pull-down">
				<h2>Overall <span id="period"></span> <?php Print $_GET['type']; ?> Report</h2>
			</div>

			<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
			<div class="row">

					
				</div>
				<!-- ACTION BUTTONS FOR FILTERS -->
				<!-- END OF ACTION BUTTONS FOR FILTERS-->
			</div>

			<!-- Table of employees -->
			<div class="row pull-down">
				<h3>Sites</h3>

		<div class="col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
			<?php
			$attCounter = 0;//Attendance Completion Checker
			$counter = 0;//Counter for the While loop

			$site_box = "SELECT location FROM site WHERE active = '1'";
			$site_box_query = mysql_query($site_box);
			while($row = mysql_fetch_assoc($site_box_query))
			{
				$attendanceStatus = 0;
				$site = mysql_real_escape_string($row['location']);
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
				/* If location is long, font-size to smaller */
				if(strlen($row['location'])>=16)
				{
					Print '	<a href="reports_overall_empattendance.php?site='.$row['location'].'&position=null&req=null" style="color: white !important; text-decoration: none !important; cursor: pointer;">
								<div class="sitebox">
									<span class="smalltext">'
										. $row['location'] .
									'</span>
									<br>
									<span class="checkmark" name="site" value="'.$attendanceStatus.'"></span>
									<br>
									<span>Employees: '. $employee_num .'</span>
								</div>
							';
				}
				else
				{
					Print '	<a href="reports_overall_empattendance.php?site='.$row['location'].'&position=null&req=null" style="color: white !important; text-decoration: none !important; cursor: pointer;">
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
			<?php
				echo "<div id='pagingg' >";
				if($statement && $limit && $page && $site_page && $position_page && $reportType && $period)
					echo pagination($statement,$limit,$page, $site_page, $position_page, $search, $reportType, $period);
				echo "</div>";
			?>
		</div>
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function changePeriod(period, position, site, search, type) {

			window.location.assign("reports_overall.php?&type="+type+"&period="+period);
			document.getElementById('period').innerHTML = period;

		}
	</script>
</body>
</html>





















