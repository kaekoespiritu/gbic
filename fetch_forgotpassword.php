<?php
	include_once('directives/db.php');
	
	$username = mysql_real_escape_string($_POST['username']);

	$admin = "SELECT * FROM administrator WHERE username = '$username'";
	$adminQuery = mysql_query($admin);

	$output = "";
	if(mysql_num_rows($adminQuery) > 0)
	{
		$adminArr = mysql_fetch_assoc($adminQuery);
		$secret = $adminArr['secret_question'];
		$question = "SELECT * FROM secret_questions WHERE id = '$secret'";
		$questionQuery = mysql_query($question);
		$secretArr = mysql_fetch_assoc($questionQuery);
		$secretQuestion = $secretArr['questions'];

		$output = '
			<div class="modal-header">
				<div class="col-md-10 text-right">
					<h5 class="modal-title">Answer the security question</h5>
				</div>
				<div class="col-md-1 pull-right">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			</div>
			<div class="modal-body">
				<h5>Security Question:<br>'.$secretQuestion.'</h5><br>
				<label>
					Answer:
					<input type="text" id="securityAnswers" name="securityAnswers" class="form-control">
				</label>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="forgotPass_Username" value="'.$username.'">
				<button class="btn btn-primary" data-toggle="modal" data-target="#newPass" onclick="resetPass()">Submit</button>
			</div>';
	}
	else
	{
		Print "<script>alert('Incorrect Username')</script>";
		Print "<script>window.location.assign('login.php')</script>";
	}
echo $output;
?>






