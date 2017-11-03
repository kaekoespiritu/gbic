<?php
include_once("directives/db.php");
include("directives/session.php");

$user = $_SESSION['user_logged_in'];
if(!empty($_POST['oldPassword']))
{
	$password = mysql_real_escape_string($_POST['oldPassword']);
	$newPassword = mysql_real_escape_string($_POST['newPassword']);
	$confirmPassword = mysql_real_escape_string($_POST['confirmPassword']);

	$pass = "SELECT * FROM administrator WHERE username = '$user' AND password = '$password'";
	$passQuery = mysql_query($pass);
	if(mysql_num_rows($passQuery) > 0)
	{
		if($confirmPassword != $newPassword)
		{
			Print "<script>alert('New password and confirm password do not match.')</script>";
			Print "<script>window.location.assign('options.php')</script>";
		}
		$passwordUpdate = "UPDATE administrator SET password = '$newPassword' WHERE username = '$user'";
		mysql_query($passwordUpdate);
	}
	else
	{
		Print "<script>alert('Wrong old-password.')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}
}
else if(!empty($_POST['newUsername']))
{
	$username = mysql_real_escape_string($_POST['newUsername']);
	$newUsername = "UPDATE administrator SET username = '$username' WHERE username = '$user'";
	mysql_query($newUsername);
}
else if(!empty($_POST['answer']))
{
	$answer = mysql_real_escape_string($_POST['answer']);
	$question = $_POST['securityQuestion'];
	$secretQuestion = "UPDATE administrator SET answer = '$answer', secret_question = '$question'  WHERE username = '$user'";
	mysql_query($secretQuestion);
}
else
{
	Print "<script>alert('You have not inputted anything.')</script>";
}
Print "<script>window.location.assign('options.php')</script>";
?>