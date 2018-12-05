<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];
$type = $_GET['type'];

switch($type)
{
	case "sss": $typeDisplay = "SSS";break;
	case "pagibig": $typeDisplay = "PAGIBIG";break;
	case "oldvale": $typeDisplay = "Old Vale";break;
	case "newvale": $typeDisplay = "New Vale";break;
}

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$empquery = mysql_query($employee);
$siteArr = mysql_fetch_assoc($empquery);

// Get requirements type (with or without)


$filename = $siteArr['lastname']. ", " .$siteArr['firstname']." ".$typeDisplay." Loan Report.xls";
// Last Name, First Name of Site (Date) - Payroll.xls


$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
if($type == 'sss' || $type == 'pagibig')
{
	$activeSheet->mergeCells('A1:G1');//Requirements field
}
else
{
	$activeSheet->mergeCells('A1:F1');//Requirements field
}

//----------------- Header Contents ---------------------//
//Title Contents
$activeSheet->setCellValue('A1', $siteArr['lastname'].",".$siteArr['firstname']."'s ".$typeDisplay." Report");

//Header Contents
if($type == 'sss' || $type == 'pagibig')
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


//----------------- Body ---------------------//

$rowCounter = 2; //start for the data in the row of excel
//------

$history = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$type' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC, id ASC";
$historyQuery = mysql_query($history);

if(mysql_num_rows($historyQuery) > 0)
{
	while($row = mysql_fetch_assoc($historyQuery))
	{
		$rowCounter++;//Increment row
		$activeSheet->setCellValue('A'.$rowCounter, $row['date']);//date
		if($row['action'] == '1')
		{
			$activeSheet->setCellValue('B'.$rowCounter, 'Loaned');//Action
			$activeSheet->setCellValue('C'.$rowCounter, '+ '.numberExactFormat($row['amount'], 2, '.', true));//Amount
		}
		else
		{
			$activeSheet->setCellValue('B'.$rowCounter, 'Paid');//Action
			$activeSheet->setCellValue('C'.$rowCounter, '-'.numberExactFormat($row['amount'], 2, '.', true));//Amount
		}

		if($type == 'sss' | $type == 'pagibig' )
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
	}
	
}
else
{
	$activeSheet->mergeCells('A3:F3');
	$activeSheet->setCellValue('A3', 'No '.$typeDisplay.' loan as of the moment.');
}

//Style for the Spreadsheet
if($type == 'sss' || $type == 'pagibig')
{
	$activeSheet->getStyle('A1:G2')->applyFromArray($border_all_medium);//Header 
	$activeSheet->getStyle('A3:G'.$rowCounter)->applyFromArray($border_all_thin);//Content
}
else
{
	$activeSheet->getStyle('A1:F2')->applyFromArray($border_all_medium);//Header 
	$activeSheet->getStyle('A3:F'.$rowCounter)->applyFromArray($border_all_thin);//Content
}

$activeSheet->getStyle('A1:F'.$rowCounter)->applyFromArray($align_center);//Centered header text
$activeSheet->getStyle('A')->applyFromArray($align_center);//Centered period text
$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setAutoSize(true);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













