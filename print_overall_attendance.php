<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$site = $_GET['site'];
$position = $_GET['position'];
$require = $_GET['req'];
$date = $_GET['date'];


//Middleware
$positionChecker = "SELECT * FROM job_position WHERE position = '$position' AND active = '1'";
$posCheckQuery = mysql_query($positionChecker);
if(mysql_num_rows($posCheckQuery) == 0)
{
	if($position != "null")	
		header("location:index.php");
	
}
// Checks if requirement in HTTP is altered by user manually 
switch($require) 
{
	case "null": 
	case "all": $reqDisplay = "COMPLETE/INCOMPLETE REQUIREMENTS";break;
	case "withReq": $reqDisplay = "COMPLETE REQUIREMENTS";break;
	case "withOReq": $reqDisplay = "INCOMPLETE REQUIREMENTS";break;
	default: header("location:index.php");;
}

if($date == "onProcess")
{
	$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";
	$payDateQuery = mysql_query($payrollDate);
	$payArr = mysql_fetch_assoc($payDateQuery);

	$startDate = $payArr['date'];
	$endDate = date('F d, Y', strtotime('+6 day', strtotime($payArr['date'])));
}
else
{
	$startDate = date('F d, Y', strtotime('-7 day', strtotime($date)));
	$endDate = date('F d, Y', strtotime('-1 day', strtotime($date)));
}
	

$filename = $site." Attendance Report ".$startDate."-".$endDate.".xlsx";
// Last Name, First Name of Site (Date) - Payroll.xls


$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//----------------- Body ---------------------//
//Filters
$appendQuery = "";
if($position != "null")
	$appendQuery .= "AND position = '".$position."' ";
if($require != "null")
{
	if($require != "all")
	{
		$req = ($require == "withReq" ? 1 : 0);
		$appendQuery .= "AND complete_doc = '".$req."' ";
	}
}

$spreadSheetCounter = 0;

$siteLocation = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' $appendQuery ORDER BY lastname ASC, position ASC";
$siteQuery = mysql_query($siteLocation) or die (mysql_error());
$rowCounter = 8; //start for the data in the row of excel

for($siteSwitch = 1; $siteSwitch <= 2; $siteSwitch++)//interchanging sheets
{
	$counter = 0;// Number of employees in a spreadsheet
	$rowCounter = 8;//the starting data row
	$activeSheet = $sheet -> createSheet($spreadSheetCounter);
	$activeSheet->setTitle($site."(".$siteSwitch.")");

	//Merge cells
	$activeSheet->mergeCells('A1:C1');//site name
	$activeSheet->mergeCells('A2:C2');//date
	$activeSheet->mergeCells('A3:C3');//"Complete requirements"
	if($siteSwitch == 1)
		$activeSheet->mergeCells('D1:AE3');//"Weekly time record employee"
	else
		$activeSheet->mergeCells('D1:X3');//"Weekly time record employee"

	$activeSheet->mergeCells('A4:A7');//"#"
	$activeSheet->mergeCells('B4:B7');//Name of worker
	$activeSheet->mergeCells('C4:C7');//Position

	//-------------- Days --------------//
	//Wednesday or Sunday
	$activeSheet->mergeCells('D4:J4');//Wednesday or Sunday
	$activeSheet->mergeCells('D5:G5');//Regular Day
	$activeSheet->mergeCells('H5:I5');//Overtime
	$activeSheet->mergeCells('G6:I6');//OT Hrs
	$activeSheet->mergeCells('J5:J7');//Remarks

	//Thusday or Monday
	$activeSheet->mergeCells('K4:Q4');//Thursday or Monday
	$activeSheet->mergeCells('K5:N5');//Regular Day
	$activeSheet->mergeCells('O5:P5');//Overtime
	$activeSheet->mergeCells('N6:P6');//OT Hrs
	$activeSheet->mergeCells('Q5:Q7');//Remarks

	//Friday or Tuesday
	$activeSheet->mergeCells('R4:X4');//Friday or Tuesday
	$activeSheet->mergeCells('R5:U5');//Regular Day
	$activeSheet->mergeCells('V5:W5');//Overtime
	$activeSheet->mergeCells('U6:W6');//OT Hrs
	$activeSheet->mergeCells('X5:X7');//Remarks

	if($siteSwitch == 1) 
	{
		//Saturday
		$activeSheet->mergeCells('Y4:AE4');//Saturday
		$activeSheet->mergeCells('Y5:AB5');//Regular Day
		$activeSheet->mergeCells('AC5:AD5');//Overtime
		$activeSheet->mergeCells('AB6:AD6');//OT Hrs
		$activeSheet->mergeCells('AE5:AE7');//Remarks
	}
		
	//----------------- Contents ---------------------//
	//Title Contents
	$activeSheet->setCellValue('A1', 'SITE: '. $site);//Site
	$activeSheet->setCellValue('A2', "PERIOD: ".$startDate." - ".$endDate);//Date
	$activeSheet->setCellValue('A3', $reqDisplay);//"Complete Requirements"
	$activeSheet->setCellValue('D1', 'WEEKLY TIME RECORD OF EMPLOYEE');//"Weekly time record comployee"

	//Header Contents
	$activeSheet->setCellValue('A4', '#');
	$activeSheet->setCellValue('B4', 'Name of worker');
	$activeSheet->setCellValue('C4', 'Position');

	//Wednesday / Sunday
	if($siteSwitch == 1)
		$activeSheet->setCellValue('D4', 'WEDNESDAY');
	else
		$activeSheet->setCellValue('D4', 'SUNDAY');
	$activeSheet->setCellValue('D5', 'REGULAR DAY');
	$activeSheet->setCellValue('H5', 'OVERTIME');
	$activeSheet->setCellValue('J5', 'Remarks');
	$activeSheet->setCellValue('D6', 'AM');
	$activeSheet->setCellValue('F6', 'PM');
	$activeSheet->setCellValue('G6', 'OT Hrs');
	$activeSheet->setCellValue('D7', 'In');
	$activeSheet->setCellValue('E7', 'Out');
	$activeSheet->setCellValue('F7', 'In');
	$activeSheet->setCellValue('G7', 'Out');
	$activeSheet->setCellValue('H7', 'In');
	$activeSheet->setCellValue('I7', 'Out');

	//Thursday / Monday
	if($siteSwitch == 1)
		$activeSheet->setCellValue('K4', 'THURSDAY');
	else
		$activeSheet->setCellValue('K4', 'MONDAY');
	$activeSheet->setCellValue('K5', 'REGULAR DAY');
	$activeSheet->setCellValue('O5', 'OVERTIME');
	$activeSheet->setCellValue('Q5', 'Remarks');
	$activeSheet->setCellValue('K6', 'AM');
	$activeSheet->setCellValue('M6', 'PM');
	$activeSheet->setCellValue('N6', 'OT Hrs');
	$activeSheet->setCellValue('K7', 'In');
	$activeSheet->setCellValue('L7', 'Out');
	$activeSheet->setCellValue('M7', 'In');
	$activeSheet->setCellValue('N7', 'Out');
	$activeSheet->setCellValue('O7', 'In');
	$activeSheet->setCellValue('P7', 'Out');

	//Friday / Tuesday
	if($siteSwitch == 1)
		$activeSheet->setCellValue('R4', 'FRIDAY');
	else
		$activeSheet->setCellValue('R4', 'TUESDAY');
	$activeSheet->setCellValue('R5', 'REGULAR DAY');
	$activeSheet->setCellValue('V5', 'OVERTIME');
	$activeSheet->setCellValue('X5', 'Remarks');
	$activeSheet->setCellValue('R6', 'AM');
	$activeSheet->setCellValue('T6', 'PM');
	$activeSheet->setCellValue('U6', 'OT Hrs');
	$activeSheet->setCellValue('R7', 'In');
	$activeSheet->setCellValue('S7', 'Out');
	$activeSheet->setCellValue('T7', 'In');
	$activeSheet->setCellValue('U7', 'Out');
	$activeSheet->setCellValue('V7', 'In');
	$activeSheet->setCellValue('W7', 'Out');

	if($siteSwitch == 1)
	{
		//Saturday
		$activeSheet->setCellValue('Y4', 'SATURDAY');
		$activeSheet->setCellValue('Y5', 'REGULAR DAY');
		$activeSheet->setCellValue('AC5', 'OVERTIME');
		$activeSheet->setCellValue('AE5', 'Remarks');
		$activeSheet->setCellValue('Y6', 'AM');
		$activeSheet->setCellValue('AA6', 'PM');
		$activeSheet->setCellValue('AB6', 'OT Hrs');
		$activeSheet->setCellValue('Y7', 'In');
		$activeSheet->setCellValue('Z7', 'Out');
		$activeSheet->setCellValue('AA7', 'In');
		$activeSheet->setCellValue('AB7', 'Out');
		$activeSheet->setCellValue('AC7', 'In');
		$activeSheet->setCellValue('AD7', 'Out');
	}
		

	if($siteSwitch == 2)
	{
		$employees = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' ".$appendQuery." ORDER BY lastname ASC, position ASC";
		$employeesQuery = mysql_query($employees) or die (mysql_error());
		while($empArray = mysql_fetch_assoc($employeesQuery))//Loop for employees in the site
		{
			$counter++;
			$employeeName = $empArray['lastname'].", ".$empArray['firstname'];
			$employeePosition = $empArray['position'];
			$empid = $empArray['empid'];

			//=================

			$sunIn1 = "";
			$sunOut1 = "";
			$sunIn2 = "";
			$sunOut2 = "";
			$sunIn3 = "";
			$sunOut3 = "";
			$sunRemarks = "";

			$monIn1 = "";
			$monOut1 = "";
			$monIn2 = "";
			$monOut2 = "";
			$monIn3 = "";
			$monOut3 = "";
			$monRemarks = "";

			$tueIn1 = "";
			$tueOut1 = "";
			$tueIn2 = "";
			$tueOut2 = "";
			$tueIn3 = "";
			$tueOut3 = "";
			$tueRemarks = "";

			//boolean for absences
			$sunBool = false;
			$monBool = false;
			$tueBool = false;

			//boolean for halfday
			$sunBoolHD = false;
			$monBoolHD = false;
			$tueBoolHD = false;

			//boolean for No repeat of day
			$sunBoolNoRep = true;
			$monBoolNoRep = true;
			$tueBoolNoRep = true;

			$attendance2 = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
			$attendanceQuery2 = mysql_query($attendance2) or die (mysql_error());
			
			while($attArr2 = mysql_fetch_assoc($attendanceQuery2))//Loop for employees in the site
			{
				$dayN = date('l', strtotime($attArr2['date']));//gets the day in the week

				if($dayN == "Sunday" && $sunBoolNoRep)
				{
					$sunBoolNoRep = false;// no repeat
					if($attArr2['attendance'] == 2)//employee is present
					{
						$sunIn1 = $attArr2['timein'];
						$sunOut1 = $attArr2['timeout'];
						$sunIn2 = $attArr2['afterbreak_timein'];
						$sunOut2 = $attArr2['afterbreak_timeout'];
						$sunIn3 = $attArr2['nightshift_timein'];
						$sunOut3 = $attArr2['nightshift_timeout'];

						if($sunIn2 == "")
							$sunBoolHD = true;//trigger H.D in display
					}
					if($attArr2['attendance'] == 1)//employee is present
					{
						$sunBool = true;//employee is absent
					}
					$sunRemarks = $attArr2['remarks'];
				}
				else if($dayN == "Monday" && $monBoolNoRep)
				{
					$monBoolNoRep = false; // no repeat
					if($attArr2['attendance'] == 2)//employee is present
					{
						$monIn1 = $attArr2['timein'];
						$monOut1 = $attArr2['timeout'];
						$monIn2 = $attArr2['afterbreak_timein'];
						$monOut2 = $attArr2['afterbreak_timeout'];
						$monIn3 = $attArr2['nightshift_timein'];
						$monOut3 = $attArr2['nightshift_timeout'];

						if($monIn2 == "")
							$monBoolHD = true;//trigger H.D in display
					}
					if($attArr2['attendance'] == 1)//employee is present
					{
						$monBool = true;//employee is absent
					}
					$monRemarks = $attArr2['remarks'];
				}
				else if($dayN == "Tuesday" && $tueBoolNoRep)
				{
					$tueBoolNoRep = false; //no repeat
					if($attArr2['attendance'] == 2)//employee is present
					{
						$tueIn1 = $attArr2['timein'];
						$tueOut1 = $attArr2['timeout'];
						$tueIn2 = $attArr2['afterbreak_timein'];
						$tueOut2 = $attArr2['afterbreak_timeout'];
						$tueIn3 = $attArr2['nightshift_timein'];
						$tueOut3 = $attArr2['nightshift_timeout'];

						if($tueIn2 == "")
							$tueBoolHD = true;//trigger H.D in display
					}
					if($attArr2['attendance'] == 1)//employee is present
					{
						$tueBool = true;//employee is absent
					}
					$wedRemarks = $attArr2['remarks'];
				}
		
			}
				$activeSheet->setCellValue('A'.$rowCounter, $counter);
				$activeSheet->setCellValue('B'.$rowCounter, $employeeName);
				$activeSheet->setCellValue('C'.$rowCounter, $employeePosition);
				
				if($sunBool)//Sunday
				{
					$activeSheet->mergeCells('D'.$rowCounter.':I'.$rowCounter);//Sunday
					$activeSheet->setCellValue('D'.$rowCounter, 'A B S E N T');
				}
				else
				{
					$activeSheet->setCellValue('D'.$rowCounter, $sunIn1);
					$activeSheet->setCellValue('E'.$rowCounter, $sunOut1);

					if($sunBoolHD)
					{
						$activeSheet->mergeCells('F'.$rowCounter.':I'.$rowCounter);//Sunday
						$activeSheet->setCellValue('F'.$rowCounter, 'H A L F  D A Y');
					}
					else
					{
						$activeSheet->setCellValue('F'.$rowCounter, $sunIn2);
						$activeSheet->setCellValue('G'.$rowCounter, $sunOut2);
						$activeSheet->setCellValue('H'.$rowCounter, $sunIn3);
						$activeSheet->setCellValue('I'.$rowCounter, $sunOut3);
						}
				}
				//Remarks
				$activeSheet->setCellValue('J'.$rowCounter, stripslashes($sunRemarks));

				if($monBool)//Monday
				{
					$activeSheet->mergeCells('K'.$rowCounter.':P'.$rowCounter);//Monday
					$activeSheet->setCellValue('K'.$rowCounter, 'A B S E N T');
				}
				else
				{
					$activeSheet->setCellValue('K'.$rowCounter, $monIn1);
					$activeSheet->setCellValue('L'.$rowCounter, $monOut1);

					if($sunBoolHD)
					{
						$activeSheet->mergeCells('M'.$rowCounter.':P'.$rowCounter);//Monday
						$activeSheet->setCellValue('M'.$rowCounter, 'H A L F  D A Y');
					}
					else
					{
						$activeSheet->setCellValue('M'.$rowCounter, $monIn2);
						$activeSheet->setCellValue('N'.$rowCounter, $monOut2);
						$activeSheet->setCellValue('O'.$rowCounter, $monIn3);
						$activeSheet->setCellValue('P'.$rowCounter, $monOut3);
					}
				}
				//Remarks
				$activeSheet->setCellValue('Q'.$rowCounter, stripslashes($monRemarks));

				if($tueBool)//Tuesday
				{
					$activeSheet->mergeCells('R'.$rowCounter.':W'.$rowCounter);//Tuesday
					$activeSheet->setCellValue('R'.$rowCounter, 'A B S E N T');
				}
				else
				{
					$activeSheet->setCellValue('R'.$rowCounter, $tueIn1);
					$activeSheet->setCellValue('S'.$rowCounter, $tueOut1);

					if($sunBoolHD)
					{
						$activeSheet->mergeCells('T'.$rowCounter.':W'.$rowCounter);//Tuesday
						$activeSheet->setCellValue('T'.$rowCounter, 'H A L F  D A Y');
					}
					else
					{
						$activeSheet->setCellValue('T'.$rowCounter, $tueIn2);
						$activeSheet->setCellValue('U'.$rowCounter, $tueOut2);
						$activeSheet->setCellValue('V'.$rowCounter, $tueIn3);
						$activeSheet->setCellValue('W'.$rowCounter, $tueOut3);
						}
				}
				//Remarks
				$activeSheet->setCellValue('X'.$rowCounter, stripslashes($tueRemarks));

			//=================
			$rowCounter++; //Row counter
			//Style of the spreadsheet
		}

	}
	else// siteSwitch == 1
	{
		while($siteArr = mysql_fetch_assoc($siteQuery))//Loop for employees in the site
		{
			$counter++;
			$employeeName = $siteArr['lastname'].", ".$siteArr['firstname'];
			$employeePosition = $siteArr['position'];
			$empid = $siteArr['empid'];

			//=================

			$attendance = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";

			$attendanceQuery = mysql_query($attendance);
				
			//preset variable for time in and time out
			$wedIn1 = "";
			$wedOut1 = "";
			$wedIn2 = "";
			$wedOut2 = "";
			$wedIn3 = "";
			$wedOut3 = "";
			$wedRemarks = "";

			$thuIn1 = "";
			$thuOut1 = "";
			$thuIn2 = "";
			$thuOut2 = "";
			$thuIn3 = "";
			$thuOut3 = "";
			$thuRemarks = "";

			$friIn1 = "";
			$friOut1 = "";
			$friIn2 = "";
			$friOut2 = "";
			$friIn3 = "";
			$friOut3 = "";
			$friRemarks = "";

			$satIn1 = "";
			$satOut1 = "";
			$satIn2 = "";
			$satOut2 = "";
			$satIn3 = "";
			$satOut3 = "";
			$satRemarks = "";

			//boolean for absences
			$wedBool = false;
			$thuBool = false;
			$friBool = false;
			$satBool = false;

			//boolean for halfday
			$wedBoolHD = false;
			$thuBoolHD = false;
			$friBoolHD = false;
			$satBoolHD = false;
			
			//boolean for No repeat of day
			$wedBoolNoRep = true;
			$thuBoolNoRep = true;
			$friBoolNoRep = true;
			$satBoolNoRep = true;

			while($attArr = mysql_fetch_assoc($attendanceQuery))
			{
				$day = date('l', strtotime($attArr['date']));

				if($day == "Wednesday" && $wedBoolNoRep)
				{
					
					$wedBoolNoRep = false;//no repeat
					if($attArr['attendance'] == 2)//employee is present
					{
						$wedIn1 = $attArr['timein'];
						$wedOut1 = $attArr['timeout'];
						$wedIn2 = $attArr['afterbreak_timein'];
						$wedOut2 = $attArr['afterbreak_timeout'];
						$wedIn3 = $attArr['nightshift_timein'];
						$wedOut3 = $attArr['nightshift_timeout'];

						if($wedIn2 == "")
							$wedBoolHD = true;//trigger H.D in display
					}
					if($attArr['attendance'] == 1)//employee is present
					{
						$wedBool = true;//employee is absent
					}
					$wedRemarks = $attArr['remarks'];
				}
				else if($day == "Thursday" && $thuBoolNoRep)
				{
					$thuBoolNoRep = false;
					if($attArr['attendance'] == 2)//employee is present
					{
						$thuIn1 = $attArr['timein'];
						$thuOut1 = $attArr['timeout'];
						$thuIn2 = $attArr['afterbreak_timein'];
						$thuOut2 = $attArr['afterbreak_timeout'];
						$thuIn3 = $attArr['nightshift_timein'];
						$thuOut3 = $attArr['nightshift_timeout'];

						if($thuIn2 == "")
							$thuBoolHD = true;//trigger H.D in display
					}
					if($attArr['attendance'] == 1)//employee is present
					{
						$thuBool = true;//employee is absent
					}
					$thuRemarks = $attArr['remarks'];

				}
				else if($day == "Friday" && $friBoolNoRep)
				{
					$friBoolNoRep = false;
					if($attArr['attendance'] == 2)//employee is present
					{
						$friIn1 = $attArr['timein'];
						$friOut1 = $attArr['timeout'];
						$friIn2 = $attArr['afterbreak_timein'];
						$friOut2 = $attArr['afterbreak_timeout'];
						$friIn3 = $attArr['nightshift_timein'];
						$friOut3 = $attArr['nightshift_timeout'];

						if($friIn2 == "")
							$friBoolHD = true;//trigger H.D in display
					}
					if($attArr['attendance'] == 1)//employee is present
					{
						$friBool = true;//employee is absent
					}
					$friRemarks = $attArr['remarks'];
				}
				else if($day == "Saturday" && $satBoolNoRep)
				{
					$satBoolNoRep = false; // no repeat
					if($attArr['attendance'] == 2)//employee is present
					{
						$satIn1 = $attArr['timein'];
						$satOut1 = $attArr['timeout'];
						$satIn2 = $attArr['afterbreak_timein'];
						$satOut2 = $attArr['afterbreak_timeout'];
						$satIn3 = $attArr['nightshift_timein'];
						$satOut3 = $attArr['nightshift_timeout'];

						if($satIn2 == "")
							$satBoolHD = true;//trigger H.D in display
					}
					if($attArr['attendance'] == 1)//employee is present
					{
						$satBool = true;//employee is absent
					}
					$satRemarks = $attArr['remarks'];
				}
				
			}
			$activeSheet->setCellValue('A'.$rowCounter, $counter);
			$activeSheet->setCellValue('B'.$rowCounter, $employeeName);
			$activeSheet->setCellValue('C'.$rowCounter, $employeePosition);
			

			if($wedBool)//WEDNESDAY
			{
				$activeSheet->mergeCells('D'.$rowCounter.':I'.$rowCounter);//Wednesday
				$activeSheet->setCellValue('D'.$rowCounter, 'A B S E N T');
			}
			else
			{
				$activeSheet->setCellValue('D'.$rowCounter, $wedIn1);
				$activeSheet->setCellValue('E'.$rowCounter, $wedOut1);

				if($wedBoolHD)
				{
					$activeSheet->mergeCells('F'.$rowCounter.':I'.$rowCounter);
					$activeSheet->setCellValue('F'.$rowCounter, 'H A L F  D A Y');
				}
				else
				{
					$activeSheet->setCellValue('F'.$rowCounter, $wedIn2);
					$activeSheet->setCellValue('G'.$rowCounter, $wedOut2);
					$activeSheet->setCellValue('H'.$rowCounter, $wedIn3);
					$activeSheet->setCellValue('I'.$rowCounter, $wedOut3);
				}
			}
			//Remarks
			$activeSheet->setCellValue('J'.$rowCounter, stripslashes($wedRemarks));

			if($thuBool)//THURSDAY
			{
				$activeSheet->mergeCells('K'.$rowCounter.':P'.$rowCounter);//Wednesday
				$activeSheet->setCellValue('K'.$rowCounter, 'A B S E N T');
			}
			else
			{
				$activeSheet->setCellValue('K'.$rowCounter, $thuIn1);
				$activeSheet->setCellValue('L'.$rowCounter, $thuOut1);

				if($thuBoolHD)
				{
					$activeSheet->mergeCells('M'.$rowCounter.':P'.$rowCounter);//Wednesday
					$activeSheet->setCellValue('M'.$rowCounter, 'H A L F  D A Y');
				}
				else
				{
					$activeSheet->setCellValue('M'.$rowCounter, $thuIn2);
					$activeSheet->setCellValue('N'.$rowCounter, $thuOut2);
					$activeSheet->setCellValue('O'.$rowCounter, $thuIn3);
					$activeSheet->setCellValue('P'.$rowCounter, $thuOut3);
					
				}
			}
			//Remarks
			$activeSheet->setCellValue('Q'.$rowCounter, stripslashes($thuRemarks));

			if($friBool)//FRIDAY
			{
				$activeSheet->mergeCells('R'.$rowCounter.':W'.$rowCounter);//Wednesday
				$activeSheet->setCellValue('R'.$rowCounter, 'A B S E N T');
			}
			else
			{
				$activeSheet->setCellValue('R'.$rowCounter, $friIn1);
				$activeSheet->setCellValue('S'.$rowCounter, $friOut1);

				if($friBoolHD)
				{
					$activeSheet->mergeCells('T'.$rowCounter.':P'.$rowCounter);//Wednesday
					$activeSheet->setCellValue('T'.$rowCounter, 'H A L F  D A Y');
				}
				else
				{
					$activeSheet->setCellValue('T'.$rowCounter, $friIn2);
					$activeSheet->setCellValue('U'.$rowCounter, $friOut2);
					$activeSheet->setCellValue('V'.$rowCounter, $friIn3);
					$activeSheet->setCellValue('W'.$rowCounter, $friOut3);
					}
			}
			//Remarks
			$activeSheet->setCellValue('X'.$rowCounter, stripslashes($friRemarks));

			if($satBool)//SATURDAY
			{
				$activeSheet->mergeCells('Y'.$rowCounter.':AD'.$rowCounter);//Wednesday
				$activeSheet->setCellValue('Y'.$rowCounter, 'A B S E N T');
			}
			else
			{
				$activeSheet->setCellValue('Y'.$rowCounter, $satIn1);
				$activeSheet->setCellValue('Z'.$rowCounter, $satOut1);

				if($satBoolHD)
				{
					$activeSheet->mergeCells('AA'.$rowCounter.':AD'.$rowCounter);//Wednesday
					$activeSheet->setCellValue('AA'.$rowCounter, 'H A L F  D A Y');
					
				}
				else
				{
					$activeSheet->setCellValue('AA'.$rowCounter, $satIn2);
					$activeSheet->setCellValue('AB'.$rowCounter, $satOut2);
					$activeSheet->setCellValue('AC'.$rowCounter, $satIn3);
					$activeSheet->setCellValue('AD'.$rowCounter, $satOut3);
				}
			}
			//Remarks
			$activeSheet->setCellValue('AE'.$rowCounter, stripslashes($satRemarks));

			//=================


			$rowCounter++; //Row counter
			//Style of the spreadsheet
		}
	}
		
	if($siteSwitch == 1) // Wednesday, Thursday, Friday, Saturday
	{
		$activeSheet->getStyle("A4:AE7")->applyFromArray($border_all_thin);
		$activeSheet->getStyle("A4:AE".$rowCounter)->applyFromArray($border_all_thin);
		$activeSheet->getStyle("D1:AE3")->applyFromArray($align_center);
		$activeSheet->getColumnDimension("B")->setAutoSize(true);
		$activeSheet->getColumnDimension("C")->setAutoSize(true);
	}	
	else // Sunday, Monday, Tuesday
	{
		$activeSheet->getStyle("A4:X7")->applyFromArray($border_all_thin);
		$activeSheet->getStyle("A4:X".$rowCounter)->applyFromArray($border_all_thin);
		$activeSheet->getStyle("D1:X3")->applyFromArray($align_center);
		$activeSheet->getColumnDimension("B")->setAutoSize(true);
		$activeSheet->getColumnDimension("C")->setAutoSize(true);
	}
	$spreadSheetCounter++;//increment the number of spreadsheet			
}


// array_map('unlink', glob( __DIR__."/*.xlsx"));

// header('Content-Type: application/vnd.ms-excel');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');

function SaveViaTempFile($objWriter)
{
    $filePath = __DIR__ . "/" . rand(0, getrandmax()) . rand(0, getrandmax()) . ".tmp";
    $objWriter->save($filePath);
    readfile($filePath);
    unlink($filePath);
}

SaveViaTempFile($objWriter);
// $objWriter->save('php://output');
// $objPHPWriter->save(str_replace('.php', '.xlsx', __FILE__));
// $file = 'test.xlsx';
// $objWriter->save($file);
// header("Location: $file");

// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="workbook2.xlsx"');
// header('Cache-Control: max-age=0');


// $objWriter->save('php://output');
// $objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
// $objWriter->save('php//output'); 
exit;

?>













