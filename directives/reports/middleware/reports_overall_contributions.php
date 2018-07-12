<?php

if(isset($_GET['site']) || isset($_GET['period']))
{
	$period = $_GET['period'];
	$site = $_GET['site'];

	$site = "SELECT * FROM site WHERE location = '$site'";
	$siteQuery = mysql_query($site);

	//verifies the empid in the http
	if(mysql_num_rows($siteQuery))
	{
		$siteArr = mysql_fetch_assoc($siteQuery);
	}
	else
	{
		// header("location: reports_overall_contributions.php?type=Contributions&period=week&site=null&position=null");
	}
}
else
{
	// header("location: reports_overall_contributions.php?type=Contributions&period=week&site=null&position=null");
}

//bread crum
$breadcrumInfo = $siteArr['location']; 

	//Print button name
	switch($period)
	{
	 	case 'week': $printButton = "Weekly";break;
	 	case 'month': $printButton = "Monthly";break;
	 	case 'year': $printButton = "Yearly";break;
	}
?>