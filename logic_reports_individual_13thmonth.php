<?php
	include_once('directives/db.php');
	include('directives/session.php');
	Print "<script>alert('yow')</script>";
	$empid = $_GET['empid'];
	$amount = $_GET['amount'];
	$pay = $_GET['pay'];

	$fromDate = $_GET['fromDate'];
	$toDate = strftime("%B %d, %Y");

	$query = "INSERT INTO thirteenth_pay(	empid, 
											amount, 
											received, 
											from_date, 
											to_date) VALUES(	'$empid',
																'$pay',
																'$amount',
																'$fromDate',
																'$toDate')";
	mysql_query($query);

	Print "<script>window.location.assign('reports_individual_13thmonthpay.php?empid=".$empid."&per=week')</script>";
?>