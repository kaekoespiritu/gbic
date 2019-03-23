<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

// $date = $_GET['date'];
$site = $_GET['site'];
$require = $_GET['req'];

$siteCheck = "SELECT * FROM site WHERE location = '$site' AND active = '1'";
$siteQuery = mysql_query($siteCheck);

if(mysql_num_rows($siteQuery) == 0)
	header("location: index.php");

switch($require)
{
	case "all": $requirementDisplay = "Complete/Incomplete Requirements"; break;
	case "withReq": $requirementDisplay = "Complete Requirements"; break;
	case "withOReq": $requirementDisplay = "Incomplete Requirements"; break;
}

$payDay = $_GET['date'];
$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDay)));
$weekBefore = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

// Check for early cutoff 
$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$payDay' LIMIT 1";
$cutoffQuery = mysql_query($cutoffCheck);
if($_GET['cutoff'] != '')// get the Start of cutoff
{
	$weekBefore = $_GET['cutoff'];
	$weekBefore = str_replace("'","",$weekBefore);
}
else if(mysql_num_rows($cutoffQuery) > 0)
{
	$cutoffArr = mysql_fetch_assoc($cutoffQuery);
	$weekBefore = $cutoffArr['start'];
	$endDate = $cutoffArr['end'];
}

$filename =  $site." Payslip ".$weekBefore." - ".$endDate.".xls";

$dateDisplay = $weekBefore." - ".$endDate;

function decimalPlaces($val) //remove decimal places if 0
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

//----------------- Header Contents ---------------------//
$endDateExplode = explode(' ', $endDate);
$endDateMonth = monthConvert($endDateExplode[0]);
$endDateDay = substr($endDateExplode[1], 0, -1);
$endYear = $endDateExplode[2];

$startDateExplode = explode(' ', $weekBefore);

$startDateMonth = monthConvert($startDateExplode[0]);
$startDateDay = substr($startDateExplode[1], 0, -1);

if($endDateMonth == $startDateMonth)
	$dateCovered = $startDateMonth."/".$startDateDay."-".$endDateDay.",".$endYear;
else
	$dateCovered = $startDateMonth."/".$startDateDay."-".$endDateMonth."/".$endDateDay.",".$endYear;

//------ Counter for merged cells ------//
$dateMergeCounter = 1;
$nameMergeCounter = 2;

//------ Counter for header cells ------//
$dateCoveredRowCounter = 1;
$nameRowCounter = 2;
$rateRowCounter = 3;
$overtimeRowCounter = 4;
$allowRowCounter = 5;
$colaRowCounter = 6;
$sundayRowCounter = 7;
$nightDiffRowCounter = 8;
$regHolRowCounter = 9;
$speHolRowCounter = 10;
$sssRowCounter = 11;
$philhealthRowCounter = 12;
$pagibigRowCounter = 13;
$oldValeRowCounter = 14;
$newValeRowCounter = 15;
$sssLoanRowCounter = 16;
$pagibigLoanRowCounter = 17;
$toolsRowCounter = 18;
$extraAllowanceRowCounter = 11;
$insuranceRowCounter = 12;

//------ Counter for date ------//
$rateDataCounter = 3;//Rate
$overtimeDataCounter = 4;//Overtime
$allowDataCounter = 5;//Allowance
$colaDataCounter = 6;//COLA
$sundayDataCounter = 7;//Sunday
$NDDataCounter = 8;//Night differential
$regHolDataCounter = 9;//Regular Holiday
$speHolDataCounter = 10;//Special Holiday

$sssDataCounter = 11;//sss
$philhealthDataCounter = 12;//philhealth
$pagibigDataCounter = 13;//pagibig
$insuranceDataCounter = 12;//insurance

$xAllowanceDataCounter = 11;//Allowance
$oldValeDataCounter = 14;//Vale
$newValeDataCounter = 15;

$sssLoanDataCounter = 16;//sss loan
$pagibigLoanDataCounter = 17;//pagibig loan

$toolsDataCounter = 18;//Tools
$totalDataCounter = 19;//Total

//------ Counter Style ------//
//Border
$borderStyleCounter1 = 1;
$borderStyleCounter2 = 19;
//Extra Allowance
$allowanceStyleCounter = 11;
//Total
$totalStyleCounter = 19;
//Header
$headerStyleCounter1 = 1;
$headerStyleCounter2 = 2;

$rowIncrement = 19;// increment by 17 inpreparation for the new horizontal data

$appendQuery = "";
if($require == "withReq")
	$appendQuery = "AND complete_doc = '1'";
else if($require == "withOReq")
	$appendQuery = "AND complete_doc = '0'";

$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' $appendQuery";
$employeeQuery = mysql_query($employee) or die(mysql_error());

$rowNum = mysql_num_rows($employeeQuery);
$rowCount = $rowNum / 5;
$countExplode = explode('.', $rowCount);
$loopCount = ($rowCount != 0 ? $countExplode[0] : 1);//gets only the whole number if zero then set to 1 for one loop

$endCounter = 5;//end Query
$startCounter = 0;// start query

$cellArray = 	array(
					array('A','B','C','D'),//1st payslip column
					array('F','G','H','I'),//2nd payslip column
					array('K','L','M','N'),//3rd payslip column
					array('P','Q','R','S'),//4th payslip column
					array('U','V','W','X'),//5th payslip column
				);
// $empCounter = 1;
for($count = 0; $count <= $loopCount; $count++)
{
	$emp = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' $appendQuery LIMIT {$startCounter}, {$endCounter}";
	
	$empQuery = mysql_query($emp) or die(mysql_error());
	if(mysql_num_rows($empQuery) != 0)
	{

		$counter = 0;//counter for loop for horizontal display
		while($empRow = mysql_fetch_assoc($empQuery))//horizontal display
		{
			$empid = $empRow['empid'];
			$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$payDay'";
			$payrollQuery = mysql_query($payroll);

			if(mysql_num_rows($payrollQuery) != 0)
			{
				// $empCounter++;	
				$cellA = $cellArray[$counter][0];
				$cellB = $cellArray[$counter][1];
				$cellC = $cellArray[$counter][2];
				$cellD = $cellArray[$counter][3];
				
				//Merge cells
				$activeSheet->mergeCells($cellA.$dateMergeCounter.':'.$cellD.$dateMergeCounter);// Date
				$activeSheet->mergeCells($cellA.$nameMergeCounter.':'.$cellD.$nameMergeCounter);// Name

				// Adding static text with name and date covered
				$activeSheet->setCellValue($cellA.$dateCoveredRowCounter, 'Date Covered: '.$dateCovered);
				$activeSheet->setCellValue($cellA.$nameRowCounter, $empRow['lastname'].", ".$empRow['firstname']);

				$activeSheet->setCellValue($cellA.$rateRowCounter, 'Rate');
				$activeSheet->setCellValue($cellA.$overtimeRowCounter, 'OT');
				$activeSheet->setCellValue($cellA.$allowRowCounter, 'Allow.');
				$activeSheet->setCellValue($cellA.$colaRowCounter, 'cola');
				$activeSheet->setCellValue($cellA.$sundayRowCounter, 'Sun');
				$activeSheet->setCellValue($cellA.$nightDiffRowCounter, 'N.D');
				$activeSheet->setCellValue($cellA.$regHolRowCounter, 'Reg. Hol');
				$activeSheet->setCellValue($cellA.$speHolRowCounter, 'Spe. Hol');
				$activeSheet->setCellValue($cellC.$insuranceRowCounter, 'Ins.');
				$activeSheet->setCellValue($cellA.$sssRowCounter, 'SSS');
				$activeSheet->setCellValue($cellA.$philhealthRowCounter, 'PhilHealth');
				$activeSheet->setCellValue($cellA.$pagibigRowCounter, 'Pag-IBIG');
				$activeSheet->setCellValue($cellA.$oldValeRowCounter, 'Old vale');
				$activeSheet->setCellValue($cellA.$newValeRowCounter, 'vale');
				$activeSheet->setCellValue($cellA.$toolsRowCounter, 'tools');

				$activeSheet->setCellValue($cellA.$sssLoanRowCounter, 'SSS loan');
				$activeSheet->setCellValue($cellA.$pagibigLoanRowCounter, 'Pagibig loan');

				$activeSheet->setCellValue($cellC.$extraAllowanceRowCounter, 'X. All.');

				//------------ Style for the Spreadsheet ------------//
				$activeSheet->getStyle($cellA.$borderStyleCounter1.':'.$cellD.$borderStyleCounter2)->applyFromArray($border_allsides_medium); 

				//extra Allowance
				$activeSheet->getStyle($cellC.$allowanceStyleCounter)->applyFromArray($border_buttom_left_thin);
				$activeSheet->getStyle($cellD.$allowanceStyleCounter)->applyFromArray($border_buttom_thin);

				//Total
				$activeSheet->getStyle($cellC.$totalStyleCounter.':'.$cellD.$totalStyleCounter)->applyFromArray($border_top_double);

				//Header
				$activeSheet->getStyle($cellA.$headerStyleCounter1.':'.$cellA.$headerStyleCounter2)->applyFromArray($align_left);
				$activeSheet->getStyle($cellA.$headerStyleCounter2)->applyFromArray($font_bold);
				

				//------------ Data for the Spreadsheet ------------//

				$payrollArr = mysql_fetch_assoc($payrollQuery);

				//Rate
				if($payrollArr['rate'] != 0)
					$activeSheet->setCellValue($cellB.$rateDataCounter, $payrollArr['rate']);
				if($payrollArr['num_days'] != 0)	
					$activeSheet->setCellValue($cellC.$rateDataCounter, 'x '.decimalPlaces($payrollArr['num_days']));

				$rateSubTotal = $payrollArr['rate'] * $payrollArr['num_days'];
				if($rateSubTotal != 0)	
					$activeSheet->setCellValue($cellD.$rateDataCounter, $rateSubTotal);

				//Overtime
				if($payrollArr['overtime'] != 0)
					$activeSheet->setCellValue($cellB.$overtimeDataCounter, $payrollArr['overtime']);
				if($payrollArr['ot_num'] != 0)
					$activeSheet->setCellValue($cellC.$overtimeDataCounter, 'x '.decimalPlaces($payrollArr['ot_num']));

				$OTSubTotal = $payrollArr['ot_num'] * $payrollArr['overtime'];
				if($OTSubTotal != 0)	
					$activeSheet->setCellValue($cellD.$overtimeDataCounter, $OTSubTotal);

				//Allowance
				$daysAllowance = $payrollArr['allow_days'];
				// if(!empty($payrollArr['sunday_hrs']))
				// 	$daysAllowance++;
				if($payrollArr['allow'] != 0)
					$activeSheet->setCellValue($cellB.$allowDataCounter, $payrollArr['allow']);
				if($daysAllowance != 0)
					$activeSheet->setCellValue($cellC.$allowDataCounter, 'x '.decimalPlaces($daysAllowance));

				$allowSubTotal = $payrollArr['allow'] * $daysAllowance;
				if($allowSubTotal != 0)	
					$activeSheet->setCellValue($cellD.$allowDataCounter, $allowSubTotal);

				//Cola
				if($payrollArr['cola'] != 0)
					$activeSheet->setCellValue($cellB.$colaDataCounter, ($payrollArr['cola']/$payrollArr['allow_days']));
				if($daysAllowance != 0)
					$activeSheet->setCellValue($cellC.$colaDataCounter, 'x '.decimalPlaces($daysAllowance));

				$colaSubTotal = ($payrollArr['cola']/$payrollArr['allow_days']) * $daysAllowance;
				if($colaSubTotal != 0)
					$activeSheet->setCellValue($cellD.$colaDataCounter, $colaSubTotal);

				//Sunday
				if($payrollArr['sunday_rate'] != 0)
					$activeSheet->setCellValue($cellB.$sundayDataCounter, $payrollArr['sunday_rate']);
				if($payrollArr['sunday_hrs'] != 0)
					$activeSheet->setCellValue($cellC.$sundayDataCounter, 'x '.decimalPlaces($payrollArr['sunday_hrs']));

				$sundaySubTotal = $payrollArr['sunday_hrs'] * $payrollArr['sunday_rate'];
				if($sundaySubTotal != 0)
				$activeSheet->setCellValue($cellD.$sundayDataCounter, $sundaySubTotal);

				//Night differential
				if($payrollArr['nightdiff_rate'] != 0)
					$activeSheet->setCellValue($cellB.$NDDataCounter, $payrollArr['nightdiff_rate']);
				if($payrollArr['nightdiff_num'] != 0)
					$activeSheet->setCellValue($cellC.$NDDataCounter, 'x '.$payrollArr['nightdiff_num']);

				$NDSubTotal = $payrollArr['nightdiff_num'] * $payrollArr['nightdiff_rate'];
				if($NDSubTotal != 0)
					$activeSheet->setCellValue($cellD.$NDDataCounter, $NDSubTotal);

				//Regular Holiday
				if($payrollArr['reg_holiday'] != 0)
					$activeSheet->setCellValue($cellB.$regHolDataCounter, $payrollArr['reg_holiday']);
				if($payrollArr['reg_holiday_num'] != 0)
					$activeSheet->setCellValue($cellC.$regHolDataCounter, 'x '.$payrollArr['reg_holiday_num']);

				$regHolSubTotal = $payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday'];
				if($regHolSubTotal != 0)
					$activeSheet->setCellValue($cellD.$regHolDataCounter, $regHolSubTotal);

				//Special Holiday
				if($payrollArr['spe_holiday'] != 0)
					$activeSheet->setCellValue($cellB.$speHolDataCounter, $payrollArr['spe_holiday']);
				if($payrollArr['spe_holiday_num'] != 0)
					$activeSheet->setCellValue($cellC.$speHolDataCounter, 'x '.$payrollArr['spe_holiday_num']);

				$speHolSubTotal = $payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday'];
				if($speHolSubTotal != 0)
					$activeSheet->setCellValue($cellD.$speHolDataCounter, $speHolSubTotal);

				//Contribution
				if($payrollArr['sss'] != 0)
					$activeSheet->setCellValue($cellB.$sssDataCounter, $payrollArr['sss']);
				if($payrollArr['philhealth'] != 0)
					$activeSheet->setCellValue($cellB.$philhealthDataCounter, $payrollArr['philhealth']);
				if($payrollArr['pagibig'] != 0)
					$activeSheet->setCellValue($cellB.$pagibigDataCounter, $payrollArr['pagibig']);

				//Allowance
				if($payrollArr['x_allowance'] != 0 || $payrollArr['x_allow_weekly'] != 0|| $payrollArr['x_allow_daily'] != 0)
					$activeSheet->setCellValue($cellD.$xAllowanceDataCounter, ($payrollArr['x_allowance'] + $payrollArr['x_allow_weekly'] + ($payrollArr['x_allow_daily'] * $payrollArr['allow_days'])));

				//Vale
				if($payrollArr['old_vale'] != 0)
					$activeSheet->setCellValue($cellB.$oldValeDataCounter, $payrollArr['old_vale']);

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

				$activeSheet->setCellValue($cellB.$newValeDataCounter, $payrollOutstanding);

				//Loans
				if($payrollArr['loan_sss'] != 0)
					$activeSheet->setCellValue($cellB.$sssLoanDataCounter, $payrollArr['loan_sss']);
				if($payrollArr['loan_pagibig'] != 0)
					$activeSheet->setCellValue($cellB.$pagibigLoanDataCounter, $payrollArr['loan_pagibig']);

				//Insurance
				if($payrollArr['insurance'] != 0)
					$activeSheet->setCellValue($cellD.$insuranceDataCounter, $payrollArr['insurance']);

				//Tools
				if($payrollArr['tools_paid'] != 0)
					$activeSheet->setCellValue($cellB.$toolsDataCounter , $payrollArr['tools_paid']);

				//Total
				$activeSheet->mergeCells($cellC.$totalDataCounter.":".$cellD.$totalDataCounter);// Date

				if($payrollArr['total_salary'] > 0)
				{
					$activeSheet->setCellValue($cellC.$totalDataCounter, $payrollArr['total_salary']);
					$activeSheet->getStyle($cellC.$totalDataCounter)->applyFromArray($font_bold);
				}
				else
				{
					$activeSheet->setCellValue($cellC.$totalDataCounter, '0');
					$activeSheet->getStyle($cellC.$totalDataCounter)->applyFromArray($font_bold);
				}

				$counter++;//Increment counter for horizontal inputs

				//Keep static row height
				$activeSheet->getRowDimension($dateCoveredRowCounter)->setRowHeight(10.5); // 1
				$activeSheet->getRowDimension($nameRowCounter)->setRowHeight(15); // 2
				$activeSheet->getRowDimension($rateRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($allowRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($overtimeRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($colaRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($sundayRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($nightDiffRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($regHolRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($speHolRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($insuranceRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($sssRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($philhealthRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($pagibigRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($oldValeRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($newValeRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($sssLoanRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($pagibigLoanRowCounter)->setRowHeight(12);
				$activeSheet->getRowDimension($totalDataCounter)->setRowHeight(15); // 19
			}
		}

		//------------ Increment Row Number ------------//

		//------ Counter for merged cells ------//
		$dateMergeCounter += $rowIncrement;
		$nameMergeCounter += $rowIncrement;

		//------ Counter for header cells ------//
		$dateCoveredRowCounter += $rowIncrement;
		$nameRowCounter += $rowIncrement;
		$rateRowCounter += $rowIncrement;
		$allowRowCounter += $rowIncrement;
		$overtimeRowCounter += $rowIncrement;
		$colaRowCounter += $rowIncrement;
		$sundayRowCounter += $rowIncrement;
		$nightDiffRowCounter += $rowIncrement;
		$regHolRowCounter += $rowIncrement;
		$speHolRowCounter += $rowIncrement;
		$insuranceRowCounter += $rowIncrement;
		$sssRowCounter += $rowIncrement;
		$philhealthRowCounter += $rowIncrement;
		$pagibigRowCounter += $rowIncrement;
		$oldValeRowCounter += $rowIncrement;
		$newValeRowCounter += $rowIncrement;

		$sssLoanRowCounter += $rowIncrement;
		$pagibigLoanRowCounter += $rowIncrement;

		$toolsRowCounter += $rowIncrement;
		$extraAllowanceRowCounter += $rowIncrement;

		//------ Counter for date ------//
		$rateDataCounter += $rowIncrement;
		$allowDataCounter += $rowIncrement;
		$overtimeDataCounter += $rowIncrement;
		$colaDataCounter += $rowIncrement;
		$sundayDataCounter += $rowIncrement;
		$NDDataCounter += $rowIncrement;
		$regHolDataCounter += $rowIncrement;
		$speHolDataCounter += $rowIncrement;

		$insuranceDataCounter += $rowIncrement;
		$sssDataCounter += $rowIncrement;
		$philhealthDataCounter += $rowIncrement;
		$pagibigDataCounter += $rowIncrement;

		$xAllowanceDataCounter += $rowIncrement;
		$oldValeDataCounter += $rowIncrement;
		$newValeDataCounter += $rowIncrement;

		$sssLoanDataCounter += $rowIncrement;
		$pagibigLoanDataCounter += $rowIncrement;

		$toolsDataCounter += $rowIncrement;
		$totalDataCounter+= $rowIncrement;

		//------ Counter Style ------//
		//Border
		$borderStyleCounter1 += $rowIncrement;
		$borderStyleCounter2 += $rowIncrement;
		//Extra Allowance
		$allowanceStyleCounter += $rowIncrement;
		//Total
		$totalStyleCounter += $rowIncrement; 
		//Header
		$headerStyleCounter1 += $rowIncrement;
		$headerStyleCounter2 += $rowIncrement;

		$startCounter += 5;//increment to next batch of employees
	}
		
}

	//----------------- Body Contents ---------------------//

// Changing column sizes
$activeSheet->getColumnDimension('A')->setWidth(15.33);
$activeSheet->getColumnDimension('B')->setWidth(7);
$activeSheet->getColumnDimension('C')->setWidth(9.33);
$activeSheet->getColumnDimension('D')->setWidth(7);
$activeSheet->getColumnDimension('E')->setWidth(0.45);//spacer
$activeSheet->getColumnDimension('F')->setWidth(15.33);
$activeSheet->getColumnDimension('G')->setWidth(7);
$activeSheet->getColumnDimension('H')->setWidth(9.33);
$activeSheet->getColumnDimension('I')->setWidth(7);
$activeSheet->getColumnDimension('J')->setWidth(0.45);//spacer
$activeSheet->getColumnDimension('K')->setWidth(15.33);
$activeSheet->getColumnDimension('L')->setWidth(7);
$activeSheet->getColumnDimension('M')->setWidth(9.33);
$activeSheet->getColumnDimension('N')->setWidth(7);
$activeSheet->getColumnDimension('O')->setWidth(0.45);//spacer
$activeSheet->getColumnDimension('P')->setWidth(15.33);
$activeSheet->getColumnDimension('Q')->setWidth(7);
$activeSheet->getColumnDimension('R')->setWidth(9.33);
$activeSheet->getColumnDimension('S')->setWidth(7);
$activeSheet->getColumnDimension('T')->setWidth(0.45);//spacer
$activeSheet->getColumnDimension('U')->setWidth(15.33);
$activeSheet->getColumnDimension('V')->setWidth(7);
$activeSheet->getColumnDimension('W')->setWidth(9.33);
$activeSheet->getColumnDimension('X')->setWidth(7);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













