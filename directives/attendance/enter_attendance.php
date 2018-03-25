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
		$employees = "SELECT * FROM employee WHERE 	site = '$site' AND firstname LIKE '%$search%' OR 
													lastname LIKE '%$search%' OR
													position LIKE '%$search%' AND employment_status = '1' ORDER BY lastname";
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
		
	//Print "<script>console.log('".$checkerBuilder."')</script>";

	$dateChecker = "SELECT date FROM attendance WHERE date = '$date' $checkerBuilder";
	// Print "<script>console.log('".$dateChecker."')</script>";
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
				$empidChecker = $row_employee['empid'];
				$empCheck = "SELECT * FROM attendance WHERE date = '$date' AND empid = '$empidChecker' ";
				$empCheckQuery = mysql_query($empCheck);
				$empRow = mysql_fetch_assoc($empCheckQuery);
			// Attendance Check
				if($empRow['attendance'] == 0)//No input
				{
					Print 	"<tr id=\"". $row_employee['empid'] ."\">
								<!-- Employee ID -->
									<td class='empName'>
										". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
									</td>
								<!-- Position -->
									<td>
										". $row_employee['position'] ."
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
									<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[".$counter."]' readonly>
								</td> 
								<!-- NIGHT SHIFT Time Out-->
								<td>
									<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[".$counter."]' readonly>
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
					Print 	"<tr id=\"". $row_employee['empid'] ."\" class='danger'>
								<!-- Employee ID -->
									<td class='empName'>
										". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
									</td>
								<!-- Position -->
									<td>
										". $row_employee['position'] ."
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
									<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value='' placeholder='ABSENT' name='timein3[".$counter."]'readonly>
								</td> 
								<!-- NIGHT SHIFT Time Out-->
								<td>
									<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' placeholder='ABSENT' name='timeout3[".$counter."]' readonly>
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
				else if($empRow['attendance'] == 2)//Present
				{
					Print 	"<tr id=\"". $row_employee['empid'] ."\" class='success'>
								<!-- Employee ID -->
									<td class='empName'>
										". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
									</td>
								<!-- Position -->
									<td>
										". $row_employee['position'] ."
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
										<input type='text' onblur='timeValidation(this)' class='timein2 timepicker form-control input-sm' placeholder='Half Day' name='timein2[".$counter."]'>
									</td>
								<!-- AFTER BREAK Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout2 timepicker form-control input-sm' placeholder='Half Day' name='timeout2[".$counter."]'>
									</td>";
					}
					else
					{
						Print 	"<!-- Half Day Checkbox-->
									<td>
										<input type='checkbox' class='halfdayChk' onclick='halfDay(\"". $row_employee['empid'] ."\")' name='halfday[".$counter."]' disabled>
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
										<input type='checkbox' class='nightshiftChk' name='nightshift[".$counter."]' onclick='nightshift_ChkBox(\"". $row_employee['empid'] ."\")' disabled>
									</td>
								<!-- NIGHT SHIFT Time In -->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[".$counter."]' readonly>
									</td> 
								<!-- NIGHT SHIFT Time Out-->
									<td>
										<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[".$counter."]' readonly>
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
								<input type='text' placeholder='--'' value='". $wHrs ." hrs, ".$wMin." mins/HALFDAY' class='form-control input-sm workinghours' disabled>
								<input type='hidden' class='workinghoursH' value='". $wHrs ." hrs, ".$wMin." mins/HALFDAY' name='workinghrs[".$counter."]' >
							</td>";
						}
						else
						{
							$wHrs = $work[0];
							//Print "<script>alert('workinghrs1')</script>";
							Print "<!-- Working Hours -->
							<td>
								<input type='text' placeholder='--'' value='". $wHrs ." hrs/HALFDAY' class='form-control input-sm workinghours' disabled>
								<input type='hidden' class='workinghoursH' value='". $wHrs ." hrs/HALFDAY' name='workinghrs[".$counter."]' >
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
								<input type='hidden' class='workinghoursH' value='". $wHrs ." hrs, ".$wMin." mins/HALFDAY' name='workinghrs[".$counter."]' >
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

							Print "<!-- Overtime -->
							<td>
								<input type='text' placeholder='--' class='form-control input-sm overtime' value='". $othrs ." hrs, ".$otmin." mins'  disabled>
								<input type='hidden' class='overtimeH' value='". $othrs ." hrs, ".$otmin." mins' name='othrs[".$counter."]' >
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

							Print "<!-- Undertime -->
							<td>
								<input type='text' placeholder='--' value='". $uHrs ." hrs, ".$uMin." mins' class='form-control input-sm undertime'  disabled>
								<input type='hidden' class='undertimeH' value='". $uHrs ." hrs, ".$uMin." mins' name='undertime[".$counter."]'>
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
						$work = explode(".", $empRow['nightdiff']);//Separates the string
						$nHrs = $work[0];
						Print "<!-- Night Differential --> 
							<td>
								<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='". $nHrs ." hrs' disabled>
								<input type='hidden' class='nightdiffH' value='". $nHrs ." hrs' name='nightdiff[".$counter."]' >
							</td>";
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
				Print 	
					"<input type='hidden' name='empid[".$counter."]' value=". $row_employee['empid'] .">";
			// REMARKS	
				if($empRow['remarks'] != "")
				{
					Print "<script>console.log('".$empRow['remarks']."')</script>";
					Print "<!-- Remarks Input --> 
						<input type='hidden' value='". $empRow['remarks'] ."' name='remarks[".$counter."]' class='hiddenRemarks'>";
					Print "<!-- Remarks Button --> 
					<td>
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\"); remarksValidation(\"". $row_employee['empid'] ."\")'>Remarks <span class='icon glyphicon glyphicon-edit'></span></a>
					</td>";
				}
				else
				{
					Print "<!-- Remarks Input --> 
						<input type='hidden' name='remarks[".$counter."]' class='hiddenRemarks'>";
					Print "<!-- Remarks Button yow--> 
					<td>
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\"); remarksValidation(\"". $row_employee['empid'] ."\");'>Remarks <span class='icon'></span></a>
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

			Print 	"	
				<tr id=\"". $row_employee['empid'] ."\">
					<input type='hidden' name='empid[".$counter."]' value=". $row_employee['empid'] .">
					<td class='empName'>
						". $row_employee['lastname'] .", ". $row_employee['firstname'] ."
					</td>
					<td>
						". $row_employee['position'] ."
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
						<input type='text' onblur='timeValidation(this)' class='timein3 timepicker form-control input-sm' value=''  name='timein3[".$counter."]' readonly>
					</td> 
					<!-- NIGHT SHIFT Time Out-->
					<td>
						<input type='text' onblur='timeValidation(this)' class='timeout3 timepicker form-control input-sm' value='' name='timeout3[".$counter."]' readonly>
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
					<!-- Remarks Input --> 
						<input type='hidden' name='remarks[".$counter."]' class='hiddenRemarks'>

					<!-- Attendance Status -->
						<input type='hidden' name='attendance[".$counter."]' class='attendance'>
					<!-- Remarks Button --> 
					<td>
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\"); remarksValidation(\"". $row_employee['empid'] ."\")'>Remarks <span class='icon'></span></a>
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