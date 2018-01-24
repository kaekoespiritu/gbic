<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$site = $_GET['site'];
	$require = $_GET['req'];
	$position = $_GET['position'];
	$period = $_GET['period'];

	// //Checks if site in HTTP is altered by user manually
	// $siteChecker = "SELECT * FROM site WHERE location = '$site'";
	// //Checks if position in HTTP is altered by user manually 
	// $positionChecker = "SELECT * FROM job_position WHERE position = '$position'";
	// $siteCheckerQuery = mysql_query($siteChecker);
	// $positionCheckerQuery = mysql_query($positionChecker);
	// if(mysql_num_rows($siteCheckerQuery) == 0)
	// {
	// 	header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
	// }
	// if($position != 'all')
	// {
	// 	if(mysql_num_rows($positionCheckerQuery) == 0)
	// 	{
	// 		header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
	// 	}
	// }
		
	
	// // Checks if requirement in HTTP is altered by user manually 
	// switch($require) {
	// 	case "null":break;
	// 	case "all":break;
	// 	case "withReq":break;
	// 	case "withOReq":break;
	// 	default: header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");;
	// }
	// //Checks if period in HTTP is altered by user manually 
	// switch($period) {
	// 	case "null":break;
	// 	case "week":break;
	// 	case "month":break;
	// 	case "year":break;
	// 	default: header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");;
	// }




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
						<li>Overall Company Expense Report for [PERIOD]</li>
					</ol>
				</div>
			</div>
		</div>

		<button class="btn btn-default">
					Print Weekly Expense Report
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