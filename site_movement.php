<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');

$location = mysql_real_escape_string($_GET['site']);

?>
<html>
	<head>
		<title>Payroll</title>
		<!-- Company Name: Green Built Industrial Corporation -->

		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body style="font-family: Quicksand;">
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
	 -->
	 <div class="container-fluid">

	 	<?php
			require_once("directives/nav.php");
		?>

		<!-- MODAL FOR VIEW SITE HISTORY -->
			<div class="modal fade" role="dialog" id="viewSiteHistory">
				<div class="modal-dialog">
					<div class="modal-content">
						<!-- Ajax Fetch modal -->
						<div id="fetchHistoryModal"></div>
					</div>
				</div>
			</div>

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li>
						<a href="site_landing.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a>
					</li>
					<li class="active">Moving employees to new site</li>
					<button class="btn btn-success pull-right" onclick="saveForm()">Save Changes</button>
				</ol>
			</div>
		</div>


		<!-- Table of vacant employees-->
		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="pull-left">
				<button type="button" data-target="#changeSite" data-toggle="modal" class="btn btn-default" id="siteButton">
					<span class="glyphicon glyphicon-arrow-down"></span> Change site for selected employees
				</button>
			</div>
			<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-4 col-lg-4 pull-right text-right">
					Filter by:
					<!-- POSITION DROPDOWN -->
					<div class="btn-group">
						<select class="form-control" id="position" onchange="position()">
							<option hidden>Position</option>
							<?php
							$position = "SELECT position FROM job_position WHERE active = '1'";
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

					<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filter</button>
				</div>

			<form method="post" action="logic_site_movement.php?s=<?php Print $location?>" id="siteMovementForm">
				<table class="table table-bordered pull-down-more">
					<thead>
					<tr>
						<td>Select</td>
						<td>Employee ID</td>
						<td>Name</td>
						<td>Position</td>
						<td>Current Site</td>
						<td>New Site</td>
						<td>View History</td>
					</tr>
					</thead>
					<tbody>
						<?php
						if($location != "pending")
						{
							if(isset($_GET['position']))
							{
								$pos = $_GET['position'];
								$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site='$location' AND position = '$pos' ORDER BY lastname ASC, firstname ASC";
							}
							else
							{
								$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site='$location' ORDER BY lastname ASC, firstname ASC";
							}
						}
						else//this is to display pending employees or idle employees with no site
						{
							$pendingSites = "SELECT * FROM site WHERE active = 'pending'";
							$pendingQuery = mysql_query($pendingSites);
							
							$initialQuery = "SELECT * FROM employee WHERE employment_status = '1' AND (site = ";

							$sites = "";//Store sites that are pending
							while($pendingArr = mysql_fetch_assoc($pendingQuery))
							{
								if($sites != "")
								{
									$sites .= " OR site = ";
								}
								$sites .= "'".mysql_real_escape_string($pendingArr['location'])."'";
							}

							if(isset($_GET['position']))
							{
								$pos = $_GET['position'];
								$employee = $initialQuery.$sites.") AND position = '$pos'";
							}
							else
							{
								$employee = $initialQuery.$sites.")";
							}
							
						}

						$empQuery = mysql_query($employee);
							
						$site = "SELECT * FROM site WHERE active = '1'";
						$siteQuery = mysql_query($site);
						
						$site_dropdown = "
											<select class='form-control' name='newSite[]' input-sm'>
											<option hidden value=''>Site</option>
										";//this will contain the available positions
						while($siteRow = mysql_fetch_assoc($siteQuery))
						{//for the site dropdown to save execution time
							if($siteRow['location'] != $location)
								$site_dropdown .= "<option value='".$siteRow['location']."'>".$siteRow['location']."</option>";
						}
						$site_dropdown .= "</select>";
						if(mysql_num_rows($empQuery) > 0)
						{
							while($row = mysql_fetch_assoc($empQuery))
							{
								Print "
								<tr>
									<input type='hidden' name='empid[]' value='".$row['empid']."'>
									<td>
										<input type='checkbox' name='chkbox_chosen[]' value=".$row['empid']." onclick='selectMany()''>
									</td>
									<td>".$row['empid']."</td>
									<td align='left'>".$row['lastname'].", ".$row['firstname']."</td>
									<td>".$row['position']."</td>
									<td>".$row['site']."</td>
									<td>
										".$site_dropdown."
									</td>
									<td><a class='btn btn-primary' data-toggle='modal' data-target='#viewSiteHistory' onclick='load_history(\"".$row['empid']."\")'><span class='glyphicon glyphicon-list'></span> History</a></td>
								</tr>
								";
							}
						}
						?>
					</tbody>
				</table>
			</form>
		</div>

		<!-- MODAL -->
			<div class="modal fade bs-example-modal-sm" role="dialog" id="changeSite">
			  <div class="modal-dialog modal-sm" role="document">
			  	<div class="modal-content">
				  	<div class="modal-header">
				  		<h4 class="modal-title col-md-1 col-lg-11">Transfer to new site</h4>
				        <button type="button" class="close col-md-1 col-lg-1" style="float:right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    </div>
				    <div class="modal-body">
			     	<select class="form-control input-sm" id="dd_groupChange" onchange="groupChange(this)">
			     		<option hidden>Site location</option>
						<?php
							$site = "SELECT * FROM site WHERE active = '1'";
							$siteQuery = mysql_query($site);
							
							while($siteRow = mysql_fetch_assoc($siteQuery))
							{
								$siteLocation = $siteRow['location'];
								$emp = "SELECT COUNT(empid) AS empNum FROM employee WHERE site = '$siteLocation' AND employment_status = '1'";
								$employeeQuery = mysql_query($emp);
								$empNum = mysql_fetch_assoc($employeeQuery);
								if($siteRow['location'] != $location)
									Print "<option value='".$siteRow['location']."'>".$siteRow['location']."[".$empNum['empNum']."]</option>";
							}
						?> 
					</select>
			     	</div>
			     	<div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary" onclick="saveFormGroup()">Save changes</button>
				      </div>
			    </div>
			  </div>
			</div>

			
	 	
	 	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
			document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
			window.onload = function(){
				document.getElementById('siteButton').setAttribute("disabled", true);
			}

			function selectMany() {
				var button = document.getElementById('siteButton');
				var checkboxes = document.querySelectorAll("input[type='checkbox']:checked").length;

				if(checkboxes >= 2)
				{
					document.getElementById('siteButton').removeAttribute('disabled');
				}
				else
				{
					if(document.getElementById('siteButton').hasAttribute('disabled')==false)
					document.getElementById('siteButton').setAttribute('disabled',true);
				}
			}

			// Position Filter 
			function position() {
				if(document.URL.match(/position=([0-9]+)/))
				{
					var arr = document.URL.match(/position=([0-9]+)/)
					var positionUrl = arr[1];
					if(positionUrl)
					{
						localStorage.setItem("counter", 0);
					}
					else if(localStorage.getItem('counter') > 2)
					{
						localStorage.clear();
					}
				}
				var position = document.getElementById("position").value;
				var positionReplaced = position.replace(/\s/g , "+");
				localStorage.setItem("glob_position", positionReplaced);
				window.location.assign("site_movement.php?position="+positionReplaced+"&site=<?php Print $location?>");
			}

			function saveFormGroup(){
				if(document.getElementById('dd_groupChange').value != "Site location")
				{
					var a = confirm("Are you sure you want to change the site of those employees?");
					if(a == true)
					{
						document.getElementById("siteMovementForm").submit();
					}
				}
				else
					alert("Please select a site.");
					
			}

			function saveForm(){
				var a = confirm("Are you sure you want to change the site of those employees?");
				if(a == true)
				{
					document.getElementById("siteMovementForm").submit();
				}
			}

			function groupChange(pos){
				var hidden = document.createElement("input");
				hidden.setAttribute("type", "hidden");
				hidden.setAttribute("value", pos.value);
				hidden.setAttribute("name", "groupChange");
				hidden.setAttribute("id", "groupModal");
				var form = document.getElementById('siteMovementForm');
				var checker = document.getElementById('groupModal');
				if(document.getElementById("siteMovementForm").contains(checker))
				{
					document.getElementById("groupModal").value =  pos.value;
				}
				else
				{
					form.appendChild(hidden);
				}
				
			}

			function clearFilter() {
				window.location.assign("site_movement.php?site=<?php Print $location?>");
			}
			$("#changeSite").on("hidden.bs.modal", function () {
				$("#groupModal").remove();
    		
			});

			function load_history(id)
			{
				$.ajax({
					url:"fetch_history_site.php",
					method:"POST",
					data:{
							empid: id
						},
					success:function(data)
					{
						$('#fetchHistoryModal').html(data);
					}
				});
			}
		</script>
	 	
	 </div>
	</body>
</html>


















