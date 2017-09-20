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
	$holiday = "SELECT * FROM holiday WHERE date = '$date'";
	$holidayQuery = mysql_query($holiday);
	if($holidayQuery)
	{
		$holidayNum = mysql_num_rows($holidayQuery);
	}
	//Print "<script>alert('Name: ". $_SESSION['holidayName'] ."/ Type: ". $_SESSION['holidayType'] ."')</script>";
	// if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
	// {
	// 	Print "<script>alert('Name: ". $_SESSION['holidayName'] ."/ Type: ". $_SESSION['holidayType'] ."')</script>";
	// }
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
				<h2 id='holidayTitle'>
					<?php 
						if($holidayNum != 0)
						{
								//Print "<script>alert('dsad')</script>";
								$holidayRow = mysql_fetch_assoc($holidayQuery);
								$_SESSION['holidayName'] = $holidayRow['holiday'];
								$_SESSION['holidayDate'] = $holidayRow['date'];
								$_SESSION['holidayType'] = $holidayRow['type'];
								$holidayName = $holidayRow['holiday'];
								$holidayType = $holidayRow['type'];
								Print  $holidayName . " attendance log";
						}
						else if(isset($_POST['holidaySubmit']))
						{
							//Print "<script>alert('lalalal')</script>";
							$holidayName = $_POST['holidayName'];
							$holidayType = $_POST['holidayType'];
							//Print "<script>alert('". $holidayType ."')</script>";
							//Print "<script>alert('". $holidayName ."')</script>";
							if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
							{
								//Print "<script>alert('1')</script>";
								if($_SESSION['holidayName'] !== $holidayName)
								{
									//Print "<script>alert('2')</script>";
									$_SESSION['holidayName'] = $holidayName;
								}
								else
								{
									//Print "<script>alert('3')</script>";
									$holidayName = $_SESSION['holidayName'];
								}
								if($_SESSION['holidayType'] !== $holidayType)
								{
									//Print "<script>alert('4')</script>";
									$_SESSION['holidayType'] = $holidayType;
								}
								else
								{
									//Print "<script>alert('5')</script>";
									$holidayType = $_SESSION['holidayType'];
								}
							}
							else
							{	
								//Print "<script>alert('6')</script>";
								$_SESSION['holidayName'] = $holidayName;
								$_SESSION['holidayType'] = $holidayType;
								$_SESSION['holidayDate'] = $date;
 								//Print "<script>alert('session ". $_SESSION['holidayName'] ."')</script>";
								//Print "<script>alert('session ". $_SESSION['holidayType'] ."')</script>";
								

							}
							

							Print  $holidayName . " attendance log";
						}
						else if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
						{

							if($_SESSION['holidayDate'] == $date)
							{

								$holidayName = $_SESSION['holidayName'];
								Print  $holidayName . " attendance log";
							}
							else if($day == "Sunday")
							{
								Print "Sunday attendance log";
							}
							else
							{
								Print "Daily attendance log";
							}
							
						}
						else if($day == "Sunday")
						{
							Print "Sunday attendance log";
						}
						else
						{
							Print "Daily attendance log";
						}
					?>
				</h2>
				<br>
				<div class="col-md-6 col-md-offset-3">
					<form>
					
						<input name="txt_attendance" type="text" size="10" class="form-control" value = <?php
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
					<?php 
						if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
						{
							if($_SESSION['holidayDate'] == $date)
							{
								if($_SESSION['holidayType'] == "regular")
								{
									Print "<h4>Regular Holiday</h4>";
								}
								else
								{
									Print "<h4>Special Holiday</h4>";
								}//dito
								Print "	<button type='button' class='btn btn-danger btn-sm pull-down' onclick='cancelHoliday()' id='cancel'>
										Cancel
									</button>";	
							}
							//Print "<script>alert('lololo')</script>";
							// else if($_SESSION['holidayType'] == "regular")
							// {
							// 	//Print "<script>alert('Regular')</script>";
							// 	Print "<h4>Regular Holiday</h4>";	
							// 	Print "	<button type='button' class='btn btn-danger btn-sm pull-down' onclick='cancelHoliday()' id='cancel'>
							// 			Cancel
							// 		</button>";	
							// }
							// else if ($_SESSION['holidayType'] == "special")
							// {
							// 	//Print "<script>alert('Special')</script>";
							// 	Print "<h4>Special Holiday</h4>";
							// 	Print "	<button type='button' class='btn btn-danger btn-sm pull-down' onclick='cancelHoliday()' id='cancel'>
							// 			Cancel
							// 		</button>";	

							// }
							else
							{
								Print "<button id='holiday' class='btn btn-primary' onclick='Holiday(this)'>Holiday?</button>";
							}
							
						}
						else
						{
							Print "<button id='holiday' class='btn btn-primary' onclick='Holiday(this)'>Holiday?</button>";
						}
					?>	
					</div>
					
					</form>
					<form method = "post" id="holidayForm" action = "">
						<?php
							if(isset($_SESSION['holidayDate']))
							{
								if($_SESSION['holidayDate'] == $date)
								{
									if(isset($_SESSION['holidayName']))
									{	
										//Print "<script>alert('beng')</script>";
										Print "<input type='hidden' id='holidayName' value='".$_SESSION['holidayName']."'name='holidayName'>";
									}
									else
									{
										Print "<input type='hidden' id='holidayName' name='holidayName'>";
									}
									if(isset($_SESSION['holidayType']))
									{
										//Print "<script>alert('boom')</script>";
										Print "<input type='hidden' id='holidayType' value='".$_SESSION['holidayType']."'name='holidayType'>";
									}
									else
									{
										Print "<input type='hidden' id='holidayType' name='holidayType'>";
									}
								}
								else if(isset($_SESSION['holidayType']) && isset($_SESSION['holidayName']))
								{
									Print "<input type='hidden' id='holidayName' value='".$_SESSION['holidayName']."' name='holidayName'>";
									Print "<input type='hidden' id='holidayType' value='".$_SESSION['holidayType']."' name='holidayType'>";
								}
							}
							else
							{
								Print "<input type='hidden' id='holidayName' name='holidayName'>";
								Print "<input type='hidden' id='holidayType' name='holidayType'>";
							}

		
						?>
						<input type='hidden' name='holidaySubmit'>
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
				$attendanceStatus = 0;
				$site = $row['location'];
				if($counter == 0)
				{
					Print '<div class="row">';
				}
				//Check if overall attendance for a certain site is done
				$attendanceChecker = "SELECT * FROM attendance WHERE date = '$date' AND site = '$site'";
				$attendanceQuery = mysql_query($attendanceChecker);
				if($attendanceQuery)
				{
					//Print "<script>alert('yeah')</script>";
					$attNum = mysql_num_rows($attendanceQuery);
					if($attNum == 0)
					{
						$attendanceStatus = 0;
					}
					else
					{
						$checker = null;
						//Print "<script>alert('". $checker ."')</script>";
						while($attRow = mysql_fetch_assoc($attendanceQuery))
						{
							if($attRow['attendance'] != 0)//0 is for no input
							{
								$checker++;//counter
							}
						}
						if($checker == $attNum)//check if number of attendance and the counter are the same
						{
							//Print "<script>alert('".$checker." = ". $attNum ."')</script>";
							$attendanceStatus = 1;//Trigger for completing the attendance for the site
						}
					}
				}
				

				$site_num = $row['location'];
				$num_employee = "SELECT * FROM employee WHERE site = '$site_num' AND employment_status = '1'";
				$employee_query = mysql_query($num_employee);
				$employee_num = 0;

				if($employee_query)
				{
					$employee_num = mysql_num_rows($employee_query);
				}
				/* If location is long, font-size to smaller */
				if(strlen($row['location'])>=16)
				{
					Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
								<div class="sitebox">
									<span class="smalltext">'
										. $row['location'] .
									'</span>
									<br><br>
									<span>Employees: '. $employee_num .'</span>
								</div>
								<input type="hidden" id="'. $row['location'] .'" value="'. $attendanceStatus .'">
							</a>';
				}
				else
				{
					Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
								<div class="sitebox">
									<span class="autofit">'
										. $row['location'] .
									'<br><br>Employees: '. $employee_num .'
									</span>
								</div>
								<input type="hidden" id="'. $row['location'] .'" value="'. $attendanceStatus .'">
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
<div id="forPHP">
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
		$( "#cancel").on("click",function(){
			window.location.href = "attendance_unset.php";
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
	function cancelHoliday()
	{
		//alert('pasok');
		document.getElementById('dynamicForm').innerHTML = "<button id='holiday' class='btn btn-primary' onclick='Holiday(this)'>Holiday?</button>";
		document.getElementById('holidayType').value = "";
		document.getElementById('holidayName').value = "";
		var sunday = "<?php Print $day ?>";
		if(sunday == "Sunday")
		{
			
			document.getElementById('holidayTitle').innerHTML = "Sunday attendance log";
		}
		else
		{
			
			document.getElementById('holidayTitle').innerHTML = "Daily attendance log";
		}
			// document.getElementById('forPHP').innerHTML = 	"<?php 	//unset($_SESSION['holidayType']);
			// 														unset($_SESSION['holidayName']);
			// 												?>";
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
			cancelButton.setAttribute("onclick", "cancelHoliday()");
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
				cancelButton.setAttribute("onclick", "cancelHoliday()");
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
		if(regularHoliday.checked == true || specialHoliday.checked == true)
		{
			holidayTitle.innerHTML = name + " attendance log";
			//alert('yow1');
			if(regularHoliday.checked == true)
			{
				//document.getElementById('regular').checked = false;
				//alert('yow');
				
				type.setAttribute("value", "regular");
				document.getElementById('holidayForm').submit();
				//special.checked = false;
			}
			else if(specialHoliday.checked == true)
			{
				//document.getElementById('special').checked = false;
				//alert('yeah');
				
				type.setAttribute("value", "special");
				document.getElementById('holidayForm').submit();
				// special.checked = false;
			}
			// if(bool == false)
			// {
			// 	
			// 	//
			// }
			// else
			// {
			// 	
			// 	d//ocument.getElementById('holidayForm').submit();
			// }
		}
	}

	fittext();
</script>

	
</body>
</html>
