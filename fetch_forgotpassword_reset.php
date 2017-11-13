<?php

	include_once('directives/db.php');

	$username = $_POST['username'];
	$answer = $_POST['answer'];
	$question = "SELECT * FROM administrator WHERE username = '$username' AND answer = '$answer'";
	$questionQuery = mysql_query($question);

	if(mysql_num_rows($questionQuery) == 1)
	{
		//Generate 10 character word
		$password = chr(rand(65,90)). chr(rand(65,90)). chr(rand(65,90)). chr(rand(97,122)). chr(rand(97,122)). chr(rand(97,122)). rand(0,9). rand(0,9). rand(0,9). rand(0,9);

		$updatePassword ="UPDATE administrator SET password = '$password' WHERE username = '$username'";
		mysql_query($updatePassword);

		$output = $password;
		echo $output;
	}
	else
	{
		Print "<script>alert('Incorrect Answer')</script>";
		Print "<script>window.location.assign('login.php')</script>";
	}

	
	
?>






