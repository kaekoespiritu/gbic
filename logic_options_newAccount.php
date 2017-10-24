<?php
	include('directives/db.php');
	include('directives/session.php');

	$firstname = mysql_real_escape_string($_POST['n_firstname']);
	$lastname = mysql_real_escape_string($_POST['n_lastname']);
	$username = mysql_real_escape_string($_POST['n_username']);
	$password = mysql_real_escape_string($_POST['n_password']);
	$Cpassword = mysql_real_escape_string($_POST['n_confirmPassword']);
	$security = $_POST['n_security'];
	$answer = mysql_real_escape_string($_POST['n_answer']);
	$role = $_POST['n_role'][0];
	
	$firstname = ucwords($firstname);
	$lastname = ucwords($lastname);

	// Print "<script>console.log('".$firstname."')</script>";
	// Print "<script>console.log('".$lastname."')</script>";
	// Print "<script>console.log('".$username."')</script>";
	// Print "<script>console.log('".$password."')</script>";
	// Print "<script>console.log('".$Cpassword."')</script>";
	// Print "<script>console.log('".$security."')</script>";
	// Print "<script>console.log('".$answer."')</script>";
	// Print "<script>console.log('".$role."')</script>";
	//Print "<script>alert('password: ".$password." | Cpass: ".$Cpassword."')</script>";
	if($password == $Cpassword)
	{
		$checker = "SELECT * FROM administrator WHERE username = '$username'";
		$checkerQuery = mysql_query($checker);
		if(mysql_num_rows($checkerQuery) > 0)
		{
			Print "<script>alert('Username already taken, please choose another username.')</script>";
			Print "<script>window.location.assign('options.php')</script>";
		}
		$account = "INSERT INTO administrator(	firstname,
												lastname,
												role,
												username,
												password,
												secret_question,
												answer) VALUES(	'$firstname',
																'$lastname',
																'$role',
																'$username',
																'$password',
																'$security',
																'$answer')";
		mysql_query($account);
		Print "<script>alert('Successfully added ".$lastname.", ".$firstname." to ".$role." Accounts')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}
	else
	{
		Print "<script>alert('Password and confirm password do not match.')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}


?>