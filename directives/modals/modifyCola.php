<!-- MODAL for modifying COLA -->
<div class="modal fade bs-example-modal-sm" role="dialog" id="modifyCola">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title col-md-11">Modify COLA for site</h4>
				<button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form>
					<div class="row">
						<div class="col-md-6">
							<div class="dropdown">
								<select class="form-control" name="dd_site" required>
									<option hidden>Select a site</option>
									<?php
									$site_query = "SELECT location FROM site WHERE active = '1'";
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
						<div class="col-md-6">
							<input type="number" name="cola" class="form-control" value="<?php Print $cola?>" placeholder="Enter COLA">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>