<!-- MODAL for adding site-->
			<div class="modal fade" role="dialog" id="addSite">
			  <div class="modal-dialog modal-sm" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11 col-lg-11">Add new site</h4>
				        <button type="button" class="close col-md-1 col-lg-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <!-- form for adding SITES -->
				    <form method="POST" action="logic_options_addSite.php">
					    <div class="modal-body">
				     		<input type="text" class="form-control" name="site_name" placeholder="Name of new site">
				     	</div>
				     	<div class="modal-footer">
				     		<div class="col-md-5 col-lg-5">
				     			<input type="number" placeholder="COLA" class="form-control input-sm">
				     		</div>
					        <button type="submit" class="btn btn-primary">Save changes</button>
					    </div>
				  	</form>
			    </div>
			  </div>
			</div>