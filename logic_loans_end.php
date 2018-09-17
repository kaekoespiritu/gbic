<?php
include_once("directives/db.php");
include("directives/session.php");

$id = $_GET['id'];
$type = $_GET['loan'];
$date = strftime("%B %d, %Y");
$time = strftime("%X");//TIME
$loans = "SELECT * FROM loans WHERE empid = '$id' AND type = '$type' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
$admin = $_SESSION['user_logged_in'];
$checkAdmin = "SELECT * FROM administrator WHERE username = '$admin'";
$adminQuery = mysql_query($checkAdmin);
$adminArr = mysql_fetch_assoc($adminQuery);

$employee = "SELECT * FROM employee WHERE empid = '$id'";
$empQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($empQuery);

$adminName = $adminArr['lastname'].", ".$adminArr['firstname'];
$loansQuery = mysql_query($loans);
if(mysql_num_rows($loansQuery) == 1)
{
	$loanRow = mysql_fetch_assoc($loansQuery);
	$finalBalance = $loanRow['balance'];// Gets the final overall balance

	mysql_query("INSERT INTO loans(	empid,
									type,
									monthly,
									balance,
									amount,
									date,
									time,
									action,
									admin ) VALUES(	'$id',
													'$type',
													'0',
													'$finalBalance',
													'0',
													'$date',
													'$time',
													'0',
													'$adminName')");
}
else
{
	mysql_query("INSERT INTO loans(	empid,
									type,
									monthly,
									balance,
									amount,
									date,
									time,
									action,
									admin ) VALUES(	'$id',
													'$type',
													'0',
													'0',
													'0',
													'$date',
													'$time',
													'0',
													'$adminName')");
}



Print "<script>alert('You have successfully Ended ".$type." loan of ".$empArr['lastname'].", ".$empArr['firstname'].".')</script>";

Print "<script>window.location.assign('loans_view.php?type=".$type."')</script>";
?>