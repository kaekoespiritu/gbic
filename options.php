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

			<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Change opening and closing payroll</h3>
				</div>

				<!-- Week table with checkbox and dropdown -->
				<form method="post" action="logic_options_payroll.php">
					<table class="table" style="margin-bottom: 10px">
						<tr>
							<td>Open</td>
							<td>Close</td>
						</tr>
						<tr>
							<td>
								<select class="form-control" onchange="setPayroll()" id="open">
									<option value="Monday">Monday</option>
									<option value="Tuesday">Tuesday</option>
									<option value="Wednesday">Wednesday</option>
									<option value="Thursday">Thursday</option>
									<option value="Friday">Friday</option>
									<option value="Saturday">Saturday</option>
									<option value="Sunday">Sunday</option>
								</select>
							</td>
							<td>
								<select class="form-control" onchange="setPayroll()" id="close">
									<option disabled selected>Choose a day</option>
									<option value="Monday">Monday</option>
									<option value="Tuesday">Tuesday</option>
									<option value="Wednesday">Wednesday</option>
									<option value="Thursday">Thursday</option>
									<option value="Friday">Friday</option>
									<option value="Saturday">Saturday</option>
									<option value="Sunday">Sunday</option>
								</select>
							</td>
						</tr>
					</table>
					<button class="btn btn-primary">Save changes</button>
				</form>
				</div>
			</div>

		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Manage accounts</h3>
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<div class="alert alert-success" role="alert">
							You're logged in as<br>
							<?php Print $adminName ?><br>
							Role: <span class="mediumtext"><?php Print $adminRole ?></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<button type="button" class="btn btn-default col-md-12" data-toggle="modal" data-target="#accountOptions"><span class="glyphicon glyphicon-user"></span> Options</button>
						</div>
						<!-- Only visible on admin side -->
						<div class="col-md-12">
							<button type="button" class="btn btn-default col-md-12" data-toggle="modal" data-target="#newAccount"><span class="glyphicon glyphicon-plus"></span> Add new account</button>
						</div>
						<!-- Only visible on admin side -->
						<div class="col-md-12">
							<button type="button" class="btn btn-default col-md-12" data-toggle="modal" data-target="#manageAccount"><span class="glyphicon glyphicon-cog"></span> Manage employees</button>
						</div>
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




















