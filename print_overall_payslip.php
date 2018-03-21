<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];
$date = $_GET['date'];
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

$weekBefore = date('F j, Y', strtotime('-6 day', strtotime($date)));
$filename =  $site." Payslip ".$weekBefore." - ".$date.".xls";

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

//------ Counter for merged cells ------//
$dateMergeCounter = 1;
$nameMergeCounter = 2;
$totalMergeCounter = 16;

//------ Counter for header cells ------//
$dateCoveredRowCounter = 1;
$nameRowCounter = 2;
$rateRowCounter = 3;
$overtimeRowCounter = 4;
$colaRowCounter = 5;
$sundayRowCounter = 6;
$nightDiffRowCounter = 7;
$regHolRowCounter = 8;
$speHolRowCounter = 9;
$sssRowCounter = 10;
$philhealthRowCounter = 11;
$pagibigRowCounter = 12;
$oldValeRowCounter = 13;
$newValeRowCounter = 14;
$toolsRowCounter = 15;
$extraAllowanceRowCounter = 10;

//------ Counter for date ------//
$rateDataCounter = 3;//Rate
$overtimeDataCounter = 4;//Overtime
$colaDataCounter = 5;//COLA
$sundayDataCounter = 6;//Sunday
$NDDataCounter = 7;//Night differential
$regHolDataCounter = 8;//Regular Holiday
$speHolDataCounter = 9;//Special Holiday

$sssDataCounter = 10;//sss
$philhealthDataCounter = 11;//philhealth
$pagibigDataCounter = 12;//pagibig

$allowanceDataCounter = 10;//Allowance
$oldValeDataCounter = 13;//Vale
$newValeDataCounter = 14;

$toolsDataCounter = 15;//Tools
$totalDataCounter = 16;//Total

//------ Counter Style ------//
//Border
$borderStyleCounter1 = 1;
$borderStyleCounter2 = 16;
//Extra Allowance
$allowanceStyleCounter = 10;
//Total
$totalStyleCounter = 16;
//Header
$headerStyleCounter1 = 1;
$headerStyleCounter2 = 2;


$employee = "SELECT * FROM exployee WHERE site = '$site' AND employment_status = '1'";
$empQuery = mysql_query($employee);

$horizontalCounter = 1;//counter for horizontal display
while($empArr = mysql_fetch_assoc($empQuery))
{
	//////////// Array index reference ///////////
	//											//
	//		0 - Name 							//
	//		1 - Rate 							//
	//		2 - Num Attendance 					//
	//		3 - Rate SubTotal 					//
	//		4 - OT 								//
	//		5 - OTNum  							//
	//		6 - OT SubTotal 					//
	//		7 - Cola 							//
	//		8 - Cola SubTotal 					//
	//		9 - Sunday  						//
	//		10 - Sunday Num 					//
	//		11 - Sunday SubTotal 				//
	//		12 - NightDiff 						//
	//		13 - Night diff Num 				//
	//		14 - NightDiff SubTotal 			//
	//		15 - Regular Holiday 				//
	//		16 - Regular Holiday Num 			//
	//		17 - Regular Holiday SubTotal 		//
	//		18 - Special Holiday 				//
	//		19 - Special Holiday Num 			// 
	//		20 - Special Holiday SubTotal 		//
	//		21 - SSS  							//
	//		22 - Philhealth  					//
	//		23 - Pagibig  						//
	//		24 - Extra Allowance  				//
	//		25 - Old Vale  						//
	//		26 - New Vale  						//
	//		27 - Tools  						//
	//		28 - Total  						//
	//											//
	//////////////////////////////////////////////

	

	//Merge cells
	$activeSheet->mergeCells('A'.$dateMergeCounter.':D'.$dateMergeCounter);// Date
	$activeSheet->mergeCells('A'.$nameMergeCounter.':D'.$nameMergeCounter);// Name

	$activeSheet->mergeCells('C'.$totalMergeCounter.':D'.$totalMergeCounter);// Total

	$activeSheet->setCellValue('A'.$dateCoveredRowCounter, 'Date Covered: '.$dateCovered);
	$activeSheet->setCellValue('A'.$nameCoveredRowCounter, $empArr['lastname'].", ".$empArr['firstname']);

	$activeSheet->setCellValue('A'.$rateRowCounter, 'Rate');
	$activeSheet->setCellValue('A'.$overtimeRowCounter, 'OT');
	$activeSheet->setCellValue('A'.$colaRowCounter, 'cola');
	$activeSheet->setCellValue('A'.$sundayRowCounter, 'Sun');
	$activeSheet->setCellValue('A'.$nightDiffRowCounter, 'N.D');
	$activeSheet->setCellValue('A'.$regHolRowCounter, 'Reg. Hol');
	$activeSheet->setCellValue('A'.$speHolRowCounter, 'Spe. Hol');
	$activeSheet->setCellValue('A'.$sssRowCounter, 'SSS');
	$activeSheet->setCellValue('A'.$philhealthRowCounter, 'PhilHealth');
	$activeSheet->setCellValue('A'.$pagibigRowCounter, 'Pag-IBIG');
	$activeSheet->setCellValue('A'.$oldValeRowCounter, 'Old vale');
	$activeSheet->setCellValue('A'.$newValeRowCounter, 'vale');
	$activeSheet->setCellValue('A'.$toolsRowCounter, 'tools');

	$activeSheet->setCellValue('C'.$extraAllowanceRowCounter, 'X. All.');

	//----------------- Body Contents ---------------------//

	$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$date'";
	$payrollQuery = mysql_query($payroll);

	$payrollArr = mysql_fetch_assoc($payrollQuery);

	//Rate
	$activeSheet->setCellValue('B'.$rateDataCounter, $payrollArr['rate']);
	$activeSheet->setCellValue('C'.$rateDataCounter, 'x '.$payrollArr['num_days']);

	$rateSubTotal = $payrollArr['rate'] * $payrollArr['num_days'];
	$activeSheet->setCellValue('D'.$rateDataCounter, $rateSubTotal);

	//Overtime
	$activeSheet->setCellValue('B'.$overtimeDataCounter, $payrollArr['overtime']);
	$activeSheet->setCellValue('C'.$overtimeDataCounter, 'x '.$payrollArr['ot_num']);

	$OTSubTotal = $payrollArr['ot_num'] * $payrollArr['overtime'];
	$activeSheet->setCellValue('D'.$overtimeDataCounter, $OTSubTotal);

	//Cola
	$activeSheet->setCellValue('B'.$colaDataCounter, $payrollArr['cola']);
	$activeSheet->setCellValue('C'.$colaDataCounter, 'x '.$payrollArr['num_days']);

	$colaSubTotal = $payrollArr['cola'] * $payrollArr['num_days'];
	$activeSheet->setCellValue('D'.$colaDataCounter, $colaSubTotal);

	//Sunday
	$activeSheet->setCellValue('B'.$sundayDataCounter, $payrollArr['sunday_rate']);
	$activeSheet->setCellValue('C'.$sundayDataCounter, 'x '.$payrollArr['sunday_hrs']);

	$sundaySubTotal = $payrollArr['sunday_hrs'] * $payrollArr['sunday_rate'];
	$activeSheet->setCellValue('D'.$sundayDataCounter, $sundaySubTotal);

	//Night differential
	$activeSheet->setCellValue('B'.$NDDataCounter, $payrollArr['nightdiff_rate']);
	$activeSheet->setCellValue('C'.$NDDataCounter, 'x '.$payrollArr['nightdiff_num']);

	$NDSubTotal = $payrollArr['nightdiff_num'] * $payrollArr['nightdiff_rate'];
	$activeSheet->setCellValue('D'.$NDDataCounter, $NDSubTotal);

	//Regular Holiday
	$activeSheet->setCellValue('B'.$regHolDataCounter, $payrollArr['reg_holiday']);
	$activeSheet->setCellValue('C'.$regHolDataCounter, 'x '.$payrollArr['reg_holiday_num']);

	$regHolSubTotal = $payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday'];
	$activeSheet->setCellValue('D'.$regHolDataCounter, $regHolSubTotal);

	//Special Holiday
	$activeSheet->setCellValue('B'.$speHolDataCounter, $payrollArr['spe_holiday']);
	$activeSheet->setCellValue('C'.$speHolDataCounter, 'x '.$payrollArr['spe_holiday_num']);

	$speHolSubTotal = $payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday'];
	$activeSheet->setCellValue('D'.$speHolDataCounter, $speHolSubTotal);

	//Contribution
	$activeSheet->setCellValue('B'.$sssDataCounter, $payrollArr['sss']);
	$activeSheet->setCellValue('B'.$philhealthDataCounter, $payrollArr['philhealth']);
	$activeSheet->setCellValue('B'.$pagibigDataCounter, $payrollArr['pagibig']);

	//Allowance
	$activeSheet->setCellValue('D'.$allowanceDataCounter, $payrollArr['x_allowance']);

	//Vale
	$activeSheet->setCellValue('B'.$oldValeDataCounter, $payrollArr['old_vale']);
	$activeSheet->setCellValue('B'.$newValeDataCounter, $payrollArr['new_vale']);

	//Tools
	$activeSheet->setCellValue('B'.$toolsDataCounter , $payrollArr['tools_paid']);

	//Total
	$activeSheet->setCellValue('C'.$totalDataCounter, $payrollArr['total_salary']);




	//------------ Style for the Spreadsheet ------------
	$activeSheet->getStyle('A'.$borderStyleCounter1.':D'.$borderStyleCounter2)->applyFromArray($border_allsides_medium); 

	//extra Allowance
	$activeSheet->getStyle('C'.$allowanceStyleCounter)->applyFromArray($border_buttom_left_thin);
	$activeSheet->getStyle('D'.$allowanceStyleCounter)->applyFromArray($border_buttom_thin);

	//Total
	$activeSheet->getStyle('C'.$totalStyleCounter.':D'.$totalStyleCounter)->applyFromArray($border_top_double);

	//Header
	$activeSheet->getStyle('A'.$headerStyleCounter1.':A'.$headerStyleCounter2)->applyFromArray($align_left);
	$activeSheet->getStyle('A'.$headerStyleCounter2)->applyFromArray($font_bold);

}


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













