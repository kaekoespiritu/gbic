<!-- MODAL for removing a position-->
<div class="modal fade bs-example-modal-sm" role="dialog" id="removePosition">
  <div class="modal-dialog modal-sm" role="document">
  	<div class="modal-content">
	  	<div class="modal-header">
	  		<h4 class="modal-title col-md-11">Remove a position</h4>
	        <button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    </div>
	    <!-- form for adding POSITIONS -->
	    <form method="POST" action="logic_options_removePosition.php">
		    <div class="modal-body">
	     		
	     		<table class="table table-bordered">
	     			<tr>
	     				<td>
	     					Position
	     				</td>
	     				<td>
	     					Employees in position
	     				</td>
	     			</tr>
	     			<tr>
	     				<td>
	     					Welder
	     				</td>
	     				<td>
	     					##
	     				</td>
	     			</tr>
	     			<tr>
	     				<td>
	     					Plumber
	     				</td>
	     				<td>
	     					##
	     				</td>
	     			</tr>
	     		</table>

	     		<div>
	     			NOTE: Be sure to remove all employees from selected position before removing the position.
	     		</div>
	     	</div>
     	
     		<div class="modal-footer">
	        	<center>
	        		<button type="submit" class="btn btn-primary">Remove</button>
	        	</center>
	      	</div>
	  	</form>
    </div>
  </div>
</div>