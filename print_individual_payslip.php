<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];
$payDay = $_GET['date'];

$empCheck = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
$empQuery = mysql_query($empCheck);

if(mysql_num_rows($empQuery) != 0)
	$empArr = mysql_fetch_assoc($empQuery);
else
	header("location: index.php");


// Get requirements type (with or without)
$endDay = date('F d, Y', strtotime('-1 day', strtotime($payDay)));
$startDay = date('F d, Y', strtotime('-6 day', strtotime($endDay)));
$filename =  $empArr['lastname'].", ".$empArr['firstname']." of ".$empArr['site']." Payslip ".$startDay." - ".$endDay.".xls";

$dateDisplay = $startDay." - ".$endDay;

function decimalPlaces($val) 
{
	$split = explode('.', $val);
	if(count($split) > 1)
	{
		if($split[1] == 0)
		{
			return $split[0];
		}
		else
		{
			return $val;
		}
	}
	else
		return $val;
}

// Last Name, First Name of Site (Date) - Payroll.xls
function monthConvert($month)
{
	switch($month)
	{
		case "January": $output = "01";break;
		case "February": $output = "02";break;
		case "March": $output = "03";break;
		case "April": $output = "04";break;
		case "May": $output = "05";break;
		case "June": $output = "06";break;
		case "July": $output = "07";break;
		case "August": $output = "08";break;
		case "September": $output = "09";break;
		case "October": $output = "10";break;
		case "November": $output = "11";break;
		case "December": $output = "12";break;
	}
	return $output;
}

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:D1');// Date
$activeSheet->mergeCells('A2:D2');// Name

//----------------- Header Contents ---------------------//
$endDateExplode = explode(' ', $endDay);
$endDateMonth = monthConvert($endDateExplode[0]);
$endYear = $endDateExplode[2];
$endDateDay = substr($endDateExplode[1], 0, -1);


$startDateExplode = explode(' ', $startDay);
$startDateMonth = monthConvert($startDateExplode[0]);
$startDateDay = substr($startDateExplode[1], 0, -1);

if($endDateMonth == $startDateMonth)
	$dateCovered = $startDateMonth."/".$startDateDay."-".$endDateDay.",".$endYear;
else
	$dateCovered = $startDateMonth."/".$startDateDay."-".$endDateMonth."/".$endDateDay.",".$endYear;

$activeSheet->setCellValue('A1', 'Date Covered: '.$dateCovered);
$activeSheet->setCellValue('A2', $empArr['lastname'].", ".$empArr['firstname']);

$activeSheet->setCellValue('A3', 'Rate');
$activeSheet->setCellValue('A4', 'OT');
$activeSheet->setCellValue('A5', 'Allow.');
$activeSheet->setCellValue('A6', 'cola');
$activeSheet->setCellValue('A7', 'Sun');
$activeSheet->setCellValue('A8', 'N.D');
$activeSheet->setCellValue('A9', 'Reg. Hol');
$activeSheet->setCellValue('A10', 'Spe. Hol');
$activeSheet->setCellValue('C12', 'Ins.');
$activeSheet->setCellValue('A11', 'SSS');
$activeSheet->setCellValue('A12', 'PhilHealth');
$activeSheet->setCellValue('A13', 'Pag-IBIG');
$activeSheet->setCellValue('A14', 'Old vale');
$activeSheet->setCellValue('A15', 'vale');

$activeSheet->setCellValue('A16', 'SSS loan');
$activeSheet->setCellValue('A17', 'Pagibig loan');

$activeSheet->setCellValue('A18', 'tools');

$activeSheet->setCellValue('C11', 'X. All.');

//----------------- Body Contents ---------------------//

$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$payDay'";
$payrollQuery = mysql_query($payroll);

$payrollArr = mysql_fetch_assoc($payrollQuery);

//Rate
$activeSheet->setCellValue('B3', $payrollArr['rate']);
$activeSheet->setCellValue('C3', 'x '.decimalPlaces($payrollArr['num_days']));

$rateSubTotal = $payrollArr['rate'] * $payrollArr['num_days'];
$activeSheet->setCellValue('D3', $rateSubTotal);

//Overtime
$activeSheet->setCellValue('B4', $payrollArr['overtime']);
$activeSheet->setCellValue('C4', 'x '.decimalPlaces($payrollArr['ot_num']));

$OTSubTotal = $payrollArr['ot_num'] * $payrollArr['overtime'];
$activeSheet->setCellValue('D4', $OTSubTotal);

//Allowance
$daysAllowance = $payrollArr['allow_days'];
// if(!empty($payrollArr['ot_num']))
// 	$daysAllowance++;
$activeSheet->setCellValue('B5', $payrollArr['allow']);
$activeSheet->setCellValue('C5', 'x '.decimalPlaces($daysAllowance));

$AllowSubTotal =  $payrollArr['allow'] * $daysAllowance;
$activeSheet->setCellValue('D5', $AllowSubTotal);

//Cola
$activeSheet->setCellValue('B6', $payrollArr['cola']);
$activeSheet->setCellValue('C6', 'x '.decimalPlaces($daysAllowance));

$colaSubTotal = $payrollArr['cola'] * $daysAllowance;
$activeSheet->setCellValue('D6', $colaSubTotal);

//Sunday
$activeSheet->setCellValue('B7', $payrollArr['sunday_rate']);
$activeSheet->setCellValue('C7', 'x '.decimalPlaces($payrollArr['sunday_hrs']));

$sundaySubTotal = $payrollArr['sunday_hrs'] * $payrollArr['sunday_rate'];
$activeSheet->setCellValue('D7', $sundaySubTotal);

//Night differential
$activeSheet->setCellValue('B8', $payrollArr['nightdiff_rate']);
$activeSheet->setCellValue('C8', 'x '.$payrollArr['nightdiff_num']);

$NDSubTotal = $payrollArr['nightdiff_num'] * $payrollArr['nightdiff_rate'];
$activeSheet->setCellValue('D8', $NDSubTotal);

//Regular Holiday
$activeSheet->setCellValue('B9', $payrollArr['reg_holiday']);
$activeSheet->setCellValue('C9', 'x '.$payrollArr['reg_holiday_num']);

$regHolSubTotal = $payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday'];
$activeSheet->setCellValue('D9', $regHolSubTotal);

//Special Holiday
$activeSheet->setCellValue('B10', $payrollArr['spe_holiday']);
$activeSheet->setCellValue('C10', 'x '.$payrollArr['spe_holiday_num']);

$speHolSubTotal = $payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday'];
$activeSheet->setCellValue('D10', $speHolSubTotal);

//Contribution
$activeSheet->setCellValue('D12', $payrollArr['insurance']);
$activeSheet->setCellValue('B11', $payrollArr['sss']);
$activeSheet->setCellValue('B12', $payrollArr['philhealth']);
$activeSheet->setCellValue('B13', $payrollArr['pagibig']);

//Allowance
$activeSheet->setCellValue('D11', $payrollArr['x_allowance']);

//Vale
$activeSheet->setCellValue('B14', $payrollArr['old_vale']);
$activeSheet->setCellValue('B15', $payrollArr['new_vale']);

//Loans
$activeSheet->setCellValue('B16', $payrollArr['loan_sss']);
$activeSheet->setCellValue('B17', $payrollArr['loan_pagibig']);

//Tools
$activeSheet->setCellValue('B18', $payrollArr['tools_paid']);

//Total
$activeSheet->mergeCells('C19:D19');
$activeSheet->setCellValue('C19', $payrollArr['total_salary']);
$activeSheet->getStyle('C19')->applyFromArray($font_bold);

//------------ Style for the Spreadsheet ------------
$activeSheet->getStyle('A1:D19')->applyFromArray($border_allsides_medium); 

//extra Allowance
$activeSheet->getStyle('C11')->applyFromArray($border_buttom_left_thin);
$activeSheet->getStyle('D11')->applyFromArray($border_buttom_thin);

//Total
$activeSheet->getStyle('C19:D19')->applyFromArray($border_top_double);

//Header
$activeSheet->getStyle('A1:A2')->applyFromArray($align_left);
$activeSheet->getStyle('A2')->applyFromArray($font_bold);

$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













