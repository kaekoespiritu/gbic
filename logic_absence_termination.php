<?php
	include('directives/session.php');
	include('directives/db.php');

	$empid = $_POST['empid'];

	$terminate = "UPDATE employee SET employment_status = '0' WHERE empid = '$empid'";
	mysql_query($terminate);

	$terminate = "DELETE FROM awol_employees WHERE empid = '$empid'";
	mysql_query($terminate);

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);
	$empRow = mysql_fetch_assoc($empQuery);
	Print "	<script>
					alert('".$empRow['lastname'].", ".$empRow['lastname']." Terminated.');
					window.location.assign('applications.php?site=null&position=null&status=null');
				</script>";
?>