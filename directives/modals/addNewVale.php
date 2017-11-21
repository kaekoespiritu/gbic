<div class="modal fade" id="addVale">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add new vale</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<h4 class="text-left">Amount:</h4>
						<input type="text" id="newAddVale" class="form-control" placeholder="Amount of new vale" onkeypress="validatenumber(event)"><br>
						<h4 class="text-left">Reason:</h4>
						<textarea class="form-control" rows="3" id="newValeRemarks" placeholder="Add reason for getting new vale"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="addvale()" data-dismiss="modal">Add</button>
			</div>
		</div>
	</div>
</div>