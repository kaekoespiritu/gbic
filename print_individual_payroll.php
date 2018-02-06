<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];
$endDate = $_GET['date'];
$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$empquery = mysql_query($employee);
$siteArr = mysql_fetch_assoc($empquery);

// Get requirements type (with or without)


$filename = $siteArr['lastname']. ", " .$siteArr['firstname']." of ".$siteArr['site']." (".$endDate.") - Payroll.xls";
// Last Name, First Name of Site (Date) - Payroll.xls


$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:F1');//Requirements field
$activeSheet->mergeCells('A2:W2');//Period
$activeSheet->mergeCells('G1:W1');//"PAYROLL"

//----------------- Header Contents ---------------------//
//Title Contents
if($siteArr['complete_doc'] === 1)
	$activeSheet->setCellValue('A1', 'With Requirements');
else
	$activeSheet->setCellValue('A1', 'Without Requirements');
$activeSheet->setCellValue('A2', 'Period: '.$startDate." - ".$endDate);
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

//----------------- Body ---------------------//

$rowCounter = 4; //start for the data in the row of excel

	$employeeName = $siteArr['lastname'].", ".$siteArr['firstname'];
	$employeePosition = $siteArr['position'];
	$empid = $siteArr['empid'];
	
	$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$endDate'";
	$payrollQuery = mysql_query($payroll) or die (mysql_error());
	$payrollArr = mysql_fetch_assoc($payrollQuery);

	//Sunday
	$sundayBool = (!empty($payrollArr['sunday_hrs']) ? true : false);// employee didn't attend sunday

	$activeSheet->setCellValue('A'.$rowCounter, $siteArr['rate']);//Rate
	$activeSheet->setCellValue('B'.$rowCounter, $payrollArr['num_days']);//ofDays
	$activeSheet->setCellValue('C'.$rowCounter, $payrollArr['overtime']);//O.T.
	$activeSheet->setCellValue('D'.$rowCounter, $payrollArr['ot_num']);//#ofHrs
	$activeSheet->setCellValue('E'.$rowCounter, $payrollArr['allow']);//Allow.
	$activeSheet->setCellValue('F'.$rowCounter, $payrollArr['cola']);//cola
	$activeSheet->setCellValue('G'.$rowCounter, $payrollArr['sunday_rate']);//Sun
	if($sundayBool)
		$activeSheet->setCellValue('H'.$rowCounter, '1');//D
	$activeSheet->setCellValue('I'.$rowCounter, $payrollArr['sunday_hrs']);//hrs
	$activeSheet->setCellValue('J'.$rowCounter, $payrollArr['nightdiff_rate']);//N.D
	$activeSheet->setCellValue('K'.$rowCounter, $payrollArr['nightdiff_num']);//#
	$activeSheet->setCellValue('L'.$rowCounter, $payrollArr['reg_holiday']);//Reg.Hol
	$activeSheet->setCellValue('M'.$rowCounter, $payrollArr['reg_holiday_num']);//#
	$activeSheet->setCellValue('N'.$rowCounter, $payrollArr['spe_holiday']);//Spe.Hol
	$activeSheet->setCellValue('O'.$rowCounter, $payrollArr['spe_holiday']);//#
	$activeSheet->setCellValue('P'.$rowCounter, $payrollArr['x_allowance']);//X All.
	$activeSheet->setCellValue('Q'.$rowCounter, $payrollArr['sss']);//SSS
	$activeSheet->setCellValue('R'.$rowCounter, $payrollArr['philhealth']);//Philhealth
	$activeSheet->setCellValue('S'.$rowCounter, $payrollArr['pagibig']);//Pagibig
	$activeSheet->setCellValue('T'.$rowCounter, $payrollArr['old_vale']);//old vale
	$activeSheet->setCellValue('U'.$rowCounter, $payrollArr['new_vale']);//vale
	$activeSheet->setCellValue('V'.$rowCounter, $payrollArr['tools_paid']);//tools

	$totalSalary = numberExactFormat($payrollArr['total_salary'], 2, '.');
	$activeSheet->setCellValue('W'.$rowCounter, $totalSalary);//Total Salary

	$rowCounter++; //Row counter

$rowCounter++;//to give space for clearer data

//Style for the Spreadsheet
$activeSheet->getStyle('A3:W3')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A4:W'.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('G1:W2')->applyFromArray($align_center);//Centered header text
$activeSheet->getStyle('A2')->applyFromArray($align_center);//Centered period text
$activeSheet->getColumnDimension('W')->setAutoSize(true);//Lengthen total salary

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













