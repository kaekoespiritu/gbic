<div class="modal fade" id="addLoan" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-7">
					<h4 class="modal-title text-right">Add new employee loan</h4>
				</div>
				<div class="col-md-5">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<form class="horizontal" method="POST" action="logic_loans.php">
					<div class="row">
						<div class="col-md-6">
							<h4 class="modal-title">Personal Information</h4><hr>

							<div class="row">
								<div class="col-md-3">
									<label for="fname">First name</label>
								</div>
								<div class="col-md-9">
									<input onkeypress="validateletter(event)" type="text" class="form-control" id="fname" name="firstname" readonly>
									<input type="hidden" id="empid" name="empid">
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3">
									<label for="lname">Last name</label>
								</div>
								<div class="col-md-9">
									<input onkeypress="validateletter(event)" type="text" class="form-control" id="lname"  name="lastname"readonly>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3">
									<label for="address">Address</label>
								</div>
								<div class="col-md-9">
									<input onkeypress="validateletter(event)" type="text" class="form-control" id="address" name="address" readonly>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3">
									<label for="contact">Contact number</label>
								</div>
								<div class="col-md-4">
									<input onkeypress="validatenumber(event)" type="text" class="form-control" id="contact" name="contactnum" readonly>
								</div>
							</div><br>

							<h4 class="modal-title">New Loan Details</h4><hr>
							<div class="row">
								<div class="col-md-3">
									<label for="loanType">Loan Type</label>
								</div>
								<div class="form-group col-md-4">
									<select class="form-control" name="loanType" required id="loanType" onchange="validate(this)">
										<option selected disabled>Loan type</option>
										<option value="SSS">SSS</option>
										<option value="PagIBIG">PagIBIG</option>
										<option value="oldVale">Old vale</option>
										<option value="newVale">New vale</option>
									</select>
								</div>
								<div class="col-md-5">
									<input type="number" class="form-control" required name="loanAmount" id="loanAmount" placeholder="Amount of loan" onchange="validateLoanAmount('loanAmount')">
								</div>
							</div>

							<div class="row">
								<div class="col-md-offset-1">
									<textarea class="form-control" rows="2" required id="reason" name="reason" placeholder="Reason for getting a loan" onchange="validate(this)"></textarea>
								</div><br>
							</div>
						</div>

						<div class="col-md-6">
							<h4 class="modal-title">Job details</h4><hr>
							<div class="row">
								<div class="col-md-5">
									<label for="position" class="text-right">Position & Site</label>
									<input type="hidden" id="position" name="position">
									<input type="hidden" id="site" name="site">
								</div>
								<div class="col-md-5" id="position&site">
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-5">
									<label for="rate">Monthly Salary</label>
								</div>
								<div class="col-md-5">
									<input name="txt_addMonthlySalary" name="monthlysalary" type="text" class="form-control" id="monthlysalary" readonly >
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-5">
									<label for="rate">Rate Per Day</label>
								</div>
								<div class="col-md-5">
									<input name="rate" type="text" class="form-control" id="rate"  readonly>
								</div>
							</div><br>

							<h4 class="modal-title">Pending loans</h4><hr>
							<div class="row">
								<div class="col-md-5">
									<label for="rate">Old vale</label>
								</div>
								<div class="col-md-5">
									<input name="txt_oldVale"  type="text" class="form-control" id="oldvale" placeholder="--" readonly>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-5">
									<label for="rate">New vale</label>
								</div>
								<div class="col-md-5">
									<input name="txt_newVale"  type="text" class="form-control" id="newvale" placeholder="--" readonly>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-5">
									<label for="rate">SSS</label>
								</div>
								<div class="col-md-5">
									<input name="txt_SSS"  type="text" class="form-control" id="sss" placeholder="--" readonly>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-5">
									<label for="rate">PagIBIG</label>
								</div>
								<div class="col-md-5">
									<input name="txt_pagibig"  type="text" class="form-control" id="pagibig" placeholder="--" readonly>
								</div>
							</div><br>
					</div>

				</div>	
				<div class="modal-footer">
					<input type="submit" name="add_submit" id="add_submit" class="btn btn-primary" value="Add new loan">
				</div>			
				</form>
			</div>
			
		</div>
	</div>
</div>