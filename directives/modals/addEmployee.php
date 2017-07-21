<div class="modal fade" id="addEmployee" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-7">
							<h4 class="modal-title text-right">Add new Employee</h4>
						</div>
						<div class="col-md-5">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<form class="horizontal">
							<div class="row">
								<div class="col-md-6">
									<h4 class="modal-title">Personal Information</h4><hr>
									<div class="row">
										<div class="col-md-3">
											<label for="fname">First name</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="fname">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="lname">Last name</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="lname">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="address">Address</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="address">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Contact number</label>
										</div>
										<div class="col-md-5">
											<input type="text" class="form-control" id="contact">
										</div>
										<div class="col-md-1">
											<label for="contact">Age</label>
										</div>
										<div class="col-md-3">
											<input type="text" class="form-control" id="contact">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Civil Status</label>
										</div>
										<div class="col-md-9">
											<div class="row">
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Single</label>
												</div>
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Married</label>
												</div>
												<div class="col-md-4">
												<input type="radio" autocomplete="off">
												<label>Divorced</label>
												</div>
												<div class="col-md-6">
												<input type="radio" autocomplete="off">
												<label>Separated</label>
												</div>
												<div class="col-md-6">
												<input type="radio" autocomplete="off">
												<label>Widowed</label>
												</div>
											</div>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-3">
											<label for="contact">Date of Hire</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" id="contact" placeholder="Turn this into a calendar date picker!">
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<h4 class="modal-title">Job details</h4><hr>
									<div class="row">
										<div class="col-md-5">
											<label for="position" class="text-right">Position</label>
										</div>
										<div class="col-md-4">
											<div class="dropdown">
												<button class="btn btn-default dropdown-toggle pull-left" type="button" id="position" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Select a position
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
													<li><a href="#">Foreman</a></li>
													<li><a href="#">Leadman</a></li>
													<li><a href="#">Time Keeper</a></li>
													<li><a href="#">Operator</a></li>
													<li><a href="#">Carpenter</a></li>
													<li><a href="#">Mason</a></li>
													<li><a href="#">Labor</a></li>
													<li><a href="#">Welder</a></li>
													<li><a href="#">Painter</a></li>
													<li><a href="#">Electrician</a></li>
													<li><a href="#">Plumber</a></li>
													<li><a href="#">Office Staff</a></li>
												</ul>
											</div>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-5">
											<label for="rate">Rate per day</label>
										</div>
										<div class="col-md-4">
											<input type="text" class="form-control" id="rate">
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5">
											<label for="allowance">Allowance</label>
										</div>
										<div class="col-md-4">
											<input type="text" class="form-control" id="allowance">
										</div>
									</div>
									<div class="row">
										<h4 class="modal-title"><br>Contributions</h4><hr><br>

										<div class="row">
											<div class="col-md-5">
												<label for="pagibig">Pag-IBIG</label>
											</div>
											<div class="col-md-4">
												<input type="text" class="form-control" id="pagibig">
											</div><br><br><br>
											<div class="col-md-8 col-md-offset-2 text-center well">
											* SSS and PhilHealth contributions are automatically computed based on employee's base pay.
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>