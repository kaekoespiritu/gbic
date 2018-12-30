<?php
	include_once('directives/db.php');
	include('directives/session.php');

	$loopNum = count($_POST['empid']);
	$site = $_POST['site'];

	$bool = true;
	$initialQuery = "INSERT INTO thirteenth_pay(empid, amount, received, from_date, to_date) VALUES";
	$secondaryQuery = "";
	for($count = 0; $count < $loopNum; $count++)
	{
		if(!empty($_POST['amount'][$count]))
		{
			$bool = false;
			$empid = $_POST['empid'][$count];
			$amount = $_POST['onetri'][$count];
			$received = $_POST['amount'][$count];
			$fromDate = $_POST['startDate'][$count];
			$toDate = $_POST['endDate'][$count];

			if($secondaryQuery != "")
			{
				$secondaryQuery .= ",";
			}

			$secondaryQuery .= "('".$empid."','".$amount."','".$received."','".$fromDate."','".$toDate."')";

		}
	}

	if($bool)
	{
		Print "	<form method='post' action='reports_overall_13thmonthpay.php?site=".$site."&position=all&req=all' id='tempForm'></form>";
		Print "<script>alert('You did not enter any amount.');
						document.getElementById('tempForm').submit()</script>";
		// Print "<script>window.location.assign('reports_overall_13thmonthpay_deduction.php?site=".$site."&period=week&position=all')</script>";
	}
	$query = $initialQuery.$secondaryQuery;
	mysql_query($query);

	Print "	<form method='post' action='reports_overall_13thmonthpay.php?site=".$site."&position=all&req=all' id='tempForm1'></form>";
	Print "<script>	alert('You have successfully given the 13th Month pay for ".$site."')
					document.getElementById('tempForm1').submit()</script>";
	// Print "<script>window.location.assign('reports_overall_13thmonthpay_deduction.php?site=".$site."&period=week&position=all')</script>";
	
?>