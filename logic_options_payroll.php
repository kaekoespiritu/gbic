<?php
	include('directives/session.php');
	include_once('directives/db.php');

	if(($_POST['openPay'] == null) && ($_POST['closePay'] == null))
	{
		header("location: options.php");
	}
	$user = $_SESSION['user_logged_in'];
	$password = mysql_real_escape_string($_POST['adminPassword']);
	
	$adminCheck = "SELECT * FROM administrator WHERE username = '$user'";
	$adminQuery = mysql_query($adminCheck);

	if(mysql_num_rows($adminQuery) != 0)
	{
		$openPayroll = $_POST['openPay'];
		$closePayroll = $_POST['closePay'];

		$query = "UPDATE payroll_day SET open = '$openPayroll', close = '$closePayroll'";
		mysql_query($query);
		Print "	<script>
					alert('Payroll day Successfully changed');
					window.location.assign('options.php');
				</script>";
	}
	else
	{
		Print "	<script>
					alert('You have entered a wrong password.');
					window.location.assign('options.php');
				</script>";
	}

	
	
?>