<!-- MODAL for COLA settings-->
			<div class="modal fade" role="dialog" id="colaSettings">
			  <div class="modal-dialog" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11">Manage COLA settings</h4>
				        <button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <!-- Form for COLA-->
				    <form method="post" action="logic_options_cola.php" id="colaForm">
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
					    				<form id="siteForm" method="post" action="logic_options_removeSite.php">
					    					<?php 
					    					$site = "SELECT * FROM site WHERE active = '1'";
					    					$siteQuery = mysql_query($site);

					    					while($siteRow = mysql_fetch_assoc($siteQuery))
					    					{
					    						Print '	<div class="alignlist">
					    						<label>
					    						<input type="radio" name="site[]" value="'.$siteRow['location'].'">
					    						'.$siteRow['location'].'
					    						</label> -- [COLA AMOUNT]
					    						</div>';
					    					}
					    					?>
					    				</form>							
					    		</div>
					    	</div>
						</div>
						</div>
				     	<div class="modal-footer">
					        <button type="submit" class="btn btn-primary">Save changes</button>
				    	</div>
					</form>
			    </div>
			  </div>
			</div>