<!-- MODAL for adding COLA -->
			<div class="modal fade bs-example-modal-sm" role="dialog" id="addCola">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<form method='POST' id='addColaForm' action='logic_options_cola.php'>
							<div class="modal-header">
							<h4 class="modal-title col-md-11 col-lg-11">Add new COLA for site</h4>
							<button type="button" class="close col-md-1 col-lg-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
								<form>
								<div class="row">
									<div class="col-md-6 col-lg-6">
										<div class="dropdown">
											<select class="form-control" name="dd_site" required>
												<option hidden>Select a site</option>
												<?php
												$site_query = "SELECT location FROM site WHERE active = '1' AND cola IS NULL";
												$location_query = mysql_query($site_query);
												$cola = "";
												while($row = mysql_fetch_assoc($location_query))
												{
													Print '<option value="'.$row["location"].'">'.$row["location"].'</option>';
													if($row["cola"] != null)
													{
														$cola = $row["cola"];
													}
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-6 col-lg-6">
										<input type="number" name="cola" class="form-control" value="<?php Print $cola?>" placeholder="Enter COLA" required>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<input type="submit" onclick="addColaSubmit()" class="btn btn-primary" value="Save changes">
						</div>
					</form>
				</div>
			</div>
		</div>
		


















