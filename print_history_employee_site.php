<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

//Sample Data
$empid = $_POST['employee'];


// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');



$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:C1');//site name


$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$employeeQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($employeeQuery);

$filename = $empArr['lastname'].", ".$empArr['firstname']."'s' site history.xls";
//----------------- Header Contents ---------------------//
//Title Contents
$activeSheet->setCellValue('A1', $empArr['lastname'].", ".$empArr['firstname']."'s site History");

//Header Contents
$activeSheet->setCellValue('A2', 'Date');
$activeSheet->setCellValue('B2', 'Site');
$activeSheet->setCellValue('C2', 'Admin');


	
$siteHist = "SELECT * FROM site_history WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
$histQuery = mysql_query($siteHist);

$rowCounter = 3;
if(mysql_num_rows($histQuery) > 0)
{
	while($row = mysql_fetch_assoc($histQuery))
	{
		$activeSheet->setCellValue('A'.$rowCounter, $row['date']);
		$activeSheet->setCellValue('B'.$rowCounter, $row['site']);
		$activeSheet->setCellValue('C'.$rowCounter, $row['admin']);

		$rowCounter++;
	}
}		
else 
{
	$activeSheet->mergeCells('A3:C3');//Merge the cell
	$activeSheet->getStyle('A3')->applyFromArray($align_center);//Style  
	$activeSheet->setCellValue('A3', 'No site movement history');
}	

//Style for the Spreadsheet
$activeSheet->getStyle('A2:C2')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A1')->applyFromArray($align_center);//Header 
$activeSheet->getStyle('A2:C'.$rowCounter)->applyFromArray($border_all_thin);//Content

$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













