<!DOCTYPE html>
<?php
// Connecting to database
session_start();
include("directives/db.php");


?>
<html>
	<head>
		<title>Payroll</title>
		<!-- Company Name: Green Built Industrial Corporation -->

		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body style='background-image: url("Images/bg.jpg");background-repeat: no-repeat;'>
		<div class="wrapper">
			<div class="panel" style="opacity: 0.8; margin-bottom: 0;">
				<div class="panel-heading">
					<img src="Images/Company Logo.png"><br>
					<h4>Please log in to continue</h4>
				</div>
			</div>
			<div class="pull-up">
				<div class="panel-body" style="background-color: #3c763d; color: white; font-family: Quicksand; ">
					<form class="horizontal" action="" method="post">
						<div class="form-group">
							<label for="username" class="control-label col-md-3" style="font-size: 20px;">Username</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="username">
							</div>
						</div><br><br>
						<div class="form-group">
							<label for="password" class="control-label col-md-3 " style="font-size: 20px;">Password</label>
							<div class="col-md-9">
								<input type="password" class="form-control" name="password">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12"><br>
								<button type="submit" class="btn btn-primary btn_loginSubmit" style="font-size: 20px; width:100px; background-color:#628686;">Log in</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);

	$sql = "SELECT * FROM administrator WHERE username = '$username' AND password = '$password'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$count = mysql_num_rows($result);

	if($count == 1)
	{
		$_SESSION['user_logged_in'] = $username;
		header("location: index.php");
	}
	else
	{
		 Print "<script>alert('Username or password is invalid.')</script>";
	}
}
?>