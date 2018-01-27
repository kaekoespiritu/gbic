<?php

if(isset($_GET['empid']) || isset($_GET['period']))
{
	$period = $_GET['period'];
	$empid = $_GET['empid'];

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);

	//verifies the empid in the http
	if(mysql_num_rows($empQuery))
	{
		$empArr = mysql_fetch_assoc($empQuery);
	}
	else
	{
		header("location: reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null");
	}
}
else
{
	header("location: reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null");
}

//bread crum
$breadcrumInfo = $empArr['lastname'].", ".$empArr['firstname']." - ".$empArr['position']." at ".$empArr['site']; 

	//Print button name
	switch($period)
	{
	 	case 'week': $printButton = "Weekly";break;
	 	case 'month': $printButton = "Monthly";break;
	 	case 'year': $printButton = "Yearly";break;
	}
?>