<div class="modal fade" id="addEmployee" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-7 col-lg-7">
					<h4 class="modal-title text-right">Add new Employee</h4>
				</div>
				<div class="col-md-5 col-lg-5">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<form class="horizontal" method="POST" action="logic_add_employee.php">
					<div class="row">
						<div class="col-md-6 col-lg-6">
							<h4 class="modal-title">Personal Information</h4><hr>

							<div class="row">
								<div class="col-md-3 col-lg-3">
									<label for="lname">Last name</label>
								</div>
								<div class="col-md-9 col-lg-9">
									<input name="txt_addLastName" autocomplete="off" onkeypress="validateletter(event)" type="text" class="form-control" id="lname" required>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3 col-lg-3">
									<label for="fname">First name</label>
								</div>
								<div class="col-md-9 col-lg-9">
									<input name="txt_addFirstName" onkeypress="validateletter(event)" type="text" autocomplete="off" class="form-control" id="fname" required>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3 col-lg-3">
									<label for="address">Address</label>
								</div>
								<div class="col-md-9 col-lg-9">
									<input name="txt_addAddress" autocomplete="off" type="text" class="form-control" id="address">
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3 col-lg-3">
									<label for="contact">Contact number</label>
								</div>
								<div class="col-md-4 col-lg-4">
									<input name="txt_addContactNum" autocomplete="off" onkeypress="validatenumber(event)" type="text" class="form-control" id="contact">
								</div>

								<div class="col-md-1 col-lg-1">
									<label for="contact">Date of Birth</label>
								</div>
								<div class="col-md-4 col-lg-4">
									<input name="txt_addDOB" type="text" autocomplete="off" placeholder="mm-dd-yyyy" class="form-control" id="dtpkr_addDOB" required>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3 col-lg-3">
									<label for="contact">Civil Status</label>
								</div>
								<div class="col-md-9 col-lg-9">
									<div class="dropdown">
										<select name="txt_addCivilStatus" class="form-control" aria-labelledby="dropdownMenu1">
											<option value="Single">Single</option>
											<option value="Married">Married</option>
											<option value="Divorced">Divorced</option>
											<option value="Separated">Separated</option>
											<option value="Widowed">Widowed</option>
										</select>
									</div>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-3 col-lg-3">
									<label for="contact">Date of Hire</label>
								</div>
								<div class="col-md-9 col-lg-9">
									<input name="txt_addDateHired" autocomplete="off" type="text" size="10" style="width:150px" class="form-control" id="dtpkr_addEmployee" placeholder="mm-dd-yyyy" required readonly>
								</div>
							</div>

							<div class="row pull-down">
								<div class="col-md-4 col-lg-4">
									<label for="emergency">Emergency contact:</label>	
								</div>
								<div class="col-md-8 col-lg-8">									
									<input name="txt_emergencyContact" type="text" autocomplete="off" class="form-control">
								</div>
							</div>

							<div class="row pull-down">
								<div class="col-md-4 col-lg-4">
									<label for="characterReference" class="no-wrap">Character Reference:</label>
								</div>
								<div class="col-md-8 col-lg-8">
									<input name="txt_characterReference" type="text" autocomplete="off" 
									class="form-control">
								</div>
							</div>

							<div class="row pull-down">
								<div class="col-md-3 col-lg-3">
									<label for="cola" class="no-wrap">COLA:</label>
								</div>
								<div class="col-md-3 col-lg-3">
									<input name="txt_cola" type="text" autocomplete="off" onchange="colaDecimal()" class="form-control">
								</div>

								<div class="col-md-3 col-lg-3">
									<label for="bank" class="no-wrap">Bank:</label>
								</div>
								<div class="col-md-3 col-lg-3">
									<select class="form-control" name="bank">
										<option value=''>N/A</option>
									<?php
										$bank = "SELECT * FROM banks ORDER BY name ASC";
										$bankQuery = mysql_query($bank);
										while($bankArr = mysql_fetch_assoc($bankQuery))
										{
											Print "<option value='".$bankArr['name']."'>".$bankArr['name']."</option>";
										}
									?>
									</select>

							</div>
						</div>
							<div class="row pull-down">
								<div class="col-md-3 col-lg-3">
									<label for="insurance" class="no-wrap">Insurance:</label>
								</div>
								<div class="col-md-3 col-lg-3">
									<input name="txt_insurance" type="text" autocomplete="off" onchange="colaDecimal()" class="form-control">
								</div>
							</div>
						</div>

						<div class="col-md-6 col-lg-6">
							<h4 class="modal-title">Job details</h4><hr>
							<div class="row">
								<div class="col-md-5 col-lg-5">
									<label for="position" class="text-right">Position</label>
								</div>
								<div class="col-md-5 col-lg-5">
									<div class="dropdown">
										<select name="dd_addPosition" class="form-control" aria-labelledby="dropdownMenu1" required>
											<option disabled value="" selected>Select a position</option>
										<?php
										$query = "SELECT position FROM job_position WHERE active = '1'";
										$job_query = mysql_query($query);
										while($row = mysql_fetch_assoc($job_query))
										{
											Print '<option value="'.$row["position"].'">'.$row["position"].'</option>';
										}
										?>
										</select>
									</div>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-5 col-lg-5">
									<label for="position" class="text-right">Site</label>
								</div>
								<div class="col-md-5 col-lg-5">
									<div class="dropdown">
										<select class="form-control" name="dd_site" required>
											<option disabled value="" selected>Select a site</option>
										<?php
											$site_query = "SELECT location FROM site WHERE active = '1'";
											$location_query = mysql_query($site_query);
											while($row = mysql_fetch_assoc($location_query))
											{
												Print '<option value="'.$row["location"].'">'.$row["location"].'</option>';
											}
										?>
										</select>
									</div>
								</div>
							</div><br> 

							<div class="row">
								<div class="col-md-5 col-lg-5">
									<label for="rate">Rate Per Day</label>
								</div>
								<div class="col-md-5 col-lg-5">
									<input name="txt_addRatePerDay" onkeypress="validatenumber(event)" onchange="salaryDecimal()" type="text" autocomplete="off" class="form-control" id="rate" required>
								</div>
							</div><br>

							<div class="row">
								<div class="col-md-5 col-lg-5">
									<label for="allowance">Allowance</label>
								</div>
								<div class="col-md-5 col-lg-5">
									<input name="txt_addAllowance" onkeypress="validatenumber(event)" type="text" class="form-control" autocomplete="off" onchange="allowanceDecimal()" id="allowance">
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-5 col-lg-5">
									<label for="allowance">Extra Allowance Daily</label>
								</div>
								<div class="col-md-5 col-lg-5">
									<input name="txt_addXAllowanceDaily" onkeypress="validatenumber(event)" type="text" class="form-control" autocomplete="off" onchange="allowanceDecimal()" id="allowance">
								</div>
							</div>

							<div class="row">
								<div class="col-md-5 col-lg-5">
									<label for="allowance">Extra Allowance Weekly</label>
								</div>
								<div class="col-md-5 col-lg-5">
									<input name="txt_addXAllowanceWeekly" onkeypress="validatenumber(event)" type="text" class="form-control" autocomplete="off" onchange="allowanceDecimal()" id="allowance">
								</div>
							</div>

							<div class="row">
								<h4 class="modal-title"><br>Contributions</h4><hr>
								<!-- //////////// -->

								<div class="row">
									<!-- SSS -->
									<div class="col-md-12 col-lg-12">
										<div class="col-md-3 col-lg-3">
											<input type="checkbox" id="sssCheckbox" name="sssCheckbox" onchange="sssCheckboxFunc()">
											<label for="sss">SSS</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<!-- <div class="form-inline"> -->
												<div class="row">
													<div class="col-md-1 col-lg-1">
														<label for="sss_ee">EE:</label>
													</div>
													<div class="col-md-4 col-lg-4">
														<input name="txt_addSSSEE" type="text" autocomplete="off" placeholder="No document" class="form-control" id="sssEE" readonly>
													</div>
													<div class="col-md-1 col-lg-1">
														<label for="sss_er">ER:</label>
													</div>
													<div class="col-md-4 col-lg-4">
														<input name="txt_addSSSER" type="text" autocomplete="off" placeholder="No document" class="form-control" id="sssER" readonly>
													</div>
												</div>
											<!-- </div> -->
										</div>
									</div>

									<!-- PhilHealth -->
									<div class="col-md-12 col-lg-12 pull-down">
										<div class="col-md-3 col-lg-3">
											<input type="checkbox" id="philhealthCheckbox" name="philhealthCheckbox" onchange="philhealthCheckboxFunc()">
											<label for="philhealth" class="nowrap">Philhealth</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<div class="row">
												<div class="col-md-1 col-lg-1">
													<label for="philhealth_ee">EE:</label>
												</div>
												<div class="col-md-4 col-lg-4">
													<input name="txt_addPhilhealthEE" type="text" placeholder="No document" class="form-control" autocomplete="off" id="philhealthEE" readonly>
												</div>
												<div class="col-md-1 col-lg-1">
													<label for="philhealth_er">ER:</label>
												</div>
												<div class="col-md-4 col-lg-4">
													<input name="txt_addPhilhealthER" type="text" placeholder="No document" autocomplete="off" class="form-control" id="philhealthER" readonly>
												</div>
											</div>
										</div>
									</div>

									<!-- PagIBIG-->
									<div class="col-md-12 col-lg-12 pull-down">
										<div class="col-md-3 col-lg-3">
											<input type="checkbox" id="pagibigCheckbox" name="pagibigCheckbox" onchange="pagibigCheckboxFunc()">
											<label for="pagibig" class="nowrap">Pagibig</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<div class="row">
												<div class="col-md-1 col-lg-1">
													<label for="pagibig_ee">EE:</label>
												</div>
												<div class="col-md-4 col-lg-4">
													<input name="txt_addPagibigEE" type="text" autocomplete="off" placeholder="No document" class="form-control" id="pagibigEE" readonly>
												</div>
												<div class="col-md-1 col-lg-1">
													<label for="pagibig_er">ER:</label>
												</div>
												<div class="col-md-4 col-lg-4">
													<input name="txt_addPagibigER" autocomplete="off" type="text" placeholder="No document" class="form-control" id="pagibigER" readonly>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-10 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down text-center well well-sm">
										Note: Check the boxes above if employee has document for SSS / PhilHealth / Pagibig.<br>Input must be on a monthly basis.
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>	
				<div class="modal-footer">
					<input type="submit" name="add_submit" id="add_submit" class="btn btn-primary" value="Add Employee">
				</div>			
			</form>
			</div>
			
		</div>
	</div>
</div>







