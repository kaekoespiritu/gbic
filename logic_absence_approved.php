<?php
	include('directives/session.php');
	include_once('directives/db.php');

	$empid = $_GET['empid'];
	// 0 - Terminated
	// 1 - Employeed
	// 2 - AWOL Pending
	$terminate = "UPDATE employee SET employment_status = '1' WHERE empid = '$empid'";
	mysql_query($terminate);

	$terminate = "DELETE FROM awol_employees WHERE empid = '$empid'";
	mysql_query($terminate);

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);
	$empRow = mysql_fetch_assoc($empQuery);
	Print "	<script>
					alert('".$empRow['lastname'].", ".$empRow['lastname']."\'s AWOL has been Approved.');
					window.location.assign('applications.php?site=null&position=null&status=null');
				</script>";
?>