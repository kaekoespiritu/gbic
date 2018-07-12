<!-- MODAL for adding a position-->
			<div class="modal fade bs-example-modal-sm" role="dialog" id="addPosition">
			  <div class="modal-dialog modal-sm" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11 col-lg-11">Add new position</h4>
				        <button type="button" class="close col-md-1 col-lg-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <!-- form for adding POSITIONS -->
				    <form method="POST" action="logic_options_addPosition.php">
					    <div class="modal-body">
				     		<input type="text" name="position_name" class="form-control" placeholder="Name of new position">
				     	</div>
			     	
			     		<div class="modal-footer">
				     		<input type="checkbox" name="driver" class="pull-left">
				     		<span class="pull-left">Driver/Truck helper</span>
					        <button type="submit" class="btn btn-primary">Save changes</button>
				      	</div>
				  	</form>
			    </div>
			  </div>
			</div>