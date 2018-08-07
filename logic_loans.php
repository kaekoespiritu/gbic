<?php
include_once('directives/db.php');
include('directives/session.php');
include('directives/admin_historical.php');
date_default_timezone_set('Asia/Hong_Kong');

$date = strftime("%B %d, %Y");// Gets the current date

$empid = $_POST['empid'];
$loanType = $_POST['loanType'];
$loanAmount = $_POST['loanAmount'];
$reason = mysql_real_escape_string($_POST['reason']);
$position = $_POST['position'];
$site = $_POST['site'];
$rate = $_POST['rate'];
$time = strftime("%X");//TIME

//Check if they already have balance for that type of loan
$loanCheck = "SELECT * FROM loans WHERE empid = '$empid' AND type='$loanType' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC  LIMIT 1";
$checkQuery = mysql_query($loanCheck);
$balance = 0;
if(mysql_num_rows($checkQuery) > 0)
{
	$checkBalance = mysql_fetch_assoc($checkQuery);
	$balance = $checkBalance['balance'] + $loanAmount; 
}
else
{
	$checkBalance = mysql_fetch_assoc($checkQuery);
	$balance = $loanAmount; 
}

// Must add a way here to save multiple loans for a single person depending on the number of entries they filled up

$loanAmount = number_format($loanAmount, 2, '.', '');//for 2 decimal places
$query = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time,action, admin) VALUES('$empid', 
																'$loanType',
																'$balance',
																'$loanAmount',
																'$reason',
																'$date',
																'$time',
																'1',
																'$adminName')";
mysql_query($query);

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$empQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($empQuery);
Print "<script>alert('You have successfully processed ".$empArr['lastname'].", ".$empArr['firstname']." ".$loanType." loan')</script>";
Print "<script>window.location.assign('loans_landing.php')</script>";



?>
















