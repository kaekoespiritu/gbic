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
		$employees = "SELECT * FROM employee WHERE site = '$site' AND position = '$position' ORDER BY lastname";
	}
	else if(isset($_POST['txt_search']))
	{
		$search = $_POST['txt_search'];
		$employees = "SELECT * FROM employee WHERE 	site = '$site' AND firstname LIKE '%$search%' OR 
													lastname LIKE '%$search%' OR
													position LIKE '%$search%' ORDER BY lastname";
	}
	else
	{
		$employees = "SELECT * FROM employee WHERE site = '$site'  ORDER BY lastname";
	}
	$employees_query = mysql_query($employees);

	$dateChecker = "SELECT date FROM attendance WHERE date = '$date'";
	$dateCheckerQuery = mysql_query($dateChecker);

	if($dateCheckerQuery)//Checks if there is already an attendance made for that specific date
	{
		$dateRows = mysql_num_rows($dateCheckerQuery);
	}	

	// theres already an data on that specific day on the database
	$counter = 0;
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
										<input type='text' value='". $empRow['timein'] ."' class='timein timepicker form-control input-sm' name='timein[".$counter."]'>
									</td>
								<!-- Time Out-->
									<td>
										<input type='text' class='timeout timepicker form-control input-sm' value='' name='timeout[".$counter."]'>
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
										<input type='text' placeholder='ABSENT' class='timein timepicker form-control input-sm' value='' name='timein[".$counter."]'>
									</td> 
								<!-- Time Out-->
									<td>
										<input type='text' class='timeout timepicker form-control input-sm' placeholder='ABSENT' value='' name='timeout[".$counter."]'>
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
										<input type='text' class='timein timepicker form-control input-sm' value='". $empRow['timein'] ."' name='timein[".$counter."]'>
									</td>
								<!-- Time Out-->
									<td>
										<input type='text' class='timeout timepicker form-control input-sm' value='". $empRow['timeout'] ."' name='timeout[".$counter."]'>
									</td>";
				// Working hours
					if($empRow['workhours'] <= 5)
					{
						Print "<!-- Working Hours -->
							<td>
								<input type='text' placeholder='--'' value='". $empRow['workhours'] ." hrs/HALFDAY' class='form-control input-sm workinghours' disabled>
								<input type='hidden' class='workinghoursH' value='". $empRow['workhours'] ." hrs/HALFDAY' name='workinghrs[".$counter."]' >
							</td>";
					}
					else
					{
						Print "<!-- Working Hours -->
							<td>
								<input type='text' placeholder='--'' value='". $empRow['workhours'] ." hours' class='form-control input-sm workinghours' disabled>
								<input type='hidden' class='workinghoursH' value='". $empRow['workhours'] ." hours' name='workinghrs[".$counter."]' >
							</td>";
					}
				// Overtime
					if($empRow['overtime'] != 0)
					{
						Print "<!-- Overtime -->
							<td>
								<input type='text' placeholder='--' class='form-control input-sm overtime' value='". $empRow['overtime'] ." hours'  disabled>
								<input type='hidden' class='overtimeH' value='". $empRow['overtime'] ." hours' name='othrs[".$counter."]' >
							</td>";
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
						Print "<!-- Undertime -->
							<td>
								<input type='text' placeholder='--' value='". $empRow['undertime'] ." hours' class='form-control input-sm undertime'  disabled>
								<input type='hidden' class='undertimeH' value='". $empRow['undertime'] ." hours' name='undertime[".$counter."]'>
							</td>";
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
						Print "<!-- Night Differential --> 
							<td>
								<input type='text' placeholder='--' class='form-control input-sm nightdiff' value='". $empRow['nightdiff'] ." hours' disabled>
								<input type='hidden' class='nightdiffH' value='". $empRow['nightdiff'] ." hours' name='nightdiff[".$counter."]' >
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
				if($empRow['remarks']!== "")
				{
					Print "<!-- Remarks Input --> 
						<input type='hidden' value='". $empRow['remarks'] ."' name='remarks[".$counter."]' class='hiddenRemarks'>";
					Print "<!-- Remarks Button --> 
					<td>
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\")'>Remarks <span class='icon glyphicon glyphicon-edit'></span></a>
					</td>";
				}
				else
				{
					Print "<!-- Remarks Input --> 
						<input type='hidden' name='remarks[".$counter."]' class='hiddenRemarks'>";
					Print "<!-- Remarks Button --> 
					<td>
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\")'>Remarks <span class='icon'></span></a>
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
						<input type='text' class='timein timepicker form-control input-sm' value='' name='timein[".$counter."]'>
					</td> 
					<!-- Time Out-->
					<td>
						<input type='text' class='timeout timepicker form-control input-sm' value='' name='timeout[".$counter."]'>
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
						<a class='btn btn-sm btn-primary remarks' data-toggle='modal' data-target='#remarks' onclick='remarks(\"". $row_employee['empid'] ."\")'>Remarks <span class='icon'></span></a>
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