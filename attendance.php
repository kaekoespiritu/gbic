<!DOCTYPE html>
<?php
include('directives/db.php');
include('directives/session.php');
  date_default_timezone_set('Asia/Hong_Kong');
  if(isset($_SESSION['date']))
	{
		$date = $_SESSION['date'];
	}
	else
	{
		$date = strftime("%B %d, %Y");
	}
	$day = date('l', strtotime($date));
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
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
		<div class="row">
			<div class="col-md-4 col-md-offset-2" style="border-right: 1px solid black;">
				<h2 id='holidayTitle'><?php 
						if($day == "Sunday")
							Print "Sunday attendance log";
						else
							Print "Daily attendance log";
					?>
				</h2>
				<br>
				<div class="col-md-6 col-md-offset-3">
					<form>
					
						<input name="txt_attendance" type="text" size="10" class="form-control" value = <?
					if(isset($_SESSION['date']))
					{
						$date = $_SESSION['date'];
						Print "'". $date ."'";
					}
					else
					{
						$date = strftime("%B %d, %Y");
						Print '""';
					}
					?> id="dtpkr_attendance" placeholder="mm-dd-yyyy" required>
					<br>
					<div id="dynamicForm">
					<button id="holiday" class="btn btn-primary" onclick="Holiday(this)">Holiday?</button>
					<input type="hidden" id="holidayName">
					<input type="hidden" id="holidayType">
					</div>

					</form>
				</div>
			</div>
			<div class="col-md-4">
				<button class="btn btn-success col-md-pull-4" onclick="printAll()">
					Print attendance sheet for all sites
				</button>
				<h4><br>--- OR ---<br><br></h4>
				<!-- DROPDOWN checkbox for selected site -->
				<form method = "post" action = "print_selected_site.php">
					<div class="col-md-6">
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
					<div class="col-md-5">
						<input type="submit" value = "Print site" name="checkbox_submit" class="btn btn-success">
					</div>
				</form>
			</div>
		</div>
	</div>


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
<script src="js/jquery-ui.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
</script>
<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
<script src="js/multiple-select.js"></script>
<script>

	$(function(){
		$("select").multipleSelect({
			placeholder: "Select site for attendance&#9662;",
			selectAll: false,
			width: 200,
			multiple: true,
			multipleWidth: 200
		});

		//var currentDate = new Date();
		var currentDate = "<?php Print "$date"; ?>";
		/* DATE PICKER CONFIGURATIONS*/
		$( "#dtpkr_attendance" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'MM dd, yy',
			showAnim: 'blind',
			maxDate: new Date(),
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			}
		});

		$("#dtpkr_attendance").datepicker("setDate", currentDate);

	
		$("#dtpkr_attendance").change(function(){
			var date = $(this).val();
			window.location.href = "date_attendance.php?date="+date;
		//Ajax
			// $.get("ajax/attendance_date.php", 	{
			// 									date: date // date to be passed on attendance_date.php
			// 								}, 
			// 								function(data)	{
   // 												//alert("data sent and received: "+date);
			// 								});
		});
		
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

	function Holiday(element)
	{
		/** CREATING THE FORM **/
		// Instantiating fields
		var nameOfHoliday = document.createElement("input");
			nameOfHoliday.setAttribute("type", "text");
			nameOfHoliday.setAttribute("class", "form-control");
			nameOfHoliday.setAttribute("placeholder", "Enter holiday name");
			nameOfHoliday.setAttribute("onkeypress", "txtHoliday(event)");
			nameOfHoliday.setAttribute("id", "nameOfHoliday");

		var cancelButton = document.createElement("button");
			cancelButton.setAttribute("class", "btn btn-danger btn-sm pull-down");
			cancelButton.setAttribute("id", "cancel");
			cancelButton.innerHTML = "Cancel";

		// Replacing button with input field
		element.parentNode.replaceChild(nameOfHoliday, document.getElementById('holiday'));

		// Adding cancel button
		document.getElementById('dynamicForm').appendChild(cancelButton);


		/** FUNCTIONS **/
		

	}
	
	function txtHoliday(e)
	{
		// Adding labels for radio buttons
		var regularLabel = document.createElement("label");
		regularLabel.setAttribute("class", "radio-inline");
		regularLabel.setAttribute("for", "regular");
		regularLabel.innerHTML = "<input type='radio' id='regular' name='holidays' onclick='titleHoliday(this)'> Regular";
		
		var specialLabel = document.createElement("label");
		specialLabel.setAttribute("class", "radio-inline");
		specialLabel.setAttribute("for", "special");
		specialLabel.innerHTML = "<input type='radio' id='special' name='holidays' onclick='titleHoliday(this)'> Special";

		// Transferring saved value to hidden input
		var name = document.getElementById('holidayName');

		// Checking if user pressed ENTER
		if(e.keyCode === 13 || e.which === 13)
		{
			e.preventDefault();
			name.setAttribute("value",document.getElementById('nameOfHoliday').value);

			// Instantiaing fields
			var form = document.getElementById('dynamicForm');
			var inputField = document.getElementById('nameOfHoliday');
			var cancel = document.getElementById('cancel');
			var cancelButton = document.createElement("button");
				cancelButton.setAttribute("class", "btn btn-danger btn-sm pull-down");
				cancelButton.setAttribute("id", "cancel");
				cancelButton.innerHTML = "Cancel";

			// Removing previous form
			form.removeChild(inputField);
			form.removeChild(cancel);

			// Adding labels
			form.appendChild(regularLabel);
			form.appendChild(specialLabel);

			document.getElementById('dynamicForm').appendChild(cancelButton);
		}
	}

	function titleHoliday(e)
	{
		var specialHoliday = document.getElementById('special');
		var regularHoliday = document.getElementById('regular');
		var holidayTitle = document.getElementById('holidayTitle');
		var name = document.getElementById('holidayName').value;
		var type = document.getElementById('holidayType');

		/* FUNCTIONS */
		if(regular.checked == true || special.checked == true)
		{
			holidayTitle.innerHTML = name + " attendance log";
			
			if(regular.checked == true)
			{
				type.setAttribute("value", "regular");
			}
			else
			{
				type.setAttribute("value", "special");
			}
		}
		
	}

	fittext();


</script>
</body>
</html>
