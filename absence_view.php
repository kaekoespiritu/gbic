<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
if(!isset($_POST['empid']))
{
	header("location:index.php");
}
else
{
	$empid = $_POST['empid'];
	Print "<script>console.log('".$empid."')</script>";
}

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$employeeQuery = mysql_query($employee);
$empRow = mysql_fetch_assoc($employeeQuery);
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
			<li class="active">AWOL pending</li>
			<button class="btn btn-danger pull-right">Terminate employee</button>
		</ol>
			<h2 class="text-left"><?php Print $empRow['lastname'].", ".$empRow['firstname']?></h2>
			<hr>

			<div class="row">
				<div class="col-md-8 text-left" style="word-break: keep-all">
					<h4><b style="font-family: QuickSandMed">Employee ID:</b><?php Print $empRow['empid']?></h4>
					<h4><b style="font-family: QuickSandMed">Date of hire:</b><?php Print $empRow['datehired']?></h4>
					<h4><b style="font-family: QuickSandMed">Address:</b><?php Print $empRow['address']?></h4>
					<h4><b style="font-family: QuickSandMed">Contact Number:</b><?php Print $empRow['contactnum']?></h4>
				</div>
				<div class="col-md-4 pull-right text-right">
					<h4>Unpaid loans:<br><br>
					SSS: AMOUNT<br>
					Pag-IBIG: AMOUNT<br>
					Vale: AMOUNT</h4>
				</div>
			</div>
			<br>

			<div class="well well-sm"><h3>Total days absent: </h3></div>

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
