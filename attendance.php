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
			<button class="btn btn-success" onclick="printAll()">
				Print attendance sheet for all sites
			</button>
		</div>

	<!-- DROPDOWN checkbox for selected site -->
		<form method = "post" action = "print_selected_site.php">
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
			<input type="submit" value = "Print site attendance sheet" name="checkbox_submit" class="btn btn-success col-md-2 col-md-pull-3">
		</form>

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
			/* If location is long, font-size to smaller */
			if(strlen($row['location'])>=16)
			{
				Print '<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;"><div class="sitebox">
				<span class="smalltext">'. $row['location'] .'</span><br><br><span>Employees: '. $employee_num .'</span>
			</div></a>';
			}
			else
			{
				Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
							<div class="sitebox">
								<span class="autofit">'. $row['location'] .'<br><br>Employees: '. $employee_num .'</span>
							</div>
						</a>';
	}
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

	function fittext()
	{
		// Declare fixed div size
		var maxW = 132, maxH = 72, maxSize = 12;
		var c = document.getElementsByClassName("smalltext");
		var d = document.createElement("span");
		d.style.fontSize = maxSize + "px";

		for (var i = 0; i < c.length; i++)
		{
			d.innerHTML = c[i].innerHTML;
			document.body.appendChild(d);
			var w = d.offsetWidth;
			var h = d.offsetHeight;
			document.body.removeChild(d);
			var x = w > maxW ? maxW / w : 1;
			var y = h > maxH ? maxH / h : 1;
			var r = Math.min(x, y) * maxSize;
			c[i].style.fontSize = r + "px";
		}
	}
	function printAll()
	{
		window.location.assign("print_all_employee.php");
	}

	fittext();
</script>
</body>
</html>
