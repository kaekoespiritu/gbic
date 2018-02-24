<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';

// --------------- SAMPLE DATA ------------------ //
$location = "Muralla";//Sample data
$startDate = "October 18, 2017";
$endDate = "October 24, 2017";
// --------------- SAMPLE DATA ------------------ //


// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');
$date =  strftime("%h %e, %Y");
$filename = $date." - Attendance.xls";

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:C1');//site name
$activeSheet->mergeCells('A2:C2');//date
$activeSheet->mergeCells('A3:C3');//"Complete requirements"
$activeSheet->mergeCells('D1:AE3');//"Weekly time record employee"

$activeSheet->mergeCells('A4:A7');//"#"
$activeSheet->mergeCells('B4:B7');//Name of worker
$activeSheet->mergeCells('C4:C7');//Position

//-------------- Days --------------//
//Wednesday
$activeSheet->mergeCells('D4:J4');//Wednesday
$activeSheet->mergeCells('D5:G5');//Regular Day
$activeSheet->mergeCells('H5:I5');//Overtime
$activeSheet->mergeCells('G6:I6');//OT Hrs
$activeSheet->mergeCells('J5:J7');//Remarks

//Thusday
$activeSheet->mergeCells('K4:Q4');//Thursday
$activeSheet->mergeCells('K5:N5');//Regular Day
$activeSheet->mergeCells('O5:P5');//Overtime
$activeSheet->mergeCells('N6:P6');//OT Hrs
$activeSheet->mergeCells('Q5:Q7');//Remarks

//Friday
$activeSheet->mergeCells('R4:X4');//Friday
$activeSheet->mergeCells('R5:U5');//Regular Day
$activeSheet->mergeCells('V5:W5');//Overtime
$activeSheet->mergeCells('U6:W6');//OT Hrs
$activeSheet->mergeCells('X5:X7');//Remarks

//Saturday
$activeSheet->mergeCells('Y4:AE4');//Saturday
$activeSheet->mergeCells('Y5:AB5');//Regular Day
$activeSheet->mergeCells('AC5:AD5');//Overtime
$activeSheet->mergeCells('AB6:AD6');//OT Hrs
$activeSheet->mergeCells('AE5:AE7');//Remarks

//----------------- Contents ---------------------//
//Title Contents
$activeSheet->setCellValue('A1', 'Site: '. $location);//Site
$activeSheet->setCellValue('A2', "For the Period: ".$startDate." - ".$endDate);//Date
$activeSheet->setCellValue('A3', 'Complete Requirements');//"Complete Requirements"
$activeSheet->setCellValue('D1', 'WEEKLY TIME RECORD OF EMPLOYEE');//"Weekly time record comployee"

//Header Contents
$activeSheet->setCellValue('A4', '#');
$activeSheet->setCellValue('B4', 'Name of worker');
$activeSheet->setCellValue('D4', 'Position');

//Wednesday
$activeSheet->setCellValue('D4', 'WEDNESDAY');
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

//Thursday
$activeSheet->setCellValue('K4', 'THURSDAY');
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

//Friday
$activeSheet->setCellValue('R4', 'FRIDAY');
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


//----------------- Body ---------------------//

$location = "Muralla";//Sample data
$startDate = "October 18, 2017";
$endDate = "October 24, 2017";

$site = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' ORDER BY lastname ASC, position ASC";
$siteQuery = mysql_query($site) or die (mysql_error());
$counter = 0;
$rowCounter = 8; //start for the data in the row of excel
while($siteArr = mysql_fetch_assoc($siteQuery))
{

	
	$counter++;
	$employeeName = $siteArr['lastname'].", ".$siteArr['firstname'];
	$employeePosition = $siteArr['position'];
	$empid = $siteArr['empid'];

	$activeSheet->setCellValue('A'.$rowCounter, $counter);//#
	$activeSheet->setCellValue('B'.$rowCounter, $employeeName);//Name of worker
	$activeSheet->setCellValue('C'.$rowCounter, $employeePosition);//Name of worker

	//WEDNESDAY
	

	$attendance = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
	$attendanceQuery = mysql_query($attendance) or die (mysql_error());

	$wedAbsent = false;
	$thuAbsent = false;
	$friAbsent = false;
	$satAbsent = false;

	$wedHalfday = false;
	$thuHalfday = false;
	$friHalfday = false;
	$satHalfday = false;
	while($attRow = mysql_fetch_assoc($attendanceQuery))
	{
	// 	Print "<script>console.log('".$attRow['empid']."')</script>";
		$day = date('l', strtotime($attRow['date']));
		if($day == "Wednesday")
		{
			$wedTimeIn1 = $attRow['timein'];// 1st timein - timeout
			$wedTimeOut1 = $attRow['timeout'];

			$wedTimeIn2 = $attRow['afterbreak_timein'];// 2st timein - timeout
			$wedTimeOut2 = $attRow['afterbreak_timeout'];

			$wedTimeIn3 = $attRow['nightshift_timein'];// 3st timein - timeout
			$wedTimeOut3 = $attRow['nightshift_timeout'];

			$wedRemarks = $attRow['remarks'];

			//Checks if employee is absent or they took a halfday
			if(empty($wedTimeIn1))
				$wedAbsent = true;
			else if(empty($wedTimeIn2))
				$wedHalfday = true;
		}
		else if($day == "Thursday")
		{
			$thuTimeIn1 = $attRow['timein'];// 1st timein - timeout
			$thuTimeOut1 = $attRow['timeout'];

			$thuTimeIn2 = $attRow['afterbreak_timein'];// 2st timein - timeout
			$thuTimeOut2 = $attRow['afterbreak_timeout'];

			$thuTimeIn3 = $attRow['nightshift_timein'];// 3st timein - timeout
			$thuTimeOut3 = $attRow['nightshift_timeout'];

			$thuRemarks = $attRow['remarks'];//remarks

			//Checks if employee is absent or they took a halfday
			if(empty($thuTimeIn1))
				$thuAbsent = true;
			else if(empty($thuTimeIn2))
				$thuHalfday = true;
		}
		else if($day == "Friday")
		{
			$friTimeIn1 = $attRow['timein'];// 1st timein - timeout
			$friTimeOut1 = $attRow['timeout'];

			$friTimeIn2 = $attRow['afterbreak_timein'];// 2st timein - timeout
			$friTimeOut2 = $attRow['afterbreak_timeout'];

			$friTimeIn3 = $attRow['nightshift_timein'];// 3st timein - timeout
			$friTimeOut3 = $attRow['nightshift_timeout'];

			$friRemarks = $attRow['remarks'];//remarks

			//Checks if employee is absent or they took a halfday
			if(empty($friTimeIn1))
				$friAbsent = true;
			else if(empty($friTimeIn2))
				$friHalfday = true;
		}
		else if($day == "Saturday")
		{
			$satTimeIn1 = $attRow['timein'];// 1st timein - timeout
			$satTimeOut1 = $attRow['timeout'];

			$satTimeIn2 = $attRow['afterbreak_timein'];// 2st timein - timeout
			$satTimeOut2 = $attRow['afterbreak_timeout'];

			$satTimeIn3 = $attRow['nightshift_timein'];// 3st timein - timeout
			$satTimeOut3 = $attRow['nightshift_timeout'];

			$satRemarks = $attRow['remarks'];//remarks

			//Checks if employee is absent or they took a halfday
			if(empty($satTimeIn1))
				$satAbsent = true;
			else if(empty($satTimeIn2))
				$satHalfday = true;
		}
	}

	//WEDNESDAY
	if($wedAbsent)//Absent
	{
		$activeSheet->setCellValue('D'.$rowCounter, "Absent");

		$activeSheet->setCellValue('J'.$rowCounter, $wedRemarks);
	}
	else if($wedHalfday)//Halfday
	{
		$activeSheet->setCellValue('D'.$rowCounter, $wedTimeIn1);
		$activeSheet->setCellValue('E'.$rowCounter, $wedTimeOut1);
		$activeSheet->setCellValue('F'.$rowCounter, "Halfday");

		$activeSheet->setCellValue('J'.$rowCounter, $wedRemarks);
	}
	else//Default
	{
		$activeSheet->setCellValue('D'.$rowCounter, $wedTimeIn1);
		$activeSheet->setCellValue('E'.$rowCounter, $wedTimeOut1);
		$activeSheet->setCellValue('F'.$rowCounter, $wedTimeIn2);
		$activeSheet->setCellValue('G'.$rowCounter, $wedTimeOut2);
		$activeSheet->setCellValue('H'.$rowCounter, $wedTimeIn1);
		$activeSheet->setCellValue('I'.$rowCounter, $wedTimeOut1);
		$activeSheet->setCellValue('J'.$rowCounter, $wedRemarks);
	}
	

	//THURSDAY
	if($thuAbsent)
	{
		$activeSheet->setCellValue('K'.$rowCounter, "Absent");

		$activeSheet->setCellValue('Q'.$rowCounter, $thuRemarks);
	}
	else if($thuHalfday)
	{
		$activeSheet->setCellValue('K'.$rowCounter, $thuTimeIn1);
		$activeSheet->setCellValue('L'.$rowCounter, $thuTimeOut1);
		$activeSheet->setCellValue('M'.$rowCounter, "Halfday");

		$activeSheet->setCellValue('Q'.$rowCounter, $thuRemarks);
	}
	else
	{
		$activeSheet->setCellValue('K'.$rowCounter, $thuTimeIn1);
		$activeSheet->setCellValue('L'.$rowCounter, $thuTimeOut1);
		$activeSheet->setCellValue('M'.$rowCounter, $thuTimeIn2);
		$activeSheet->setCellValue('N'.$rowCounter, $thuTimeOut2);
		$activeSheet->setCellValue('O'.$rowCounter, $thuTimeIn1);
		$activeSheet->setCellValue('P'.$rowCounter, $thuTimeOut1);
		$activeSheet->setCellValue('Q'.$rowCounter, $thuRemarks);
	}
	

	//FRIDAY
	if($friAbsent)
	{
		$activeSheet->setCellValue('R'.$rowCounter, "Absent");

		$activeSheet->setCellValue('X'.$rowCounter, $friRemarks);
	}
	else if($friHalfday)
	{
		$activeSheet->setCellValue('R'.$rowCounter, $friTimeIn1);
		$activeSheet->setCellValue('S'.$rowCounter, $friTimeOut1);
		$activeSheet->setCellValue('T'.$rowCounter, "Halfday");

		$activeSheet->setCellValue('X'.$rowCounter, $friRemarks);
	}
	else
	{
		$activeSheet->setCellValue('R'.$rowCounter, $friTimeIn1);
		$activeSheet->setCellValue('S'.$rowCounter, $friTimeOut1);
		$activeSheet->setCellValue('T'.$rowCounter, $friTimeIn2);
		$activeSheet->setCellValue('U'.$rowCounter, $friTimeOut2);
		$activeSheet->setCellValue('V'.$rowCounter, $friTimeIn1);
		$activeSheet->setCellValue('W'.$rowCounter, $friTimeOut1);
		$activeSheet->setCellValue('X'.$rowCounter, $friRemarks);
	}
	

	//SATURDAY
	if($satAbsent)
	{
		$activeSheet->setCellValue('Y'.$rowCounter, "Absent");

		$activeSheet->setCellValue('AE'.$rowCounter, $satRemarks);
	}
	else if($satHalfday)
	{
		$activeSheet->setCellValue('Y'.$rowCounter, $satTimeIn1);
		$activeSheet->setCellValue('Z'.$rowCounter, $satTimeOut1);
		$activeSheet->setCellValue('AA'.$rowCounter, "Halfday");

		$activeSheet->setCellValue('AE'.$rowCounter, $satRemarks);
	}
	else
	{
		$activeSheet->setCellValue('Y'.$rowCounter, $satTimeIn1);
		$activeSheet->setCellValue('Z'.$rowCounter, $satTimeOut1);
		$activeSheet->setCellValue('AA'.$rowCounter, $satTimeIn2);
		$activeSheet->setCellValue('AB'.$rowCounter, $satTimeOut2);
		$activeSheet->setCellValue('AC'.$rowCounter, $satTimeIn1);
		$activeSheet->setCellValue('AD'.$rowCounter, $satTimeOut1);
		$activeSheet->setCellValue('AE'.$rowCounter, $satRemarks);
	}
	

	$rowCounter++; //Row counter
}




//Style of the spreadsheet
$title_style = array(
           		 	'alignment' => array(
           		 		'horizontal' => 'center',
           		 		'vertical' => 'center'
           		 	),
           		 	'font' => array(
           		 		'style' => 'underline'
           		 	)
				);

$border_style = array(	
					'borders' => array(
						'allborders' => array(
                			'style' => 'thin',
                			'color' => array('rgb' => '000000')
                		)
           		 	),
           		 	'alignment' => array(
           		 		'horizontal' => 'center',
           		 		'vertical' => 'center'
           		 	)
				);

$activeSheet->getStyle("D1")->applyFromArray($title_style);
$activeSheet->getStyle("A4:AE7")->applyFromArray($border_style);
$activeSheet->getStyle("A4:AE".$rowCounter)->applyFromArray($border_style);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













