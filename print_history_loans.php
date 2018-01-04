<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

// --------------- SAMPLE DATA ------------------ //
$empid = "2017-5751856";//Sample data
$loanType = "oldVale";
// --------------- SAMPLE DATA ------------------ //

$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
$empQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($empQuery);

$employeeName = $empArr['lastname'].", ".$empArr['firstname'];


// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');

$filename = $employeeName."'s ". $loanType ." loan history.xls";

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:F1');//site name

//----------------- Header Contents ---------------------//
//Title Contents
$activeSheet->setCellValue('A1', $employeeName."'s loan history");

//Header Contents
$activeSheet->setCellValue('A2', 'Balance');
$activeSheet->setCellValue('B2', 'Amount');
$activeSheet->setCellValue('C2', 'Action');
$activeSheet->setCellValue('D2', 'Remarks');
$activeSheet->setCellValue('E2', 'Date');
$activeSheet->setCellValue('F2', 'Approved by');


$history = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' ORDER BY date DESC, time DESC";
$historyQuery = mysql_query($history);

$rowCounter = 3;//this is where the row of data starts

while($row = mysql_fetch_assoc($historyQuery))
{
	$activeSheet->setCellValue('A'.$rowCounter, number_format($row['balance'], 2, '.', ','));//Balance

	if($row['action'] == '1')
	{
		$activeSheet->setCellValue('B'.$rowCounter, '+'.number_format($row['amount'], 2, '.', ','));//Amount
		$activeSheet->setCellValue('C'.$rowCounter, 'Loaned');//Action
	}
	else
	{
		$activeSheet->setCellValue('B'.$rowCounter, '-'.number_format($row['amount'], 2, '.', ','));//Amount
		$activeSheet->setCellValue('C'.$rowCounter, 'Paid');//Action
	}
	$activeSheet->setCellValue('D'.$rowCounter, $row['remarks']);//Remarks
	$activeSheet->setCellValue('E'.$rowCounter, $row['date']);//Date
	$activeSheet->setCellValue('F'.$rowCounter, $row['admin']);//Admin

	$rowCounter++;
}
	

//Style for the Spreadsheet
$activeSheet->getStyle('A2:F2')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A1')->applyFromArray($align_center);//Header 
$activeSheet->getStyle('A2:F'.$rowCounter)->applyFromArray($border_all_thin);//Content




header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













