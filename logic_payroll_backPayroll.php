<?php
	include('directives/session.php');
	require_once('directives/db.php');

	$empid = $_GET['e'];
	$date = "October 24, 2017";

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);
	$empArr = mysql_fetch_assoc($empQuery);
	$position = $empArr['position'];

	$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND date = '$date' AND action = '0'";
	$loansQuery = mysql_query($loanChecker);

	if(mysql_num_rows($loansQuery) > 0)
	{
		mysql_query("DELETE FROM loans WHERE empid = '$empid' AND date = '$date' AND action = '0'");
	}

	Print "<script>window.location.assign('payroll.php?site=".$empid."&position=".$position."&empid=".$empid."')</script>";
?>
