<?php
include_once('directives/db.php');
include('directives/session.php');
include('directives/admin_historical.php');
date_default_timezone_set('Asia/Hong_Kong');

$date = $_POST['loandate'];// Gets the current date
// $date = strftime('September 15, 2018');// Gets the current date

$empid = $_POST['empid'];

if(count($_POST['loanType']) == 1)
{
	if(isset($_POST['inOrOut']))
		$loanType = $_POST['loanType'];
	else
		$loanType = $_POST['loanType'][0];
	$loanAmount = $_POST['loanAmount'][0];
	$reason = mysql_real_escape_string($_POST['reason'][0]);
	$time = strftime("%X");//TIME

	//Check if they already have balance for that type of loan
	$loanCheck = "SELECT * FROM loans WHERE empid = '$empid' AND type='$loanType' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
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


	$loanAmount = numberExactFormat($loanAmount, 2, '.', false);//for 2 decimal places
	if($loanType == 'SSS' || $loanType == 'PagIBIG')
	{
		$query = "INSERT INTO loans(empid, type, monthly, balance, amount, remarks, date, time,action, admin) VALUES('$empid', 
							'$loanType',
							'$loanAmount',
							'0',
							'0',
							'$reason',
							'$date',
							'$time',
							'1',
							'$adminName')";
	}
	else
	{
		$query = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time,action, admin) VALUES('$empid', 
																		'$loanType',
																		'$balance',
																		'$loanAmount',
																		'$reason',
																		'$date',
																		'$time',
																		'1',
																		'$adminName')";
	}
		
	mysql_query($query);

	$loanDisplay = $loanType;
}
else
{
	$initialQuery = "INSERT INTO loans(empid, type, monthly, balance, amount, remarks, date, time, action, admin) VALUES";
	$secondaryQuery = "";
	$loanDisplay = "";
	$loanNum = count($_POST['loanType']);// Number of loan
	for($counter = 0; $counter < $loanNum; $counter++)
	{
		$loanType = $_POST['loanType'][$counter];
		$loanAmount = $_POST['loanAmount'][$counter];
		$reason = mysql_real_escape_string($_POST['reason'][$counter]);
		$time = strftime("%X");//TIME

		//Check if they already have balance for that type of loan
		$loanCheck = "SELECT * FROM loans WHERE empid = '$empid' AND type='$loanType' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC  LIMIT 1";
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

		//Query Builder
		if($secondaryQuery != "")
			$secondaryQuery .= ",";

		if($loanType == 'SSS' || $loanType == 'PagIBIG')
			$secondaryQuery .= "('$empid', '$loanType', '$loanAmount', '0', '0', '$reason', '$date', '$time', '1', '$adminName')";
		else
			$secondaryQuery .= "('$empid', '$loanType', '', '$balance', '$loanAmount', '$reason', '$date', '$time', '1', '$adminName')";

		$primaryQuery = $initialQuery.$secondaryQuery;
		
		// Loan type display for alert 
		if($secondaryQuery != "")
			$loanDisplay .= ", ";
		$loanDisplay .= $loanType;
	}

	mysql_query($primaryQuery);
}	

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$empQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($empQuery);
Print "<script>alert('You have successfully processed ".$empArr['lastname'].", ".$empArr['firstname']." ".$loanDisplay." loan')</script>";
if(isset($_POST['loanShortcut']))
{
	$loanTypeRedirect = $_POST['loanShortcut'];
	Print "<script>window.location.assign('loans_view.php?type=$loanTypeRedirect')</script>";
}
else
{
	Print "<script>window.location.assign('loans_landing.php')</script>";
}


?>
















