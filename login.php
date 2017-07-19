<!DOCTYPE html>
<?php

// Connecting to database
Include("config.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$adminusername = mysqli_real_escape_string($db, $_POST['username']);
	$adminpassword = mysqli_real_escape_string($db, $_POST['password']);

	$sql = "SELECT * FROM admin WHERE username = '$adminusername' AND password = '$adminpassword'";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$count = mysqli_num_rows($result);

	if($count == 1)
	{
		$_SESSION['user_logged_in'] = $adminusername;
		header("location: index.php");
	}
	else
	{
		$error = "Username or password is invalid.";
	}
}

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
