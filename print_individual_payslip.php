<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];
$date = $_GET['date'];

$empCheck = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
$empQuery = mysql_query($empCheck);

if(mysql_num_rows($empQuery) != 0)
	$empArr = mysql_fetch_assoc($empQuery);
else
	header("location: index.php");


// Get requirements type (with or without)

$weekBefore = date('F j, Y', strtotime('-6 day', strtotime($date)));
$filename =  $empArr['lastname'].", ".$empArr['firstname']." Payslip ".$weekBefore." - ".$date.".xls";

$dateDisplay = $weekBefore." - ".$date;

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

$activeSheet->mergeCells('C16:D16');// Total

//----------------- Header Contents ---------------------//
$endDateExplode = explode(' ', $date);
$endDateMonth = monthConvert($endDateExplode[0]);
$endDateDay = substr($endDateExplode[1], 0, -1);

$startDateExplode = explode(' ', $weekBefore);
$startDateMonth = monthConvert($startDateExplode[0]);
$startDateDay = substr($startDateExplode[1], 0, -1);

if($endDateMonth == $startDateMonth)
	$dateCovered = $startDateMonth."/".$startDateDay."-".$endDateDay;
else
	$dateCovered = $startDateMonth."/".$startDateDay."-".$endDateMonth."/".$endDateDay;

$activeSheet->setCellValue('A1', 'Date Covered: '.$dateCovered);
$activeSheet->setCellValue('A2', $empArr['lastname'].", ".$empArr['firstname']);

$activeSheet->setCellValue('A3', 'Rate');
$activeSheet->setCellValue('A4', 'OT');
$activeSheet->setCellValue('A5', 'cola');
$activeSheet->setCellValue('A6', 'Sun');
$activeSheet->setCellValue('A7', 'N.D');
$activeSheet->setCellValue('A8', 'Reg. Hol');
$activeSheet->setCellValue('A9', 'Spe. Hol');
$activeSheet->setCellValue('A10', 'SSS');
$activeSheet->setCellValue('A11', 'PhilHealth');
$activeSheet->setCellValue('A12', 'Pag-IBIG');
$activeSheet->setCellValue('A13', 'Old vale');
$activeSheet->setCellValue('A14', 'vale');
$activeSheet->setCellValue('A15', 'tools');

$activeSheet->setCellValue('C10', 'X. All.');

//----------------- Body Contents ---------------------//

$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$date'";
$payrollQuery = mysql_query($payroll);

$payrollArr = mysql_fetch_assoc($payrollQuery);

//Rate
$activeSheet->setCellValue('B3', $payrollArr['rate']);
$activeSheet->setCellValue('C3', 'x '.$payrollArr['num_days']);

$rateSubTotal = $payrollArr['rate'] * $payrollArr['num_days'];
$activeSheet->setCellValue('D3', $rateSubTotal);

//Overtime
$activeSheet->setCellValue('B4', $payrollArr['overtime']);
$activeSheet->setCellValue('C4', 'x '.$payrollArr['ot_num']);

$OTSubTotal = $payrollArr['ot_num'] * $payrollArr['overtime'];
$activeSheet->setCellValue('D4', $OTSubTotal);

//Cola
$activeSheet->setCellValue('B5', $payrollArr['cola']);
$activeSheet->setCellValue('C5', 'x '.$payrollArr['num_days']);

$colaSubTotal = $payrollArr['cola'] * $payrollArr['num_days'];
$activeSheet->setCellValue('D5', $colaSubTotal);

//Sunday
$activeSheet->setCellValue('B6', $payrollArr['sunday_rate']);
$activeSheet->setCellValue('C6', 'x '.$payrollArr['sunday_hrs']);

$sundaySubTotal = $payrollArr['sunday_hrs'] * $payrollArr['sunday_rate'];
$activeSheet->setCellValue('D6', $sundaySubTotal);

//Night differential
$activeSheet->setCellValue('B7', $payrollArr['nightdiff_rate']);
$activeSheet->setCellValue('C7', 'x '.$payrollArr['nightdiff_num']);

$NDSubTotal = $payrollArr['nightdiff_num'] * $payrollArr['nightdiff_rate'];
$activeSheet->setCellValue('D7', $NDSubTotal);

//Regular Holiday
$activeSheet->setCellValue('B8', $payrollArr['reg_holiday']);
$activeSheet->setCellValue('C8', 'x '.$payrollArr['reg_holiday_num']);

$regHolSubTotal = $payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday'];
$activeSheet->setCellValue('D8', $regHolSubTotal);

//Special Holiday
$activeSheet->setCellValue('B9', $payrollArr['spe_holiday']);
$activeSheet->setCellValue('C9', 'x '.$payrollArr['spe_holiday_num']);

$speHolSubTotal = $payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday'];
$activeSheet->setCellValue('D9', $speHolSubTotal);

//Contribution
$activeSheet->setCellValue('B10', $payrollArr['sss']);
$activeSheet->setCellValue('B11', $payrollArr['philhealth']);
$activeSheet->setCellValue('B12', $payrollArr['pagibig']);

//Allowance
$activeSheet->setCellValue('D10', $payrollArr['x_allowance']);

//Vale
$activeSheet->setCellValue('B13', $payrollArr['old_vale']);
$activeSheet->setCellValue('B14', $payrollArr['new_vale']);

//Tools
$activeSheet->setCellValue('B15', $payrollArr['tools_paid']);

//Total
$activeSheet->setCellValue('C16', $payrollArr['total_salary']);

//------------ Style for the Spreadsheet ------------
$activeSheet->getStyle('A1:D16')->applyFromArray($border_allsides_medium); 

//extra Allowance
$activeSheet->getStyle('C10')->applyFromArray($border_buttom_left_thin);
$activeSheet->getStyle('D10')->applyFromArray($border_buttom_thin);

//Total
$activeSheet->getStyle('C16:D16')->applyFromArray($border_top_double);

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












