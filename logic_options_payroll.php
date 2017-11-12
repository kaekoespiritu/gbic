<?php
	include('directives/session.php');
	include_once('directives/db.php');

	if(($_POST['openPayroll'] == null) && ($_POST['closePayroll'] == null))
	{
		header("location: options.php");
	}

	$openPayroll = $_POST['openPayroll'];
	$closePayroll = $_POST['closePayroll'];

	$query = "UPDATE payroll_day SET open = '$openPayroll', close = '$closePayroll'";
	mysql_query($query);
	Print "	<script>
				alert('Payroll day Successfully changed');
				window.location.assign('options.php');
			</script>";
	
?>