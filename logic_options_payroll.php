<?php
	include('directives/session.php');
	include_once('directives/db.php');

	if(isset($_POST['payrolldaySubmit']))
	{
		if(($_POST['openPayroll'] == null) && ($_POST['closePayroll'] == null))
		{
			header("location: options.php");
		}

		$openPayroll = $_POST['openPayroll'];
		$closePayroll = $_POST['closePayroll'];

		Print "<script>alert('open:".$openPayroll."|close:".$closePayroll."')</script>";
		$query = "UPDATE payroll_day SET open = '$openPayroll', close = '$closePayroll'";
		mysql_query($query);
		Print "	<script>
					alert('Payroll day Successfully changed');
					window.location.assign('options.php');
				</script>";
	}
?>