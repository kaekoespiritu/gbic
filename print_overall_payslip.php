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
$filename =  $site." Payslip ".$weekBefore." - ".$endDate.".xls";

$dateDisplay = $weekBefore." - ".$endDate;

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
$totalMergeCounter = 16;

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
$toolsRowCounter = 16;
$extraAllowanceRowCounter = 11;

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

$xAllowanceDataCounter = 11;//Allowance
$oldValeDataCounter = 14;//Vale
$newValeDataCounter = 15;

$toolsDataCounter = 16;//Tools
$totalDataCounter = 17;//Total

//------ Counter Style ------//
//Border
$borderStyleCounter1 = 1;
$borderStyleCounter2 = 17;
//Extra Allowance
$allowanceStyleCounter = 11;
//Total
$totalStyleCounter = 17;
//Header
$headerStyleCounter1 = 1;
$headerStyleCounter2 = 2;

$rowIncrement = 17;// increment by 17 inpreparation for the new horizontal data

$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1'";
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
	$emp = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' LIMIT {$startCounter}, {$endCounter}";
	
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

				$activeSheet->mergeCells($cellC.$totalMergeCounter.':'.$cellD.$totalMergeCounter);// Total

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
				$activeSheet->setCellValue($cellA.$sssRowCounter, 'SSS');
				$activeSheet->setCellValue($cellA.$philhealthRowCounter, 'PhilHealth');
				$activeSheet->setCellValue($cellA.$pagibigRowCounter, 'Pag-IBIG');
				$activeSheet->setCellValue($cellA.$oldValeRowCounter, 'Old vale');
				$activeSheet->setCellValue($cellA.$newValeRowCounter, 'vale');
				$activeSheet->setCellValue($cellA.$toolsRowCounter, 'tools');

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
				

				//------------ Date for the Spreadsheet ------------//

				

				$payrollArr = mysql_fetch_assoc($payrollQuery);

				//Rate
				$activeSheet->setCellValue($cellB.$rateDataCounter, $payrollArr['rate']);
				$activeSheet->setCellValue($cellC.$rateDataCounter, 'x '.$payrollArr['num_days']);

				$rateSubTotal = $payrollArr['rate'] * $payrollArr['num_days'];
				$activeSheet->setCellValue($cellD.$rateDataCounter, $rateSubTotal);

				//Overtime
				$activeSheet->setCellValue($cellB.$overtimeDataCounter, $payrollArr['overtime']);
				$activeSheet->setCellValue($cellC.$overtimeDataCounter, 'x '.$payrollArr['ot_num']);

				$OTSubTotal = $payrollArr['ot_num'] * $payrollArr['overtime'];
				$activeSheet->setCellValue($cellD.$overtimeDataCounter, $OTSubTotal);

				//Allowance
				$daysAllowance = $payrollArr['num_days'];
				if(!empty($payrollArr['sunday_hrs']))
					$daysAllowance++;
				$activeSheet->setCellValue($cellB.$allowDataCounter, $payrollArr['allow']);
				$activeSheet->setCellValue($cellC.$allowDataCounter, 'x '.$daysAllowance);

				$allowSubTotal = $payrollArr['allow'] * $daysAllowance;
				$activeSheet->setCellValue($cellD.$allowDataCounter, $allowSubTotal);

				//Cola
				$activeSheet->setCellValue($cellB.$colaDataCounter, $payrollArr['cola']);
				$activeSheet->setCellValue($cellC.$colaDataCounter, 'x '.$daysAllowance);

				$colaSubTotal = $payrollArr['cola'] * $daysAllowance;
				$activeSheet->setCellValue($cellD.$colaDataCounter, $colaSubTotal);

				//Sunday
				$activeSheet->setCellValue($cellB.$sundayDataCounter, $payrollArr['sunday_rate']);
				$activeSheet->setCellValue($cellC.$sundayDataCounter, 'x '.$payrollArr['sunday_hrs']);

				$sundaySubTotal = $payrollArr['sunday_hrs'] * $payrollArr['sunday_rate'];
				$activeSheet->setCellValue($cellD.$sundayDataCounter, $sundaySubTotal);

				//Night differential
				$activeSheet->setCellValue($cellB.$NDDataCounter, $payrollArr['nightdiff_rate']);
				$activeSheet->setCellValue($cellC.$NDDataCounter, 'x '.$payrollArr['nightdiff_num']);

				$NDSubTotal = $payrollArr['nightdiff_num'] * $payrollArr['nightdiff_rate'];
				$activeSheet->setCellValue($cellD.$NDDataCounter, $NDSubTotal);

				//Regular Holiday
				$activeSheet->setCellValue($cellB.$regHolDataCounter, $payrollArr['reg_holiday']);
				$activeSheet->setCellValue($cellC.$regHolDataCounter, 'x '.$payrollArr['reg_holiday_num']);

				$regHolSubTotal = $payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday'];
				$activeSheet->setCellValue($cellD.$regHolDataCounter, $regHolSubTotal);

				//Special Holiday
				$activeSheet->setCellValue($cellB.$speHolDataCounter, $payrollArr['spe_holiday']);
				$activeSheet->setCellValue($cellC.$speHolDataCounter, 'x '.$payrollArr['spe_holiday_num']);

				$speHolSubTotal = $payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday'];
				$activeSheet->setCellValue($cellD.$speHolDataCounter, $speHolSubTotal);

				//Contribution
				$activeSheet->setCellValue($cellB.$sssDataCounter, $payrollArr['sss']);
				$activeSheet->setCellValue($cellB.$philhealthDataCounter, $payrollArr['philhealth']);
				$activeSheet->setCellValue($cellB.$pagibigDataCounter, $payrollArr['pagibig']);

				//Allowance
				$activeSheet->setCellValue($cellD.$xAllowanceDataCounter, $payrollArr['x_allowance']);

				//Vale
				$activeSheet->setCellValue($cellB.$oldValeDataCounter, $payrollArr['old_vale']);
				$activeSheet->setCellValue($cellB.$newValeDataCounter, $payrollArr['new_vale']);

				//Tools
				$activeSheet->setCellValue($cellB.$toolsDataCounter , $payrollArr['tools_paid']);

				//Total
				$activeSheet->mergeCells($cellC.$totalDataCounter.":".$cellD.$totalDataCounter);// Date
				$activeSheet->setCellValue($cellC.$totalDataCounter, $payrollArr['total_salary']);

				$counter++;//Increment counter for horizontal inputs
			}
		}

		//------------ Increment Row Number ------------//
		//------ Counter for merged cells ------//
		$dateMergeCounter += $rowIncrement;
		$nameMergeCounter += $rowIncrement;
		$totalMergeCounter += $rowIncrement;

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
		$sssRowCounter += $rowIncrement;
		$philhealthRowCounter += $rowIncrement;
		$pagibigRowCounter += $rowIncrement;
		$oldValeRowCounter += $rowIncrement;
		$newValeRowCounter += $rowIncrement;
		$toolsRowCounter += $rowIncrement;
		$extraAllowanceRowCounter += $rowIncrement;

		//------ Counter for date ------//
		$rateDataCounter += $rowIncrement;
		$overtimeDataCounter += $rowIncrement;
		$colaDataCounter += $rowIncrement;
		$sundayDataCounter += $rowIncrement;
		$NDDataCounter += $rowIncrement;
		$regHolDataCounter += $rowIncrement;
		$speHolDataCounter += $rowIncrement;

		$sssDataCounter += $rowIncrement;
		$philhealthDataCounter += $rowIncrement;
		$pagibigDataCounter += $rowIncrement;

		$xAllowanceDataCounter += $rowIncrement;
		$oldValeDataCounter += $rowIncrement;
		$newValeDataCounter += $rowIncrement;

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


$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setAutoSize(true);
$activeSheet->getColumnDimension('G')->setAutoSize(true);
$activeSheet->getColumnDimension('H')->setAutoSize(true);
$activeSheet->getColumnDimension('I')->setAutoSize(true);
$activeSheet->getColumnDimension('K')->setAutoSize(true);
$activeSheet->getColumnDimension('L')->setAutoSize(true);
$activeSheet->getColumnDimension('M')->setAutoSize(true);
$activeSheet->getColumnDimension('N')->setAutoSize(true);
$activeSheet->getColumnDimension('P')->setAutoSize(true);
$activeSheet->getColumnDimension('Q')->setAutoSize(true);
$activeSheet->getColumnDimension('R')->setAutoSize(true);
$activeSheet->getColumnDimension('S')->setAutoSize(true);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













