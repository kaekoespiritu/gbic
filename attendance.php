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
	<link href="css/multiple-select.css" rel="stylesheet"/>
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
			<div class="col-md-4 col-md-pull-2">
				<select multiple="multiple" class="text-left">
					<?php
					$site = "SELECT location FROM site";
					$site_query = mysql_query($site);
					while($row_site = mysql_fetch_assoc($site_query))
					{
						Print '<option name="selectedSite[]" value="'. $row_site['location'] .'"> '. $row_site['location'] .'</option>';
					}
					?>
				</select>
			</div>
			<button class="btn btn-success col-md-2 col-md-pull-3">Print site attendance sheet</button>
		</div>
	</div>

<!-- TODO: Sites to have max characters of 12 -->
	<div class="container">
		<h3>Sites</h3>

		<div class="col-md-9 col-md-offset-2">
		<?php
		
			$counter = 0;

			$site_box = "SELECT location FROM site";
			$site_box_query = mysql_query($site_box);
			while($row = mysql_fetch_assoc($site_box_query))
			{
				
				if($counter == 0)
				{
					Print '<div class="row">';
				}
				
				$site_num = $row['location'];
				$num_employee = "SELECT * FROM employee WHERE site = '$site_num'";
				$employee_query = mysql_query($num_employee);
				$employee_num = 0;

				if($employee_query)
				{
					$employee_num = mysql_num_rows($employee_query);
				}

				Print '	<div class="col-md-2 sitebox">
							<a href="enterattendance.php"><h4>'. $row['location'] .'<br><br>Employees: '. $employee_num .'</h4></a>
						</div>';
				$counter++;
				if($counter == 5)
				{
					Print '</div>';	
					$counter = 0;
				}
				
			}
			?>
		</div>
	</div>


<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
	</script>
	<script rel="javascript" src="js/dropdown.js"></script>
	<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
	<script src="js/multiple-select.js"></script>
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

		$("select").multipleSelect({
			placeholder: "Select site for attendance&#9662;",
			selectAll: false,
			width: 200,
			multiple: true,
			multipleWidth: 200
		});
	</script>
</body>
</html>
