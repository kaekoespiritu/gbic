<!DOCTYPE html>
<?php
include('session.php');
?>

<html>
	<head>
		<title>Payroll</title>
		<!-- Company Name: Green Built Industrial Corporation -->

		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body>
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
	 -->
	 <div class="container-fluid">

<?php
	require_once("nav.php");
?>
	 	<div class="container pull-down">
	 		<table class="table table-bordered table-responsive" style="color: white; font-family: Quicksand;">
	 		<tr>
	 			<td style="background-color:#AA6F38">
	 					<h4>Today is<br></h4>
			 			<h3>
			 			<?php 
			 			date_default_timezone_set('Asia/Hong_Kong');
			 			$date = date('l\<\b\\r\>F d, Y', time());
			 			echo $date; ?>
			 			</h3>
			 	</td>
	 			<td style="background-color: #236068">
		 				<h1 class="text-center">100</h1>
		 				<h4 class="text-center">Total Employees</h4>
		 			</div>
		 		</td>
	 			<td style="background-color: #AA4038"><h3>Today's<br>Payroll Status:<br><i>Incomplete!</i></h3></td>
	 		</tr>
	 		</table>
	 	</div>

	 	<!-- SITES | Spread it out more evenly -->
			 		<div class="col-md-2 col-md-offset-1 card card-1">
			 		<h4>SITE 1</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2 card card-1">
			 		<h4>SITE 2</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2  card card-1">
			 		<h4>SITE 3</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2  card card-1">
			 		<h4>SITE 4</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2 col-md-offset-1 card card-1">
			 		<h4>SITE 5</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2  card card-1">
			 		<h4>SITE 6</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2  card card-1">
			 		<h4>SITE 7</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2 card card-1">
			 		<h4>SITE 8</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2 col-md-offset-1 card card-1">
			 		<h4>SITE 9</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2 card card-1">
			 		<h4>SITE 10</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2 card card-1">
			 		<h4>SITE 11</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>
			 		<div class="col-md-2 card card-1">
			 		<h4>SITE 12</h4><br><br>
			 		SOME WORDS KARAMBA
			 		</div>

	 </div>

	 <script>
		document.getElementById("home").setAttribute("class", "active");
	</script>

	</body>
</html>
