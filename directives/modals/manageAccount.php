<!-- MODAL for managing account -->
		<div class="modal fade" id="manageAccount" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-11">
							<h4 class="modal-title">Manage employee accounts</h4>
						</div>
						<div class="col-md-1">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow:scroll; height: 500px">
							<?php
							$accounts = "SELECT * FROM administrator WHERE role = 'Employee'";
							$accountQuery = mysql_query($accounts);
							
							if(mysql_num_rows($accountQuery) > 0)
							{
								while($AcctRow = mysql_fetch_assoc($accountQuery))
								{
									Print "
										<div class='col-md-12'>
											<div class='panel panel-primary'>
												<div class='panel-body'>
												<h4>
													".$AcctRow['lastname'].", ".$AcctRow['firstname']."
												</h4>
												<button class='btn btn-default' data-toggle='modal' data-target='#setRestrictions' onclick='editRestrictions(\"".$AcctRow['username']."\")'>
													Set Restrictions
												</button>
												<button class='btn btn-danger' onclick='removeAccount(\"".$AcctRow['username']."\")''>
													Remove Account
												</button>
												<button class='btn btn-warning' data-toggle='modal' data-target='#resetPass' onclick='passwordReset(\"".$AcctRow['username']."\")'>Reset Password</button>
												</div>
											</div>
										</div>	
									";
								}
							}
							?>
							

						</div>
					</div>
				</div>
			</div>
		</div>