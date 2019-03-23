<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];
$payDay = $_GET['date'];
$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDay)));
$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

// Check for early cutoff 
$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$payDay' LIMIT 1";
$cutoffQuery = mysql_query($cutoffCheck);
if($_GET['cutoff'] != '')// get the Start of cutoff
{
	$startDate = $_GET['cutoff'];
}
else if(mysql_num_rows($cutoffQuery) > 0)
{
	$cutoffArr = mysql_fetch_assoc($cutoffQuery);
	$startDate = $cutoffArr['start'];
	$endDate = $cutoffArr['end'];
}

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
$activeSheet->mergeCells('A2:F2');//Period
$activeSheet->mergeCells('G1:AD2');//"PAYROLL"

//----------------- Header Contents ---------------------//
//Title Contents
if($siteArr['complete_doc'] == 1)
	$activeSheet->setCellValue('A1', 'With Requirements');
else
	$activeSheet->setCellValue('A1', 'Without Requirements');
$activeSheet->setCellValue('A2', 'Period: '.$startDate." - ".$endDate);
$activeSheet->setCellValue('G1', 'P A Y R O L L');

//Header Contents
$activeSheet->setCellValue('B3', 'Name');
$activeSheet->setCellValue('C3', 'Position');
$activeSheet->setCellValue('D3', 'Rate');
$activeSheet->setCellValue('E3', '# of days');
$activeSheet->setCellValue('F3', 'O.T.');
$activeSheet->setCellValue('G3', '# of hours');
$activeSheet->setCellValue('H3', 'Allow.');
$activeSheet->setCellValue('I3', 'COLA');
$activeSheet->setCellValue('J3', 'Sun');
$activeSheet->setCellValue('K3', 'D');
$activeSheet->setCellValue('L3', 'hrs');
$activeSheet->setCellValue('M3', 'N.D');
$activeSheet->setCellValue('N3', '#');
$activeSheet->setCellValue('O3', 'Reg. Hol');
$activeSheet->setCellValue('P3', '#');
$activeSheet->setCellValue('Q3', 'Spe. Hol');
$activeSheet->setCellValue('R3', '#');
$activeSheet->setCellValue('S3', 'X All.');
$activeSheet->setCellValue('T3', 'SSS');
$activeSheet->setCellValue('U3', 'Philhealth');
$activeSheet->setCellValue('V3', 'Pagibig');
$activeSheet->setCellValue('W3', 'Old vale');
$activeSheet->setCellValue('X3', 'Vale');

$activeSheet->setCellValue('Y3', 'SSS loan');
$activeSheet->setCellValue('Z3', 'P-ibig loan');

$activeSheet->setCellValue('AA3', 'Ins.');

$activeSheet->setCellValue('AB3', 'Tools');
$activeSheet->setCellValue('AC3', 'Total Salary');

$activeSheet->setCellValue('AD3', 'Signature');

//----------------- Body ---------------------//

$rowCounter = 4; //start for the data in the row of excel

	$employeeName = $siteArr['lastname'].", ".$siteArr['firstname'];
	$employeePosition = $siteArr['position'];
	$empid = $siteArr['empid'];
	
	$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$payDay'";
	$payrollQuery = mysql_query($payroll) or die (mysql_error());
	$payrollArr = mysql_fetch_assoc($payrollQuery);

	//Gets the actual holiday num
	if($payrollArr['reg_holiday_num'] > 1)
	{
		// $holidayRegChecker = "SELECT * FROM holiday AS h INNER JOIN attendance AS a ON h.date = a.date WHERE a.empid = '$empid' AND a.attendance = '2' AND h.type = 'regular'";
		// $holidayRegQuery = mysql_query($holidayRegChecker);
		// $regHolidayNum = mysql_num_rows($holidayRegQuery);
		$regHolidayNum = $payrollArr['reg_holiday_num'];
	}
	else if($payrollArr['reg_holiday_num'] == 1)
	{
		$regHolidayNum = 1;
	}
	else
	{
		$regHolidayNum = 0;
	}
	
	//Sunday
	$sundayBool = (!empty($payrollArr['sunday_hrs']) ? true : false);// employee didn't attend sunday
	// Removing zeros from payroll
	$NDnumBool = (intval($payrollArr['nightdiff_num'] == 0) ? true : false);
	$regHolBool = (intval($regHolidayNum == 0) ? true : false);
	$speHolBool = (intval($payrollArr['spe_holiday_num'] == 0) ? true : false);
	$AllowBool = (intval($payrollArr['allow'] == 0) ? true : false);
	$colaBool = (intval($payrollArr['cola'] == 0) ? true : false);
	$XallowBool = (	intval($payrollArr['x_allowance']) == 0 || 
					intval($payrollArr['x_allow_daily']) == 0 || 
					intval($payrollArr['x_allow_weekly']) == 0 ? true : false);
	$SSSBool = (intval($payrollArr['sss'] == 0) ? true : false);
	$PhilHealthBool = (intval($payrollArr['philhealth'] == 0) ? true : false);
	$PagibigBool = (intval($payrollArr['pagibig'] == 0) ? true : false);
	$OldValeBool = (intval($payrollArr['old_vale'] == 0) ? true : false);
	$NewValeBool = (intval($payrollArr['new_vale'] == 0) ? true : false);
	$LoanSSSBool = (intval($payrollArr['loan_sss'] == 0) ? true : false);
	$LoanPagibigBool = (intval($payrollArr['loan_pagibig'] == 0) ? true : false);
	$ToolsBool = (intval($payrollArr['tools_paid'] == 0) ? true : false);
	$InsuranceBool = (intval($payrollArr['insurance'] == 0) ? true : false);
	$OTBool = (intval($payrollArr['overtime'] == 0) ? true : false);
	$OTHrsBool = (intval($payrollArr['ot_num'] == 0) ? true : false);
	$AttendanceBool = (intval($payrollArr['num_days'] == 0) ? true : false);

	$activeSheet->setCellValue('A'.$rowCounter, '1');//#
	$activeSheet->setCellValue('B'.$rowCounter, $employeeName);// Name
	$activeSheet->setCellValue('C'.$rowCounter, $siteArr['position']);//Position
	$activeSheet->setCellValue('D'.$rowCounter, $payrollArr['rate']);//Rate
	if(!$AttendanceBool)
	$activeSheet->setCellValue('E'.$rowCounter, $payrollArr['num_days']);//ofDays
	if(!$OTBool)
	$activeSheet->setCellValue('F'.$rowCounter, $payrollArr['overtime']);//O.T.
	if(!$OTHrsBool)
	$activeSheet->setCellValue('G'.$rowCounter, abs($payrollArr['ot_num']));//#ofHrs
	if(!$AllowBool)
		$activeSheet->setCellValue('H'.$rowCounter, $payrollArr['allow']);//Allow.
	if(!$colaBool)
		$activeSheet->setCellValue('I'.$rowCounter, ($payrollArr['cola']/$payrollArr['allow_days']));//cola
	$activeSheet->setCellValue('J'.$rowCounter, $payrollArr['sunday_rate']);//Sun
	if($sundayBool)
		$activeSheet->setCellValue('K'.$rowCounter, '1');//D
	$activeSheet->setCellValue('L'.$rowCounter, $payrollArr['sunday_hrs']);//hrs
	$activeSheet->setCellValue('M'.$rowCounter, $payrollArr['nightdiff_rate']);//N.D
	if(!$NDnumBool)
		$activeSheet->setCellValue('N'.$rowCounter, $payrollArr['nightdiff_num']);//#
	$activeSheet->setCellValue('O'.$rowCounter, $payrollArr['reg_holiday']);//Reg.Hol
	if(!$regHolBool)
		$activeSheet->setCellValue('P'.$rowCounter, $regHolidayNum);//#
	$activeSheet->setCellValue('Q'.$rowCounter, $payrollArr['spe_holiday']);//Spe.Hol
	if(!$speHolBool)
		$activeSheet->setCellValue('R'.$rowCounter, $payrollArr['spe_holiday_num']);//#
	if(!$XallowBool)
		$activeSheet->setCellValue('S'.$rowCounter, ($payrollArr['x_allowance'] + $payrollArr['x_allow_weekly'] + ($payrollArr['x_allow_daily'] * $payrollArr['allow_days'])));//X All.
	if(!$SSSBool)
		$activeSheet->setCellValue('T'.$rowCounter, $payrollArr['sss']);//SSS
	if(!$PhilHealthBool)
		$activeSheet->setCellValue('U'.$rowCounter, $payrollArr['philhealth']);//Philhealth
	if(!$PagibigBool)
		$activeSheet->setCellValue('V'.$rowCounter, $payrollArr['pagibig']);//Pagibig
	if(!$OldValeBool)
		$activeSheet->setCellValue('W'.$rowCounter, $payrollArr['old_vale']);//old vale

	$payrollDay = date('F d, Y', strtotime('+1 day', strtotime($endDate)));
						

	$payrollOutstandingSql = "SELECT total_salary FROM payroll WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') < STR_TO_DATE('$payrollDay', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC LIMIT 1";

	$payrollOutstandingQuery = mysql_query($payrollOutstandingSql);
	$payrollOutstanding = 0.00;
	
	if(mysql_num_rows($payrollOutstandingQuery))
	{
		$outStandingCheck = mysql_fetch_assoc($payrollOutstandingQuery);
		if($outStandingCheck['total_salary'] < 0.00){
			$payrollOutstanding = abs($outStandingCheck['total_salary']);
			$payrollOutstanding += $payrollArr['new_vale'];
		}
		else {
			$payrollOutstanding = $payrollArr['new_vale'];
		}
	}
	else
	{
		$payrollOutstanding = $payrollArr['new_vale'];
	}

	$activeSheet->setCellValue('X'.$rowCounter, ($payrollOutstanding == 0 ? "" : $payrollOutstanding));//vale

	if(!$LoanSSSBool)
		$activeSheet->setCellValue('Y'.$rowCounter, $payrollArr['loan_sss']);//SSS loan
	if(!$LoanPagibigBool)
		$activeSheet->setCellValue('Z'.$rowCounter, $payrollArr['loan_pagibig']);//Pagibig loan
	if(!$InsuranceBool)
		$activeSheet->setCellValue('AA'.$rowCounter, $payrollArr['insurance']);//Pagibig loan
	if(!$ToolsBool)
		$activeSheet->setCellValue('AB'.$rowCounter, $payrollArr['tools_paid']);//tools

	if($payrollArr['total_salary'] > 0)
	{
		$totalSalary = numberExactFormat($payrollArr['total_salary'], 2, '.', true);
		$activeSheet->setCellValue('AC'.$rowCounter, $totalSalary);//Total Salary
	}
	else
	{
		$activeSheet->setCellValue('AC'.$rowCounter, '0');//Total Salary
	}

	$activeSheet->setCellValue('AD'.$rowCounter, '1');//Total Salary
	if($payrollArr['bank'] != '')
	{
		 $activeSheet-> 
		 		getStyle('A'.$rowCounter.':AD'.$rowCounter)->
                getFill()->
                setFillType(PHPExcel_Style_Fill::FILL_SOLID)->
                getStartColor()->
                setRGB($payrollArr['bank']);
	}
	$rowCounter++; //Row counter

//Style for the Spreadsheet
$activeSheet->getStyle('A1:AD3')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A4:AD'.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('G1:AD2')->applyFromArray($align_center);//Centered header text
$activeSheet->getStyle('A')->applyFromArray($align_center);//Centered period text
$activeSheet->getStyle('AD4')->applyFromArray($signature);//Centered header text
$activeSheet->getStyle('B4')->applyFromArray($align_left);// Left align employee name
$activeSheet->getStyle('AC4')->applyFromArray($font_bold);// Make total value bold

// Set the text color to RED
$activeSheet->getStyle('E4:E4')->applyFromArray($font_red); // # of days
$activeSheet->getStyle('G4:G4')->applyFromArray($font_red); // # of Hours
$activeSheet->getStyle('K4:L4')->applyFromArray($font_red); // Sunday
$activeSheet->getStyle('N4:N4')->applyFromArray($font_red); // # Nightdiff
$activeSheet->getStyle('P4:P4')->applyFromArray($font_red); // # Regular holiday
$activeSheet->getStyle('R4:AB4')->applyFromArray($font_red); // # Specialholiday to tools column

$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setAutoSize(true);
$activeSheet->getColumnDimension('G')->setAutoSize(true);
$activeSheet->getColumnDimension('H')->setAutoSize(true);
$activeSheet->getColumnDimension('I')->setAutoSize(true);
$activeSheet->getColumnDimension('J')->setAutoSize(true);
$activeSheet->getColumnDimension('K')->setAutoSize(true);
$activeSheet->getColumnDimension('L')->setAutoSize(true);
$activeSheet->getColumnDimension('M')->setAutoSize(true);
$activeSheet->getColumnDimension('N')->setAutoSize(true);
$activeSheet->getColumnDimension('O')->setAutoSize(true);
$activeSheet->getColumnDimension('P')->setAutoSize(true);
$activeSheet->getColumnDimension('Q')->setAutoSize(true);
$activeSheet->getColumnDimension('R')->setAutoSize(true);
$activeSheet->getColumnDimension('S')->setAutoSize(true);
$activeSheet->getColumnDimension('T')->setAutoSize(true);
$activeSheet->getColumnDimension('U')->setAutoSize(true);
$activeSheet->getColumnDimension('V')->setAutoSize(true);
$activeSheet->getColumnDimension('W')->setAutoSize(true);
$activeSheet->getColumnDimension('X')->setWidth(9);
$activeSheet->getColumnDimension('Y')->setAutoSize(true);
$activeSheet->getColumnDimension('Z')->setAutoSize(true);
$activeSheet->getColumnDimension('AA')->setAutoSize(true);
$activeSheet->getColumnDimension('AB')->setAutoSize(true);
$activeSheet->getColumnDimension('AC')->setAutoSize(true);
$activeSheet->getColumnDimension('AD')->setAutoSize(true);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













