<div class="modal fade" id="secureChanges">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Secure change</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">Save changes by entering your password:</p>
						<form method="post" id="payrollForm" action="logic_options_payroll.php">
							<input type="password" id="newAddVale" name="adminPassword" class="form-control" placeholder="Enter your password"><br>
							<input type="hidden" name="openPay" id="openPay">
							<input type="hidden" name="closePay" id="closePay">
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="submitPayroll()">Continue</button>
			</div>
		</div>
	</div>
</div>