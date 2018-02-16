<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php'); //Styles for PHPexcel


$filename = "Overall SSS Contributions.xls";
// Filename: Last name, First name SSS contribution for Date

// Variables to get:
// Period
// Contribution Type
// Employee ID

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

// Sheets named by site

// Merge cells
$activeSheet->mergeCells('A1:F1'); // Header
$activeSheet->mergeCells('A2:A3'); // Period
$activeSheet->mergeCells('B2:B3'); // Name
$activeSheet->mergeCells('C2:C3'); // Position
$activeSheet->mergeCells('D2:E2'); // Contribution Header
$activeSheet->mergeCells('F2:F3'); // Total

// Header
$activeSheet->setCellValue('A1', 'Overall Site Contribution	');
$activeSheet->setCellValue('A2', 'Period Type');
$activeSheet->setCellValue('B2', 'Name');
$activeSheet->setCellValue('C2', 'Position');
$activeSheet->setCellValue('D2', 'SSS'); // Contribution type
$activeSheet->setCellValue('F2', 'Total');
$activeSheet->setCellValue('D3', 'Employee');
$activeSheet->setCellValue('E3', 'Employer');

// Style
// --- Centering text
$activeSheet->getStyle('A1')->applyFromArray($align_center); // Centered header text
$activeSheet->getStyle('A2')->applyFromArray($align_center); // Centered Period type text
$activeSheet->getStyle('B2')->applyFromArray($align_center); // Centered name text
$activeSheet->getStyle('C2')->applyFromArray($align_center); // Centered position text
$activeSheet->getStyle('D2')->applyFromArray($align_center); // Centered contribution type text
$activeSheet->getStyle('F2')->applyFromArray($align_center); // Centered contribution type text
// -- Changing cell width
$activeSheet->getColumnDimension('A')->setAutoSize(true); // Lengthen Period
$activeSheet->getColumnDimension('B')->setAutoSize(true); // Lengthen Name
$activeSheet->getColumnDimension('C')->setAutoSize(true); // Lengthen Position
// --- Styling borders
$activeSheet->getStyle('A1:F3')->applyFromArray($border_all_thin);


//----------------- Body ---------------------//


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













