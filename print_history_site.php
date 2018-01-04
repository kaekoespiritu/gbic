<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel


// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');

$filename = "Overall site history.xls";

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:C1');//site name

//----------------- Header Contents ---------------------//
//Title Contents
$activeSheet->setCellValue('A1', 'Site History');

//Header Contents
$activeSheet->setCellValue('A2', 'Site Name');
$activeSheet->setCellValue('B2', 'Start Date');
$activeSheet->setCellValue('C2', 'End Date');

$siteHist = "SELECT * FROM site ORDER BY end ASC";
$siteHistQuery = mysql_query($siteHist);

$rowCounter = 3;//Start of data in the row
while($siteHistArr = mysql_fetch_assoc($siteHistQuery))
{
	$activeSheet->setCellValue('A'.$rowCounter, $siteHistArr['location']);
	$activeSheet->setCellValue('B'.$rowCounter, $siteHistArr['start']);
	
	
	if($siteHistArr['end'] != null)
		$activeSheet->setCellValue('C'.$rowCounter, $siteHistArr['end']);
	else
		$activeSheet->setCellValue('C'.$rowCounter, 'On going');

	$rowCounter++;
}

//Style for the Spreadsheet
$activeSheet->getStyle('A2:C2')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A1')->applyFromArray($align_center);//Header 
$activeSheet->getStyle('A2:C'.$rowCounter)->applyFromArray($border_all_thin);//Content




header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













