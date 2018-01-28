<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	if(isset($_GET['empid']) || isset($_GET['period']))
	{
		$period = $_GET['period'];
		$empid = $_GET['empid'];

		$employee = "SELECT * FROM employee WHERE empid = '$empid'";
		$empQuery = mysql_query($employee);

		//verifies the empid in the http
		if(mysql_num_rows($empQuery))
		{
			$empArr = mysql_fetch_assoc($empQuery);
		}
		else
		{
			header("location: reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null");
		}
	}
	else
	{
		header("location: reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null");
	}

	//bread crum
	$breadcrumInfo = $empArr['lastname'].", ".$empArr['firstname']." - ".$empArr['position']." at ".$empArr['site']; 

	//Print button name
	switch($period)
	{
	 	case 'week': $printButton = "Weekly";break;
	 	case 'month': $printButton = "Monthly";break;
	 	case 'year': $printButton = "Yearly";break;
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

		<div class="col-md-10 col-md-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Contributions</a></li>
						<li>Individual PagIBIG Contributions Report for <?php Print $breadcrumInfo?></li>
					</ol>
				</div>
			</div>

			<div class="form-inline">
				<h4>Select view</h4>
				<select onchange="periodChange(this.value)" class="form-control">
					<?php 
						if($period == "week")
							Print "<option value='week' selected>Weekly</option>";
						else
							Print "<option value='week'>Weekly</option>";
						if($period == "month")
							Print "<option value='month'selected>Monthly</option>";
						else
							Print "<option value='month'>Monthly</option>";
						if($period == "year")
							Print "<option value='year' selected>Yearly</option>";
						else
							Print "<option value='year'>Yearly</option>";
					?>
				</select>
				<h4>Select period</h4>
				<select class="form-control">
					<option>Sample date</option>
				</select>
			</div>

			<div class="col-md-4">
				<div class="pull-down">
				<button class="btn btn-default">
					Print Weekly
				</button>
				<table class="table table-bordered pull-down">
					<tr>
						<td colspan="4">
							[NAME], [POSITION] at [SITE] 
						</td>
					</tr>
					<tr>
						<td rowspan="2">
							Week
						</td>
						<td colspan="2">
							PagIBIG
						</td>
						<td rowspan="2">
							Total
						</td>
					</tr>
					<tr>
						<td>
							Employee
						</td>
						<td>
							Employer
						</td>
					</tr>
					<tr>
						<td>
							Dec. 1-7
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
					</tr>
					<tr>
						<td>
							
						</td>
						<td>
							
						</td>
						<td>
							Grand Total
						</td>
						<td>
							$$$
						</td>
					</tr>
				</table>
				</div>
			</div>

			<div class="col-md-4">
				<div class="pull-down">
				<button class="btn btn-default">
					Print Monthly
				</button>
				<table class="table table-bordered pull-down">
					<tr>
						<td colspan="4">
							[NAME], [POSITION] at [SITE] 
						</td>
					</tr>
					<tr>
						<td rowspan="2">
							Month
						</td>
						<td colspan="2">
							PagIBIG
						</td>
						<td rowspan="2">
							Total
						</td>
					</tr>
					<tr>
						<td>
							Employee
						</td>
						<td>
							Employer
						</td>
					</tr>
					<tr>
						<td>
							December
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
					</tr>
					<tr>
						<td>
							
						</td>
						<td>
							
						</td>
						<td>
							Grand Total
						</td>
						<td>
							$$$
						</td>
					</tr>
				</table>
				</div>
			</div>

			<div class="col-md-4">
				<div class="pull-down">
				<button class="btn btn-default">
					Print Yearly
				</button>
				<table class="table table-bordered pull-down">
					<tr>
						<td colspan="4">
							[NAME], [POSITION] at [SITE] 
						</td>
					</tr>
					<tr>
						<td rowspan="2">
							Year
						</td>
						<td colspan="2">
							PagIBIG
						</td>
						<td rowspan="2">
							Total
						</td>
					</tr>
					<tr>
						<td>
							Employee
						</td>
						<td>
							Employer
						</td>
					</tr>
					<tr>
						<td>
							2017
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
						<td>
							$$$
						</td>
					</tr>
					<tr>
						<td>
							
						</td>
						<td>
							
						</td>
						<td>
							Grand Total
						</td>
						<td>
							$$$
						</td>
					</tr>
				</table>
				</div>
			</div>
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