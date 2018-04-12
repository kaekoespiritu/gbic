<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];
$period = $_GET['per'];

switch($period)
{
	case "week": $periodDisplay = "Weekly"; ;break;
	case "month": $periodDisplay = "Monthly";break;
	case "year": $periodDisplay = "Yearly";break;
}

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$empquery = mysql_query($employee) or die (mysql_error());

if(mysql_num_rows($empquery) != 0)
	$empArr = mysql_fetch_assoc($empquery);
else
	header("location:index.php");


// Get requirements type (with or without)


$filename = $empArr['lastname']. ", " .$empArr['firstname']." 13th Month pay ".$periodDisplay." Report.xls";
// Last Name, First Name of Site (Date) - Payroll.xls


$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:B1');// Employee name 13thmonth pay

//----------------- Header Contents ---------------------//
$activeSheet->setCellValue('A1', $empArr['lastname']. ", " .$empArr['firstname'].'\'s 13th Month pay');
$activeSheet->setCellValue('A2', ucwords($period));
$activeSheet->setCellValue('B2', 'Amount');
				
$oneThreeMonthBool = false;//for print button
$thirteenthBool = true;// boolean for giving the "from to" date in the 13th month
$remainderBool = false; // boolean for displaying the remainder once

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

$rowCounter = 3; // row start for inputs
if($period == "week")
{
	$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
	$payrollQuery = mysql_query($payrollDate);
	$dateLength = mysql_num_rows($payrollQuery);

	//adds the 13th month pay remainder if there is
	$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

	if($remainderBool)
	{
		if($thirteenthRemainder != 0)
		{
			$activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));
			$rowCounter++;//increment row
			$remainderBool = false;

		}
		
	}

	//Evaluates the attendance and compute the 13th monthpay
	while($payDateArr = mysql_fetch_assoc($payrollQuery))
	{
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

		$activeSheet->setCellValue('A'.$rowCounter, $startDate." - ".$endDate);
		$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));

		$overallPayment += $thirteenthMonth;
		$rowCounter++;//increment row
	}
}
else if($period == "month")
{
	$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
	$attQuery = mysql_query($attendance);

	$daysAttended = 0;//counter for days attended
	$noRepeat = null;
	//adds the 13th month pay remainder if there is
	$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

	if($remainderBool)
	{
		if($thirteenthRemainder != 0)
		{
			$activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));
			$rowCounter++;//increment row
			$remainderBool = false;
		}
	}
	//Computes 13th monthpay per month
	while($attDate = mysql_fetch_assoc($attQuery))
	{
		$dateExploded = explode(" ", $attDate['date']);
		$month = $dateExploded[0];
		$year = $dateExploded[2];

		if ($noRepeat != $month.$year  || $noRepeat == null)
		{
			$attMonth = "SELECT * FROM attendance WHERE empid = '$empid' AND (date LIKE '$month%' AND date LIKE '%$year') $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
			$attMonthQuery = mysql_query($attMonth);
			//Computes 13th month per day of the month
			while($attArr = mysql_fetch_assoc($attMonthQuery))
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

			$activeSheet->setCellValue('A'.$rowCounter, $month." ".$year);
			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));
			
			$overallPayment += $thirteenthMonth;
			$rowCounter++;//increment row
		}
		
		$noRepeat = $month.$year;
		
	}


}
else if($period == "year")
{
	$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
	$attQuery = mysql_query($attendance) or die(mysql_error());

	$daysAttended = 0;//counter for days attended
	$noRepeat = null;
	//adds the 13th month pay remainder if there is
	$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

	if($remainderBool)
	{
		if($thirteenthRemainder != 0)
		{
			$activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));
			$rowCounter++;//increment row
			$remainderBool = false;
		}
	}
	//Computes 13th monthpay per month
	while($attDate = mysql_fetch_assoc($attQuery))
	{
		$dateExploded = explode(" ", $attDate['date']);
		$month = $dateExploded[0];
		$year = $dateExploded[2];

		if ($noRepeat != $year  || $noRepeat == null)
		{
			$attMonth = "SELECT * FROM attendance WHERE empid = '$empid' AND (date LIKE '$month%' AND date LIKE '%$year') $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
			$attMonthQuery = mysql_query($attMonth) or die (mysql_error());
			//Computes 13th month per day of the month
			while($attArr = mysql_fetch_assoc($attMonthQuery))
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

			$yearBefore = $year - 1;

			$activeSheet->setCellValue('A'.$rowCounter, $yearBefore." - ".$year);
			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));
			
			$overallPayment += $thirteenthMonth;
			$rowCounter++;//increment row
		}
		
		$noRepeat = $year;
		
	}

	// $attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
	// $attQuery = mysql_query($attendance);

	// $daysAttended = 0;//counter for days attended
	// $noRepeat = null;
	// //adds the 13th month pay remainder if there is
	// $overallPayment = ($thirteenthRemainder != 0 ? $overallPayment = $overallPayment : 0);

	// if($remainderBool)
	// {
	// 	if($thirteenthRemainder != 0)
	// 	{
	// 		$activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
	// 		$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));

	// 		$remainderBool = false;
	// 	}
		
	// }
	// //Computes 13th monthpay per month
	// while($attDate = mysql_fetch_assoc($attQuery))
	// {
	// 	$dateExploded = explode(" ", $attDate['date']);
	// 	$year = $dateExploded[2];

	// 	if ($noRepeat != $year || $noRepeat == null)
	// 	{
	// 		$attYear = "SELECT * FROM attendance WHERE empid = '$empid' AND date LIKE '%$year' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
	// 		$attYearQuery = mysql_query($attYear);
	// 		//Computes 13th month per day of the month
	// 		while($attArr = mysql_fetch_assoc($attYearQuery))
	// 		{
	// 			$date = $attArr['date'];

	// 			$workHrs = $attArr['workhours'];

	// 			$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
	// 			$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

	// 			if(mysql_num_rows($holidayCheckQuery) == 0)
	// 			{
	// 				if($attArr['attendance'] == '2')//check if student is present
	// 				{
	// 					if($attArr['workhours'] >= 8)//check if employee attended 8hours
	// 					{
	// 						$daysAttended++;
	// 					}
	// 				}
	// 			}
	// 		}
	// 		$thirteenthMonth = ($daysAttended * $empArr['rate']) / 12; 
	// 		$yearBefore = $year - 1;

	// 		$activeSheet->setCellValue('A'.$rowCounter, $yearBefore." - ".$year);
	// 		$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));

	// 		$overallPayment += $thirteenthMonth;
	// 	}
		
	// 	$noRepeat = $year;
	// 	$rowCounter++;//increment row
	// }

}

$activeSheet->setCellValue('A'.$rowCounter, 'Total');
$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($overallPayment, 2, '.', true));

//Style for the Spreadsheet
$activeSheet->getStyle('A1:B2')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A3:B'.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('A1:B'.$rowCounter)->applyFromArray($align_center);//Centered header text

$activeSheet->getColumnDimension('A')->setAutoSize(false);
$activeSheet->getColumnDimension('B')->setAutoSize(false);
$activeSheet->getColumnDimension('A')->setWidth('30');
$activeSheet->getColumnDimension('B')->setWidth('30');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













