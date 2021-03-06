<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');
$user = $_SESSION['user_logged_in'];
$admin = "SELECT * FROM administrator WHERE username = '$user'";
$adminQuery = mysql_query($admin) or die(mysql_error());
$adminArr = mysql_fetch_assoc($adminQuery);
$adminName = $adminArr['firstname']." ".$adminArr['lastname'];
$adminRole = $adminArr['role'];
?>
<html>
<head>
	<title>Payroll</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: Quicksand;">

	<div class="container-fluid">

		<!-- Navigation bar -->
		<?php
		require_once("directives/nav.php");
		
		// <!-- MODALS -->
		require_once("directives/modals/accountOptions.php");
		require_once("directives/modals/newAccount.php");
		require_once("directives/modals/manageAccount.php");
		require_once("directives/modals/resetPass.php");
		require_once("directives/modals/secureChanges.php");
		require_once("directives/modals/setRestrictions.php");
		require_once("directives/modals/addSite.php");
		require_once("directives/modals/modifyCola.php");
		require_once("directives/modals/addCola.php");
		require_once("directives/modals/addPosition.php");
		require_once("directives/modals/removePosition.php");
		require_once("directives/modals/addBank.php");
		?>

		<!-- Open/Close payroll options-->
		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">

			<div class="col-md-6 col-lg-6 <?php Print $openNclosingPayroll ?>">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Change opening and closing payroll</h3>
				</div>

				<!-- Week table with checkbox and dropdown -->
				
					<table class="table" style="margin-bottom: 10px">
						<tr>
							<td>Open</td>
							<td>Close</td>
						</tr>
						<tr>
							<?php
								//For the opening payroll
								$payrollDay = "SELECT * FROM payroll_day";
								$payrollQuery = mysql_query($payrollDay) or die(mysql_error());

								$payrollArr = mysql_fetch_assoc($payrollQuery);
								$openingPayroll = $payrollArr['open'];
								$closingPayroll = $payrollArr['close'];
								$WeekDays = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");

							?>
							<td>
								<?php
								//For the system to know what day is the default
									Print "<select class='form-control' name='openPayroll' id='open'>";
									
									foreach($WeekDays as $dayOfWeek)
									{
										if($openingPayroll == $dayOfWeek)
											Print "<option value='".$dayOfWeek."' selected>".$dayOfWeek."</option>";
										else
											Print "<option value='".$dayOfWeek."'>".$dayOfWeek."</option>";
									}
									?>
								</select>
							</td>
							<td>
								<?php
								//For the system to know what day is the default
									Print "<select class='form-control' name='closePayroll' id='close'>";
									
									foreach($WeekDays as $daysOfWeek)
									{
										if($closingPayroll == $daysOfWeek)
											Print "<option value='".$daysOfWeek."' selected>".$daysOfWeek."</option>";
										else
											Print "<option value='".$daysOfWeek."'>".$daysOfWeek."</option>";
									}
									?>
								</select>
							</td>
						</tr>
					</table>
				<button class="btn btn-primary marginbottom" id="setPayrollDate">Save changes</button>
				</div>
			</div>

		<div class="col-md-6 col-lg-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Manage accounts</h3>
				</div>
				<div class="panel-body">
					<div class="col-md-6 col-lg-6">
						<div class="alert alert-success" role="alert">
							You're logged in as<br>
							<?php Print $adminName ?><br>
							Role: <span class="mediumtext"><?php Print $adminRole ?></span>
						</div>
					</div>
					<div class="col-md-6 col-lg-6">
						<div class="col-md-1 col-lg-12 margin-separator">
							<button type="button" class="btn btn-default col-md-1 col-lg-12" data-toggle="modal" data-target="#accountOptions"><span class="glyphicon glyphicon-user"></span> Options</button>
						</div>
						<!-- Only visible on admin side -->
						<div class="col-md-1 col-lg-12 margin-separator <?php Print $addNewAccountAdmin ?>">
							<button type="button" class="btn btn-default col-md-1 col-lg-12" data-toggle="modal" data-target="#newAccount"><span class="glyphicon glyphicon-plus"></span> Add new account</button>
						</div>
						<!-- Only visible on admin side -->
						<div class="col-md-1 col-lg-12 margin-separator <?php Print $manageEmployee ?>">
							<button type="button" class="btn btn-default col-md-1 col-lg-12" data-toggle="modal" data-target="#manageAccount"><span class="glyphicon glyphicon-cog"></span> Manage employees</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>

		<!-- Hidden form for removing users -->
		<div id="hiddenFormDiv"></div>


		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="col-md-6 col-lg-6 <?php Print $siteManagement?>">

				<!-- Site management -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Site management</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-5 col-lg-5">
							<a data-target="#addSite" data-toggle="modal" class="btn btn-success col-md-1 col-lg-12 pull-down">ADD SITE</a>
							<a class="btn btn-primary col-md-1 col-lg-12 pull-down" data-toggle="modal" data-target="#siteHistory">SITE HISTORY</a>
						</div>

						<div class="col-md-7 col-lg-7 text-left">
							<div class="sitelist">
								<form id="siteForm" method="post" action="logic_options_removeSite.php">
									<?php 
									$site = "SELECT * FROM site WHERE active = '1'";
									$siteQuery = mysql_query($site);
									while($siteRow = mysql_fetch_assoc($siteQuery))
									{
										Print '	<div class="alignlist">
										<label>
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
			<div class="col-md-6 col-lg-6 <?php Print $positionManagement?>">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Position management</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-5 col-lg-5">
							<a data-target="#addPosition" data-toggle="modal" class="btn btn-success col-md-1 col-lg-12 pull-down">ADD POSITION</a>
							<a data-target="#removePosition" data-toggle="modal" class="btn btn-danger col-md-1 col-lg-12 pull-down disabletotally" id="removePositionButton" onclick="removeSite()">REMOVE POSITION</a>
						</div>

						<div class="col-md-7 col-lg-7 text-left">
							<div class="sitelist">
								<form id="positionForm" method="post" action="logic_options_removePosition.php">
									<?php 
									$position = "SELECT * FROM job_position WHERE active = '1'";
									$positionQuery = mysql_query($position);
									while($positionRow = mysql_fetch_assoc($positionQuery))
									{
										Print '	<div class="alignlist">
										<label>
										<input type="checkbox" name="position[]" onclick="trackCheckbox()" value="'.$positionRow['position'].'">
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

			<!-- Bank Management -->
			<div class="col-md-6 col-lg-6 <?php Print $bankManagement?>">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Bank management</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-5 col-lg-5">
							<a data-target="#addBank" data-toggle="modal" class="btn btn-success col-md-1 col-lg-12 pull-down">ADD BANK</a>
							<a class="btn btn-danger col-md-1 col-lg-12 pull-down disabletotally" id="removeBankButton" onclick="removeBank()">REMOVE BANK</a>
						</div>

						<div class="col-md-7 col-lg-7 text-left">
							<div class="sitelist">
								<form id="bankForm" method="post" action="">
									<?php
									 $bank = 'SELECT * FROM banks ORDER BY name ASC';
									 $bankQuery = mysql_query($bank);
									 while($bankArr = mysql_fetch_assoc($bankQuery))
									 {
									 	Print '	<div class="alignlist">
									 				<input type="checkbox" name="bankCheck[]" onclick="removeBankCheckbox(this)" value="'.$bankArr['name'].'">
													<span style="background-color:#'.$bankArr['color'].'; width: 55px; display: inline-block; border: 10px solid #'.$bankArr['color'].';"></span> '.$bankArr['name'].'

												</div>';
									 }
									// $position = "SELECT * FROM job_position WHERE active = '1'";
									// $positionQuery = mysql_query($position);
									// while($positionRow = mysql_fetch_assoc($positionQuery))
									// {
									// 	Print '	<div class="alignlist">
									// 	<label>
									// 	<input type="checkbox" name="position[]" onclick="trackCheckbox()" value="'.$positionRow['position'].'">
									// 	'.$positionRow['position'].'
									// 	</label>
									// 	</div>';
									// }
									?>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Site History Modal -->
		<div class="modal fade bs-example-modal-sm" role="dialog" id="siteHistory">
			  <div class="modal-dialog" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-1 col-lg-11">View site history</h4>
				        <button type="button" class="close col-md-1 col-lg-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <div class="modal-body">
				    	<ul class="nav nav-tabs">
							<li class="active"><a href="#onGoing" data-toggle="tab">On-going site</a></li>
							<li><a href="#siteArchive" data-toggle="tab">Site archive</a></li>
						</ul>
						<div class="tab-content">
							<div id="onGoing" class="tab-pane active">
								<table class="table table-bordered">
						    		<tr>
						    			<td>Site Name</td>
						    			<td>Start Date</td>
						    			<td>End Date</td>
						    			<td>Action</td>
						    		</tr>
						    		<?php
						    		$siteHist = "SELECT * FROM site ORDER BY end ASC";
						    		$siteHistQuery = mysql_query($siteHist);
						    		while($siteHistArr = mysql_fetch_assoc($siteHistQuery))
						    		{
						    			if($siteHistArr['end'] == null) {
						    			Print "
						    				<tr>
								    			<td>".$siteHistArr['location']."</td>
								    			<td>".$siteHistArr['start']."</td>";
								    		Print 	"<td>On-going</td>
								    				<td><a class='btn btn-danger' onclick='siteRemove(\"".$siteHistArr['location']."\")'>END CONTRACT</a>
								    			</td>";
								    		}
						    		}
						    		?>
						    	</table>
							</div>
							<div id="siteArchive" class="tab-pane">
								<table class="table table-bordered">
						    		<tr>
						    			<td>Site Name</td>
						    			<td>Start Date</td>
						    			<td>End Date</td>
						    			<td>Action</td>
						    		</tr>
						    		<?php
						    		$siteHist = "SELECT * FROM site ORDER BY end ASC";
						    		$siteHistQuery = mysql_query($siteHist);
						    		while($siteHistArr = mysql_fetch_assoc($siteHistQuery))
						    		{
						    			if($siteHistArr['end'] != null) {
						    				Print "
						    				<tr>
								    			<td>".$siteHistArr['location']."</td>
								    			<td>".$siteHistArr['start']."</td>";
								    		Print 	"<td>".$siteHistArr['end']."</td>
								    				 <td>Contract Ended</td>
								    		</tr>";
						    			}
						    		}
						    		?>
						    	</table>
							</div>
						</div>
				    	<form method='POST' action='print_history_site.php'>
							<input type='submit' value='Print site history' class='btn btn-success pull-up'>
						</form>
				    </div>
			    </div>
			  </div>
			</div>


<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script rel="javascript" src="js/options.js"></script>
<script>
	function removeBankCheckbox(bank) {
		if(bank.checked == true)
			document.getElementById('removeBankButton').classList.remove('disabletotally');
		else
			document.getElementById('removeBankButton').classList.add('disabletotally');
	}

	function removeBank() {
		var bank = document.getElementsByName('bankCheck[]');
		var loopNum = bank.length;

		var removeBank = "";
		for(var counter = 0; counter < loopNum; counter++) {
			if(bank[counter].checked == true){
				if(removeBank != "") {
					removeBank += ",";
				}
				removeBank += bank[counter].value;

			}
		}

		var q = confirm('Are you sure you want to remove this bank from the list?');
		if(q) {
			window.location.assign('logic_options_removeBank.php?bank='+removeBank);
		}

	}

	function trackCheckbox(){
		var position = document.getElementsByName('position[]');
		var loopNum = position.length;

		var data = "";

		var bool = false;
		for(var counter = 0; counter < loopNum; counter++) {
			if(position[counter].checked == true){
				bool = true;//enable the remove position button
			}
		}
		if(bool)
			document.getElementById('removePositionButton').classList.remove("disabletotally");
		else
			document.getElementById('removePositionButton').classList.add("disabletotally");
	}

	function removeSite() {
		var position = document.getElementsByName('position[]');
		var loopNum = position.length;

		var positions = "";
		for(var counter = 0; counter < loopNum; counter++) {
			if(position[counter].checked == true){
				if(positions != "") {
					positions += "+";
				}
				positions += position[counter].value;

			}
		}

		$.ajax({
			url:"fetch_options_remove_position.php",
			method:"POST",
			data:{
				job: positions
			},
			success:function(data)
			{
				$('#removePositionDiv').html(data);
			}
		});

	}
	
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
			}
		});
	}

	function editRestrictions(user) {
		$.ajax({
			url:"fetch_edit_restrictions.php",
			method:"POST",
			data:{
				username: user
			},
			success:function(data)
			{
				 $('#restrictionsData').html(data);
			}
		});
	}

	$('#setPayrollDate').click(function() {
		var open = $('#open').val();
		var close = $('#close').val();

		function getDayIndex(day) {
			switch(day){
				case "Monday": output = 0; break;
				case "Tuesday": output = 1; break;
				case "Wednesday": output = 2; break;
				case "Thursday": output = 3; break;
				case "Friday": output = 4; break;
				case "Saturday": output = 5; break;
				case "Sunday": output = 6; break;
			}
			return output;
		}
		openIndex = getDayIndex(open);
		closeIndex = getDayIndex(close);
		
		var indexCheck = openIndex - closeIndex;
		if(Math.abs(indexCheck) == 1 || (openIndex == 0 && closeIndex == 6) || (openIndex == 6 && closeIndex == 0)){
			$('#openPay').val(open);
			$('#closePay').val(close);
			$('#secureChanges').modal('show');
			
		}
		else if(closeIndex == openIndex){
			alert("Error. Please select two different days.");
		}
		else if(Math.abs(indexCheck) != 1){
			alert("You have selected an invalid date range. Please select dates that are adjacent. Like Monday-Tuesday.");
		}
			
			
	});

	function submitPayroll() {
		document.getElementById('payrollForm').submit();
	}
	

			
			

</script>

</div>
</body>
</html>