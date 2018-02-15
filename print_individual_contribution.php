<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php'); //Styles for PHPexcel


$filename = "Individual SSS Contributions.xls";
// Filename: Last name, First name SSS contribution for Date

// Variables to get:
// Period
// Contribution Type
// Employee ID

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

// Merge cells
$activeSheet->mergeCells('A1:D1'); // Header
$activeSheet->mergeCells('A2:A3'); // Period
$activeSheet->mergeCells('B2:C2'); // Contribution Type Header
$activeSheet->mergeCells('D2:D3'); // Total

// Header
$activeSheet->setCellValue('A1', 'Last name, First name - Position at Site');
$activeSheet->setCellValue('A2', 'Period');
$activeSheet->setCellValue('B2', 'SSS'); // Contribution type
$activeSheet->setCellValue('D2', 'Total');
$activeSheet->setCellValue('B3', 'Employee');
$activeSheet->setCellValue('C3', 'Employer');

// Style
// ---  Centering text
$activeSheet->getStyle('A2')->applyFromArray($align_center); // Centered Period text
$activeSheet->getStyle('D2')->applyFromArray($align_center); // Centered Total text
$activeSheet->getStyle('B2')->applyFromArray($align_center); // Centered header 
$activeSheet->getStyle('A1')->applyFromArray($align_center); // Centered header text
// --- Changing cell width


//----------------- Body ---------------------//


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













