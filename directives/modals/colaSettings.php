<!-- MODAL for COLA settings-->
			<div class="modal fade" role="dialog" id="colaSettings">
			  <div class="modal-dialog" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11">Manage COLA settings</h4>
				        <button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <!-- Form for COLA-->
				    
					    <div class="modal-body">
					    	<div class="row">
					    		<h4 class="modal-title">Sites with COLA</h4><br>
					    		<div class="col-md-3 col-md-offset-2">
					    			<a data-target="#addCola" data-toggle="modal" class="btn btn-success col-md-12 pull-down">ADD COLA</a>
					    			<a data-target="#modifyCola" data-toggle="modal" class="btn btn-primary col-md-12 pull-down">MODIFY COLA</a>
					    			<a class="btn btn-danger col-md-12 pull-down" onclick="colaRemove()">REMOVE COLA</a>
					    		</div>

					    		<div class="col-md-6 text-left">
					    			<div class="sitelist">
					    					<?php 
					    					$site = "SELECT * FROM site WHERE active = '1' AND cola IS NOT NULL";
					    					$siteQuery = mysql_query($site);

					    					while($siteRow = mysql_fetch_assoc($siteQuery))
					    					{
					    						Print '	<div class="alignlist">
					    						<label>
					    						<input type="radio" name="remcola[]" onclick="removeSiteCola(this.value)" value="'.$siteRow['location'].'">
					    						'.$siteRow['location'].'
					    						</label> - ['.$siteRow['cola'].']
					    						</div>';
					    					}
					    					?>
					    				<input type="hidden" id="colaToRemove">
					    			</div>
					    		</div>
							</div>
						</div>
				     	<div class="modal-footer">
				    	</div>
					</form>
			    </div>
			  </div>
			</div>