<!DOCTYPE html>
<?php
// Connecting to database
session_start();
include_once("directives/db.php");


?>
<html>
	<head>
		<title>Payroll</title>
		<!-- Company Name: Green Built Industrial Corporation -->

		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="css/style.css" type="text/css">

	</head>
	<body>
		<!-- MODAL FOR FORGOT PASSWORD -->
		<div class="modal fade" role="dialog" id="forgotPass">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-10 text-right">
							<h5 class="modal-title">Forgot your password?</h5>
						</div>
						<div class="col-md-1 pull-right">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
					</div>
					<div class="modal-body">
						<label>
							Username:
							<input type="text" class="form-control" id="forgotPassUsername" name="forgot_username">
						</label>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary" onclick="forgotPass_User()" data-toggle="modal" data-target="#askQ">Submit</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Forgot password secret Question-->
		<div class="modal fade" role="dialog" id="askQ">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div id="modal_forgotQuestions"></div>
				</div>
			</div>
		</div>

		<!-- New password Modal -->
		<div class="modal fade" role="dialog" id="newPass">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">

					<!-- This will be the new password-->
					<span id="newPassword"></span>
					<!-- ------------------------------ -->

				</div>
			</div>
		</div>


		<img src="Images/bg.jpg" class="bg">
		<div class="wrapper">
			<div class="panel clear-fix">
				<div class="panel-heading text-center">
					<img src="Images/Company Logo.png"><br>
					<h4>Please log in to continue</h4>
				</div>
			</div>
			<div class="pull-up">
				<div class="panel-body login">

					<form class="horizontal" action="" method="post">
						<div class="form-group">
							<label for="username" class="control-label col-md-3 login-text">Username</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="username">
							</div>
						</div>

						<br><br>
						

						<div class="form-group">
							<label for="password" class="control-label col-md-3 login-text">Password</label>
							<div class="col-md-9">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12"><br>
								<!-- FIX PLACEMENT OF LINK AND ADD MODAL -->
								<a href="#" data-toggle="modal" data-target="#forgotPass" class='whitelink'><h5>Forgot your password?</h5></a>
								<input type="hidden" name="login">
								<button type="submit" class="btn btn-warning btn_loginSubmit login-text login-button">Log in</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<script>
			// var $slider = document.getElementById('slider');
			// var $toggle = document.getElementById('toggle');

			// $toggle.addEventListener('click', function() {
			// 	var isOpen = $slider.classList.contains('slide-in');

			// 	$slider.setAttribute('class', isOpen ? 'slide-out' : 'slide-in');
			// });
		</script>
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
		
		// function forgotPass_User() {
		// 	var user = document.getElementById('forgotPassUsername').value;
		//   	$.ajax({
		//    	url:"fetch_forgotpassword.php",
		//    	method:"POST",
		//    	info:{
		//    		username : user
		//    	},
		//    	success:function(info){
		//    		// if(data != ""){
		//     		$('#modal_forgotQuestions').html(info);
		//    		// }
		//     	// else {
		//     	// 	alert('Username Invalid');
		//     	// 	window.location.assign('login.php');
		//     	// }
		//    	}
		//   	});
		// }

		function forgotPass_User()
		{
			var user = document.getElementById('forgotPassUsername').value;
			$.ajax({
				url:"fetch_forgotpassword.php",
				method:"POST",
				data:{
						username: user
					},
				success:function(data)
				{
					if(data != ""){
		    			$('#modal_forgotQuestions').html(data);
		    			$('#forgotPass').modal('hide');
		   			}
		   			else
		   			{
		   				alert("none");
		   			}
				}
			});
		}

		function resetPass() {
			var ans = document.getElementById('securityAnswers').value;
			var user = document.getElementById('forgotPass_Username').value;
			$.ajax({
				url:"fetch_forgotpassword_reset.php",
				method:"POST",
				data:{
						answer: ans,
						username: user
					},
				success:function(data)
				{
		    			$('#newPassword').html(data);
		    			$('#forgotPass').modal('hide');
		    			$('#askQ').modal('hide');
				}
			});
		}
		</script>
		
	</body>
</html>
<?php
if(isset($_POST['login']))
{
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);

	$sql = "SELECT * FROM administrator WHERE username = '$username' AND password = '$password'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$count = mysql_num_rows($result);
	$user = $row['username'];
	$pass = $row['password'];

	if($username === $user && $password === $pass)
	{
		$_SESSION['user_logged_in'] = $username;
		Print "<script>window.location.assign('index.php')</script>";
	}
	else
	{
		 Print "<script>alert('Username or password is invalid.')</script>";
	}
}
?>