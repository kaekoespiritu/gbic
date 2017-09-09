<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
if(isset($_GET['site']) && isset($_GET['position']))
{}
else
{
	header("location:loans.php?site=null&position=null");
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
				<li><a href="employees.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a></li>
				<li class="active">Loan Applications</li>
			</ol>
			</div>
				<div class="col-md-4 col-md-offset-1">
					<div class="">
						<form method="post" action="" id="search_form">
							<input type="text" class="form-control" name="search" placeholder="Search" onkeypress="enter(enter)"">
						</form>
					</div>
				</div>
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-5 col-md-pull-1 text-right">
					Filter by:
					<div class="btn-group">
						<select class="form-control" id="position" onchange="position()">
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
						<select class="form-control" id="site" onchange="site()">
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
				</div>
				<div class="col-md-1 col-md-pull-1 text-right">
					<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filters</button>
					<button class="btn btn-primary" onclick="saveChanges()">Save changes</button>
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
						<td colspan="3">Loans</td>
					</tr>
					<?php
						if(isset($_POST['search']))
						{
							$find = $_POST['search'];
							$employee = "SELECT * FROM employee WHERE 
											empid LIKE '%$find%' OR 
											firstname LIKE '%$find%' OR 
											lastname LIKE '%$find%' OR
											position LIKE '%$find%' OR
											site LIKE '%$find%' ORDER BY site, empid DESC";
						}
						else if($_GET['position'] != "null")
						{
							$position = $_GET['position'];
							if($_GET['site'] != "null")
							{
								$site = $_GET['site'];
								$employee = "SELECT * FROM employee WHERE site = '$site' AND position = '$position' ORDER BY site, empid DESC";
							}
							else
							{
								$employee = "SELECT * FROM employee WHERE position = '$position' ORDER BY site, empid DESC";
							}
						}
						else if($_GET['site'] != "null")
						{
							$site = $_GET['site'];
							if($_GET['position'] != "null")
							{
								$position = $_GET['position'];
								$employee = "SELECT * FROM employee WHERE site = '$site' AND position = '$position' ORDER BY site, empid DESC";
							}
							else
							{
								$employee = "SELECT * FROM employee WHERE site = '$site' ORDER BY site, empid DESC";
							}
						}
						else
						{
							$employee = "SELECT * FROM employee ORDER BY site, empid DESC";
						}
						
						$empQuery = mysql_query($employee);

						while($row = mysql_fetch_assoc($empQuery))
						{
							Print "	<tr>
										<td>". $row['empid'] ."</td>
										<td>". $row['lastname'] .", ". $row['firstname'] ."</td>
										<td>". $row['position'] ."</td>
										<td>". $row['site'] ."</td>
										<td>
											<input type='checkbox' onchange='triggerSSS()'/> 
												SSS 
											<input type='text' id='sss' name='sss[]' disabled='disabled'/>
										</td>
										<td>
											<input type='checkbox' onchange='triggerPagIBIG()'/> 
												Pag-IBIG 
											<input type='text' id='philhealth' name='philhealth[]' disabled='disabled'/>
										</td>
										<td>
											<input type='checkbox' onchange='triggerVale()'/> 
												Vale 
											<input type='text' id='pagibig' name='pagibig[]' disabled='disabled'/>
										</td>
									</tr>";
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
	// Prompt to save changes
	function saveChanges()
	{
		confirm("Note: After saving these changes, the loans you've entered will no longer be editable. Are you sure you want to save changes?");
	}
	// SITE FILTER 
	function site() {
		if(document.URL.match(/site=([0-9]+)/))
		{
			var arr = document.URL.match(/site=([0-9]+)/)
			var siteUrl = arr[1];
			if(siteUrl)
			{
				localStorage.setItem("counter", 0);
			}
			else if(localStorage.getItem('counter') > 2)
			{
				localStorage.clear();
			}
		}
		var site = document.getElementById("site").value;
		var siteReplaced = site.replace(/\s/g , "+");
		localStorage.setItem("glob_site", siteReplaced);
		window.location.assign("loans.php?site="+siteReplaced+"&position="+localStorage.getItem('glob_position'));
	}

	// POSITION FILTER 
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
		window.location.assign("loans.php?site="+localStorage.getItem("glob_site")+"&position="+positionReplaced);
	}
	// Setting active color of menu to Employees
	document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
	function clearFilter() {
		localStorage.clear();
		window.location.assign("loans.php?site=null&position=null");
	}
	function enter(e) {
		if (e.keyCode == 13) {
		document.getElementById('search_form').submit();
		}
	}
	// Triggering input fields onchange of checkbox
	function triggerSSS()
	{

	}

	function triggerPagIBIG()
	{

	}

	function triggerVale()
	{

	}
	</script>
</body>
</html>
<!--
      changeMonth: true,
      changeYear: true