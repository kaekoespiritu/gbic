<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');

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
						<a href="loans_landing.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Loans Application</a>
					</li>
					<li class="active">Viewing loans for SSS/PagIBIG/Vale</li>
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

			<table class="table table-bordered table-condensed" style="background-color:white;">
				<tr>
					<td style='width:130px !important;'>ID</td>
					<td style='width:200px !important;'>Name</td>
					<td>Position</td>
					<td>Site</td>
					<td>Amount to be paid</td>
					<td>History</td>
				</tr>
				<tr>
					<input type='hidden' name='empid[]' value='". $empid ."'>
					<td style='vertical-align: inherit'>
						EMPID HERE
					</td>
					<td style='vertical-align: inherit'>
						Last Name, First Name
					</td>
					<td style='vertical-align: inherit'>
						Position
					</td>
					<td style='vertical-align: inherit'>
						Site
					</td>
					<td style='vertical-align: inherit'>
						AMOUNT TO BE PAID HERE
					</td>
					<td>
						<button class='btn btn-primary'><span class="glyphicon glyphicon-list-alt"></span> View</button>
					</td>
				</tr>
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