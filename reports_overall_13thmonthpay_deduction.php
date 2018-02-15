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
						<li><a href="reports_overall_13thmonthpay.php?position=all&period=week&req=null&site=<?php Print $site ?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Back to table</a></li>
						<li>Overall 13th Month Pay Report for <?php Print $site?></li>
						<button class="btn btn-primary pull-right">
							Give 13th Month Pay
						</button>
					</ol>
				</div>
			</div>

			<table class="table table-bordered pull-down">
				<tr>
					<td>
						Name
					</td>
					<td>
						Position
					</td>
					<td>
						From - To Date
					</td>
					<td>
						13th Month Pay Amount
					</td>
					<td>
						Amount to give
					</td>
					<td>
						Copy full amount
					</td>
				</tr>
				<tr>
					<td>
						[Name]
					</td>
					<td>
						[Position]
					</td>
					<td>
						[Date] - [Date]
					</td>
					<td>
						[Amount]
					</td>
					<td>
						<input type="text">
					</td>
					<td>
						<input type="checkbox">
					</td>
				</tr>
			</table>

		</div>

			
	</div>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
</body>
</html>