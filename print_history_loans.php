<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_POST['employee'];//empid
$loanType = $_POST['type'];

$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
$empQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($empQuery);

$employeeName = $empArr['lastname'].", ".$empArr['firstname'];


// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');

$filename = $employeeName."'s ". $loanType ." loan history.xls";

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

// Merge cells
if($loanType == 'SSS' || $loanType == 'PagIBIG')
{
	$activeSheet->mergeCells('A1:G1');//Requirements field
}
else
{
	$activeSheet->mergeCells('A1:F1');//Requirements field
}

//----------------- Header Contents ---------------------//
//Title Contents
$activeSheet->setCellValue('A1', $employeeName."'s ".$loanType." history");

//Header Contents
if($loanType == 'SSS' || $loanType == 'PagIBIG')
{
	$activeSheet->setCellValue('A2', 'Date');
	$activeSheet->setCellValue('B2', 'Action');
	$activeSheet->setCellValue('C2', 'Amount');
	$activeSheet->setCellValue('D2', 'Monthly Due');
	$activeSheet->setCellValue('E2', 'Balance');
	$activeSheet->setCellValue('F2', 'Remarks');
	$activeSheet->setCellValue('G2', 'Approved By');
}
else
{
	$activeSheet->setCellValue('A2', 'Date');
	$activeSheet->setCellValue('B2', 'Action');
	$activeSheet->setCellValue('C2', 'Amount');
	$activeSheet->setCellValue('D2', 'Balance');
	$activeSheet->setCellValue('E2', 'Remarks');
	$activeSheet->setCellValue('F2', 'Approved By');
}


$history = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC, id ASC";
$historyQuery = mysql_query($history);

$rowCounter = 3;//this is where the row of data starts

while($row = mysql_fetch_assoc($historyQuery))
{
	$activeSheet->setCellValue('A'.$rowCounter, $row['date']);//date

	if($row['action'] == '1')
	{
		$activeSheet->setCellValue('B'.$rowCounter, 'Loaned');//Action
		$activeSheet->setCellValue('C'.$rowCounter, '+'.number_format($row['amount'], 2, '.', ','));//Amount
	}
	else
	{
		$activeSheet->setCellValue('B'.$rowCounter, 'Paid');//Action
		$activeSheet->setCellValue('C'.$rowCounter, '-'.number_format($row['amount'], 2, '.', ','));//Amount
	}

	if($loanType == 'SSS' | $loanType == 'PagIBIG' )
	{
		$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($row['monthly'], 2, '.', true));//Amount

		$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($row['balance'], 2, '.', true));//Balance

		$activeSheet->setCellValue('F'.$rowCounter, $row['remarks']);//Remarks
		$activeSheet->setCellValue('G'.$rowCounter, $row['admin']);//Admin responsible	
	}
	else
	{
		$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($row['balance'], 2, '.', true));//Balance

		$activeSheet->setCellValue('E'.$rowCounter, $row['remarks']);//Remarks
		$activeSheet->setCellValue('F'.$rowCounter, $row['admin']);//Admin responsible	
	}

	$rowCounter++;
}
	
//Style for the Spreadsheet
if($loanType == 'SSS' || $loanType == 'PagIBIG')
{
	$activeSheet->getStyle('A1:G2')->applyFromArray($border_all_medium);//Header 
	$activeSheet->getStyle('A3:G'.$rowCounter)->applyFromArray($border_all_thin);//Content
}
else
{
	$activeSheet->getStyle('A1:F2')->applyFromArray($border_all_medium);//Header 
	$activeSheet->getStyle('A3:F'.$rowCounter)->applyFromArray($border_all_thin);//Content
}
$activeSheet->getStyle('A1')->applyFromArray($align_center);//Header

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













