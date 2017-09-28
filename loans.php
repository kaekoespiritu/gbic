<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
if(!isset($_GET['site']) && !isset($_GET['position']))
{
	header("location:loans.php?site=null&position=null");
}

$date = strftime("%B %d, %Y");
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

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li>
						<a href="employees.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a>
					</li>
					<li class="active">Loan Applications</li>
					<button class="btn btn-success pull-right" onclick="saveChanges()">Save changes</button>
				</ol>
			</div>

			<!-- Search bar -->
			<div class="col-md-3 col-md-offset-1">
				<div class="">
					<form method="post" action="" id="search_form">
						<input type="text" class="form-control" name="search" placeholder="Search" onkeypress="enter(enter)"">
					</form>
				</div>
			</div>


			<div class="col-md-7 text-right">
				Filter by:


				<!-- Filter by POSITION -->
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


				<!-- Filter by LOCATION -->
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

				<!-- Clear Filters button -->
				<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filters</button>
			</div>
		</div>


		<br>


		<!-- EMPLOYEE TABLE -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
			<?php 
			
			Print "<form class=form-inline' id='loansForm' method='post' action='logic_loans.php?site=".$_GET['site']."&position=".$_GET['position']."'>";
			
			?>

			<table class="table table-bordered table-condensed" style="background-color:white;">
				<tr>
					<td style='width:130px !important;'>ID</td>
					<td style='width:200px !important;'>Name</td>
					<td>Position</td>
					<td>Site</td>
					<td colspan="3">Loans</td>
				</tr>
				<?php
					if(isset($_POST['search'])) // Search output
					{
						$find = $_POST['search'];
						$employee = "SELECT * FROM employee WHERE 
										empid LIKE '%$find%' OR 
										firstname LIKE '%$find%' OR 
										lastname LIKE '%$find%' OR
										position LIKE '%$find%' OR
										site LIKE '%$find%' AND employment_status = '1' ORDER BY site, empid DESC";
					}
					else if($_GET['position'] != "null") // Position Filter Output
					{
						$position = $_GET['position'];
						if($_GET['site'] != "null")
						{
							$site = $_GET['site'];
							$employee = "SELECT * FROM employee WHERE site = '$site' AND position = '$position' AND employment_status = '1' ORDER BY site, empid DESC";
						}
						else
						{
							$employee = "SELECT * FROM employee WHERE position = '$position' AND employment_status = '1' ORDER BY site, empid DESC";
						}
					}
					else if($_GET['site'] != "null") // Site Filter Output
					{
						$site = $_GET['site'];
						if($_GET['position'] != "null")
						{
							$position = $_GET['position'];
							$employee = "SELECT * FROM employee WHERE site = '$site' AND position = '$position' AND employment_status = '1' ORDER BY site, empid DESC";
						}
						else
						{
							$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' ORDER BY site, empid DESC";
						}
					}
					else // Default output
					{
						//Print "<script>alert('yeah')</script>";
						$employee = "SELECT * FROM employee WHERE employment_status = '1' ORDER BY site, empid DESC";
					}
					
					
					$empQuery = mysql_query($employee);

					while($row = mysql_fetch_assoc($empQuery))
					{
						$empid = $row['empid'];
						$dateChecker = "SELECT * FROM loans WHERE empid = '$empid' ORDER BY date DESC LIMIT 1";
						$dateQuery = mysql_query($dateChecker);
						if($dateQuery)
						{
							$dateNum = mysql_num_rows($dateQuery);
						}
						else
						{
							$dateNum = 0;
						}
						
						if($dateNum != 0)
						{
							// $getSSS = "SELECT DISTINCT sss FROM loans WHERE empid = '$empid' AND sss IS NOT NULL ORDER BY date DESC LIMIT 2";
							// $getPAGIBIG = "SELECT DISTINCT pagibig FROM loans WHERE empid = '$empid' AND pagibig IS NOT NULL ORDER BY date DESC LIMIT 2";
							// $getVALE = "SELECT DISTINCT vale FROM loans WHERE empid = '$empid' AND vale IS NOT NULL ORDER BY date DESC LIMIT 2";

							$getSSS = "SELECT sss FROM loans WHERE empid = '$empid' AND sss IS NOT NULL ORDER BY date DESC";
							$getPAGIBIG = "SELECT pagibig FROM loans WHERE empid = '$empid' AND pagibig IS NOT NULL ORDER BY date DESC";
							$getVALE = "SELECT vale FROM loans WHERE empid = '$empid' AND vale IS NOT NULL ORDER BY date DESC";

							$sssQuery = mysql_query($getSSS);
							$pagibigQuery = mysql_query($getPAGIBIG);
							$valeQuery = mysql_query($getVALE);

							if($sssQuery)
							{
								while($sssLatest = mysql_fetch_assoc($sssQuery))
								{
									if($sssLatest['sss'] != NULL)
									{
										$sss = $sssLatest['sss'];
										break 1;
									}
									else
									{
										$sss = "";
									}
								}
							}
							else
							{
								$sss = "";
							}
							if($pagibigQuery)
							{
								while($pagibigLatest = mysql_fetch_assoc($pagibigQuery))
								{
									if($pagibigLatest['pagibig'] != NULL)
									{
										$pagibig = $pagibigLatest['pagibig'];
										break 1;
									}
									else
									{
										$pagibig = "";
									}
								}
							}
							else
							{
								$pagibig = "";
							}
							if($valeQuery)
							{
								while($valeLatest = mysql_fetch_assoc($valeQuery))
								{
									if($valeLatest['vale'] != NULL)
									{
										$vale = $valeLatest['vale'];
										break 1;
									}
									else
									{
										$vale = "";
									}
								}
							}
							else
							{
								$vale = "";
							}
							
							Print "	<tr class='warning'>
									<input type='hidden' name='empid[]' value='". $empid ."'>
									<td style='vertical-align: inherit'>
										". $row['empid'] ."
									</td>
									<td style='vertical-align: inherit'>
										". $row['lastname'] .", ". $row['firstname'] ."
									</td>
									<td style='vertical-align: inherit'>
										". $row['position'] ."
									</td>
									<td style='vertical-align: inherit'>
										". $row['site'] ."
									</td>
									<td style='vertical-align: inherit'>
										<div class='form-group'>
											<label for='sss'>SSS</label>
											<input type='text' id='sss' name='sss[]' placeholder='". $sss ."' onkeypress='numValidate(event)' onblur='colorChange(this)' class='form-control input-sm hasInput' />
										</div>
									</td>
									<td>
										<div class='form-group'>
											<label for='pagibig'>Pag-IBIG</label>
											<input type='text' id='pagibig' name='pagibig[]' placeholder='". $pagibig ."' onkeypress='numValidate(event)' onblur='colorChange(this)' class='form-control input-sm hasInput' />
										</div>
									</td>
									<td>
										<div class='form-group'>
											<label for='vale'>Vale</label>
											<input type='text' id='vale' name='vale[]' placeholder='". $vale ."' onkeypress='numValidate(event)' onblur='colorChange(this) class='form-control input-sm hasInput' />
										</div>
									</td>
								</tr>";
						}
						else
						{
							Print "	<tr>
									<input type='hidden' name='empid[]' value='". $empid ."'>
									<td style='vertical-align: inherit'>
										". $row['empid'] ."
									</td>
									<td style='vertical-align: inherit'>
										". $row['lastname'] .", ". $row['firstname'] ."
									</td>
									<td style='vertical-align: inherit'>
										". $row['position'] ."
									</td>
									<td style='vertical-align: inherit'>
										". $row['site'] ."
									</td>
									<td style='vertical-align: inherit'>
										<div class='form-group'>
											<label for='sss'>SSS</label>
											<input type='text' id='sss' name='sss[]' onkeypress='numValidate(event)' onblur='colorChange(this)' class='form-control input-sm'/>
										</div>
									</td>
									<td>
										<div class='form-group'>
											<label for='pagibig'>Pag-IBIG</label>
											<input type='text' id='pagibig' name='pagibig[]' onkeypress='numValidate(event)' onblur='colorChange(this)'class='form-control input-sm'/>
										</div>
									</td>
									<td>
										<div class='form-group'>
											<label for='vale'>Vale</label>
											<input type='text' id='vale' name='vale[]' onkeypress='numValidate(event)' onblur='colorChange(this)' class='form-control input-sm'/>
										</div>
									</td>
								</tr>";
						}
					}
				?>
			</table>
			</form>
		</div>	
	</div>
</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
// Regex for loan input fields
function numValidate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

// Changing row color when changes are made
function colorChange(element)
{
	var row = element.parentNode.parentNode.parentNode;
	
	// If there is input show color change
	if(element.value.length!==0)
	{
		if(row.hasAttribute("class"))
		{
			row.removeAttribute("class");
			row.setAttribute("class","success");	
		}
		else
		{
			row.setAttribute("class","success");	
		}
	}
	else // Else, remove color change
	{
		if(element.classList.contains("hasInput")==true)
		{
			row.setAttribute("class","warning");
		}
		else
		{
			row.removeAttribute("class");
		}
	}
}
// Prompt to save changes
function saveChanges()
{
	// var a = confirm("Note: After saving these changes, the loans you've entered will no longer be editable. Are you sure you want to save changes?");
	// if(a == true)
	// {
		document.getElementById('loansForm').submit();
	// }

}
// Site filter
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

// Position filter
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

// Clearing filters
function clearFilter() {
	localStorage.clear();
	window.location.assign("loans.php?site=null&position=null");
}

// Search bar
function enter(e) {
	if (e.keyCode == 13) {
	document.getElementById('search_form').submit();
	}
}
</script>
</body>
</html>