<?php
	include('directives/session.php');
	include('directives/db.php');

	if(isset($_POST['payrolldaySubmit']))
	{
		if(($_POST['openPayroll'] == null) && ($_POST['closePayroll'] == null))
		{
			header("location: options.php");
		}

		$openPayroll = $_POST['openPayroll'];
		$closePayroll = $_POST['closePayroll'];

		$query = "INSERT payroll_day(open, close) VALUES('$openPayroll','$closePayroll')";
		$dayPayrollQuery = mysql_query($query);
		Print "	<script>
					alert('Payroll day Successfully changed');
					window.location.assign('options.php');
				</script>";
	}
?>