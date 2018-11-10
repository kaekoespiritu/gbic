<?php

function attendance () 
{
	if(isset($_SESSION['date']))
	{
		$date = $_SESSION['date'];
	}
	else
	{
		$date = strftime("%B %d, %Y");
	}

	$day = date('l', strtotime($date));

	if(!isset($_GET['position']))
	{
		header("location:enterattendance.php?site=".$site_name."&position=null");
	}

	$site = $_GET['site'];
	if($_GET['position'] != "null")
	{
		$position = $_GET['position'];
		$employees = "SELECT * FROM employee WHERE site = '$site' AND position = '$position' AND employment_status = '1' ORDER BY lastname";
	}
	else if(isset($_POST['txt_search']))
	{
		$search = $_POST['txt_search'];
		$employees = "SELECT * FROM employee WHERE 	site = '$site' AND (firstname LIKE '%$search%' OR 
													lastname LIKE '%$search%' OR
													position LIKE '%$search%') AND employment_status = '1' ORDER BY lastname";
	}
	else
	{
		$employees = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' ORDER BY lastname";
	}
	$employees_query = mysql_query($employees);

	$empCheckerQuery = mysql_query($employees);

	
	$empNum = mysql_num_rows($empCheckerQuery);// gets the number of employees in the query
	$count = 1;// counter for number of loops
	$checkerBuilder = "";
	if($empNum != 0)
	{
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

	$dateChecker = "SELECT date FROM attendance WHERE date = '$date' $checkerBuilder";
	$dateCheckerQuery = mysql_query($dateChecker);

	if($dateCheckerQuery)//Checks if there is already an attendance made for that specific date
	{
		$dateRows = mysql_num_rows($dateCheckerQuery);
	}	

	// theres already an data on that specific day on the database
	$counter = 0;
	//Print "<script>alert('".$dateRows."')</script>";
	if(!empty($dateRows))
	{
		if($dateRows >= 1)
		{	
			while($row_employee = mysql_fetch_assoc($employees_query))
			{
				$job = $row_employee['position'];
				$driverCheck = mysql_query("SELECT * FROM job_position WHERE position = '$job'");//check if position is driver
				$driverCheckArr = mysql_fetch_array($driverCheck);
				$driverBool = false;//boolean for hidden input of driver
				if($driverCheckArr['driver'] == '1')
					$driverBool = true;

				$empidChecker = $row_employee['empid'];
				$empCheck = "SELECT * FROM attendance WHERE date = '$date' AND empid = '$empidChecker' ";
				$empCheckQuery = mysql_query($empCheck);
				$empRow = mysql_fetch_assoc($empCheckQuery);
			// Attendance Check
				if($empRow['attendance'] == 0)//No input
				{
					Print 	"<tr id=\"". $row_employee['empid'] ."\">";

						Print "<input type='hidden' class='driver' value='".$driverBool."' >";//Boolean for driver
						
						if( $day == 'Sunday') 
						{
							Print '<input type="hidden" id="isSunday">';
						}
						if($row_employee['complete_doc'] == '1')
						{
							Print '<input type="hidden" id="completeReq">';
						}

						Print '<script>console.log("'.$day.'")</script>';

						Print		"
								<!-- Work Status -->
									<td class='status' align='left'>
										<div class='pull-right spacer'>
											<input type='button' id='workstatus-".$row_employee['empid']."' class='btn btn-info' autocomplete='off' value='Working'>
										</div>
									</td>
								<!-- Employee ID -->
									<td class='empName' align='left'>
										". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
									</td>
								<!-- Position -->
									<td>
										". $row_employee['position'] ."
									</td>
								<!-- Automatic timein -->
									<td>
										<input type='button' value='8-5' class='btn btn-primary auto' onclick='AutoTimeIn85(\"". $row_employee['empid'] ."\")'>
									</td>
									<td>
										<input type='button' value='7-4' class='btn btn-primary auto' onclick='AutoTimeIn74(\"". $row_employee['empid'] ."\")'>
									</td>
								<!-- Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' value='". $empRow['timein'] ."' class='timein1 timepicker form-control input-sm' name='timein1[".$counter."]'>
									</td>
								<!-- Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' value='' name='timeout1[".$counter."]'>
									</td>
								<!-- Half Day Checkbox-->
									<td>
										<input type='checkbox' value='' class='halfdayChk' onclick='halfDay(\"". $row_employee['empid'] ."\")' name='halfday[".$counter."]' disabled>
									</td>
								<!-- AFTER BREAK Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' value='". $empRow['timein'] ."' class='timein2 timepicker form-control input-sm' name='timein2[".$counter."]'>
									</td>
								<!-- AFTER BREAK Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' value='' name='timeout2[".$counter."]'>
									</td>
								<!-- Night Shift Checkbox-->
								<td>
									<input type='checkbox' class='nightshiftChk' name='nightshift[".$counter."]' onclick='nightshift_ChkBox(\"". $row_employee['empid'] ."\")' disabled>
								</td>
								<!-- NIGHT SHIFT Time In -->
								<td>
									<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[".$counter."]' disabled>
								</td> 
								<!-- NIGHT SHIFT Time Out-->
								<td>
									<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[".$counter."]' disabled>
								</td> 
								<!-- Working Hours -->
									<td>
										<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
										<input type='hidden' class='workinghoursH'  name='workinghrs[".$counter."]' >
									</td>
								<!-- Overtime -->
									<td>
										<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
										<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
									</td>
								<!-- Undertime -->
									<td>
										<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
										<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
									</td>
								<!-- Night Differential --> 
									<td>
										<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
										<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
									</td>
								<!-- Attendance Status -->
									<input type='hidden' name='attendance[".$counter."]' value='' class='attendance'>";
				}
				else if($empRow['attendance'] == 1)//Absent
				{
					Print 	"<tr id=\"". $row_employee['empid'] ."\" class='danger'>";

						Print "<input type='hidden' class='driver' value='".$driverBool."' >";//Boolean for driver
						if( $day == 'Sunday') {
							Print '<input type="hidden" id="isSunday">';
						}
						if($row_employee['complete_doc'] == '1')
						{
							Print '<input type="hidden" id="completeReq">';
						}

						Print '<script>console.log("'.$day.'")</script>';

						Print		"
								<!-- Work Status -->
									<td class='status' align='left'>
										<div class='pull-right spacer'>
											<input type='button' id='workstatus-".$row_employee['empid']."' class='btn btn-info' autocomplete='off' value='Working'>
										</div>
									</td>
								<!-- Employee ID -->
									<td class='empName' align='left'>
										". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
									</td>
								<!-- Position -->
									<td>
										". $row_employee['position'] ."
									</td>
								<!-- Automatic timein -->
									<td>
										<input type='button' value='8-5' class='btn btn-primary auto' onclick='AutoTimeIn85(\"". $row_employee['empid'] ."\")'>
									</td>
									<td>
										<input type='button' value='7-4' class='btn btn-primary auto' onclick='AutoTimeIn74(\"". $row_employee['empid'] ."\")'>
									</td>
								<!-- Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' placeholder='ABSENT' class='timein1 timepicker form-control input-sm' value='' name='timein1[".$counter."]'>
									</td> 
								<!-- Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' placeholder='ABSENT' value='' name='timeout1[".$counter."]'>
									</td>
								<!-- Half Day Checkbox-->
									<td>
										<input type='checkbox' onclick='halfDay(\"". $row_employee['empid'] ."\")' class='halfdayChk' name='halfday[".$counter."]' disabled>
									</td>
								<!-- AFTER BREAK Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' placeholder='ABSENT' class='timein2 timepicker form-control input-sm' value='' name='timein2[".$counter."]'>
									</td> 
								<!-- AFTER BREAK Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' placeholder='ABSENT' value='' name='timeout2[".$counter."]'>
									</td>
								<!-- Night Shift Checkbox-->
								<td>
									<input type='checkbox' class='nightshiftChk' name='nightshift[".$counter."]'  onclick='nightshift_ChkBox(\"". $row_employee['empid'] ."\")' disabled>
								</td>
								<!-- NIGHT SHIFT Time In -->
								<td>
									<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value='' placeholder='ABSENT' name='timein3[".$counter."]'disabled>
								</td> 
								<!-- NIGHT SHIFT Time Out-->
								<td>
									<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' placeholder='ABSENT' name='timeout3[".$counter."]' disabled>
								</td> 
								<!-- Working Hours -->
									<td>
										<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
										<input type='hidden' class='workinghoursH'  name='workinghrs[".$counter."]' >
									</td>
								<!-- Overtime -->
									<td>
										<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
										<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
									</td>
								<!-- Undertime -->
									<td>
										<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
										<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
									</td>
								<!-- Night Differential --> 
									<td>
										<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
										<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
									</td>
								<!-- Attendance Status -->
									<input type='hidden' name='attendance[".$counter."]' value='ABSENT' class='attendance'>";
				}
				else if($empRow['attendance'] == 2)// Present
				{
					Print 	"<tr id=\"". $row_employee['empid'] ."\" class='success'>";
							
						Print "<input type='hidden' class='driver' value='".$driverBool."' >";//Boolean for driver
						if( $day == 'Sunday') {
							Print '<input type="hidden" id="isSunday">';
						}
						if($row_employee['complete_doc'] == '1')
						{
							Print '<input type="hidden" id="completeReq">';
						}

						Print '<script>console.log("'.$day.'")</script>';

						Print	"
								<!-- Work Status -->
									<td class='status' align='left'>
										<div class='pull-right spacer'>
											<input type='button' id='workstatus-".$row_employee['empid']."' class='btn btn-info' autocomplete='off' value='Working'>
										</div>
									</td>
								<!-- Employee ID -->
									<td class='empName' align='left'>
										". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
									</td>
								<!-- Position -->
									<td>
										". $row_employee['position'] ."
									</td>
								<!-- Automatic timein -->
									<td>
										<input type='button' value='8-5' class='btn btn-primary auto' onclick='AutoTimeIn85(\"". $row_employee['empid'] ."\")'>
									</td>
									<td>
										<input type='button' value='7-4' class='btn btn-primary auto' onclick='AutoTimeIn74(\"". $row_employee['empid'] ."\")'>
									</td>
								<!-- Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timein1 timepicker form-control input-sm' value='". $empRow['timein'] ."' name='timein1[".$counter."]'>
									</td>
								<!-- Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' value='". $empRow['timeout'] ."' name='timeout1[".$counter."]'>
									</td>";

					if(empty($empRow['afterbreak_timein']) && empty($empRow['afterbreak_timeout']))//employee took a halfday
					{
						Print 	"<!-- Half Day Checkbox-->
									<td>
										<input type='checkbox' value='' class='halfdayChk'  onclick='halfDay(\"". $row_employee['empid'] ."\")' name='halfday[".$counter."]' checked>
									</td>";
						Print	"<!-- AFTER BREAK Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timein2 timepicker form-control input-sm' placeholder='' name='timein2[".$counter."]' disabled>
									</td>
								<!-- AFTER BREAK Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' placeholder='' name='timeout2[".$counter."]' disabled>
									</td>";
					}
					else
					{
						Print 	"<!-- Half Day Checkbox-->
									<td>
										<input type='checkbox' class='halfdayChk' onclick='halfDay(\"". $row_employee['empid'] ."\")' name='halfday[".$counter."]' >
									</td>";
						Print	"<!-- AFTER BREAK Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timein2 timepicker form-control input-sm' value='". $empRow['afterbreak_timein'] ."' name='timein2[".$counter."]'>
									</td>
								<!-- AFTER BREAK Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' value='". $empRow['afterbreak_timeout'] ."' name='timeout2[".$counter."]'>
									</td>";
					}

					//Employee has nightshift 
					if(!empty($empRow['nightshift_timein']) && !empty($empRow['nightshift_timeout']))
					{
						Print "
								<!-- Night Shift Checkbox-->
									<td>
										<input type='checkbox' class='nightshiftChk' name='nightshift[".$counter."]' onclick='nightshift_ChkBox(\"". $row_employee['empid'] ."\")' checked>
									</td>
								<!-- NIGHT SHIFT Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value='".$empRow['nightshift_timein']."'  name='timein3[".$counter."]'>
									</td> 
								<!-- NIGHT SHIFT Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='".$empRow['nightshift_timeout']."' name='timeout3[".$counter."]'>
									</td> 
						";
					}
					else
					{
						Print "
								<!-- Night Shift Checkbox-->
									<td>
										<input type='checkbox' class='nightshiftChk' name='nightshift[".$counter."]' onclick='nightshift_ChkBox(\"". $row_employee['empid'] ."\")' >
									</td>
								<!-- NIGHT SHIFT Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[".$counter."]' disabled>
									</td> 
								<!-- NIGHT SHIFT Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[".$counter."]' disabled>
									</td> 
						";
					}
					

					// Working hours
					if($empRow['workhours'] <= 5)
					{
						$workhours = $empRow['workhours'];
						$hasMins = strpos($workhours, ".");
						$work = explode(".", $workhours);
						if($hasMins == true)
						{
							//Separates the string
							$wHrs = $work[0];
							$wMin = $work[1];
							//Print "<script>alert('workinghrs')</script>";
							Print "<!-- Working Hours -->
							<td>
								<input type='text' placeholder='--'' value='". $wHrs ." hrs, ".$wMin." mins' class='form-control input-sm workinghours' disabled>
								<input type='hidden' class='workinghoursH' value='". $wHrs ." hrs, ".$wMin." mins/HALFDAY' name='workinghrs[".$counter."]' >
							</td>";
						}
						else
						{
							$wHrs = $work[0];
							//Print "<script>alert('workinghrs1')</script>";
							Print "<!-- Working Hours -->
							<td>
								<input type='text' placeholder='--'' value='". $wHrs ." hrs' class='form-control input-sm workinghours' disabled>
								<input type='hidden' class='workinghoursH' value='". $wHrs ." hrs' name='workinghrs[".$counter."]' >
							</td>";
						}
						
					}
					else
					{
						$workhours = $empRow['workhours'];
						$hasMins = strpos($workhours, ".");
						$work = explode(".", $workhours);//Separates the string
						//Print "<script>alert('".$hasMins."')</script>";
						if($hasMins == true) //if it has minutes
						{
							
							$wHrs = $work[0];
							$wMin = $work[1];

							//Print "<script>alert('workinghrs12')</script>";
							Print "<!-- Working Hours -->
							<td>
								<input type='text' placeholder='--'' value='". $wHrs ." hrs, ".$wMin." mins/HALFDAY' class='form-control input-sm workinghours' disabled>
								<input type='hidden' class='workinghoursH' value='". $wHrs ." hrs, ".$wMin." mins' name='workinghrs[".$counter."]' >
							</td>";
						}
						else// just hours
						{
							//Print "<script>alert('workinghrs1')</script>";
							$wHrs = $work[0];
							Print "<!-- Working Hours -->
							<td>
								<input type='text' placeholder='--'' value='". $wHrs ." hrs' class='form-control input-sm workinghours' disabled>
								<input type='hidden' class='workinghoursH' value='". $wHrs ." hrs' name='workinghrs[".$counter."]' >
							</td>";
						}
						
					}
					// Overtime
					if($empRow['overtime'] != 0)
					{
						$overtime = $empRow['overtime'];
						$hasMins = strpos($overtime, ".");
						$work = explode(".", $overtime);//Separates the string
						if($hasMins == true)
						{
							$othrs = $work[0];
							$otmin = $work[1];
							if($othrs != 0 && $otmin != 0)
								$otDisplay = $othrs ." hrs, ".$otmin." mins";
							else if($othrs != 0 && $otmin == 0)
								$otDisplay = $othrs ." hrs";
							else if($othrs == 0 && $otmin != 0)
								$otDisplay = $otmin." mins";

							Print "<!-- Overtime -->
							<td>
								<input type='text' placeholder='--' class='form-control input-sm overtime' value='".$otDisplay."'  disabled>
								<input type='hidden' class='overtimeH' value='".$otDisplay."' name='othrs[".$counter."]' >
							</td>";
						}
						else
						{
							$othrs = $work[0];
							//Print "<script>alert('YEAH')</script>";
							Print "<!-- Overtime -->
							<td>
								<input type='text' placeholder='--' class='form-control input-sm overtime' value='". $othrs ." hrs'  disabled>
								<input type='hidden' class='overtimeH' value='". $othrs ." hrs' name='othrs[".$counter."]' >
							</td>";
						}
						
					}
					else
					{
						Print "<!-- Overtime -->
						<td>
							<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
							<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
						</td>";
					}
					// Undertime
					if($empRow['undertime'] != 0)
					{
						$undertime = $empRow['undertime'];
						$hasMins = strpos($undertime, ".");
						$work = explode(".", $undertime);//Separates the string
						if($hasMins == true)
						{
							$uHrs = $work[0];
							$uMin = $work[1];
							if($uHrs != 0 && $uMin != 0)
								$utDisplay = $uHrs ." hrs, ".$uMin." mins";
							else if($uHrs != 0 && $uMin == 0)
								$utDisplay = $uHrs ." hrs";
							else if($uHrs == 0 && $uMin != 0)
								$utDisplay = $uMin." mins";

							Print "<!-- Undertime -->
							<td>
								<input type='text' placeholder='--' value='".$utDisplay."' class='form-control input-sm undertime'  disabled>
								<input type='hidden' class='undertimeH' value='".$utDisplay."' name='undertime[".$counter."]'>
							</td>";
						}
						else
						{
							$uHrs = $work[0];
							Print "<!-- Undertime -->
							<td>
								<input type='text' placeholder='--' value='". $uHrs ." hrs' class='form-control input-sm undertime'  disabled>
								<input type='hidden' class='undertimeH' value='". $uHrs ." hrs' name='undertime[".$counter."]'>
							</td>";
						}
						
					}
					else
					{
						Print "<!-- Undertime -->
							<td>
								<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
								<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
							</td>";
					}
					// NightDiff
					if($empRow['nightdiff'] != 0)
					{
						$nightdiff = $empRow['nightdiff'];
						$hasMins = strpos($nightdiff, ".");
						$work = explode(".", $nightdiff);//Separates the string
						if($hasMins == true)
						{
							$ndhrs = $work[0];
							$ndmin = $work[1];
							if($ndhrs != 0 && $ndmin != 0)
								$ndDisplay = $ndhrs ." hrs, ".$ndmin." mins";
							else if($ndhrs != 0 && $ndmin == 0)
								$ndDisplay = $ndhrs ." hrs";
							else if($ndhrs == 0 && $ndmin != 0)
								$ndDisplay = $ndmin." mins";

							Print "<!-- Night Differential -->
							<td>
								<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='".$ndDisplay."'  disabled>
								<input type='hidden' class='nightdiffH' value='".$ndDisplay."' name='nightdiff[".$counter."]' >
							</td>";
						}
						else
						{
							$ndHrs = $work[0];
							Print "<!-- Night Differential -->
							<td>
								<input type='text' placeholder='--' value='". $ndHrs ." hrs' class='form-control input-sm nightdiff'  disabled>
								<input type='hidden' class='nightdiffH' value='". $ndHrs ." hrs' name='nightdiff[".$counter."]'>
							</td>";
						}
					}
					else
					{
						Print "<!-- Night Differential --> 
							<td>
								<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
								<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
							</td>";
					}
					// Attendance Status
					Print "<!-- Attendance Status -->
						<input type='hidden' name='attendance[".$counter."]' value='PRESENT' class='attendance'>";
				}
				else if($empRow['attendance'] == 3)// No Work
				{
					Print 	"<tr id=\"". $row_employee['empid'] ."\">";

						Print "<input type='hidden' class='driver' value='".$driverBool."' >";//Boolean for driver
						
						if( $day == 'Sunday') 
						{
							Print '<input type="hidden" id="isSunday">';
						}
						if($row_employee['complete_doc'] == '1')
						{
							Print '<input type="hidden" id="completeReq">';
						}

						Print '<script>console.log("'.$day.'")</script>';

						Print		"
								<!-- Work Status -->
									<td class='status' align='left'>
										<div class='pull-right spacer'>
											<input type='button' id='workstatus-".$row_employee['empid']."' class='btn btn-default' autocomplete='off' value='No Work'>
										</div>
									</td>
								<!-- Employee ID -->
									<td class='empName' align='left'>
										". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
									</td>
								<!-- Position -->
									<td>
										". $row_employee['position'] ."
									</td>
								<!-- Automatic timein -->
									<td>
										<input type='button' value='8-5' class='btn btn-primary auto' onclick='AutoTimeIn85(\"". $row_employee['empid'] ."\")'>
									</td>
									<td>
										<input type='button' value='7-4' class='btn btn-primary auto' onclick='AutoTimeIn74(\"". $row_employee['empid'] ."\")'>
									</td>
								<!-- Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' value='". $empRow['timein'] ."' class='timein1 timepicker form-control input-sm' name='timein1[".$counter."]'>
									</td>
								<!-- Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' value='' name='timeout1[".$counter."]'>
									</td>
								<!-- Half Day Checkbox-->
									<td>
										<input type='checkbox' value='' class='halfdayChk' onclick='halfDay(\"". $row_employee['empid'] ."\")' name='halfday[".$counter."]' disabled>
									</td>
								<!-- AFTER BREAK Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' value='". $empRow['timein'] ."' class='timein2 timepicker form-control input-sm' name='timein2[".$counter."]'>
									</td>
								<!-- AFTER BREAK Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' value='' name='timeout2[".$counter."]'>
									</td>
								<!-- Night Shift Checkbox-->
								<td>
									<input type='checkbox' class='nightshiftChk' name='nightshift[".$counter."]' onclick='nightshift_ChkBox(\"". $row_employee['empid'] ."\")' disabled>
								</td>
								<!-- NIGHT SHIFT Time In -->
								<td>
									<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[".$counter."]' disabled>
								</td> 
								<!-- NIGHT SHIFT Time Out-->
								<td>
									<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[".$counter."]' disabled>
								</td> 
								<!-- Working Hours -->
									<td>
										<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
										<input type='hidden' class='workinghoursH'  name='workinghrs[".$counter."]' >
									</td>
								<!-- Overtime -->
									<td>
										<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
										<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
									</td>
								<!-- Undertime -->
									<td>
										<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
										<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
									</td>
								<!-- Night Differential --> 
									<td>
										<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
										<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
									</td>
								<!-- Attendance Status -->
									<input type='hidden' name='attendance[".$counter."]' value='NOWORK' class='attendance'>";
				}
				Print 	
					"<input type='hidden' name='empid[".$counter."]' value=". $row_employee['empid'] .">";

			// Extra allowance	
				if($empRow['xallow'] != 0)
				{
					Print '<!-- Extra allowance Input --> 
						<input type="hidden" value="'. $empRow['xallow'] .'" name="xallow['.$counter.']" class="hiddenXAllow">';
					Print "<!-- Extra Allowance Button --> 
					<td>
						<a class='btn btn-sm btn-primary xallowance' data-toggle='modal' data-target='#XAllowanceModal' onclick='xAllowance(\"". $row_employee['empid'] ."\")'>X Allow <span class='xall-icon badge'>".$empRow['xallow']."</span></a>
					</td>";
				}
				else 
				{
					Print "<!-- Extra allowance Input --> 
						<input type='hidden' name='xallow[".$counter."]' class='hiddenXAllow'>";
					Print "<!-- Extra Allowance Button --> 
					<td>
						<a class='btn btn-sm btn-primary xallowance' data-toggle='modal' data-target='#XAllowanceModal' onclick='xAllowance(\"". $row_employee['empid'] ."\")'>X Allow <span class='xall-icon'></span></a>
					</td>";
				}
			// REMARKS	
				if($empRow['remarks'] != "")
				{
					// Print "<script>alert('".$empRow['remarks']."')</script>";
					Print '<!-- Remarks Input --> 
						<input type="hidden" value="'. $empRow['remarks'] .'" name="remarks['.$counter.']" class="hiddenRemarks">';
					Print "<!-- Remarks Button --> 
					<td>
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\"); remarksValidation(\"". $row_employee['empid'] ."\")'>Remarks <span class='remarks-icon glyphicon glyphicon-edit'></span></a>
					</td>";
				}
				else
				{
					Print "<!-- Remarks Input --> 
						<input type='hidden' name='remarks[".$counter."]' class='hiddenRemarks'>";
					Print "<!-- Remarks Button yow--> 
					<td>
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\"); remarksValidation(\"". $row_employee['empid'] ."\");'>Remarks <span class='remarks-icon'></span></a>
					</td>";
				}
					Print 
					"<td>
						<a class='btn btn-sm btn-danger absent' onclick='absent(\"". $row_employee['empid'] ."\")'>Absent</a>
					</td>
				</tr>
					";
					$counter++;
			}
		}
	}
	// if there is no data on the specific day on the database
	else if($employees_query)
	{
		//Print "<script>alert('YEAH')</script>";
		while($row_employee = mysql_fetch_assoc($employees_query))
		{
			$job = $row_employee['position'];
			$driverCheck = mysql_query("SELECT * FROM job_position WHERE position = '$job'");//check if position is driver
			$driverCheckArr = mysql_fetch_array($driverCheck);
			$driverBool = false;//boolean for hidden input of driver
			if($driverCheckArr['driver'] == '1')
				$driverBool = true;

			Print 	"	
				<tr id=\"". $row_employee['empid'] ."\">

					<input type='hidden' class='driver' value='".$driverBool."' >";
					if( $day == 'Sunday') {
						Print '<input type="hidden" id="isSunday">';
					}
					if($row_employee['complete_doc'] == '1')
					{
						Print '<input type="hidden" id="completeReq">';
					}

			Print "<input type='hidden' name='empid[".$counter."]' value=". $row_employee['empid'] .">
					<!-- Work Status -->
					<td class='status' align='left'>
						<div class='pull-right spacer'>
							<input type='button' id='workstatus-".$row_employee['empid']."' class='btn btn-info' autocomplete='off' value='Working'>
						</div>
					</td>
					<td class='empName' align='left'>
						". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
					</td>
					<td>
						". $row_employee['position'] ."
					</td>
					<!-- Automatic timein -->
					<td>
						<input type='button' value='8-5' class='btn btn-primary auto' onclick='AutoTimeIn85(\"". $row_employee['empid'] ."\")'>
					</td>
					<td>
						<input type='button' value='7-4' class='btn btn-primary auto' onclick='AutoTimeIn74(\"". $row_employee['empid'] ."\")'>
					</td>
					<!-- Time In -->
					<td>
						<input type='text' onblur='timeValidation(this)' class='timein1 timepicker form-control input-sm' value='' name='timein1[".$counter."]'>
					</td> 
					<!-- Time Out-->
					<td>
						<input type='text' onblur='timeValidation(this)' class='timeout1 timepicker form-control input-sm' value='' name='timeout1[".$counter."]'>
					</td> 
					<!-- Half Day Checkbox-->
					<td>
						<input type='checkbox' class='halfdayChk' name='halfday[".$counter."]' onclick='halfDay(\"". $row_employee['empid'] ."\")' disabled>
					</td>
					<!-- AFTER BREAK Time In -->
					<td>
						<input type='text' onblur='timeValidation(this)' class='timein2 timepicker form-control input-sm' value=''  name='timein2[".$counter."]'>
					</td> 
					<!-- AFTER BREAK Time Out-->
					<td>
						<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' value='' name='timeout2[".$counter."]'>
					</td> 
					<!-- Night Shift Checkbox-->
					<td>
						<input type='checkbox' class='nightshiftChk' name='nightshift[".$counter."]' onclick='nightshift_ChkBox(\"". $row_employee['empid'] ."\")' disabled>
					</td>
					<!-- NIGHT SHIFT Time In -->
					<td>
						<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[".$counter."]' disabled>
					</td> 
					<!-- NIGHT SHIFT Time Out-->
					<td>
						<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[".$counter."]' disabled>
					</td> 
					<!-- Working Hours -->
					<td>
						<input type='text' placeholder='--'' class='form-control input-sm workinghours' value='' disabled>
						<input type='hidden' class='workinghoursH'  name='workinghrs[".$counter."]' >
					</td> 
					<!-- Overtime -->
					<td>
						<input type='text' placeholder='--' class='form-control input-sm overtime' value=''  disabled>
						<input type='hidden' class='overtimeH' name='othrs[".$counter."]' >
					</td> 
					<!-- Undertime -->
					<td>
						<input type='text' placeholder='--' class='form-control input-sm undertime' value='' disabled>
						<input type='hidden' class='undertimeH' name='undertime[".$counter."]' >
					</td>
					<!-- Night Differential --> 
					<td>
						<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='' disabled>
						<input type='hidden' class='nightdiffH' name='nightdiff[".$counter."]' >
					</td>
					<!-- Extra allowance Input --> 
						<input type='hidden' name='xallow[".$counter."]' class='hiddenXAllow'>
					<!-- Remarks Input --> 
						<input type='hidden' name='remarks[".$counter."]' class='hiddenRemarks'>

					<!-- Attendance Status -->
						<input type='hidden' name='attendance[".$counter."]' class='attendance'>
					
					<!-- Extra allowance Button --> 
					<td>
						<a class='btn btn-sm btn-primary xallowance' data-toggle='modal' data-target='#XAllowanceModal' onclick='xAllowance(\"". $row_employee['empid'] ."\")'>X Allow <span class='xall-icon'></span></a>
					</td>	
					

					<!-- Remarks Button --> 
					<td>
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\"); remarksValidation(\"". $row_employee['empid'] ."\")'>Remarks <span class='remarks-icon'></span></a>
					</td>
					<td>
						<a class='btn btn-sm btn-danger absent' onclick='absent(\"". $row_employee['empid'] ."\")'>Absent</a>
					</td>
				</tr>
					";
					$counter++;
		}
	}
}
?>