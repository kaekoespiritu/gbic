<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company id: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: Quicksand;">

	<div class="container-fluid">

		<!-- Navigation bar -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- Open/Close payroll options-->
		<div class="col-md-10 col-md-offset-1 pull-down">

			<div class="panel panel-default">
				<a data-toggle="collapse" href="#collapseChangePayroll">
					<div class="panel-heading">
						<h3 class="panel-title">Change opening and closing payroll</h3>
					</div>
				</a>
			
			<!-- Week table with checkbox and dropdown -->
				<div id="collapseChangePayroll" class="panel-collapse collapse">
					<form method="post" action="logic_options_payroll.php">
						<table class="table">
							<tr>
								<td>Monday</td>
								<td>Tuesday</td>
								<td>Wednesday</td>
								<td>Thursday</td>
								<td>Friday</td>
								<td>Saturday</td>
								<td>Sunday</td>
							</tr>
							<tr>
								<?php
								$payroll = "SELECT * FROM payroll_day";
								$payrollQuery = mysql_query($payroll);
								$payrollRow = mysql_fetch_assoc($payrollQuery);
								?>
								<td>
									<?php
										if(($payrollRow['open'] == 'Monday') || ($payrollRow['close'] == 'Monday'))
										{
											Print '<select id="Monday" class="form-control" name="dropdown" onchange="swap(\'Monday\')">';
										}
										else
										{
											Print '<select id="Monday" class="form-control" name="dropdown" onchange="swap(\'Monday\')" disabled>';
										}
									?>
										<option value="" disabled selected>--</option>
										<?php
										if($payrollRow['open'] == 'Monday')
										{
											Print '	<option value="open" class="open" selected>Open</option>
													<option value="close" class="close">Close</option>';
										}
										else if(($payrollRow['close'] == 'Monday'))
										{
											Print '	<option value="open" class="open" >Open</option>
													<option value="close" class="close" selected>Close</option>';
										}
										else
										{
											Print '	<option value="open" class="open" >Open</option>
													<option value="close" class="close">Close</option>';
										}
										?>
									</select>
								</td>
								<td>
									<?php
										if(($payrollRow['open'] == 'Tuesday') || ($payrollRow['close'] == 'Tuesday'))
										{
											Print '<select id="Tuesday" class="form-control" name="dropdown" onchange="swap(\'Tuesday\')">';
										}
										else
										{
											Print '<select id="Tuesday" class="form-control" name="dropdown" onchange="swap(\'Tuesday\')" disabled>';
										}
									?>
									
										<option value="" disabled selected>--</option>
										<?php
										if($payrollRow['open'] == 'Tuesday')
										{
											Print '	<option value="open" class="open" selected>Open</option>
												<option value="close" class="close">Close</option>';
										}
										
										else if(($payrollRow['close'] == 'Tuesday')) 
										{
											Print '	<option value="open" class="open">Open</option>
												<option value="close" class="close" selected>Close</option>';
										}
										else
										{
											Print '	<option value="open" class="open" >Open</option>
													<option value="close" class="close">Close</option>';
										}
										?>
									</select>
								</td>
								<td>
									<?php
										if(($payrollRow['open'] == 'Wednesday') || ($payrollRow['close'] == 'Wednesday'))
										{
											Print '<select id="Wednesday" class="form-control" name="dropdown" onchange="swap(\'Wednesday\')">';
										}
										else
										{
											Print '<select id="Wednesday" class="form-control" name="dropdown" onchange="swap(\'Wednesday\')" disabled>';
										}

									?>
										<option value="" disabled selected>--</option>
										<?php
										if($payrollRow['open'] == 'Wednesday')
										{
											Print '	<option value="open" class="open" selected>Open</option>
												<option value="close" class="close">Close</option>';
											}
										else if(($payrollRow['close'] == 'Wednesday'))
										{
											Print '	<option value="open" class="open" >Open</option>
												<option value="close" class="close" selected>Close</option>';
										}
										else
										{
											Print '	<option value="open" class="open" >Open</option>
													<option value="close" class="close">Close</option>';
										}
										
										?>
									</select>
								</td>
								<td>
									<?php
										if(($payrollRow['open'] == 'Thursday') || ($payrollRow['close'] == 'Thursday'))
										{
											Print '<select id="Thursday" class="form-control" name="dropdown" onchange="swap(\'Thursday\')">';
										}
										else
										{
											Print '<select id="Thursday" class="form-control" name="dropdown" onchange="swap(\'Thursday\')" disabled>';
										}
									?>
										<option value="" disabled selected>--</option>
										<?php
										if($payrollRow['open'] == 'Thursday')
										{
											Print '	<option value="open" class="open" selected>Open</option>
												<option value="close" class="close">Close</option>';
										}
										else if(($payrollRow['close'] == 'Thursday'))
										{
											Print '	<option value="open" class="open" >Open</option>
												<option value="close" class="close" selected>Close</option>';
										}
										else
										{
											Print '	<option value="open" class="open" >Open</option>
													<option value="close" class="close">Close</option>';
										}
										?>
									</select>
								</td>
								<td>
									<?php
										if(($payrollRow['open'] == 'Friday') || ($payrollRow['close'] == 'Friday'))
										{
											Print '<select id="Friday" class="form-control" name="dropdown" onchange="swap(\'Friday\')">';
										}
										else
										{
											Print '<select id="Friday" class="form-control" name="dropdown" onchange="swap(\'Friday\')" disabled>';
										}
									?>
										<option value="" disabled selected>--</option>
										<?php
										if($payrollRow['open'] == 'Friday')
										{
											Print '	<option value="open" class="open" selected>Open</option>
												<option value="close" class="close">Close</option>';
										}
										else if(($payrollRow['close'] == 'Friday'))
										{
											Print '	<option value="open" class="open" >Open</option>
												<option value="close" class="close" selected>Close</option>';
										}
										else
										{
											Print '	<option value="open" class="open" >Open</option>
													<option value="close" class="close">Close</option>';
										}
										?>
									</select>
								</td>
								<td>
									<?php
										if(($payrollRow['open'] == 'Saturday') || ($payrollRow['close'] == 'Saturday'))
										{
											Print '<select id="Saturday" class="form-control" name="dropdown" onchange="swap(\'Saturday\')">';
										}
										else
										{
											Print '<select id="Saturday" class="form-control" name="dropdown" onchange="swap(\'Saturday\')" disabled>';
										}
									?>
										<option value="" disabled selected>--</option>
										<?php
										if($payrollRow['open'] == 'Saturday')
										{
											Print '	<option value="open" class="open" selected>Open</option>
												<option value="close" class="close">Close</option>';
										}
										else if(($payrollRow['close'] == 'Saturday'))
										{
											Print '	<option value="open" class="open" >Open</option>
												<option value="close" class="close" selected>Close</option>';
										}
										else
										{
											Print '	<option value="open" class="open" >Open</option>
													<option value="close" class="close">Close</option>';
										}
										?>
									</select>
								</td>
								<td>
									<?php
										if(($payrollRow['open'] == 'Sunday') || ($payrollRow['close'] == 'Sunday'))
										{
											Print '<select id="Sunday" class="form-control" name="dropdown" onchange="swap(\'Sunday\')">';
										}
										else
										{
											Print '<select id="Sunday" class="form-control" name="dropdown" onchange="swap(\'Sunday\')" disabled>';
										}
									?>
										<option value="" disabled selected>--</option>
										<?php
										if($payrollRow['open'] == 'Sunday')
										{
											Print '	<option value="open" class="open" selected>Open</option>
												<option value="close" class="close">Close</option>';
										}
										else if(($payrollRow['close'] == 'Sunday'))
										{
											Print '	<option value="open" class="open" >Open</option>
												<option value="close" class="close" selected>Close</option>';
										}
										else
										{
											Print '	<option value="open" class="open" >Open</option>
													<option value="close" class="close">Close</option>';
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<?php
								//Monday Checkbox
								if($payrollRow['open'] == 'Monday' || $payrollRow['close'] == 'Monday')
								{
									Print '	<td>
												<input type="checkBOX" name="checkboxes" id="MondayBOX" onchange="triggerInput(\'Monday\')" checked>
											</td>';
								}
								else
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="MondayBOX" onchange="triggerInput(\'Monday\')" disabled>
											</td>';
								}
								//Tuesday Checkbox
								if($payrollRow['open'] == 'Tuesday' || $payrollRow['close'] == 'Tuesday')
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="TuesdayBOX" onchange="triggerInput(\'Tuesday\')" checked>
											</td>';
								}	
								else
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="TuesdayBOX" onchange="triggerInput(\'Tuesday\')" disabled>
											</td>';
								}
								//Wednesday Checkbox
								if($payrollRow['open'] == 'Wednesday' || $payrollRow['close'] == 'Wednesday')
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="WednesdayBOX" onchange="triggerInput(\'Wednesday\')" checked>
											</td>';
								}	
								else
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="WednesdayBOX" onchange="triggerInput(\'Wednesday\')" disabled>
											</td>';
								}
								//Thursday Checkbox
								if($payrollRow['open'] == 'Thursday' || $payrollRow['close'] == 'Thursday')	
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="ThursdayBOX" onchange="triggerInput(\'Thursday\')" checked>
											</td>';
								}
								else
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="ThursdayBOX" onchange="triggerInput(\'Thursday\')" disabled>
											</td>';
								}
								//Friday Checkbox
								if($payrollRow['open'] == 'Friday' || $payrollRow['close'] == 'Friday')	
								{
									Print '		
											<td>
												<input type="checkBOX" name="checkboxes" id="FridayBOX" onchange="triggerInput(\'Friday\')" checked>
											</td>';
								}
								else
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="FridayBOX" onchange="triggerInput(\'Friday\')" disabled>
											</td>';
								}
								//Saturday Checkbox
								if($payrollRow['open'] == 'Saturday' || $payrollRow['close'] == 'Saturday')	
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="SaturdayBOX" onchange="triggerInput(\'Saturday\')" checked>
											</td>';
								}
								else
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="SaturdayBOX" onchange="triggerInput(\'Saturday\')" disabled>
											</td>';
								}
								//Sunday Checkbox
								if($payrollRow['open'] == 'Sunday' || $payrollRow['close'] == 'Sunday')
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="SundayBOX" onchange="triggerInput(\'Sunday\')" checked>
											</td>';
								}	
								else
								{
									Print '	
											<td>
												<input type="checkBOX" name="checkboxes" id="SundayBOX" onchange="triggerInput(\'Sunday\')" disabled>
											</td>';
								}
								?>
								
							</tr>
							
						</table>

						<!-- Save changes button -->
						<div class="panel-body">
							<form method="post" action="logic_options_payroll.php">
								<!-- hidden inputs for database use -->
								<input type="hidden" name="openPayroll" id="open" value="<?php Print $payrollRow['open']?>">
								<input type="hidden" name="closePayroll" id="close" value="<?php Print $payrollRow['close']?>">
								<input type="submit" name="payrolldaySubmit" class="btn btn-primary">
							</form>
						</div>
					</form>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Manage accounts</h3>
				</div>
				<div class="panel-body">
					<div class="col-md-12">
						<div class="alert alert-success col-md-6 col-md-offset-3" role="alert">
							NAME, you're an <span class="mediumtext">admin/employee.</span>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-4 pull-right">
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#accountOptions"><span class="glyphicon glyphicon-user"></span> Options for this account</button>
						</div>
						<!-- Only visible on admin side -->
						<div class="col-md-4">
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#newAccount"><span class="glyphicon glyphicon-plus"></span> Add new admin/employee account</button>
						</div>
						<!-- Only visible on admin side -->
						<div class="col-md-4">
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#manageAccount"><span class="glyphicon glyphicon-cog"></span> Manage employees account</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="accountOptions" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-11">
							<h4 class="modal-title">Options for this account</h4>
						</div>
						<div class="col-md-1">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-5">
							<ul class="nav nav-pills nav-stacked">
								<li class="active"><a href="#changepass" data-toggle="tab">Change Password</a></li>
								<li><a href="#changeuser" data-toggle="tab">Change Username</a></li>
								<li><a href="#securityq" data-toggle="tab">Configure security questions</a></li>
							</ul>
							</div>
							<div class="col-md-7 text-left" style="border-left-style: solid;">
								<form class="form-inline">
								<div class="tab-content">
									<div id="changepass" class="tab-pane active">
									<label>Old password:
										<input type="text" class="form-control">
									</label>
									<label>New password:
										<input type="text" class="form-control">
									</label>
									<label>Confirm password:
										<input type="text" class="form-control">
									</label>
									</div>
									<div id="changeuser" class="tab-pane">
										<h4>Current username: Username</h4>
									<label>New username:
										<input type="text" class="form-control">
									</label>
									</div>
									<div id="securityq" class="tab-pane">
									<label>
										Security Question:
										<select class="form-control">
											<option>City were you born in?</option>
											<option>Province were you born in?</option>
											<option>Name of the street you grew up in?</option>
											<option>Your childhood hero?</option>
											<option>Name of your elementary school?</option>
											<option>Name of your first pet?</option>
										</select>
									</label>
									<label>
										Answer:
										<input type="text" class="form-control">
									</label>
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary">Save Changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="newAccount" role="dialog">
			<div class="modal-dialog" id='modalsize'>
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-12">
							<h4 class="modal-title">Add new account</h4>
						</div>
					</div>
					<div class="modal-body">
						<div class="row">
						<form class="form-inline text-left">
						<div class="col-md-6" id='modalcol'>
							<label class="col-md-12">
								Username:
								<input type="text" class="form-control">
							</label>
							<label class="col-md-12">
								Password:
								<input type="text" class="form-control">
							</label>
							<label class="col-md-12">
								Confirm Password:
								<input type="text" class="form-control">
							</label>
							<label class="col-md-12">
								Security Question:
								<select class="form-control">
									<option>City were you born in?</option>
									<option>Province were you born in?</option>
									<option>Name of the street you grew up in?</option>
									<option>Your childhood hero?</option>
									<option>Name of your elementary school?</option>
									<option>Name of your first pet?</option>
								</select>
							</label>
							<label class="col-md-12">
								Answer:
								<input type="text" class="form-control">
							</label>
							<div class="col-md-12">
								Choose account role:
								<div class="radio">
									<label>
										<input type="radio" name="account" value="Employee" checked onchange="hideRestrictions()">
										Employee
									</label>
									<label>
										<input type="radio" name="account" value="Employee" onchange="hideRestrictions()" id='adminradio'>
										Administrator
									</label>
								</div>
							</div>
						</div>
						<!-- To appear only when employee is selected -->
							<div class="col-md-6" id='restrictions' style="display:block">
								<h4>Restrictions</h4>
								<ul class="list-unstyled">
									<li>
										<label>
											<input type="checkbox">
											View Payroll
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Manage AWOL employees
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Adding of employees
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Editing details of employees
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Managing site movement
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Adding of sites
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Adding of positions
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Viewing or reports
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Managing loans application
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Changing open/close payroll
										</label>
									</li>
								</ul>
							</div>

						</form>
					</div>
					<div class="modal-footer pull-down">
						<button class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button class="btn btn-primary">Save Changes</button>
					</div>
					</div>
				</div>
			</div>
		</div>

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
						<div class="row" style="overflow:scroll; height:300px">
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-body">
									<h4>JustineDiza</h4>
									<button class="btn btn-default" data-toggle="modal" data-target="#setRestrictions">Set Restrictions</button>
									<button class="btn btn-danger" onclick="removeAccount()">Remove Account</button>
									<button class="btn btn-warning" data-toggle="modal" data-target="#resetPass">Reset Password</button>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-body">
									<h4>KarloEspiritu</h4>
									<button class="btn btn-default" data-toggle="modal" data-target="#setRestrictions">Set Restrictions</button>
									<button class="btn btn-danger" onclick="removeAccount()">Remove Account</button>
									<button class="btn btn-warning" data-toggle="modal" data-target="#resetPass">Reset Password</button>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-body">
									<h4>OliviaEscartin</h4>
									<button class="btn btn-default" data-toggle="modal" data-target="#setRestrictions">Set Restrictions</button>
									<button class="btn btn-danger" onclick="removeAccount()">Remove Account</button>
									<button class="btn btn-warning" data-toggle="modal" data-target="#resetPass">Reset Password</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Reset Pass -->
		<div class="modal fade" id="resetPass" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-10">
							<h4 class="modal-title">Reset password</h4>
						</div>
						<div class="col-md-1">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<label>
									New password
									<input type="text" placeholder="SOMETHING" class="form-control" readonly>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- Set Restrictions -->
		<div class="modal fade" id="setRestrictions" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-10">
							<h4 class="modal-title">Set Restrictions</h4>
						</div>
						<div class="col-md-1">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<ul class="list-unstyled text-left">
									<li>
										<label>
											<input type="checkbox">
											View Payroll
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Manage AWOL employees
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Adding of employees
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Editing details of employees
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Managing site movement
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Adding of sites
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Adding of positions
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Viewing or reports
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Managing loans application
										</label>
									</li>
									<li>
										<label>
											<input type="checkbox">
											Changing open/close payroll
										</label>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary">Save Changes</button>
					</div>
				</div>
			</div>
		</div>


		<div class="col-md-10 col-md-offset-1">
			<div class="col-md-6">

				<!-- Site management -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Site management</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-5">
							<a data-target="#addSite" data-toggle="modal" class="btn btn-primary col-md-12 pull-down">ADD SITE</a>
							<a class="btn btn-danger col-md-12 pull-down" onclick="siteRemove()">END CONTRACT</a>
							<a class="btn btn-warning col-md-12 pull-down" data-target="#colaSettings" data-toggle="modal">SETTINGS FOR COLA</a>
						</div>

						<div class="col-md-7 text-left">
							<div class="sitelist">
								<form id="siteForm" method="post" action="logic_options_removeSite.php">
									<?php 
									$site = "SELECT * FROM site WHERE active = '1'";
									$siteQuery = mysql_query($site);
									
									while($siteRow = mysql_fetch_assoc($siteQuery))
									{
										Print '	<div class="alignlist">
												  <label>
												    <input type="checkbox" name="site[]" value="'.$siteRow['location'].'">
												    '.$siteRow['location'].'
												  </label>
												</div>';
									}
									?>
								</form>							
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- MODAL for adding site-->
			<div class="modal fade" role="dialog" id="addSite">
			  <div class="modal-dialog modal-sm" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11">Add new site</h4>
				        <button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <!-- form for adding SITES -->
				    <form method="POST" action="logic_options_addSite.php">
					    <div class="modal-body">
				     		<input type="text" class="form-control" name="site_name" placeholder="Name of new site">
				     	</div>
				     	<div class="modal-footer">
				     		<div class="col-md-5">
				     			<input type="number" placeholder="COLA" class="form-control input-sm">
				     		</div>
					        <button type="submit" class="btn btn-primary">Save changes</button>
					    </div>
				  	</form>
			    </div>
			  </div>
			</div>

			<!-- MODAL for COLA settings-->
			<div class="modal fade" role="dialog" id="colaSettings">
			  <div class="modal-dialog modal-sm" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11">Manage COLA settings</h4>
				        <button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <!-- Form for COLA-->
				    <form method="post" action="logic_options_cola.php" id="colaForm">
					    <div class="modal-body">
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
						</div>
				     	<div class="modal-footer">
					        <button type="submit" class="btn btn-primary">Save changes</button>
				    	</div>
					</form>
			    </div>
			  </div>
			</div>


			<!-- MODAL for adding a position-->
			<div class="modal fade bs-example-modal-sm" role="dialog" id="addPosition">
			  <div class="modal-dialog modal-sm" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-11">Add new position</h4>
				        <button type="button" class="close col-md-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <!-- form for adding POSITIONS -->
				    <form method="POST" action="logic_options_addPosition.php">
					    <div class="modal-body">
				     		<input type="text" name="position_name" class="form-control" placeholder="Name of new position">
				     	</div>
			     	
			     		<div class="modal-footer">
				        <button type="submit" class="btn btn-primary">Save changes</button>
				      	</div>
				  	</form>
			    </div>
			  </div>
			</div>

			<!-- Position Management -->
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Position management</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-5">
							<a data-target="#addPosition" data-toggle="modal" class="btn btn-primary col-md-12 pull-down">ADD POSITION</a>
						</div>

						<div class="col-md-7 text-left">
							<div class="sitelist">
								<form id="positionForm" method="post" action="logic_options_removePosition.php">
									<?php 
									$position = "SELECT * FROM job_position WHERE active = '1'";
									$positionQuery = mysql_query($position);
									while($positionRow = mysql_fetch_assoc($positionQuery))
									{
										Print '	<div class="alignlist">
												  <label>
												    '.$positionRow['position'].'
												  </label>
												</div>';
									}
									?>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		// Changes color of active tab to admin options
		document.getElementById("adminOptions").setAttribute("style", "background-color: #10621e;");

		// Save function to database
		function save()
		{
			confirm("Are you sure you want to save this new open/close payroll schedule?");
		}


		// Checkbox to trigger dropdown
		function triggerInput(dayOfWeek)
		{
			//alert('yow');
			var checkbox = document.getElementsByName('checkboxes'), i;
			var checkboxlength = document.querySelectorAll('input[type=checkbox]').length;
			var changeDefault = document.getElementById(dayOfWeek);

			// Disabled dropdown when checkbox is deselected
			if(document.getElementById(dayOfWeek+"BOX").checked==false)
			{
				var cellUNCHECK = document.getElementById(dayOfWeek);
				cellUNCHECK.setAttribute('disabled', '');

				if(changeDefault.options[2].hasAttribute('selected'))
				{
					changeDefault.options[2].removeAttribute('selected');
					changeDefault.options[0].setAttribute('selected','');
					document.getElementById('close').value = "";//set hidden text to the day
				}
					
				else if(changeDefault.options[1].hasAttribute('selected'))
				{
					changeDefault.options[1].removeAttribute('selected');
					changeDefault.options[0].setAttribute('selected','');
					document.getElementById('open').value = "";//set hidden text to the day
				}
			}

			// Enable dropdown when checkbox is selected
			else if(document.getElementById(dayOfWeek+"BOX").checked==true)
			{
				var cellCHECK = document.getElementById(dayOfWeek);
				cellCHECK.removeAttribute('disabled');
				cellCHECK.options[0].removeAttribute('selected');
				cellCHECK.options[1].setAttribute('selected','');
				if(document.getElementById('open').value == "") 
				{
					document.getElementById('open').value = dayOfWeek;//set hidden text to the day
				}
				else
				{
					document.getElementById('close').value = dayOfWeek;//set hidden text to the day
				}
				
			}

			// Checking if 2 checkboxes are active
			if(document.querySelectorAll('input[type=checkbox]:checked').length === 2)
			{
				changeDefault.options[2].setAttribute('selected','');
				document.getElementById('close').value = dayOfWeek;//set hidden text to the day
				
				 for(i = 0; i <= checkboxlength; i++)
				 {

				 	if(!checkbox[i].checked)
				 	{
				 		checkbox[i].setAttribute('disabled', 'disabled');	
				 	}

				 	if(checkbox[i].checked==true)
				 	{
				 		console.log(changeDefault);
				 	}
				 }
			} 
			
			// Checking if only 1 checkbox is active
			if(document.querySelectorAll('input[type=checkbox]:checked').length === 1)
			{		
				for(var i = 0; i <= checkboxlength; i++)
				{
				    if(!checkbox[i].checked)
				    {
				    	checkbox[i].removeAttribute('disabled');
				    	document.getElementById(dayOfWeek).options[0].selected = true;
				    }   
				}
			}
		}

		// Swapping elements after selecting a different dropdown option
		function swap(chosenDay) {
			var day = ['Monday', 'Tuesday','Wednesday','Thursday','Friday','Saturday'];		
			var first = "";	

			for(var a = 0; a < 7; a++)
			{
				
				if(chosenDay != day[a])
				{
					if(document.getElementById(day[a]).disabled == false)
					{	
						//alert(chosenDay +" "+ day[a]);
						if(document.getElementById(day[a]).options[1].selected == true) // OPEN
						{
							document.getElementById(day[a]).options[2].selected = true; // CLOSE
							document.getElementById(day[a]).options[1].selected = false;
							document.getElementById(chosenDay).options[1].selected = true;
							document.getElementById('close').value = day[a];//set hidden text to the day
							document.getElementById('open').value = chosenDay;//set hidden text to the day
							//alert('yeah');

						}
						else if(document.getElementById(day[a]).options[2].selected == true)
						{
							document.getElementById(day[a]).options[1].selected = true; // CLOSE
							document.getElementById(day[a]).options[2].selected = false;
							document.getElementById(chosenDay).options[2].selected = true;
							document.getElementById('open').value = day[a];//set hidden text to the day
							document.getElementById('close').value = chosenDay;//set hidden text to the day
							//alert('yeah');
						}
					}
				}
			}
		}

		function siteRemove(){
			var a = confirm("Are you sure you want to Archive these Site(s)?")
			if(a)
			{
				document.getElementById('siteForm').submit();
			}
		}
		

		function hideRestrictions() {
			var admin = document.getElementById('adminradio');
			
			// To change when admin is selected
			var checkboxlength = document.querySelectorAll('input[type="checkbox"]').length;
			var checkbox = document.querySelectorAll('input[type="checkbox"]');

			// If admin is selected, hide restrictions pane and set width to full
			if(admin.checked == true)
			{
				
				for(var i = 0; i < checkboxlength; i++){
					checkbox[i].disabled = true;
				}

			}
			else
			{
				// If previously selected admin revert changes
				for(var i = 0; i < checkboxlength; i++){
					checkbox[i].disabled = false;
				}
			}
		}

		function removeAccount(){
			confirm("Are you sure you want to remove this employee account?");
		}


	</script>
</div>
</body>
</html>