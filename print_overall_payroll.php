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
$activeSheet->mergeCells('G1:AD2');//"PAYROLL"

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

$activeSheet->setCellValue('AA3', 'Ins.');

$activeSheet->setCellValue('AB3', 'tools');
$activeSheet->setCellValue('AC3', 'Total Salary');
$activeSheet->setCellValue('AD3', 'Employee Signature');

//----------------- Body ---------------------//
$appendQuery = "";
if($req == "withReq")
	$appendQuery = " AND complete_doc = '1' ";
else if($req == "withOReq")
	$appendQuery = " AND complete_doc = '0' ";

$site = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' $appendQuery ORDER BY position ASC, lastname ASC";
$siteQuery = mysql_query($site) or die (mysql_error());
$counter = 0;
$rowCounter = 4; //start for the data in the row of excel
$GrandTotal = 0;
$positionSort = '';
$positionSortOnceBool = true;
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

		if($positionSortOnceBool)//pass this only once to start the sorting process
		{
			$positionSort = $siteArr['position'];// change the position
			$positionSortOnceBool = false;
		}

		// Add space when new position is seen
		if($positionSort != $siteArr['position'])
		{
			$positionSort = $siteArr['position'];// Change the position
			$activeSheet->getRowDimension($rowCounter)->setRowHeight(32);
			$rowCounter++;// Add one space
			$counter = 1;
		}
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
		$sundayBool = ($payrollArr['sunday_hrs'] == 0 ? true : false);// employee didn't attend sunday

		// Removing zeros from payroll
		$NDnumBool = (intval($payrollArr['nightdiff_num'] == 0) ? true : false);
		$regHolBool = (intval($regHolidayNum == 0) ? true : false);
		$speHolBool = (intval($payrollArr['spe_holiday_num'] == 0) ? true : false);
		$AllowBool = (intval($payrollArr['allow'] == 0) ? true : false);
		$colaBool = (intval($payrollArr['cola'] == 0) ? true : false);
		if(	intval($payrollArr['x_allowance']) != 0 || 
			intval($payrollArr['x_allow_daily']) != 0 || 
			intval($payrollArr['x_allow_weekly']) != 0)
			$XallowBool = true;
		else
			$XallowBool = false;
		// $XallowBool = (	intval($payrollArr['x_allowance']) == 0 || 
		// 				intval($payrollArr['x_allow_daily']) == 0 || 
		// 				intval($payrollArr['x_allow_weekly']) == 0 ? true : false);
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

		$activeSheet->setCellValue('D'.$rowCounter, $siteArr['rate']);//Rate
		if(!$AttendanceBool)
			$activeSheet->setCellValue('E'.$rowCounter, $payrollArr['num_days']);//ofDays
		if(!$OTBool)
			$activeSheet->setCellValue('F'.$rowCounter, $payrollArr['overtime']);//O.T.
		if(!$OTHrsBool)
			$activeSheet->setCellValue('G'.$rowCounter, $payrollArr['ot_num']);//#ofHrs
		if(!$AllowBool)
			$activeSheet->setCellValue('H'.$rowCounter, $payrollArr['allow']);//Allow.
		if(!$colaBool)
			$activeSheet->setCellValue('I'.$rowCounter, ($payrollArr['cola']/$payrollArr['allow_days']));//cola
		//---
		if($siteArr['complete_doc'] == '1')
		{
			$activeSheet->setCellValue('J'.$rowCounter, $payrollArr['sunday_rate']);//Sun
			if(!$sundayBool)
			{
				$activeSheet->setCellValue('K'.$rowCounter, '1');//D
				$activeSheet->setCellValue('L'.$rowCounter, $payrollArr['sunday_hrs']);//hrs
			}
			$activeSheet->setCellValue('M'.$rowCounter, $payrollArr['nightdiff_rate']);//N.D
			if(!$NDnumBool)
				$activeSheet->setCellValue('N'.$rowCounter, $payrollArr['nightdiff_num']);//#
			$activeSheet->setCellValue('O'.$rowCounter, $payrollArr['reg_holiday']);//Reg.Hol
			if(!$regHolBool)
				$activeSheet->setCellValue('P'.$rowCounter, $regHolidayNum);//#
			$activeSheet->setCellValue('Q'.$rowCounter, $payrollArr['spe_holiday']);//Spe.Hol
			if(!$speHolBool)
				$activeSheet->setCellValue('R'.$rowCounter, $payrollArr['spe_holiday_num']);//#
		}
			
		//---
		if($XallowBool)
			$activeSheet->setCellValue('S'.$rowCounter, ($payrollArr['x_allowance'] + $payrollArr['x_allow_weekly'] + ($payrollArr['x_allow_daily'] * $payrollArr['allow_days'])));//X All.
		if(!$SSSBool)
			$activeSheet->setCellValue('T'.$rowCounter, $payrollArr['sss']);//SSS
		if(!$PhilHealthBool)
			$activeSheet->setCellValue('U'.$rowCounter, $payrollArr['philhealth']);//Philhealth
		if(!$PagibigBool)
			$activeSheet->setCellValue('V'.$rowCounter, $payrollArr['pagibig']);//Pagibig
		if(!$OldValeBool)
			$activeSheet->setCellValue('W'.$rowCounter, $payrollArr['old_vale']);//old vale
		if(!$NewValeBool)
			$activeSheet->setCellValue('X'.$rowCounter, $payrollArr['new_vale']);//vale
		if(!$LoanSSSBool)
			$activeSheet->setCellValue('Y'.$rowCounter, $payrollArr['loan_sss']);//SSS loan
		if(!$LoanPagibigBool)
			$activeSheet->setCellValue('Z'.$rowCounter, $payrollArr['loan_pagibig']);//Pagibig loan
		if(!$InsuranceBool)
			$activeSheet->setCellValue('AA'.$rowCounter, $payrollArr['insurance']);//Insurance
		if(!$ToolsBool)
			$activeSheet->setCellValue('AB'.$rowCounter, $payrollArr['tools_paid']);//tools

		$totalSalary = numberExactFormat($payrollArr['total_salary'], 2, '.', true);
		$activeSheet->setCellValue('AC'.$rowCounter, $totalSalary);//Total Salary

		$activeSheet->setCellValue('AD'.$rowCounter, $counter);//tools

		$GrandTotal += $payrollArr['total_salary'];// Gets the overall total salary

		if($payrollArr['bank'] != '')
		{
			 $activeSheet-> 
			 		getStyle('A'.$rowCounter.':AD'.$rowCounter)->
                    getFill()->
                    setFillType(PHPExcel_Style_Fill::FILL_SOLID)->
                    getStartColor()->
                    setRGB($payrollArr['bank']);
		}
		$activeSheet->getRowDimension($rowCounter)->setRowHeight(32);
		$rowCounter++; //Row counter
	}
}

$rowCounter++;//to give space for clearer data

//------ Grand total -----
$grandTotalRow = $rowCounter + 1;

//Grandtotal Merge cell
// $activeSheet->mergeCells('AA'.$grandTotalRow.':AB'.$grandTotalRow);
$GrandTotal = numberExactFormat($GrandTotal, 2, '.', true);
$activeSheet->setCellValue('AC'.$grandTotalRow, 'Grand Total:');
$activeSheet->setCellValue('AD'.$grandTotalRow, $GrandTotal);

// Set the text color to RED
$activeSheet->getStyle('E4:E'.$rowCounter)->applyFromArray($font_red); // # of days
$activeSheet->getStyle('G4:G'.$rowCounter)->applyFromArray($font_red); // # of Hours
$activeSheet->getStyle('K4:L'.$rowCounter)->applyFromArray($font_red); // Sunday
$activeSheet->getStyle('N4:N'.$rowCounter)->applyFromArray($font_red); // # Nightdiff
$activeSheet->getStyle('P4:P'.$rowCounter)->applyFromArray($font_red); // # Regular holiday
$activeSheet->getStyle('R4:AB'.$rowCounter)->applyFromArray($font_red); // # Specialholiday to tools column


//Style for the Spreadsheet
$activeSheet->getStyle('A3:AD3')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A4:AD'.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('AC'.$grandTotalRow.':AD'.$grandTotalRow)->applyFromArray($border_all_medium);//Grand Total
$activeSheet->getStyle('AD1:AD'.$rowCounter)->applyFromArray($signature);//Centered header text
$activeSheet->getStyle('B4:B'.$rowCounter)->applyFromArray($align_left); // Left align employee name
$activeSheet->getStyle('AC4:AC'.$rowCounter)->applyFromArray($align_right); // right align employee name
$activeSheet->getStyle('AC'.$grandTotalRow)->applyFromArray($align_right); // right align employee name
$activeSheet->getStyle('B3:AB3')->applyFromArray($column_header_font);
$activeSheet->getStyle('A4:AD'.$rowCounter)->applyFromArray($data_font);


//Font sizes

$activeSheet->getStyle('A1')->applyFromArray($grand_total_font);// ALL except employee name and PAYROLL header
$activeSheet->getStyle('A2')->applyFromArray($employee_name_font); // Date covered to size 15
$activeSheet->getStyle('A4:AD'.$grandTotalRow)->applyFromArray($grand_total_font);// ALL except employee name and PAYROLL header
$activeSheet->getStyle('G1')->applyFromArray($payroll_font);// Payroll
$activeSheet->getStyle('B4:B'.$rowCounter)->applyFromArray($employee_name_font);// Employee name
$activeSheet->getStyle('A4:A'.$rowCounter)->applyFromArray($data_font);// Column data font
$activeSheet->getStyle('C4:AD'.$rowCounter)->applyFromArray($data_font);// Column data font
$activeSheet->getStyle('AB'.$grandTotalRow.':AC'.$grandTotalRow)->applyFromArray($font_bold);// Make total value bold

$activeSheet->getStyle('G1:AC2')->applyFromArray($align_center);//Centered header text
$activeSheet->getColumnDimension('A')->setWidth(3.66);
$activeSheet->getColumnDimension('B')->setWidth(35);
$activeSheet->getColumnDimension('C')->setWidth(21);
$activeSheet->getColumnDimension('D')->setWidth(7.5);
$activeSheet->getColumnDimension('E')->setWidth(9.5); 
$activeSheet->getColumnDimension('F')->setWidth(9.5);
$activeSheet->getColumnDimension('G')->setWidth(9.5);
$activeSheet->getColumnDimension('H')->setWidth(8.16);
$activeSheet->getColumnDimension('I')->setWidth(5.83);
$activeSheet->getColumnDimension('J')->setWidth(5.5);
$activeSheet->getColumnDimension('K')->setWidth(3.66);
$activeSheet->getColumnDimension('L')->setWidth(4.5);
$activeSheet->getColumnDimension('M')->setWidth(4.5);
$activeSheet->getColumnDimension('N')->setWidth(7.5);
$activeSheet->getColumnDimension('O')->setWidth(9.33);
$activeSheet->getColumnDimension('P')->setWidth(3.66);
$activeSheet->getColumnDimension('Q')->setWidth(9.33);
$activeSheet->getColumnDimension('R')->setWidth(2.33);
$activeSheet->getColumnDimension('S')->setWidth(8.16);
$activeSheet->getColumnDimension('T')->setWidth(11.5);
$activeSheet->getColumnDimension('U')->setWidth(12.83);
$activeSheet->getColumnDimension('V')->setWidth(9.33);
$activeSheet->getColumnDimension('W')->setWidth(10.5);
$activeSheet->getColumnDimension('X')->setWidth(9);
$activeSheet->getColumnDimension('Y')->setWidth(13.33);
$activeSheet->getColumnDimension('Z')->setWidth(14);
$activeSheet->getColumnDimension('AA')->setWidth(5.83);
$activeSheet->getColumnDimension('AB')->setWidth(25);
$activeSheet->getColumnDimension('AC')->setWidth(19.16);
$activeSheet->getColumnDimension('AD')->setWidth(22.16);

// Setting row height
$activeSheet->getRowDimension(1)->setRowHeight(24);
$activeSheet->getRowDimension(2)->setRowHeight(20);
$activeSheet->getRowDimension(3)->setRowHeight(15);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment; filename="'.$filename.'"');
// header('Cache-Control: max-age=0');

// $objWriter = PHPExcel_IOFactory::createWriter($sheet,'Excel2007');
// $objWriter->save('php://output');
// exit;

?>













