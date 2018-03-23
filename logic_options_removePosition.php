<?php
	include_once('directives/db.php');
	include('directives/session.php');

	$position = mysql_real_escape_string($_POST['positionList']);

	$posArr = explode("+", $position);

	$initialQuery = "DELETE FROM job_position WHERE ";

	$secondaryQuery = "";

	$job = "";
	foreach($posArr as $pos)
	{
		if($secondaryQuery != "")
			$secondaryQuery .= " OR ";
		$secondaryQuery .= " position = '$pos' ";

		if($job != "")
			$job .= ", ";
		$job .= $pos;
	}

	$finalQuery = $initialQuery.$secondaryQuery;
	mysql_query($finalQuery) or die(mysql_error());
	Print "<script>alert('Successfully removed ".$job." from the list of positions.')</script>";
	Print "<script>window.location.assign('options.php')</script>";
	

?>























