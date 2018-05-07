<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$date = strftime("%B %d, %Y");//Current date

	if(isset($_POST['password']))
	{
		$password = mysql_real_escape_string($_POST['password']);
		$username = $_SESSION['user_logged_in'];

		$admin = "SELECT * FROM administrator WHERE username = '$username' AND password = '$password'";
		$adminQuery = mysql_query($admin);

		if(mysql_num_rows($adminQuery) != 0)
			header("location: payroll_site.php");
		else
			Print "<script>alert('You have entered a wrong password.')</script>";

	}

	//Checks if the current date is the closed payroll
	$day = date('l', strtotime($date));
	$payrollCheck = "SELECT * FROM payroll_day";
	$payrollDayQuery = mysql_query($payrollCheck) or die(mysql_error());
	$payrollArr = mysql_fetch_assoc($payrollDayQuery);

	if($payrollArr['open'] == $day)
		$head = "Enter password to access payroll";
	else
		$head = "Payroll is currently disabled and can be only accessed on ".$payrollArr['open']."(Open payroll)";
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: QuicksandBold;">
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
-->
<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<div class="jumbotron pull-down">
	<div class="row pull-down">
		<div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 pull-down text-center">
				<h2><?php Print $head?></h2>
				</div>
				<div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4 pull-down">
				<form action="" method="post">
					<?php
						//Checks if the current date is the closed payroll
						$day = date('l', strtotime($date));
						$payrollCheck = "SELECT * FROM payroll_day";
						$payrollDayQuery = mysql_query($payrollCheck) or die(mysql_error());
						$payrollArr = mysql_fetch_assoc($payrollDayQuery);

						if($payrollArr['open'] == $day)
							Print '
								<input type="password" class="form-control" id="payrollpass" name="password" placeholder="Password">
								<input type="submit" value="Submit" class="btn btn-primary pull-down" >
								';
						else
							Print '
								<input type="password" class="form-control" id="payrollpass" name="password" placeholder="Password" readonly>
								<input type="submit" value="Submit" class="btn btn-primary pull-down" disabled>
								';
					?>
					
				</form>
				</div>
			</div>
		</div>

		<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
			document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");
		</script>


	</div>
</body>
</html>

