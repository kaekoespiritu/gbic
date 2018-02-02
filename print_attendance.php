<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');

// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');
if(isset($_SESSION['date']))
	$date = $_SESSION['date'];
else
	$date =  strftime("%h %e, %Y");

//DATE
for($dayCounter = 0; $dayCounter <= 6; $dayCounter++)//Finds the nearest wednesday on the date
{
	$decrement = '-'.$dayCounter.' day';
	$day = date('l', strtotime($decrement, strtotime($date)));//get the day of the week
	if($day == "Wednesday")
		$startDate = date('F j, Y', strtotime($decrement, strtotime($date)));
}
$endDate = date('F j, Y', strtotime('+6 day', strtotime($startDate)));


$filename = $date." - Attendance.xls";

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);



//----------------- Body ---------------------//


$location = "SELECT * FROM site WHERE active = '1'";
$locQuery = mysql_query($location);

$spreadSheetCounter = 0;
while($locationArr = mysql_fetch_assoc($locQuery))//looping for the Site
{
	$siteLocation = $locationArr['location'];
	$site = "SELECT * FROM employee WHERE site = '$siteLocation' AND employment_status = '1' ORDER BY lastname ASC, position ASC";
	$siteQuery = mysql_query($site) or die (mysql_error());
	$counter = 0;
	$rowCounter = 8; //start for the data in the row of excel
	
	for($siteSwitch = 1; $siteSwitch <= 2; $siteSwitch++)//interchanging sheets
	{
		$counter = 0;// Number of employees in a spreadsheet
		$rowCounter = 8;//the starting data row
		$activeSheet = $sheet -> createSheet($spreadSheetCounter);
		$activeSheet->setTitle($siteLocation."(".$siteSwitch.")");

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
		$activeSheet->setCellValue('A1', 'Site: '. $siteLocation);//Site
		$activeSheet->setCellValue('A2', "For the Period: ".$startDate." - ".$endDate);//Date
		$activeSheet->setCellValue('A3', 'Complete Requirements');//"Complete Requirements"
		$activeSheet->setCellValue('D1', 'WEEKLY TIME RECORD OF EMPLOYEE');//"Weekly time record comployee"

		//Header Contents
		$activeSheet->setCellValue('A4', '#');
		$activeSheet->setCellValue('B4', 'Name of worker');
		$activeSheet->setCellValue('D4', 'Position');

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
			$sites = "SELECT * FROM employee WHERE site = '$siteLocation' AND employment_status = '1' ORDER BY lastname ASC, position ASC";
			$sitesQuery = mysql_query($sites) or die (mysql_error());
			while($siteArray = mysql_fetch_assoc($sitesQuery))//Loop for employees in the site
			{
				$counter++;
				$employeeName = $siteArray['lastname'].", ".$siteArray['firstname'];
				$employeePosition = $siteArray['position'];
				$empid = $siteArray['empid'];

				$activeSheet->setCellValue('A'.$rowCounter, $counter);//#
				$activeSheet->setCellValue('B'.$rowCounter, $employeeName);//Name of worker
				$activeSheet->setCellValue('C'.$rowCounter, $employeePosition);//Name of worker

				$rowCounter++; //Row counter
				//Style of the spreadsheet
			}

		}
		else
		{
			while($siteArr = mysql_fetch_assoc($siteQuery))//Loop for employees in the site
			{
				$counter++;
				$employeeName = $siteArr['lastname'].", ".$siteArr['firstname'];
				$employeePosition = $siteArr['position'];
				$empid = $siteArr['empid'];

				$activeSheet->setCellValue('A'.$rowCounter, $counter);//#
				$activeSheet->setCellValue('B'.$rowCounter, $employeeName);//Name of worker
				$activeSheet->setCellValue('C'.$rowCounter, $employeePosition);//Name of worker


				$rowCounter++; //Row counter
				//Style of the spreadsheet
			}
		}
			
		if($siteSwitch == 1)
		{
			$activeSheet->getStyle("A4:AE7")->applyFromArray($border_all_thin);
			$activeSheet->getStyle("A4:AE".$rowCounter)->applyFromArray($border_all_thin);
		}	
		else
		{
			$activeSheet->getStyle("A4:X7")->applyFromArray($border_all_thin);
			$activeSheet->getStyle("A4:X".$rowCounter)->applyFromArray($border_all_thin);
		}
		$spreadSheetCounter++;//increment the number of spreadsheet			
	}
}


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













