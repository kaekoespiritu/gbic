<!DOCTYPE html>
<?php
error_reporting(0);
include('directives/session.php');
include_once('directives/db.php');
include("pagination/employees_function.php");//For pagination
if(!isset($_GET['site']) && !isset($_GET['position']))
{
	header("location:employees.php?site=null&position=null");
}
$site_page = $_GET['site'];
$position_page = $_GET['position'];
$statement = "";
$search = "";
if(isset($_GET['search']))
{
	if($_GET['search'] != "" || $_GET['search'] != null)
	{
		$search = $_GET['search'];
	}
}
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">

	<!-- For pagination -->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />

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
		<div class="row">
				<div class="col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1 pull-down">
					<div class="form-group">
						<input type="text" placeholder="Search then press Enter" id="search_box" name="txt_search" onkeyup="enter(event)" class="form-control">
					</div>
				</div>
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-7 col-lg-7 pull-down text-right">
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
					<!-- END OF POSITION DROPDOWN -->
					<!-- SITES DROPDOWN -->
					<div class="btn-group">
						<select class="form-control" id="site" onchange="site()">
							<option hidden>Site</option>
							<?php
							$site = "SELECT location FROM site WHERE active = '1'";
							$site_query = mysql_query($site);

							while($row_site = mysql_fetch_assoc($site_query))
							{
								$siteReplaced = str_replace('/+/', ' ', $_GET['site']);
								if(mysql_real_escape_string($row_site['location']) == $siteReplaced)
								{
									Print '<option value="'. mysql_real_escape_string($row_site['location']) .'" selected="selected">'. mysql_real_escape_string($row_site['location']) .'</option>';
								}
								else
								{
									Print '<option value="'. mysql_real_escape_string($row_site['location']) .'">'. mysql_real_escape_string($row_site['location']) .'</option>';
								}
							}
							?>
						</select>
					</div>
					<!-- END OF SITES DROPDOWN -->
					<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filters</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee">Add new Employee</button>
				</div>
				<!-- ACTION BUTTONS FOR FILTERS -->
				<!-- END OF ACTION BUTTONS FOR FILTERS-->
				</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row">
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
				<table class="table table-bordered table-condensed" style="background-color:white;">
					<tr>
						<th class='fixedWidth text-center'>Employee ID</th>
						<th class='text-center'>Name</th>
						<th class='text-center'>Position</th>
						<th class='text-center'>Site</th>
						<th class='text-center'>Actions</th>
					</tr>

					<div id="change_table">
						<?php
						$emp_query = "SELECT * FROM employee ORDER BY site";
						$emp_display = mysql_query($emp_query);
//--------Search
						//Print "<script>alert('search')</script>";
						if($_GET['search'] != "" || $_GET['search'] != null)
						{
							$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
					    	$limit = 20; //if you want to dispaly 10 records per page then you have to change here
					    	$startpoint = ($page * $limit) - $limit;
					        $statement = "employee WHERE 
											(empid LIKE '%$search%' OR 
											firstname LIKE '%$search%' OR 
											lastname LIKE '%$search%' OR
											position LIKE '%$search%' OR
											site LIKE '%$search%') AND employment_status = '1' ORDER BY lastname ASC, firstname ASC";

							$res=mysql_query("select empid, firstname, lastname, position, site from {$statement} LIMIT {$startpoint} , {$limit}");
							while($emp_row=mysql_fetch_array($res))
							{
								Print "	<tr>
										<td style='vertical-align: inherit'>".$emp_row['empid']."</td>
										<td style='vertical-align: inherit' align='left'>".$emp_row ['lastname'].", ".$emp_row['firstname']."</td>
										<td style='vertical-align: inherit'>".$emp_row['position']."</td>
										<td style='vertical-align: inherit'>".$emp_row['site']."</td>
										<td>
											<button type='button' class='btn btn-default' onclick='Edit(\"".$emp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
										</td>
									</tr>";
							}
						}
//--------site		
						else if($_GET['site'] != "null")
						{
							$siteReplaced = str_replace('/+/', ' ', $site_page);
							if($_GET['position'] != "null")
							{
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
						    	$limit = 20; //if you want to dispaly 10 records per page then you have to change here
						    	$startpoint = ($page * $limit) - $limit;
						        $statement = "employee WHERE position = '$positionReplaced' AND site = '$siteReplaced' AND employment_status = '1' ORDER BY lastname ASC, firstname ASC";

								$res=mysql_query("select * from {$statement} LIMIT {$startpoint} , {$limit}");
								while($emp_row=mysql_fetch_array($res))
								{
									Print "	<tr>
											<td style='vertical-align: inherit'>".$emp_row['empid']."</td>
											<td style='vertical-align: inherit' align='left'>".$emp_row ['lastname'].", ".$emp_row['firstname']."</td>
											<td style='vertical-align: inherit'>".$emp_row['position']."</td>
											<td style='vertical-align: inherit'>".$emp_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$emp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}
							}
							else
							{
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
						    	$limit = 20; //if you want to dispaly 10 records per page then you have to change here
						    	$startpoint = ($page * $limit) - $limit;
						        $statement = "employee WHERE site = '$siteReplaced' AND employment_status = '1' ORDER BY lastname ASC, firstname ASC";

								$res=mysql_query("select * from {$statement} LIMIT {$startpoint} , {$limit}");
								while($emp_row=mysql_fetch_array($res))
								{
									Print "	<tr>
											<td style='vertical-align: inherit'>".$emp_row['empid']."</td>
											<td style='vertical-align: inherit' align='left'>".$emp_row ['lastname'].", ".$emp_row['firstname']."</td>
											<td style='vertical-align: inherit'>".$emp_row['position']."</td>
											<td style='vertical-align: inherit'>".$emp_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$emp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}

							}

						} 

	//--------position						
						else if($_GET['position'] != "null")
						{
							//Print "<script>alert('yow')</script>";
							$positionReplaced = str_replace('/+/', ' ', $position_page);
							if($_GET['site'] != "null")
							{
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
						    	$limit = 20; //if you want to dispaly 10 records per page then you have to change here
						    	$startpoint = ($page * $limit) - $limit;
						        $statement = "employee WHERE position = '$positionReplaced' AND site = '$siteReplaced' AND employment_status = '1' ORDER BY lastname ASC, firstname ASC";

								$res=mysql_query("select * from {$statement} LIMIT {$startpoint} , {$limit}");
								while($emp_row=mysql_fetch_array($res))
								{
									Print "	<tr>
											<td style='vertical-align: inherit'>".$emp_row['empid']."</td>
											<td style='vertical-align: inherit' align='left'>".$emp_row ['lastname'].", ".$emp_row['firstname']."</td>
											<td style='vertical-align: inherit'>".$emp_row['position']."</td>
											<td style='vertical-align: inherit'>".$emp_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$emp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}
							}

							else
							{
								$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
						    	$limit = 20; //if you want to dispaly 10 records per page then you have to change here
						    	$startpoint = ($page * $limit) - $limit;
						        $statement = "employee WHERE position = '$positionReplaced' AND employment_status = '1' ORDER BY lastname ASC, firstname ASC";

								$res=mysql_query("select * from {$statement} LIMIT {$startpoint} , {$limit}");
								while($emp_row=mysql_fetch_array($res))
								{
									Print "	<tr>
											<td style='vertical-align: inherit'>".$emp_row['empid']."</td>
											<td style='vertical-align: inherit' align='left'>".$emp_row ['lastname'].", ".$emp_row['firstname']."</td>
											<td style='vertical-align: inherit'>".$emp_row['position']."</td>
											<td style='vertical-align: inherit'>".$emp_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$emp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}
							}
						}
	//-------default
						else
						{
							//Print "<script>alert('default')</script>";
							$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
					    	$limit = 20; //if you want to dispaly 10 records per page then you have to change here
					    	$startpoint = ($page * $limit) - $limit;
					        $statement = "employee WHERE employment_status = '1' ORDER BY lastname ASC, firstname ASC";

							$res=mysql_query("select * from {$statement} LIMIT {$startpoint} , {$limit}");
							while($emp_row=mysql_fetch_array($res))
							{
								Print "	<tr>
										<td style='vertical-align: inherit'>".$emp_row['empid']."</td>
										<td style='vertical-align: inherit' align='left'>".$emp_row ['lastname'].", ".$emp_row['firstname']."</td>
										<td style='vertical-align: inherit'>".$emp_row['position']."</td>
										<td style='vertical-align: inherit'>".$emp_row['site']."</td>
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
		<?php
			echo "<div id='pagingg' >";
			if($statement && $limit && $page && $site_page && $position_page)
				echo pagination($statement,$limit,$page, $site_page, $position_page, $search);
			echo "</div>";
		?>
	</div>

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script rel="javascript" src="js/employees.js"></script>

<script>
	/* SEARCHING DATABASE VIA ENTER KEYPRESS */
	function enter(e) {
		if(e.keyCode == 13 || e.which == 13) 
		{
			var value = document.getElementById('search_box').value;
			window.location.assign("employees.php?site=<?php Print $site_page?>&position=<?php Print $position_page?>&search="+value);
		}
	}
	/* VIEW/EDIT EMPLOYEE DETAILS */
	function Edit(id) {

		window.location.assign("editEmployee.php?empid="+id);
	}

	/* COSMETIC CHANGES */
	document.getElementById("employees").setAttribute("style", "background-color: #10621e;");
</script>

</body>
</html>
