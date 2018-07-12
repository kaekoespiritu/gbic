<?php

	include_once('directives/db.php');

	$username = $_POST['username'];
	$user = "SELECT * FROM administrator WHERE username = '$username'";
	$userQuery = mysql_query($user);

	if(mysql_num_rows($userQuery) > 0)
	{
		$output = "has-warning";
	}
	else
	{
		$output = "has-success";
	}

	echo $output;
	
?>