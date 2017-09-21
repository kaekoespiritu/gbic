<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
if(!isset($_GET['site']) && !isset($_GET['site']) && !isset($_GET['site']))
{
	header("Location: applications.php?site=null&position=null&status=null");
}
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li><a href="employees.php?site=null&position=null" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a></li>
					<li class="active">Absence Notifications</li>
				</ol>
			</div>
			<div class="col-md-4 col-md-offset-1">
				<div class="input-group">
					<input type="text" class="form-control">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
					</span>
				</div>
			</div>
			<!-- FILTER EMPLOYEE BY POSITION -->
			<div class="col-md-6 text-right">
				Filter by:
				<div class="btn-group">
					<select class="form-control">
						<option hidden>Position</option>
						<?php
							$position = "SELECT position FROM job_position";
							$position_query = mysql_query($position);

							while($row_position = mysql_fetch_assoc($position_query))
							{
								$positionReplaced = str_replace('/+/', ' ', $_GET['position']);
								$position = mysql_real_escape_string($row_position['position']);
								if($position == $positionReplaced)
								{
									Print '<option value="'. $position .'" selected="selected">'. $position .'</option>';
								}
								else
								{
									Print '<option value="'. $position .'">'. $position .'</option>';
								}
							}
						?>
					</select>
				</div>
				<div class="btn-group">
					<select class="form-control">
						<option hidden>Site</option>
						<?php
							$site = "SELECT location FROM site";
							$site_query = mysql_query($site);

							while($row_site = mysql_fetch_assoc($site_query))
							{
								$siteReplaced = str_replace('/+/', ' ', $_GET['site']);
								if($row_site['location'] == $siteReplaced)
								{
									Print '<option value="'. $row_site['location'] .'" selected="selected">'. $row_site['location'] .'</option>';
								}
								else
								{
									Print '<option value="'. $row_site['location'] .'">'. $row_site['location'] .'</option>';
								}
							}
							?>
					</select>
				</div>
				<div class="btn-group">
					<select class="form-control">
						<option hidden>Status</option>
						<option value="Pending Approval">Pending Approval</option>
						<option value="Absence Approved">Absence Approved</option>
					</select>
				</div>
			</div>
		</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row pull-down">
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-bordered table-condensed" style="background-color:white;">
					<tr>
						<td>ID</td>
						<td>Name</td>
						<td>Position</td>
						<td>Site</td>
						<td>Days absent</td>
						<td>Status</td>
						<td>Actions</td>
					</tr>
					<?php
						$awol = "SELECT * FROM awol_employees";
						$awolQuery = mysql_query($awol);

						while($row = mysql_fetch_assoc($awolQuery))
						{
							$empid = $row['empid'];
							$employee = "SELECT * FROM employee WHERE empid = '$empid'";
							$employeeQuery = mysql_query($employee);
							$empInfo = mysql_fetch_assoc($employeeQuery);
							Print "
										<tr>
											
											<td>
												".$empid."
											</td>
											<td>"
												.$empInfo['lastname'].", ".$empInfo['firstname'].
										   "</td>
											<td>"
												.$empInfo['position'].
										   "</td>
											<td>"
												.$empInfo['site'].
										   "</td>
											<td>
												7
											</td>
											<td>"
												.$row['status'].
										   "</td>
											<td>
												<button class='btn btn-default' onclick='next(\"".$empid."\")'>
													Check details
												</button>
												<input type='hidden' name='empid' value='".$empid."'>
											</td>
										</tr>
									";
						}
					?>
				</table>
			</div>	
		</div>


	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		function saveChanges(){
			confirm("Note: After saving these changes, the loans you've entered will no longer be editable. Are you sure you want to save changes?");
		}
		document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
		function next(id){
			//alert(id);
			window.location.assign("applications_next.php?id="+id);
		}
	</script>
</body>
</html>
<!--
      changeMonth: true,
      changeYear: true