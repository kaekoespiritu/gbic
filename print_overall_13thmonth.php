<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$site = $_GET['site'];
$require = $_GET['req'];
$position = $_GET['position'];

//Checks if site in HTTP is altered by user manually
$siteChecker = "SELECT * FROM site WHERE location = '$site'";
//Checks if position in HTTP is altered by user manually 
$positionChecker = "SELECT * FROM job_position WHERE position = '$position'  AND active = '1'";
$siteCheckerQuery = mysql_query($siteChecker);
$positionCheckerQuery = mysql_query($positionChecker);
if(mysql_num_rows($siteCheckerQuery) == 0)
{
	header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
}
if($position != 'all')
{
	if(mysql_num_rows($positionCheckerQuery) == 0)
	{
		header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
	}
}
	
// Checks if requirement in HTTP is altered by user manually 
switch($require) {
	case "all":break;
	case "withReq":break;
	case "withOReq":break;
	default: header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");;
}

// Get requirements type (with or without)


$filename =  $site." 13th Month pay Report.xls";
// Last Name, First Name of Site (Date) - Payroll.xls


$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:E1');// Employee name 13thmonth pay

//----------------- Header Contents ---------------------//
$activeSheet->setCellValue('A1', $site.' 13th Month Pay Report');

$activeSheet->setCellValue('A2', 'Employee ID');
$activeSheet->setCellValue('B2', 'Name');
$activeSheet->setCellValue('C2', 'Position');
$activeSheet->setCellValue('D2', 'From - To date');
$activeSheet->setCellValue('E2', '13th MonthPay Amount');

//Filters
$appendQuery = "";
if($position != "all")
{
	$appendQuery .= " AND position = '$position' ";
}
if($require != "all")
{
	$req = ($require == "withReq" ? 1 : 0);
	$appendQuery .= " AND complete_doc = '$req' ";
}


$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' $appendQuery ORDER BY lastname ASC, firstname ASC";
$empQuery = mysql_query($employee) or die(mysql_error());

$rowCounter = 3;//start of row data
if(mysql_num_rows($empQuery) != 0)
{
	$overall13thMonth = 0;//Accumulate all the 13th monthpay
	while($empArr = mysql_fetch_assoc($empQuery))
	{
		$empid = $empArr['empid'];

		$oneThreeMonthBool = false;//for print button
		$thirteenthBool = true;// boolean for giving the "from to" date in the 13th month
		$remainderBool = false; // boolean for displaying the remainder once

		$printBool = false;//printable disabled

		//Check if employee have already received past 13th month pay
		$thirteenthChecker = "SELECT * FROM thirteenth_pay WHERE empid = '$empid' ORDER BY STR_TO_DATE(from_date, '%M %e, %Y ') DESC LIMIT 1";
		$thirteenthCheckQuery = mysql_query($thirteenthChecker) or die (mysql_error());
		$pastThirteenthDate = "";
		$thirteenthRemainder = 0;

		if(mysql_num_rows($thirteenthCheckQuery) == 1)
		{

			$thirteenthCheckArr = mysql_fetch_assoc($thirteenthCheckQuery);
			$pastThirteenthDate = "AND STR_TO_DATE(date, '%M %e, %Y ') >= STR_TO_DATE('".$thirteenthCheckArr['to_date']."', '%M %e, %Y ')";
			$thirteenthRemainder = $thirteenthCheckArr['amount'] - $thirteenthCheckArr['received'];
			$thirteenthRemainder = abs($thirteenthRemainder);// makes the value absolute

			// display in the duration of 13th month pay of employee
			$pastToDateThirteenthPay = $thirteenthCheckArr['to_date'];
			$thirteenthBool = false;
			$remainderBool = true;// displays the remainder

			
		}
			

		$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
		$payrollQuery = mysql_query($payrollDate);
		$dateLength = mysql_num_rows($payrollQuery);

		//adds the 13th month pay remainder if there is
		$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

		

		//counter to get the last date
		$loopMax = mysql_num_rows($payrollQuery);
		$loopCounter = 0;

		$finalDate = $date = strftime("%B %d, %Y");
		//Evaluates the attendance and compute the 13th monthpay
		while($payDateArr = mysql_fetch_assoc($payrollQuery))
		{
			//Gets the last date
			$loopCounter++;
			if($loopCounter == $loopMax)
				$finalDate = $payDateArr['date'];

			if($thirteenthBool)
			{
				$pastToDateThirteenthPay = date('F d, Y', strtotime('-6 day', strtotime($payDateArr['date'])));
				$thirteenthBool = false;
			}
			$endDate = $payDateArr['date'];
			$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

			$attendance = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
			$attQuery = mysql_query($attendance);

			$daysAttended = 0;//counter for days attended
			//Computes the 13th month
			while($attArr = mysql_fetch_assoc($attQuery))
			{
				$date = $attArr['date'];

				$workHrs = $attArr['workhours'];

				$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
				$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

				if(mysql_num_rows($holidayCheckQuery) == 0)
				{
					if($attArr['attendance'] == '2')//check if student is present
					{
						if($attArr['workhours'] >= 8)//check if employee attended 8hours
						{
							$daysAttended++;
						}
					}
				}
			}
			$thirteenthMonth = ($daysAttended * $empArr['rate']) / 12; 

			$printBool = true;//enable printable

			$overallPayment += $thirteenthMonth;
		}
		$activeSheet->setCellValue('A'.$rowCounter, $empid);
		$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']);
		$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']);
		$activeSheet->setCellValue('D'.$rowCounter, $pastToDateThirteenthPay." - ".$finalDate);
		$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($overallPayment, 2, '.', true));
		
		$rowCounter++;
		$overall13thMonth += $overallPayment;
	}
	$activeSheet->mergeCells('A'.$rowCounter.':C'.$rowCounter);
	$activeSheet->setCellValue('D'.$rowCounter, 'Grand Total');
	$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($overall13thMonth, 2, '.', true));
}

//Style for the Spreadsheet
$activeSheet->getStyle('A1:E2')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A3:E'.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('A1:E'.$rowCounter)->applyFromArray($align_center);//Centered header text

$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













