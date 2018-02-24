<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$site = $_GET['site'];
	$require = $_GET['req'];
	$position = $_GET['position'];
	$period = $_GET['period'];

	//Checks if site in HTTP is altered by user manually
	$siteChecker = "SELECT * FROM site WHERE location = '$site'";
	//Checks if position in HTTP is altered by user manually 
	$positionChecker = "SELECT * FROM job_position WHERE position = '$position'";

	$siteCheckerQuery = mysql_query($siteChecker);
	$positionCheckerQuery = mysql_query($positionChecker);
	if(mysql_num_rows($siteCheckerQuery) == 0)
	{
		header("location:reports_company_expenses.php?type=Expenses&period=Weekly");
	}
	if($position != 'all')
	{
		if(mysql_num_rows($positionCheckerQuery) == 0)
		{
			header("location:reports_company_expenses.php?type=Expenses&period=Weekly");
		}
	}
		
	
	// Checks if requirement in HTTP is altered by user manually 
	switch($require) {
		case "null":break;
		case "all":break;
		case "withReq":break;
		case "withOReq":break;
		default: header("location:reports_company_expenses.php?type=Expenses&period=Weekly");;
	}
	//Checks if period in HTTP is altered by user manually 
	switch($period) {
		case "week": $periodDisplay = "Weekly";break;
		case "month": $periodDisplay = "Monthly";break;
		case "year": $periodDisplay = "Yearly";break;
		default: header("location:reports_company_expenses.php?type=Expenses&period=Weekly");;
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

		<!-- 
			Add the following:
			Filter selection if necessary
			Table for expenses
		-->

		<div class="col-md-10 col-md-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_company_expenses.php?type=Expenses&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Expenses</a></li>
						<li>Overall Company Expense Report for <?php Print $site?></li>
					</ol>
				</div>
			</div>
			<div class="form-inline">
				<h4>Select Period</h4>
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
				<h4>Select <?php Print $period?></h4>
					<select class="form-control" onchange="changeDate(this.value)">
						<option hidden>Choose a <?php Print $period?></option>
					<?php
						

							
							$payrollDates = "SELECT DISTINCT date FROM payroll";
							$payrollDQuery = mysql_query($payrollDates) or die(mysql_error());
							
							if(mysql_num_rows($payrollDQuery) > 0)//check if there's payroll
							{
								$monthNoRep = "";
								$yearNoRep = "";
								while($payrollDateArr = mysql_fetch_assoc($payrollDQuery))
								{
									
									if($_GET['period'] == 'week')
									{
										$payrollEndDate = $payrollDateArr['date'];
										$payrollStartDate = date('F j, Y', strtotime('-6 day', strtotime($payrollEndDate)));
										if(isset($_POST['date']))
										{
											if($_POST['date'] == $payrollEndDate)
											{
												Print "<option value = '".$payrollEndDate."' selected>".$payrollStartDate." - ".$payrollEndDate."</option>";
											}
											else
											{
												Print "<option value = '".$payrollEndDate."'>".$payrollStartDate." - ".$payrollEndDate."</option>";
											}
										}
										else
										{
											Print "<option value = '".$payrollEndDate."'>".$payrollStartDate." - ".$payrollEndDate."</option>";
										}
									}
									else if($_GET['period'] == 'month')
									{
										$payrollArrDate = explode(" ", $payrollDateArr['date']);
										$payrollMonth = $payrollArrDate[0];
										$payrollYear = $payrollArrDate[2];

										if($monthNoRep != $payrollMonth." ".$payrollYear)
										{	
											if(isset($_POST['date']))
											{
												if($_POST['date'] == $payrollMonth." ".$payrollYear)
												{
													Print "<option value = '".$payrollMonth." ".$payrollYear."' selected>".$payrollMonth." ".$payrollYear."</option>";
												}
												else
												{
													Print "<option value = '".$payrollMonth." ".$payrollYear."'>".$payrollMonth." ".$payrollYear."</option>";
												}
											}
											else
											{
												Print "<option value = '".$payrollMonth." ".$payrollYear."'>".$payrollMonth." ".$payrollYear."</option>";
											}
										}
										$monthNoRep = $payrollMonth." ".$payrollYear;
									}
									else if($_GET['period'] == 'year')
									{
										$payrollArrDate = explode(" ", $payrollDateArr['date']);
										$payrollYear = $payrollArrDate[2];
										$yearBef = $payrollYear -1;//gets the year before

										if($yearNoRep != $payrollYear)
										{	
											if(isset($_POST['date']))
											{
												if($_POST['date'] == $payrollYear)
												{
													Print "<option value = '".$payrollYear."' selected>".$yearBef." - ".$payrollYear."</option>";
												}
												else
												{
													Print "<option value = '".$payrollYear."'>".$yearBef." - ".$payrollYear."</option>";
												}
											}
											else
											{
												Print "<option value = '".$payrollYear."'>".$yearBef." - ".$payrollYear."</option>";
											}
											
										}
										$yearNoRep = $payrollYear;
									}
									
								}
							}
					
					?>
				</select>
				<h5>Filter:</h5>
				<h4>Position</h4>
				<select onchange="periodChange(this.value)" class="form-control">
					<option hidden>position</option>
					<?php 
						$position_dd = "SELECT * FROM job_position WHERE active = '1'";
						$posQuery = mysql_query($position_dd);

						while($posArr = mysql_fetch_assoc($posQuery))
						{
							if($position == $posArr['position'])
								Print "<option value = '".$posArr['position']."' selected>".$posArr['position']."</option>";
							else
								Print "<option value = '".$posArr['position']."'>".$posArr['position']."</option>";
						}
					?>
				</select>
			</div>
		</div>

		<button class="btn btn-default pull-down">
					Print <?php Print $periodDisplay?> Expense Report
		</button>

		<table class='table table-bordered pull-down'>
			<tr>
				<td colspan='13'>
					Weekly Expenses 
				</td>
			</tr>
			<tr>
				<td rowspan='3'>
					Name
				</td>
				<td rowspan='3'>
					Position
				</td>
				<td rowspan='3'>
					Site
				</td>
				<td rowspan='3'>
					Salary
				</td>
				<td colspan='6'>
					Contributions
				</td>
				<td colspan='2' rowspan='3'>
					Vale
				</td>
				<td rowspan='3'>
					Total
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					SSS
				</td>
				<td colspan='2'>
					Pag-ibig
				</td>
				<td colspan='2'>
					PhilHealth
				</td>
			</tr>
			<tr>
				<td>
					Employee
				</td>
				<td>
					Employer
				</td>
				<td>
					Employee
				</td>
				<td>
					Employer
				</td>
				<td>
					Employee
				</td>
				<td>
					Employer
				</td>
			</tr>
			<tr>
				<td>
					[NAME]
				</td>
				<td>
					[POSITION]
				</td>
				<td>
					[SITE]
				</td>
				<td>
					[SALARY]
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
				<td>
					$$$
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
				<td>
					$$$
				</td>
				<td>
					$$$
				</td>
			</tr>
		</table>

	</div>


	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function periodChange(period) {
			window.location.assign("reports_overall_13thmonthpay.php?req=<?php Print $require?>&site=<?php Print $site?>&period="+period+"&position=<?php Print $position?>");
		}

		function requirementChange(req) {
			window.location.assign("reports_overall_13thmonthpay.php?req="+req+"&site=<?php Print $site?>&period=<?php Print $period?>&position=<?php Print $position?>");
		}

		function positionChange(position) {
			window.location.assign("reports_overall_13thmonthpay.php?req=<?php Print $require?>&site=<?php Print $site?>&period=<?php Print $period?>&position="+position);
		}
		function weekDates(date) {
			document.getElementsByName('chosenDate')[0].value = date;
			document.getElementById('dynamicForm').submit();
		}

	</script>
</body>
</html>