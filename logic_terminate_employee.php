<?php
	include('directives/session.php');
	include_once('directives/db.php');

	$empid = $_GET['empid'];

	$employee = "UPDATE employee SET employment_status = '0' WHERE empid = '$empid'";
	$empQuery = mysql_query($employee) or die (mysql_error());

	Print "<script>window.location.assign('employees.php')</script>";
?>