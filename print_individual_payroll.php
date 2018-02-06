<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$empquery = mysql_query($employee);
$empArr = mysql_fetch_assoc($empquery);

$filename = $empArr['lastname']. " " .$empArr['firstname']." - Payroll.xls";
// Last Name, First Name of Site (Date) - Payroll.xls

// Get requirements type

// Get period


$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

/* Layout */

// For your reference:
// $activeSheet->setCellValue('A1','INFO');
// $activeSheet->mergeCells('A1:A2');
// $activeSheet->setTitle('Title');
// $activeSheet->getColumnDimension("A")->setAutoSize(true);

//Merge cells
$activeSheet->mergeCells('A1:F1');//Requirements field
$activeSheet->mergeCells('A2:W2');//Period
$activeSheet->mergeCells('G1:W1');//"PAYROLL"

//----------------- Header Contents ---------------------//
//Title Contents
$activeSheet->setCellValue('A1', 'With Requirements');
$activeSheet->setCellValue('A2', 'Period: '.$period);
$activeSheet->setCellValue('G1', 'PAYROLL');

//Header Contents
$activeSheet->setCellValue('A3', 'Rate');
$activeSheet->setCellValue('B3', '# of days');
$activeSheet->setCellValue('C3', 'O.T.');
$activeSheet->setCellValue('D3', '# of hours');
$activeSheet->setCellValue('E3', 'Allow.');
$activeSheet->setCellValue('F3', 'COLA');
$activeSheet->setCellValue('G3', 'Sun');
$activeSheet->setCellValue('H3', 'D');
$activeSheet->setCellValue('I3', 'hrs');
$activeSheet->setCellValue('J3', 'N.D');
$activeSheet->setCellValue('K3', '#');
$activeSheet->setCellValue('L3', 'Reg. Hol');
$activeSheet->setCellValue('M3', '#');
$activeSheet->setCellValue('N3', 'Spe. Hol');
$activeSheet->setCellValue('O3', '#');
$activeSheet->setCellValue('P3', 'X All.');
$activeSheet->setCellValue('Q3', 'SSS');
$activeSheet->setCellValue('R3', 'Philhealth');
$activeSheet->setCellValue('S3', 'Pagibig');
$activeSheet->setCellValue('T3', 'Old vale');
$activeSheet->setCellValue('U3', 'Vale');
$activeSheet->setCellValue('V3', 'Tools');
$activeSheet->setCellValue('W3', 'Total Salary');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













