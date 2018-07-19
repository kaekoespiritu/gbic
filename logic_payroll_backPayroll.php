<?php
//Checks and delete the loans if ever there is already a deducted loan
	include('directives/session.php');
	require_once('directives/db.php');

	$empid = $_GET['e'];
	// $date = (isset($_SESSION['payrollDate']) ? $_SESSION['payrollDate'] : strftime("%B %d, %Y")); // Gets the payroll date if admin didn't finish the payroll for the week
	$date = "July 11, 2018";
	// $date = "May 9, 2018";

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
