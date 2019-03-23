<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$date = strftime("%B %d, %Y");//Current date
	$date = "March 21, 2019";//Current date
	// $date = "December 27, 2018";//Current date
// $date = "July 11, 2018";
	echo "<script>console.log('".$date."')</script>";

	
	//Checks if the current date is the closed payroll
	$day = date('l', strtotime($date));
	$payrollCheck = "SELECT * FROM payroll_day";
	$payrollDayQuery = mysql_query($payrollCheck) or die(mysql_error());
	$payrollArr = mysql_fetch_assoc($payrollDayQuery);

	//Check if there's an early cutoff
	$cutoffCheck = "SELECT * FROM early_payroll ORDER BY id DESC LIMIT 1";
	$cutoffCheckQuery = mysql_query($cutoffCheck) or die(mysql_error());
	$latestCutoff = '';
	$cutoffBool = false;// Boolean for cutoff in login

	if(mysql_num_rows($cutoffCheckQuery))
	{
		$cutoffArr = mysql_fetch_assoc($cutoffCheckQuery);
		$latestCutoffStart = $cutoffArr['start'];
		$latestCutoff = $cutoffArr['end'];
		$latestCutoffDay = date('l', strtotime($latestCutoff));


		// Check for the unfinished early cutoff
		$undifinishedCutoffChecker = date('F d, Y', strtotime('+13 day', strtotime($latestCutoffStart)));
		$startChecker = strtotime($latestCutoff);
		$endChecker = strtotime($undifinishedCutoffChecker);// This is the Payroll weeks after the initial early payroll

		echo "<script>console.log('$latestCutoffStart')</script>";
		echo "<script>console.log('$undifinishedCutoffChecker')</script>";

		if(strtotime($date) >= $startChecker && strtotime($date) <= $endChecker)// If the current date is inbetween the start checker and the end checker
		{
			$cutoffBool = true;
		}
		else
		{
			echo "<script>console.log('no')</script>";
		}
		
	}

	$payrollBool = false;// Boolean for unfinished payroll for the week
	//Check if they did not do payroll for the week
	$day1 = date('F d, Y', strtotime('-1 day', strtotime($date)));
	$day2 = date('F d, Y', strtotime('-2 day', strtotime($date)));
	$day3 = date('F d, Y', strtotime('-3 day', strtotime($date)));
	$day4 = date('F d, Y', strtotime('-4 day', strtotime($date)));
	$day5 = date('F d, Y', strtotime('-5 day', strtotime($date)));
	$day6 = date('F d, Y', strtotime('-6 day', strtotime($date)));

	$checkDays = array($day1, $day2, $day3, $day4, $day5, $day6);
	$checkDaysCutoff = array($date, $day1, $day2, $day3, $day4, $day5, $day6);

	if($latestCutoff != '')
	{
		if(in_array($latestCutoff, $checkDaysCutoff))
		{
			$cutoffBool = true;
		}
	}

	foreach($checkDays as $days)
	{
		$dayName = date('l', strtotime($days));

		if($payrollArr['open'] == $dayName)
		{
			$payCheck = "SELECT * FROM payroll WHERE date = '$days' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC";
			$payCheckQuery = mysql_query($payCheck);
			$employee = "SELECT * FROM employee WHERE employment_status = '1'";
			$empQuery = mysql_query($employee);
			$unfinishedPayrollDate = $days;// Get the specific day
			if(mysql_num_rows($empQuery) != mysql_num_rows($payCheckQuery))// If they didn't finish the payroll for the week
			{
				$payrollBool = true;// Unaccomplished payroll
			}
		}
	}

	if(isset($_POST['password']) || isset($_POST['early']))
	{
		if($payrollBool)//Pass Session variable to modify all the date involving the payroll
		{
			$_SESSION['payrollDate'] = $unfinishedPayrollDate;
		}
		else
		{
			if(isset($_SESSION['payrollDate']))
				unset($_SESSION['payrollDate']);
		}
		

		if(isset($_POST['early']))
			$password = mysql_real_escape_string($_POST['earlyPayrollpass']);
		else
			$password = mysql_real_escape_string($_POST['password']);
				
		$username = $_SESSION['user_logged_in'];

		$admin = "SELECT * FROM administrator WHERE username = '$username' AND password = '$password'";
		$adminQuery = mysql_query($admin);

		if(mysql_num_rows($adminQuery) != 0)
		{
			// if(isset($_POST['early']))// Check if they chose early payroll
			// {
				if($payrollBool || $cutoffBool)//Pass Session variable to modify all the date involving the payroll
				{
					if(!$cutoffBool && isset($_POST['early']))// Check if cutoff is already in the database
					{
						$earlyStartDate = $unfinishedPayrollDate;// Open payroll
						$earlyEndDate = $date;// date today
						$insertCutoff = "INSERT INTO early_payroll(start, end) VALUES('$earlyStartDate', '$earlyEndDate')";
						$_SESSION['payrollDate'] = $date;// Cutoff
						$_SESSION['earlyCutoff'] = $earlyStartDate;
						$cutoffQuery = mysql_query($insertCutoff) OR DIE (mysql_error());
						// echo "<script>alert('1')</script>";
					}	
					else if($cutoffBool)
					{
						$_SESSION['payrollDate'] = $latestCutoff;
						$_SESSION['earlyCutoff'] = $latestCutoffStart;
						// echo "<script>alert('2')</script>";
					}
					else// Unset earlycutoff session variable
					{
						// echo "<script>alert('3')</script>";
						if(isset($_SESSION['earlyCutoff']))
							unset($_SESSION['earlyCutoff']);
					}
					
				}
				else
				{
					if(isset($_SESSION['earlyCutoff']))
							unset($_SESSION['earlyCutoff']);
				}
			// }
			// echo "<script>alert('4')</script>";
			Print "<script>window.location.assign('payroll_site.php')</script>";
		}
		else
			Print "<script>alert('You have entered a wrong password.')</script>";

	}

	if($cutoffBool)
		$head = "You haven't finished the early payroll cutoff, to access the early payroll cutoff from ".$payrollArr['open']." to ".$latestCutoffDay." please enter your password.";
	else if($payrollBool)
		$head = "You haven't finished the payroll for this week, to access the payroll for last ".$payrollArr['open']." please enter your password.";
	else if($payrollArr['open'] == $day)
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

	<!-- Modal -->
	<div class="modal fade" tabindex="-1" id="earlyCutOff" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Please enter your password to access early cut-off for payroll.</h4>
				</div>
				<form class="form-inline" action="" method="post">
					<div class="modal-body">
						<div class="row">
						
							<div class="form-group col-md-12">
								<span>Password: </span>
								<input type="password" class="form-control" id="earlyPayrollpass" name="earlyPayrollpass" placeholder="Password">
								<input type="hidden" name="early" value="1">
							</div>
						
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<input type="submit" class="btn btn-primary" value="Submit">
					</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="row pull-down">
		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<ol class="breadcrumb text-left">
				<li>
					<h5>Payroll</h5>
				</li>
				<?php
				if($payrollArr['open'] != $day)
				{
					Print '
					<a type="button" class="pull-right btn btn-primary '.($cutoffBool ? "disabletotally":"").'" data-target="#earlyCutOff" data-toggle="modal">Early cut-off</a>';
				}
					
				?>
			</ol>
		</div>
		<div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 pull-down text-center">
			<h2><?php Print $head?></h2>
		</div>
		<div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4 pull-down">
			<form action="" method="post">
				<?php
					//Checks if the current date is the closed payroll
					// $day = date('l', strtotime($date));
					// $payrollCheck = "SELECT * FROM payroll_day";
					// $payrollDayQuery = mysql_query($payrollCheck) or die(mysql_error());
					// $payrollArr = mysql_fetch_assoc($payrollDayQuery);

					
					if($payrollArr['open'] == $day || $payrollBool)
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

		<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
			document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");
		</script>


	</div>
</body>
</html>

