<!DOCTYPE html>
<?php
include('directives/session.php');
if(isset($_SESSION['empid']))
{
	$empid = $_SESSION['empid'];
}
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: QuicksandMed;">
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
-->
<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<div class="row pull-down">
		<div class="col-md-10 col-md-offset-1">
		<ol class="breadcrumb text-left">
			<li><a href="applications.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Absence Notifications</a></li>
			<li class="active">Check details for [EMPLOYEE NAME]</li>
			<button class="btn btn-danger pull-right">Terminate employee</button>
		</ol>
			<h2 class="text-left">Miguelito Joselito Dela Cruz</h2>
			<hr>

			<div class="row">
				<div class="col-md-8 text-left" style="word-break: keep-all">
					<h4><b style="font-family: QuickSandMed">Employee ID:</b> 2014-1352845</h4>
					<h4><b style="font-family: QuickSandMed">Date of hire:</b> July 14, 2014 </h4>
					<h4><b style="font-family: QuickSandMed">Address:</b> 97 Waco St. Greenheights Village, Quezon City</h4>
					<h4><b style="font-family: QuickSandMed">Contact Number:</b> 09123456789</h4>
				</div>
				<div class="col-md-4 pull-right text-right">
					<h4>Unpaid loans:<br><br>
					SSS: AMOUNT<br>
					Pag-IBIG: AMOUNT<br>
					Vale: AMOUNT</h4>
				</div>
			</div>
			<br>

			<div class="well well-sm"><h3>Total days absent: 3</h3></div>

		</div>	
	</div>
</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
</script>


</div>
</body>
</html>
