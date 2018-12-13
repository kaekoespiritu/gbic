<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$date = strftime("%B %d, %Y");//Current date
	// $date = "July 12, 2018";//Current date
// $date = "July 11, 2018";

	
	//Checks if the current date is the closed payroll
	$day = date('l', strtotime($date));
	$payrollCheck = "SELECT * FROM payroll_day";
	$payrollDayQuery = mysql_query($payrollCheck) or die(mysql_error());
	$payrollArr = mysql_fetch_assoc($payrollDayQuery);

	$payrollBool = false;// Boolean for unfinished payroll for the week
	//Check if they did not do payroll for the week
	$day1 = date('F d, Y', strtotime('-1 day', strtotime($date)));
	$day2 = date('F d, Y', strtotime('-2 day', strtotime($date)));
	$day3 = date('F d, Y', strtotime('-3 day', strtotime($date)));
	$day4 = date('F d, Y', strtotime('-4 day', strtotime($date)));
	$day5 = date('F d, Y', strtotime('-5 day', strtotime($date)));
	$day6 = date('F d, Y', strtotime('-6 day', strtotime($date)));

	$checkDays = array($day1, $day2, $day3, $day4, $day5, $day6);

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
	if(isset($_POST['password']))
	{
		if($payrollBool)//Pass Session variable to modify all the date involving the payroll
			$_SESSION['payrollDate'] = $unfinishedPayrollDate;
		else
		{
			if(isset($_SESSION['payrollDate']))
				unset($_SESSION['payrollDate']);
		}
		

		$password = mysql_real_escape_string($_POST['password']);
		$username = $_SESSION['user_logged_in'];

		$admin = "SELECT * FROM administrator WHERE username = '$username' AND password = '$password'";
		$adminQuery = mysql_query($admin);

		if(mysql_num_rows($adminQuery) != 0)
			header("location: payroll_site.php");
		else
			Print "<script>alert('You have entered a wrong password.')</script>";

	}


	
	if($payrollBool)
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
				<div class="modal-body">
					<div class="row">
					<form class="form-inline">
						<div class="form-group col-md-12">
							<span>Password: </span>
							<input type="password" class="form-control" id="payrollpass" name="password" placeholder="Password">
						</div>
					</form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-primary" data-dismiss="modal" value="Submit">
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="row pull-down">
		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<ol class="breadcrumb text-left">
				<li>
					<h5>Payroll</h5>
				</li>
				<a type="button" class="pull-right btn btn-primary" data-target='#earlyCutOff' data-toggle='modal'>Early cut-off</a>
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

