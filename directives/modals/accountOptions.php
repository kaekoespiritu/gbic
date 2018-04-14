<!-- Modal for account options -->
		<div class="modal fade" id="accountOptions" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-11 col-lg-11">
							<h4 class="modal-title">Options for this account</h4>
						</div>
						<div class="col-md-1 col-lg-1">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-5 col-lg-5">
							<ul class="nav nav-pills nav-stacked">
								<li class="active"><a href="#changepass" data-toggle="tab">Change Password</a></li>
								<li><a href="#changeuser" data-toggle="tab">Change Username</a></li>
								<li><a href="#securityq" data-toggle="tab">Configure security questions</a></li>
							</ul>
							</div>
							<div class="col-md-7 col-lg-7 text-left" style="border-left-style: solid;">
								<?php

									$user = $_SESSION['user_logged_in'];

									$admin = "SELECT * FROM administrator WHERE username = '$user'";
									$adminQuery = mysql_query($admin);
									$adminRow = mysql_fetch_assoc($adminQuery);
									$user = $adminRow['username'];

									$question = "SELECT * FROM secret_questions";
									$quesQuery = mysql_query($question);

								//Secret Questions
									$secretQuestions = "";
									while($questionArr = mysql_fetch_assoc($quesQuery))
									{
										$secretQuestions .=  "<option value='".$questionArr['id']."'>".$questionArr['questions']."</option>";
									}
								?>
								<form class="form-inline" id="account_option" method="POST" action="logic_options_account.php">
									<div class="tab-content">
										<div id="changepass" class="tab-pane active">
										<label>Old password:
											<input type="password" name="oldPassword" class="form-control">
										</label>
										<label>New password:
											<input type="password" name="newPassword" class="form-control">
										</label>
										<label>Confirm password:
											<input type="password" name="confirmPassword" class="form-control">
										</label>
										</div>
										<div id="changeuser" class="tab-pane">
											<h4>Current username: <?php Print $user ?></h4>
										<label id="currUsername">New username:
											<input type="text" name="newUsername" onkeyup="updateUsernameValidation(this.value)" id="newUsername" class="form-control">
										</label>
										<br><br>
										<div>
											<span class="well well-sm">Remember: Username is case sensitive.</span>
										</div>
										</div>
										<div id="securityq" class="tab-pane">
										<label>
											Security Question:
											<!-- TODO: CHANGE DROPDOWN TO RADIO BUTTON -->
											<div class="">
											<select class="form-control fixed-width" name="securityQuestion">
												<option value="" hidden>-- Choose security question --</option>
												<?php Print $secretQuestions ?>
											</select>
											</div>
										</label>
										<label>
											Answer:
											<input type="text" name="answer" class="form-control">
										</label>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button onclick="optionAccount()" class="btn btn-primary" id="accountOptionsSubmit">Save Changes</button>
					</div>
				</div>
			</div>
		</div>