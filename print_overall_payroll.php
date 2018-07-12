<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$location = $_GET['site'];//Sample data
$payDay = $_GET['date'];
$req = $_GET['req'];

$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDay)));
$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));


// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');
$date = $startDate." - ".$endDate;
$filename = $location." Payroll ".$date.".xls";

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:F1');//site name
$activeSheet->mergeCells('A2:F2');//date
$activeSheet->mergeCells('G1:AC2');//"PAYROLL"

//----------------- Header Contents ---------------------//
//Title Contents
if($req == "withReq")
	$activeSheet->setCellValue('A1', $location." W/ Requirements");//Site w/ requirements
else if($req == "withOReq")
	$activeSheet->setCellValue('A1', $location." W/O Requirements");//Site w/ requirements
else
	$activeSheet->setCellValue('A1', $location." All Employees");//Site w/ requirements

$activeSheet->setCellValue('A2', "Date Covered: ".$startDate." - ".$endDate);//Date
$activeSheet->setCellValue('G1', 'PAYROLL');//"Payroll"

//Header Contents
$activeSheet->setCellValue('B3', 'Name');
$activeSheet->setCellValue('C3', 'Position');
$activeSheet->setCellValue('D3', 'Rate');
$activeSheet->setCellValue('E3', '#ofDays');
$activeSheet->setCellValue('F3', 'O.T.');
$activeSheet->setCellValue('G3', '#ofHrs');
$activeSheet->setCellValue('H3', 'Allow.');
$activeSheet->setCellValue('I3', 'cola');
$activeSheet->setCellValue('J3', 'Sun');
$activeSheet->setCellValue('K3', 'D');
$activeSheet->setCellValue('L3', 'hrs');
$activeSheet->setCellValue('M3', 'N.D');
$activeSheet->setCellValue('N3', '#');
$activeSheet->setCellValue('O3', 'Reg.Hol');
$activeSheet->setCellValue('P3', '#');
$activeSheet->setCellValue('Q3', 'Spe.Hol');
$activeSheet->setCellValue('R3', '#');
$activeSheet->setCellValue('S3', 'X All.');
$activeSheet->setCellValue('T3', 'SSS');
$activeSheet->setCellValue('U3', 'Philhealth');
$activeSheet->setCellValue('V3', 'Pagibig');
$activeSheet->setCellValue('W3', 'old vale');
$activeSheet->setCellValue('X3', 'vale');

$activeSheet->setCellValue('Y3', 'SSS loan');
$activeSheet->setCellValue('Z3', 'P-ibig loan');

$activeSheet->setCellValue('AA3', 'tools');
$activeSheet->setCellValue('AB3', 'Total Salary');
$activeSheet->setCellValue('AC3', 'Signature');


//----------------- Body ---------------------//
$appendQuery = "";
if($req == "withReq")
	$appendQuery = " AND complete_doc = '1' ";
else if($req == "withOReq")
	$appendQuery = " AND complete_doc = '0' ";

$site = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' $appendQuery ORDER BY lastname ASC, position ASC";
$siteQuery = mysql_query($site) or die (mysql_error());
$counter = 0;
$rowCounter = 4; //start for the data in the row of excel
$GrandTotal = 0;
while($siteArr = mysql_fetch_assoc($siteQuery))
{
	
	$employeeName = $siteArr['lastname'].", ".$siteArr['firstname'];
	$employeePosition = $siteArr['position'];
	$empid = $siteArr['empid'];
	
	$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$payDay'";
	$payrollQuery = mysql_query($payroll) or die (mysql_error());
	if(mysql_num_rows($payrollQuery) != 0)
	{
		$counter++;
		$payrollArr = mysql_fetch_assoc($payrollQuery);

		$activeSheet->setCellValue('A'.$rowCounter, $counter);//#
		$activeSheet->setCellValue('B'.$rowCounter, $employeeName);//Name of worker
		$activeSheet->setCellValue('C'.$rowCounter, $employeePosition);//Name of worker

		//Gets the actual holiday num
		if($payrollArr['reg_holiday_num'] > 1)
		{
			$holidayRegChecker = "SELECT * FROM holiday AS h INNER JOIN attendance AS a ON h.date = a.date WHERE a.empid = '$empid' AND a.attendance = '2' AND h.type = 'regular'";
			$holidayRegQuery = mysql_query($holidayRegChecker);
			$regHolidayNum = mysql_num_rows($holidayRegQuery);
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

		$activeSheet->setCellValue('D'.$rowCounter, $siteArr['rate']);//Rate
		$activeSheet->setCellValue('E'.$rowCounter, $payrollArr['num_days']);//ofDays
		$activeSheet->setCellValue('F'.$rowCounter, $payrollArr['overtime']);//O.T.
		$activeSheet->setCellValue('G'.$rowCounter, $payrollArr['ot_num']);//#ofHrs
		$activeSheet->setCellValue('H'.$rowCounter, $payrollArr['allow']);//Allow.
		$activeSheet->setCellValue('I'.$rowCounter, $payrollArr['cola']);//cola
		$activeSheet->setCellValue('J'.$rowCounter, $payrollArr['sunday_rate']);//Sun
		if($sundayBool)
			$activeSheet->setCellValue('K'.$rowCounter, '1');//D
		else
			$activeSheet->setCellValue('K'.$rowCounter, '0');
		$activeSheet->setCellValue('L'.$rowCounter, $payrollArr['sunday_hrs']);//hrs
		$activeSheet->setCellValue('M'.$rowCounter, $payrollArr['nightdiff_rate']);//N.D
		$activeSheet->setCellValue('N'.$rowCounter, $payrollArr['nightdiff_num']);//#
		$activeSheet->setCellValue('O'.$rowCounter, $payrollArr['reg_holiday']);//Reg.Hol
		$activeSheet->setCellValue('P'.$rowCounter, $regHolidayNum);//#
		$activeSheet->setCellValue('Q'.$rowCounter, $payrollArr['spe_holiday']);//Spe.Hol
		$activeSheet->setCellValue('R'.$rowCounter, $payrollArr['spe_holiday_num']);//#
		$activeSheet->setCellValue('S'.$rowCounter, $payrollArr['x_allowance']);//X All.
		$activeSheet->setCellValue('T'.$rowCounter, $payrollArr['sss']);//SSS
		$activeSheet->setCellValue('U'.$rowCounter, $payrollArr['philhealth']);//Philhealth
		$activeSheet->setCellValue('V'.$rowCounter, $payrollArr['pagibig']);//Pagibig
		$activeSheet->setCellValue('W'.$rowCounter, $payrollArr['old_vale']);//old vale
		$activeSheet->setCellValue('X'.$rowCounter, $payrollArr['new_vale']);//vale

		$activeSheet->setCellValue('Y'.$rowCounter, $payrollArr['loan_sss']);//SSS loan
		$activeSheet->setCellValue('Z'.$rowCounter, $payrollArr['loan_pagibig']);//Pagibig loan

		$activeSheet->setCellValue('AA'.$rowCounter, $payrollArr['tools_paid']);//tools

		$totalSalary = numberExactFormat($payrollArr['total_salary'], 2, '.', true);
		$activeSheet->setCellValue('AB'.$rowCounter, $totalSalary);//Total Salary

		$activeSheet->setCellValue('AC'.$rowCounter, $counter);//tools

		$GrandTotal += $payrollArr['total_salary'];// Gets the overall total salary

		$rowCounter++; //Row counter
	}
}

$rowCounter++;//to give space for clearer data

//------ Grand total -----
$grandTotalRow = $rowCounter + 1;

//Grandtotal Merge cell
$activeSheet->mergeCells('AA'.$grandTotalRow.':AB'.$grandTotalRow);
$GrandTotal = numberExactFormat($GrandTotal, 2, '.', true);
$activeSheet->setCellValue('AA'.$grandTotalRow, 'Grand Total:        '.$GrandTotal);

//Style for the Spreadsheet
$activeSheet->getStyle('A3:AC3')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A4:AC'.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('AA'.$grandTotalRow.':AB'.$grandTotalRow)->applyFromArray($border_allsides_medium);//Grand Total
$activeSheet->getStyle('AC1:AC'.$rowCounter)->applyFromArray($signature);//Centered header text

$activeSheet->getStyle('G1:AC2')->applyFromArray($align_center);//Centered header text
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
$activeSheet->getColumnDimension('X')->setAutoSize(true);
$activeSheet->getColumnDimension('Y')->setAutoSize(true);
$activeSheet->getColumnDimension('Z')->setAutoSize(true);
$activeSheet->getColumnDimension('AA')->setAutoSize(true);
$activeSheet->getColumnDimension('AB')->setAutoSize(true);

// header('Content-Type: application/vnd.ms-excel');
// header('Content-Disposition: attachment; filename="'.$filename.'"');
// header('Cache-Control: max-age=0');

// $objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
// $objWriter->save('php://output');
// exit;

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet,'Excel2007');
$objWriter->save('php://output');
exit;

?>













