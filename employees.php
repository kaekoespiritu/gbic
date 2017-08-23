<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');

if(isset($_GET['site']) && isset($_GET['position']))
{}
else
{
	header("location:employees.php?site=null&position=null");
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
		<?php
		require_once('directives/modals/addEmployee.php');

		?>
		<div class="modal fade" id="editEmployee" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div id="fetch-modal">
					</div>
				</div>
			</div>
		</div>
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-md-offset-1 pull-down">
					<form method="post" action="" id="search_form">
						<div class="form-group">
							<input type="text" placeholder="Search" id="search_box" name="txt_search" onkeypress="enter(enter)" class="form-control">
						</div>
					</form>
				</div>
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-5 col-md-pull-1 pull-down text-right">
					Filter by:
					<!-- POSITION DROPDOWN -->
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
					<!-- END OF POSITION DROPDOWN -->
					<!-- SITES DROPDOWN -->
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
					<!-- END OF SITES DROPDOWN -->
				</div>
				<!-- ACTION BUTTONS FOR FILTERS -->
				<div class="col-md-3 pull-down col-md-pull-1">
					<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filters</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee">Add new Employee</button>
				</div>
				<!-- END OF ACTION BUTTONS FOR FILTERS-->
			</div>
		</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row pull-down">
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-bordered" style="background-color:white;">
					<tr>
						<td>Employee ID</td>
						<td>Name</td>
						<td>Position</td>
						<td>Site</td>
						<td>Actions</td>
					</tr>

					<div id="change_table">
						<?php
						$emp_query = "SELECT * FROM employee ORDER BY site";
						$emp_display = mysql_query($emp_query);
//--------Search
						if(isset($_POST['txt_search']))
						{
							$find = mysql_real_escape_string($_POST['txt_search']);
							$search = "SELECT empid, firstname, lastname, position, site FROM employee WHERE 
							empid LIKE '%$find%' OR 
							firstname LIKE '%$find%' OR 
							lastname LIKE '%$find%' OR
							position LIKE '%$find%' OR
							site LIKE '%$find%' ORDER BY position";
							$searchQuery = mysql_query($search);
							
							while($search_row = mysql_fetch_assoc($searchQuery))
							{
								Print "	<tr>
								<td>".$search_row['empid']."</td>
								<td>".$search_row['firstname']." ".$search_row['lastname']."</td>
								<td>".$search_row['position']."</td>
								<td>".$search_row['site']."</td>
								<td>
									<button type='button' class='btn btn-default' onclick='Edit(\"".$search_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
								</td>
							</tr>";
						}

					}
//--------site		

					else if($_GET['site'] != "null")
					{

						$site = $_GET['site'];
						$siteReplaced = str_replace('/+/', ' ', $site);
						if($_GET['position'] != "null")
						{

							$position = $_GET['position'];
							$positionReplaced = str_replace('/+/', ' ', $position);
							$pos_query = "SELECT * FROM employee WHERE position = '$positionReplaced' AND site = '$siteReplaced' ORDER BY position";
							$position_query = mysql_query($pos_query);
							while($PosEmp_row = mysql_fetch_assoc($position_query))
							{
								Print "	<tr>
								<td>".$PosEmp_row['empid']."</td>
								<td>".$PosEmp_row['firstname']." ".$PosEmp_row['lastname']."</td>
								<td>".$PosEmp_row['position']."</td>
								<td>".$PosEmp_row['site']."</td>
								<td>
									<button type='button' class='btn btn-default' onclick='Edit(\"".$PosEmp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
								</td>
							</tr>";
						}
					}
					else
					{

						$query = "SELECT * FROM employee WHERE site = '$siteReplaced' ORDER BY position";
						$site_query = mysql_query($query);
						while($site_row = mysql_fetch_assoc($site_query))
						{
							Print "	<tr>
							<td>".$site_row['empid']."</td>
							<td>".$site_row['firstname']." ".$site_row['lastname']."</td>
							<td>".$site_row['position']."</td>
							<td>".$site_row['site']."</td>
							<td>
								<button type='button' class='btn btn-default' onclick='Edit(\"".$site_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
							</td>
						</tr>";
					}
				}

			} 

//--------position						
			else if($_GET['position'] != "null")
			{

				$position = $_GET['position'];
				$positionReplaced = str_replace('/+/', ' ', $position);
				if($_GET['site'] != "null")
				{

					$site = $_GET['site'];
					$siteReplaced = str_replace('/+/', ' ', $site);
					$pos_query = "SELECT * FROM employee WHERE position = '$positionReplaced' AND site = '$siteReplaced' ORDER BY site";
					$position_query = mysql_query($pos_query);
					while($PosEmp_row = mysql_fetch_assoc($position_query))
					{
						Print "	<tr>
						<td>".$PosEmp_row['empid']."</td>
						<td>".$PosEmp_row['firstname']." ".$PosEmp_row['lastname']."</td>
						<td>".$PosEmp_row['position']."</td>
						<td>".$PosEmp_row['site']."</td>
						<td>
							<button type='button' class='btn btn-default' onclick='Edit(\"".$PosEmp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
						</td>
					</tr>";
				}
			}

			else
			{
				$query = "SELECT * FROM employee WHERE position = '$positionReplaced' ORDER BY site";
				$position_query = mysql_query($query);
				while($position_row = mysql_fetch_assoc($position_query))
				{
					Print "	<tr>
					<td>".$position_row['empid']."</td>
					<td>".$position_row['firstname']." ".$position_row['lastname']."</td>
					<td>".$position_row['position']."</td>
					<td>".$position_row['site']."</td>
					<td>
						<button type='button' class='btn btn-default' onclick='Edit(\"".$position_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
					</td>
				</tr>";
			}
		}
	}
//-------default
	else
	{

		while($emp_row = mysql_fetch_assoc($emp_display))
		{
			Print "	<tr>
			<td>".$emp_row['empid']."</td>
			<td>".$emp_row ['firstname']." ".$emp_row['lastname']."</td>
			<td>".$emp_row['position']."</td>
			<td>".$emp_row['site']."</td>
			<td>
				<button type='button' class='btn btn-default' onclick='Edit(\"".$emp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
			</td>
		</tr>";
	}
}
?>
</div>
</table>
</div>	
</div>


</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script rel="javascript" src="js/employees.js"></script>

<script>
	/* VIEW/EDIT EMPLOYEE DETAILS */
	function Edit(id) {

		window.location.assign("editEmployee.php?empid="+id);
	}

	/* COSMETIC CHANGES */
	document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
</script>

</body>
</html>
