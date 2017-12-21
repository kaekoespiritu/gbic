<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';

// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');
$date =  strftime("%h %e, %Y");
$filename = $date." - Attendance.xls";

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(1);

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
$activeSheet->setCellValue('A1', 'Site: <site name>');//Site
$activeSheet->setCellValue('A2', "For the Period: Date - Date");//Date
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
$activeSheet->setCellValue('E4', 'Out');
$activeSheet->setCellValue('F4', 'In');
$activeSheet->setCellValue('G4', 'Out');
$activeSheet->setCellValue('H4', 'In');
$activeSheet->setCellValue('I4', 'Out');

//Thursday
$activeSheet->setCellValue('K4', 'THURSDAY');
$activeSheet->setCellValue('K5', 'REGULAR DAY');
$activeSheet->setCellValue('O5', 'OVERTIME');
$activeSheet->setCellValue('Q5', 'Remarks');
$activeSheet->setCellValue('K6', 'AM');
$activeSheet->setCellValue('M6', 'PM');
$activeSheet->setCellValue('N6', 'OT Hrs');
$activeSheet->setCellValue('K7', 'In');
$activeSheet->setCellValue('L4', 'Out');
$activeSheet->setCellValue('M4', 'In');
$activeSheet->setCellValue('N4', 'Out');
$activeSheet->setCellValue('O4', 'In');
$activeSheet->setCellValue('P4', 'Out');

//Friday
$activeSheet->setCellValue('R4', 'FRIDAY');
$activeSheet->setCellValue('R5', 'REGULAR DAY');
$activeSheet->setCellValue('V5', 'OVERTIME');
$activeSheet->setCellValue('X5', 'Remarks');
$activeSheet->setCellValue('R6', 'AM');
$activeSheet->setCellValue('T6', 'PM');
$activeSheet->setCellValue('U6', 'OT Hrs');
$activeSheet->setCellValue('R7', 'In');
$activeSheet->setCellValue('S4', 'Out');
$activeSheet->setCellValue('T4', 'In');
$activeSheet->setCellValue('U4', 'Out');
$activeSheet->setCellValue('V4', 'In');
$activeSheet->setCellValue('W4', 'Out');

//Saturday
$activeSheet->setCellValue('Y4', 'SATURDAY');
$activeSheet->setCellValue('Y5', 'REGULAR DAY');
$activeSheet->setCellValue('AC5', 'OVERTIME');
$activeSheet->setCellValue('AE5', 'Remarks');
$activeSheet->setCellValue('Y6', 'AM');
$activeSheet->setCellValue('AA6', 'PM');
$activeSheet->setCellValue('AB6', 'OT Hrs');
$activeSheet->setCellValue('Y7', 'In');
$activeSheet->setCellValue('Z4', 'Out');
$activeSheet->setCellValue('AA4', 'In');
$activeSheet->setCellValue('AB4', 'Out');
$activeSheet->setCellValue('AC4', 'In');
$activeSheet->setCellValue('AD4', 'Out');

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

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













