<!DOCTYPE html>
<?php
include_once('directives/db.php');
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
	$dateToday = strftime("%B %d, %Y");
	$day = date('l', strtotime($date));
	$holiday = "SELECT * FROM holiday WHERE date = '$date'";
	$holidayQuery = mysql_query($holiday);
	if($holidayQuery)
	{
		$holidayNum = mysql_num_rows($holidayQuery);
	}
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

<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
	<div class="row pull-down">
		<div class="row" style="width:99%">
			<div class="col-md-4 col-lg-4 col-md-offset-2 col-lg-offset-2" style="border-right: 1px solid black;">
				<h2 id='holidayTitle'>
					<?php 
						if($holidayNum != 0)
						{
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
							
							$holidayName = $_POST['holidayName'];
							$holidayType = $_POST['holidayType'];
							
							if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
							{
								
								if($_SESSION['holidayName'] !== $holidayName)
								{
									
									$_SESSION['holidayName'] = $holidayName;
								}
								else
								{
									
									$holidayName = $_SESSION['holidayName'];
								}
								if($_SESSION['holidayType'] !== $holidayType)
								{
									
									$_SESSION['holidayType'] = $holidayType;
								}
								else
								{
									
									$holidayType = $_SESSION['holidayType'];
								}
							}
							else
							{	
								
								$_SESSION['holidayName'] = $holidayName;
								$_SESSION['holidayType'] = $holidayType;
								$_SESSION['holidayDate'] = $date;

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
				<div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
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
								}
								Print "	<a href='holiday_query.php?date=".$date."' class='btn btn-danger btn-sm pull-down col-md-12 col-lg-12'  id='cancel'>
										Cancel
									</a>";	
							}
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
										Print "<input type='hidden' id='holidayName' value='".$_SESSION['holidayName']."'name='holidayName'>";
									}
									else
									{
										Print "<input type='hidden' id='holidayName' name='holidayName'>";
									}
									if(isset($_SESSION['holidayType']))
									{
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
			<div class="col-md-4 col-lg-4">
				<button class="btn btn-success col-md-pull-4 pull-down-more" onclick="printAll()">
					Print attendance sheet for all sites
				</button>
			</div>
		</div>
	</div>


	<div class="container">
		<h3>Sites</h3>

		<div class="col-md-9 col-lg-9 col-md-offset-2 col-lg-offset-2">
			<?php
			$attCounter = 0;//Attendance Completion Checker
			$counter = 0;//Counter for the While loop

			$site_box = "SELECT location FROM site WHERE active = '1'";
			$site_box_query = mysql_query($site_box);
			while($row = mysql_fetch_assoc($site_box_query))
			{
				$attendanceStatus = 0;
				$site = $row['location'];
				if($counter == 0)
				{
					Print '<div class="row">';
				}
				$employees = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1'";
				$empCheckerQuery = mysql_query($employees);
				
				$siteBool = false;

				$empNum = mysql_num_rows($empCheckerQuery);// gets the number of employees in the query
				$count = 1;// counter for number of loops
				$employeeNum = mysql_num_rows($empCheckerQuery);//Number of employee on site
				Print "<script>console.log('employeeNum: ".$employeeNum."')</script>";
				$checkerBuilder = "";
				if($empNum != 0)
				{
					$siteBool = true;
					$checkerBuilder = " AND (";
					while($empArr = mysql_fetch_assoc($empCheckerQuery))
					{
						$employeeId = $empArr['empid'];
						$checkerBuilder .= " empid = '".$employeeId."' ";

						if($empNum != $count)
							$checkerBuilder .= " OR ";

						$count++;
					}
					$checkerBuilder .= ")";
				}
				else
				{
					$attendanceStatus = 1;//Trigger for completing the attendance for the site
				}
		
				if($siteBool)//if site has employees
				{
					//Check if overall attendance for a certain site is done
					$attendanceChecker = "SELECT * FROM attendance WHERE date = '$date' $checkerBuilder";
					$attendanceQuery = mysql_query($attendanceChecker);
					$attendanceNum = mysql_num_rows($attendanceQuery);// Number of attendance of employee
					Print "<script>console.log('attendanceNum: ".$attendanceNum."')</script>";
					if($attendanceQuery)
					{
						$attNum = mysql_num_rows($attendanceQuery);
						if($attNum == 0)
						{
							$attendanceStatus = 0;
						}
						else
						{
							$checker = null;
							while($attRow = mysql_fetch_assoc($attendanceQuery))
							{
								if($attRow['attendance'] != 0)//0 is for no input
								{
									$checker++;//counter
								}
							}
							if($checker == $attNum  && $employeeNum == $attendanceNum)//check if number of attendance and the counter are the same
							{
								$attendanceStatus = 1;//Trigger for completing the attendance for the site
							}
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
					if($employee_num != 0)
						Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
									<div class="sitebox">
										<span class="smalltext">'
											. $row['location'] .
										'</span>
										<br>
										<span class="checkmark" name="site" value="'.$attendanceStatus.'"></span>
										<br>
										<span>Employees: '. $employee_num .'</span>
									</div>
								</a>';
					else
						Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important; pointer-events:none; cursor:not-allowed;" disabled>
									<div class="sitebox" style="background-color:grey !important; ">
										<span class="smalltext">'
											. $row['location'] .
										'</span>
										<br>
										<span class="glyphicon glyphicon-ban-circle"></span>
										<br>
										<span>Employees: '. $employee_num .'</span>
									</div>
								</a>';
				}
				else
				{
					if($employee_num != 0)
						Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important;">
									<div class="sitebox">
										<span class="autofit">'
											. $row['location'] .
										'<br>
										<span class="checkmark" name="site" value="'.$attendanceStatus.'"></span>
										<br>Employees: '. $employee_num .'
										</span>
									</div>
								</a>';
					else
						Print '	<a href="enterattendance.php?site='. $row['location'] .'" style="color: white !important; text-decoration: none !important; pointer-events:none; cursor:not-allowed;">
									<div class="sitebox" style="background-color:grey !important; ">
										<span class="autofit">'
											. $row['location'] .
										'<br>
										<span class="glyphicon glyphicon-ban-circle"></span>
										<br>Employees: '. $employee_num .'
										</span>
									</div>
								</a>';
				}
				$counter++;
				if($counter == 5)
				{
					Print '</div>';	
					$counter = 0;
				}
				
				// Counter for completed attendance each site
				if($attendanceStatus == 1)
				{
					$attCounter++;
				}
			}
				//Attendance Completion Checker
				$siteChecker = "SELECT * FROM site WHERE active = '1'";
				$siteQuery = mysql_query($siteChecker);
				$siteNum = mysql_num_rows($siteQuery);
				

				if($siteNum == $attCounter && $date == $dateToday)
				{
					$_SESSION['completeAtt'] = true;
				}
				else
				{
					unset($_SESSION['completeAtt']);
				}
			?>
		</div>
	</div>
<?php
// Algorithm on getting the opening Day for the date picker
	$payrollDay = "SELECT * FROM payroll_day";
	$payrollQuery = mysql_query($payrollDay);
	$payrollArr = mysql_fetch_assoc($payrollQuery);
	$openPayroll = $payrollArr['open'];//Gets the open payroll

	for($a = 1; $a<7; $a++)
	{
		$dayCheck = "-".$a." day";
		$checker = date('F d, Y', strtotime($dayCheck, strtotime($date)));
		$dayOfWeek = date('l', strtotime($checker));
		if($dayOfWeek == $openPayroll)
		{
			$a++;
			$datePickerMin = "-".$a;
		}
		else if($day == $openPayroll)
		{
			$datePickerMin = "0";
			break;
		}

	}
	Print "<input type='hidden' id='datePickerMin' value='".$datePickerMin."'>";
?>
<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
<script src="js/multiple-select.js"></script>
<script src="js/attendance.js"></script>
<script>

	document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
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
			//minDate: $("#datePickerMin").val(), 
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			}
		});

		$("#dtpkr_attendance").datepicker("setDate", currentDate);

	
		$("#dtpkr_attendance").change(function(){
			var date = $(this).val();
			window.location.href = "date_attendance.php?date="+date;
		});
		$( "#cancel").on("click",function(){
			window.location.href = "attendance_unset.php";
		});
		
	});
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
			var cancelButton = document.createElement("a");
				cancelButton.setAttribute("class", "btn btn-danger btn-sm pull-down col-md-12 col-lg-12");
				cancelButton.setAttribute("id", "cancel");
				cancelButton.setAttribute("href", "holiday_query.php?date=<?php Print $date ?>");
				//cancelButton.setAttribute("onclick", "cancelHoliday()");
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

			if(regularHoliday.checked == true)
			{
				
				type.setAttribute("value", "regular");
				document.getElementById('holidayForm').submit();
				window.location.href = "holiday_query.php?type=regular&name="+name+"&date=<?php Print $date ?>";
			}
			else if(specialHoliday.checked == true)
			{
				
				type.setAttribute("value", "special");
				document.getElementById('holidayForm').submit();
				window.location.href = "holiday_query.php?type=special&name="+name+"&date=<?php Print $date ?>";
			}
		}
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

		var cancelButton = document.createElement("a");
			cancelButton.setAttribute("class", "btn btn-danger btn-sm pull-down col-md-12 col-lg-12");
			cancelButton.setAttribute("id", "cancel");
			cancelButton.setAttribute("href", "holiday_query.php?date=<?php Print $date ?>");
			cancelButton.innerHTML = "Cancel";

		// Replacing button with input field
		element.parentNode.replaceChild(nameOfHoliday, document.getElementById('holiday'));

		// Adding cancel button
		document.getElementById('dynamicForm').appendChild(cancelButton);
	}
</script>

	
</body>
</html>
