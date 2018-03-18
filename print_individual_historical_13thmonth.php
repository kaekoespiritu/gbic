<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$empquery = mysql_query($employee) or die (mysql_error());

if(mysql_num_rows($empquery) != 0)
	$empArr = mysql_fetch_assoc($empquery);
else
	header("location:index.php");

$filename = $empArr['lastname']. ", " .$empArr['firstname']." 13th Month pay Historical Report.xls";
// Last Name, First Name of Site (Date) - Payroll.xls

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:D1');// Employee name 13thmonth pay

//Header cells
$activeSheet->setCellValue('A1', $empArr['lastname']. ", " .$empArr['firstname']." 13th Month pay Historical");
$activeSheet->setCellValue('A2', 'From Date');
$activeSheet->setCellValue('B2', 'To Date');
$activeSheet->setCellValue('C2', '13th Month Pay Amount');
$activeSheet->setCellValue('D2', 'Amount given');

$rowCounter = 3; //starting row for data

$thirteenthHist = "SELECT * FROM thirteenth_pay WHERE empid = '$empid' ORDER BY STR_TO_DATE(from_date, '%M %e, %Y') ASC";
$thirteenthHistQuery = mysql_query($thirteenthHist) or die(mysql_error()) ;

$histBool = false;//historical print disabled
if(mysql_num_rows($thirteenthHistQuery) != 0)
{
	$histBool = true;//historical print enabled
	while($histRow = mysql_fetch_assoc($thirteenthHistQuery))
	{
		$activeSheet->setCellValue('A'.$rowCounter, $histRow['from_date']);
		$activeSheet->setCellValue('B'.$rowCounter, $histRow['to_date']);
		$activeSheet->setCellValue('C'.$rowCounter, $histRow['amount']);
		$activeSheet->setCellValue('D'.$rowCounter, $histRow['received']);

		$rowCounter++;//increment row
	}
	
}

//Style for the Spreadsheet
$activeSheet->getStyle('A1:D2')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A3:D'.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('A1:D'.$rowCounter)->applyFromArray($align_center);//Centered header text

$activeSheet->getColumnDimension('A')->setAutoSize(false);
$activeSheet->getColumnDimension('B')->setAutoSize(false);
$activeSheet->getColumnDimension('C')->setAutoSize(false);
$activeSheet->getColumnDimension('D')->setAutoSize(false);
$activeSheet->getColumnDimension('A')->setWidth('20');
$activeSheet->getColumnDimension('B')->setWidth('20');
$activeSheet->getColumnDimension('C')->setWidth('20');
$activeSheet->getColumnDimension('D')->setWidth('20');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













