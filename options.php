<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');

$user = $_SESSION['user_logged_in'];

$admin = "SELECT * FROM administrator WHERE username = '$user'";
$adminQuery = mysql_query($admin);
$adminArr = mysql_fetch_assoc($adminQuery);


$adminName = $adminArr['firstname']." ".$adminArr['lastname'];
$adminRole = $adminArr['role'];
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

		<!-- MODALS -->
		<?php
		require_once("directives/modals/accountOptions.php");
		require_once("directives/modals/newAccount.php");
		require_once("directives/modals/manageAccount.php");
		require_once("directives/modals/resetPass.php");
		require_once("directives/modals/setRestrictions.php");
		require_once("directives/modals/addSite.php");
		require_once("directives/modals/colaSettings.php");
		require_once("directives/modals/modifyCola.php");
		require_once("directives/modals/addCola.php");
		require_once("directives/modals/addPosition.php");
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
				<?php Print $adminName ?>, you're an <span class="mediumtext"><?php Print $adminRole ?>.</span>
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

<!-- Hidden form for removing users -->
<div id="hiddenFormDiv"></div>


<div class="col-md-10 col-md-offset-1">
	<div class="col-md-6">

		<!-- Site management -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Site management</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-5">
					<a data-target="#addSite" data-toggle="modal" class="btn btn-success col-md-12 pull-down">ADD SITE</a>
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

	<!-- Position Management -->
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Position management</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-5">
					<a data-target="#addPosition" data-toggle="modal" class="btn btn-success col-md-12 pull-down">ADD POSITION</a>
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
<script rel="javascript" src="js/options.js"></script>
<script>
	function addColaSubmit() {
			document.getElementById('addColaForm').submit();
	}

	function modColaSubmit() {
			document.getElementById('modColaForm').submit();
	}

	function modifyCola(val) {
		var site = 'mod'+val;
		var loc = document.getElementById(site).value;
		document.getElementById('modifyColaInput').value = loc;
	}

	function removeSiteCola(val){
		document.getElementById('colaToRemove').value = val;
	}

	function colaRemove() {
		if(document.getElementById('colaToRemove').value != "")
		{
			var site = document.getElementById('colaToRemove').value;
			window.location.assign('logic_options_removeCola.php?site='+site);
		}
	}

	function usernameValidation(val) {
		$.ajax({
			url:"fetch_validation_username.php",
			method:"POST",
			data:{
					username: val
				},
			success:function(data)
			{
	    		if(data == "has-success"){
	    			$('#usernameVal').removeClass("has-warning");
    				$('#usernameVal').addClass(data);
    				if($('#errorPrompt').length)
    					$('#errorPrompt').remove();
    				$('#addAccountSubmit').prop("disabled", false);
	    		}
	    			
    			else{
    				$('#usernameVal').removeClass("has-success");
    				$('#usernameVal').addClass(data);
    				if($('#errorPrompt').length == 0)
    					$('#usernameVal').append("<b style='color:red' id='errorPrompt'><br>*Invalid Username</b>");
    				$('#addAccountSubmit').prop("disabled", true);
    			}
    				
    			console.log(data);
			}
		});
	}

	function updateUsernameValidation(val) {
		$.ajax({
			url:"fetch_validation_username.php",
			method:"POST",
			data:{
					username: val
				},
			success:function(data)
			{
	    		if(data == "has-success"){
	    			$('#currUsername').removeClass("has-warning");
    				$('#currUsername').addClass(data);
    				if($('#errorPrompt').length)
    					$('#errorPrompt').remove();
    				$('#accountOptionsSubmit').prop("disabled", false);
	    		}
	    			
    			else{
    				$('#currUsername').removeClass("has-success");
    				$('#currUsername').addClass(data);
    				if($('#errorPrompt').length == 0)
    					$('#currUsername').append("<b style='color:red' id='errorPrompt'>*Invalid Username</b>");
    				$('#accountOptionsSubmit').prop("disabled", true);
    			}
    				
    			console.log(data);
			}
		});
	}
</script>

</div>
</body>
</html>




















