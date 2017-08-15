<!DOCTYPE html>
<?php
include('directives/db.php');
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

	<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
			<div class="row pull-down">
			<h2>Daily attendance log<br><br></h2>
			<div class="col-md-5 col-md-offset-1">
			<button class="btn btn-success">Print attendance sheet for all sites</button>
			</div>
			<div class="col-md-4">
					Print only:
					<div class="btn-group">
						<select class="form-control">
							<option hidden>Site</option>
							<?php
								$site = "SELECT location FROM site";
								$site_query = mysql_query($site);
								while($row_site = mysql_fetch_assoc($site_query))
								{
									Print '<option value="'. $row_site['location'] .'">'. $row_site['location'] .'</option>';
								}
							?>
						</select>
					</div>
					<button class="btn btn-success">Print site attendance</button>
				</div>
			</div>
			</div>

			<!-- TODO: Sites to have max characters of 12 -->
			<div class="row">
				<h3>Sites</h3>
				<?php
					$counter = 0;

					Print "<script>alert('yeah1')</script>";
					$site_box = "SELECT location FROM site";
					$site_box_query = mysql_query($site_box);
					while($row = mysql_fetch_assoc($site_box_query))
					{
						
						if($counter == 0)
						{
							Print '<div class="col-md-8 col-md-offset-2">';
						}
						
						$site_num = $row['location'];
						$num_employee = "SELECT * FROM employee WHERE site = '$site_num'";
						$employee_query = mysql_query($num_employee);
						$employee_num = 0;

						if($employee_query)
						{
							$employee_num = mysql_num_rows($employee_query);
						}

						Print '<a href="enterattendance.php" class="btn btn-primary btn-lg" style="margin:2px">'. $row['location'] .'<br><br>Employees: '. $employee_num .'</a>';
						$counter++;
						if($counter == 5)
						{
							Print '</div>';	
							$counter = 0;
						}
						
					}
				?>
			
			</div>




<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
</script>
<script rel="javascript" src="js/dropdown.js"></script>
<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
<script>
	$(document).ready(function(){
		$('input.timein').timepicker({
			timeFormat: 'hh:mm p',
			dynamic: false,
			scrollbar: false,
			dropdown: false
		});
		$('input.timeout').timepicker({
			timeFormat: 'hh:mm p',
			dynamic: false,
			scrollbar: false,
			dropdown: false
		});
	});
</script>

</div>
</body>
</html>
