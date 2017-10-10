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
				<form class="horizontal" method="POST" action="">
					<div class="row">
						<div class="col-md-6">
							<h4 class="modal-title">Personal Information</h4><hr>

							<div class="row">
								<div class="col-md-3">
									<label for="fname">First name</label>
								</div>
								<div class="col-md-9">
									<input name="txt_addFirstName" onkeypress="validateletter(event)" type="text" class="form-control" id="fname" placeholder="Miguelito Joselito" readonly>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3">
									<label for="lname">Last name</label>
								</div>
								<div class="col-md-9">
									<input name="txt_addLastName" onkeypress="validateletter(event)" type="text" class="form-control" id="lname" placeholder="Dela Cruz" readonly>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3">
									<label for="address">Address</label>
								</div>
								<div class="col-md-9">
									<input name="txt_addAddress" onkeypress="validateletter(event)" type="text" class="form-control" id="address" placeholder="123 Horizon Boulevard Sunset City" readonly>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3">
									<label for="contact">Contact number</label>
								</div>
								<div class="col-md-4">
									<input name="txt_addContactNum" onkeypress="validatenumber(event)" type="text" class="form-control" id="contact" placeholder="09123456789" readonly>
								</div>
							</div><br>

							<h4 class="modal-title">New Loan Details</h4><hr>
							<div class="row">
								<div class="col-md-3">
									<label for="loanType">Loan Type</label>
								</div>
								<div class="form-group col-md-4">
									<select class="form-control" id="loanType">
										<option>SSS</option>
										<option>PagIBIG</option>
										<option>Old vale</option>
										<option>New vale</option>
									</select>
								</div>
								<div class="col-md-5">
									<input type="text" class="form-control" placeholder="Amount of loan">
								</div>
							</div>

							<div class="row">
								<div class="col-md-offset-1">
									<textarea class="form-control" rows="2" placeholder="Reason for getting a loan" required></textarea>
								</div><br>
							</div>
						</div>

						<div class="col-md-6">
							<h4 class="modal-title">Job details</h4><hr>
							<div class="row">
								<div class="col-md-5">
									<label for="position" class="text-right">Position & Site</label>
								</div>
								<div class="col-md-5">
									POSITION AT SITE
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-5">
									<label for="rate">Monthly Salary</label>
								</div>
								<div class="col-md-5">
									<input name="txt_addMonthlySalary"  type="text" class="form-control" id="monthlysalary" readonly placeholder="9500.00">
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-5">
									<label for="rate">Rate Per Day</label>
								</div>
								<div class="col-md-5">
									<input name="txt_addRatePerDay"  type="text" class="form-control" id="rate" placeholder="380" readonly>
								</div>
							</div><br>

							<h4 class="modal-title">Pending loans</h4><hr>
							<div class="row">
								<div class="col-md-5">
									<label for="rate">Old vale</label>
								</div>
								<div class="col-md-5">
									<input name="txt_oldVale"  type="text" class="form-control" id="vale" placeholder="--" readonly>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-5">
									<label for="rate">New vale</label>
								</div>
								<div class="col-md-5">
									<input name="txt_newVale"  type="text" class="form-control" id="vale" placeholder="--" readonly>
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