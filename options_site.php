<!DOCTYPE html>
<?php
include('directives/session.php');
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

		<!-- Breadcrumbs -->
		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">
			<ol class="breadcrumb text-left">
				<li>
					<a href="options.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Options</a>
				</li>
				<?php
					$pendingEmp = "SELECT * FROM site WHERE active = 'pending'";
					$pendingQuery = mysql_query($pendingEmp);
					$heading = "";
					while($display = mysql_fetch_assoc($pendingQuery))
					{
						if($heading != "")
						{
							$heading .= ", ";
						}
						$heading .= $display['location'];
					}
				?>
				<li class="active">Moving employees from <?php Print $heading?> to new site</li>
			</ol>
		</div>

		<!-- Table of vacant employees-->
		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<table class="table table-bordered">
				<thead>
				<tr>
					<td>Employee ID</td>
					<td>Name</td>
					<td>Position</td>
					<td>Previous Site</td>
					<td>New Site</td>
				</tr>
				</thead>
				<tbody>
					<?php

					$pendingEmp = "SELECT * FROM site WHERE active = 'pending'";
					$pendingQuery = mysql_query($pendingEmp);
					if(mysql_num_rows($pendingQuery) > 0)//Check if there is a pending Site
					{
						while($row = mysql_fetch_assoc($pendingQuery))
						{
							$empSite = $row['location'];
							$employee = "SELECT * FROM employee WHERE site = '$empSite'";
							$employeeQuery = mysql_query($employee);
							while($empArr = mysql_fetch_assoc($employeeQuery))
							{
								Print "
										<tr>
											<td>".$empArr['empid']."</td>
											<td>".$empArr['lastname'].", ".$empArr['firstname']."</td>
											<td>".$empArr['position']."</td>
											<td>".$empArr['site']."</td>
											<td><select name='site[]' class='form-control'>
									";
								$site = "SELECT * FROM site WHERE active = '1'";
								$site = mysql_query($site);
								while($siteArr = mysql_fetch_assoc($site))
								{
									Print 	"
												  <option value='".$siteArr['location']."'>".$siteArr['location']."</option>
											";
										
								}
								Print "			</select>
											</td>
										</tr>";	
							}
						}
					}
					else
					{
						Print "<center><h2>No employees to move</h2></center>";
					}
					?>
					

				</tbody>
			</table>
		</div>
	 	
	 	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
		<script rel="javascript" src="js/jquery.min.js"></script>
		<script rel="javascript" src="js/bootstrap.min.js"></script>
		<script>
			document.getElementById("adminOptions").setAttribute("style", "background-color: #10621e;");
		</script>
	 	
	 </div>
	</body>
</html>
