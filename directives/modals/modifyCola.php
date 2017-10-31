<!-- MODAL for modifying COLA -->
<div class="modal fade bs-example-modal-sm" role="dialog" id="modifyCola">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title col-md-11">Modify COLA for site</h4>
				<button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="logic_options_modCola.php" id="modColaForm">
			<div class="modal-body">
				
					<div class="row">
						<div class="col-md-6">
							<div class="dropdown">
								<select class="form-control" name="dd_site" onchange="modifyCola(this.value)" required>
									<option hidden>Select a site</option>
									<?php
									$site_query = "SELECT * FROM site WHERE active = '1' AND cola IS NOT NULL";
									$location_query = mysql_query($site_query);
									$cola = "";
									while($row = mysql_fetch_assoc($location_query))
									{
										Print '<option value="'.$row["location"].'">'.$row["location"].'</option>';
										
										if($row['cola'] != null)
										{
											$cola = $row['cola'];
										}
									}
									Print "</select>";
									$siteQuery = "SELECT * FROM site WHERE active = '1' AND cola IS NOT NULL";
									$locationQuery = mysql_query($siteQuery);
									while($rowArr = mysql_fetch_assoc($locationQuery))
									{
										Print '<input type="hidden" id="mod'.$rowArr["location"].'" value="'.$rowArr["cola"].'">';
									}
									
								?>
							</div>
						</div>
						<div class="col-md-6">
							<input type="number" name="cola" id="modifyColaInput" class="form-control" placeholder="Enter COLA">
						</div>
					</div>
				
				<div class="modal-footer">
					<button type="submit" onclick="modColaSubmit()" class="btn btn-primary">Save changes</button>
				</div>
				</form>
			</div>
			
		</div>
	</div>
</div>