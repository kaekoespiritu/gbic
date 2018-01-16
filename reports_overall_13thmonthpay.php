<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	//Checks if site in HTTP is altered by user manually
	$site = $_GET['site'];
	$siteChecker = "SELECT * FROM site WHERE location = '$site'";
	$siteCheckerQuery = mysql_query($siteChecker);
	if(mysql_num_rows($siteCheckerQuery) != 1)
	{
		header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
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

<<<<<<< HEAD
		<h3 class="pull-down">Overall 13th Month Pay Report for <?php Print $site?></h3>
		Filters:
		
		<select>
			<option hidden>Requirements</option>
			<option value='all'>All</option>
			<option value='withReq'>W/ Requirements</option>
			<option value='withOReq'>W/o Requirements</option>
		</select>

		<select>
			<option hidden>Position</option>
			<?php
				$position = "SELECT * FROM job_position WHERE active='1'";
				$positionQuery = mysql_query($position);
				Print "<option value='all'>All</option>";
				while($positionArr = mysql_fetch_assoc($positionQuery))
				{
					Print "<option value='".$positionArr['position']."'>".$positionArr['position']."</option>";
				}

			?>
		</select>
		<select>
			<option hidden>Period</option>
			<option value='week'>Weekly</option>
			<option value='month'>Monthly</option>
			<option value='year'>Yearly</option>
		</select>

		<?php
		//Computation for 13th month pay

		
		?>
		<div class="pull-down">
			<div class="col-md-6 col-md-offset-3">
				<button class="btn btn-default">
					Print Weekly
				</button>
				<table class="table table-bordered pull-down">
				
				<tr>
					<td>
						Employee ID
					</td>
					<td>
						Name
					</td>
					<td>
						Position
					</td>
					<td>
						Week
					</td>
					<td>
						13th Month
					</td>
				</tr>
				<tr>
					<td>
						[empid]
					</td>
					<td>
						[NAME]
					</td>
					<td>
						[position]
					</td>
					<td>
						Dec. 1 - 7
					</td>
					<td>
						$$$
					</td>
				</tr>
				<tr>
					<td colspan='3'>
					</td>
					<td>
						Total
					</td>
					<td>
						$$$
					</td>
				</tr>
				</table>
=======
		<div class="col-md-10 col-md-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_overall_earnings.php?type=Earnings&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Overall 13th Month Pay Report for [POSITION]s at [SITE]</li>
					</ol>
				</div>
			</div>

			<div class="pull-down">
				<div class="col-md-4">
					<button class="btn btn-default">
						Print Weekly
					</button>
					<table class="table table-bordered pull-down">
					<tr>
						<td colspan="3">
							[POSITION]s at [SITE]
						</td>
					</tr>
					<tr>
						<td>
							Name
						</td>
						<td>
							Week
						</td>
						<td>
							13th Month
						</td>
					</tr>
					<tr>
						<td>
							[NAME]
						</td>
						<td>
							Dec. 1 - 7
						</td>
						<td>
							$$$
						</td>
					</tr>
					<tr>
						<td>
							Total
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
					</tr>
					</table>
				</div>

				<div class="col-md-4">
					<button class="btn btn-default">
						Print Monthly
					</button>
					<table class="table table-bordered pull-down">
					<tr>
						<td colspan="3">
							[POSITION]s at [SITE]
						</td>
					</tr>
					<tr>
						<td>
							Name
						</td>
						<td>
							Month
						</td>
						<td>
							13th Month
						</td>
					</tr>
					<tr>
						<td>
							[NAME]
						</td>
						<td>
							December
						</td>
						<td>
							$$$
						</td>
					</tr>
					<tr>
						<td>
							Total
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
					</tr>
					</table>
				</div>

				<div class="col-md-4">
					<button class="btn btn-default">
						Print Yearly
					</button>
					<table class="table table-bordered pull-down">
					<tr>
						<td colspan="3">
							[POSITION]s at [SITE]
						</td>
					</tr>
					<tr>
						<td>
							[NAME]
						</td>
						<td>
							Year
						</td>
						<td>
							13th Month
						</td>
					</tr>
					<tr>
						<td>
							Name goes here
						</td>
						<td>
							2017
						</td>
						<td>
							$$$
						</td>
					</tr>
					<tr>
						<td>
							Total
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
					</tr>
					</table>
				</div>
>>>>>>> 113f319489220386f9e238ebbddce36c1eefb40c
			</div>

			
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>