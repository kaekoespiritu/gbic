<?php
//Checks and delete the loans if ever there is already a deducted loan
	include('directives/session.php');
	require_once('directives/db.php');

	$empid = $_GET['e'];
	  //1st sample date
	   //$date = "October 24, 2017";
	  //2nd sample date
	  $date = "October 31, 2017";

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);
	$empArr = mysql_fetch_assoc($empQuery);
	$position = $empArr['position'];
	$site = $empArr['site'];

	$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND date = '$date' AND action = '0'";
	$loansQuery = mysql_query($loanChecker);

	if(mysql_num_rows($loansQuery) > 0)
	{
		mysql_query("DELETE FROM loans WHERE empid = '$empid' AND date = '$date'");
	}

	Print "<script>window.location.assign('payroll.php?site=".$site."&position=".$position."&empid=".$empid."')</script>";
?>
