<!-- MODAL for new account -->
		<div class="modal fade" id="newAccount" role="dialog">
			<div class="modal-dialog modal-lg" id='modalsize'>
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-12">
							<h4 class="modal-title">Add new account</h4>
						</div>
					</div>
					<div class="modal-body">
						<div class="row">
						<form id="newAccountForm" method="POST" action="logic_options_newAccount.php" class="form-inline text-left">
						<div class="col-md-6" id='modalcol'>
							<?php
							
							?>
							<label class="col-md-12">
								Firstname:
								<input type="text" name="n_firstname" class="form-control" required>
							</label>
							<label class="col-md-12">
								Lastname:
								<input type="text" name="n_lastname" class="form-control" required>
							</label>
							<label class="col-md-12" id="usernameVal">
								Username:
								<input type="text" name="n_username" onkeyup="usernameValidation(this.value)" class="form-control" required>
							</label>
							<label class="col-md-12">
								Password:
								<input type="password" name="n_password" class="form-control" required>
							</label>
							<label class="col-md-12">
								Confirm Password:
								<input type="password" name="n_confirmPassword" class="form-control" required>
							</label>
							<label class="col-md-12">
								Security Question:
								<select class="form-control" name="n_security">
									<?php Print $secretQuestions?>
								</select>
							</label>
							<label class="col-md-12">
								Answer:
								<input type="text" name="n_answer" class="form-control" required>
							</label>
							<div class="col-md-12">
								Choose account role:
								<div class="radio">
									<label>
										<input type="radio" name="n_role[]" value="Employee" checked onchange="hideRestrictions()">
										Employee
									</label>
									<label>
										<input type="radio" name="n_role[]" value="Administrator" onchange="hideRestrictions()" id='adminradio'>
										Administrator
									</label>
								</div>
							</div>
						</div>
						<!-- To appear only when employee is selected -->
							<div class="col-md-6" id='restrictions' style="display:block">
								<h4>Restrictions</h4>
								<ul class="list-unstyled">
									<li>
										<label>
											<input type="checkbox" disabled>
											Employees Tab
										</label>
										<ul style="list-style: none;">
											<li>
												<label>
													<input type="checkbox" disabled>
													Access to list of employees
												</label>
											</li>
											<li>
												<label>
													<input type="checkbox" disabled>
													Access to list of loan applications
												</label>
											</li>
											<li>
												<label>
													<input type="checkbox" disabled>
													Access to list of absence notifications
												</label>
											</li>
											<li>
												<label>
													<input type="checkbox" disabled>
													Access to list of site management
												</label>
											</li>
										</ul>
									</li>
									<li>
										<label>
											<input type="checkbox" disabled>
											Attendance Access
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox" disabled>
											Payroll Access
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox" disabled>
											Reports
										</label>
										<ul style="list-style: none;">
											<li>
												<label>
													<input type="checkbox" disabled>
													Access Earnings
												</label>
											</li>
											<li>
												<label>
													<input type="checkbox" disabled>
													Access Contributions
												</label>
											</li>
											<li>
												<label>
													<input type="checkbox" disabled>
													Access Loans
												</label>
											</li>
											<li>
												<label>
													<input type="checkbox" disabled>
													Access Attendance
												</label>
											</li>
											<li>
												<label>
													<input type="checkbox" disabled>
													Access Company Expenses
												</label>
											</li>
										</ul>
									</li>
									<li>
										<label>
											<input type="checkbox" disabled>
											Options
										</label>
										<ul style="list-style: none;">
											<li>
												<label>
													<input type="checkbox" disabled>
													Access to site management
												</label>
											</li>
											<li>
												<label>
													<input type="checkbox" disabled>
													Access to position management
												</label>
											</li>
										</ul>
									</li>
								</ul>
							</div>
							<input id="newAccount_submit" type="submit" style="display: none">
						</form>
					</div>
					<div class="modal-footer pul class="list-unstyled"l-down">
						<button class="btn btn-defaul class="list-unstyled"t" data-dismiss="modal">Cancel</button>
						<button class="btn btn-primary" onclick="newAccountFunction()" id="addAccountSubmit" disabled>Save Changes</button>
					</div>
					</div>
				</div>
			</div>
		</div>