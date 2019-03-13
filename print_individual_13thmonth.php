<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$empid = $_GET['empid'];
$period = $_GET['per'];
$dateToday = strftime("%B %d, %Y");
$columnLet = 'B';
switch($period)
{
	case "week": $periodDisplay = "Weekly"; $columnLet = 'C';break;
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
$activeSheet->mergeCells('A1:'.$columnLet.'1');// Employee name 13thmonth pay

//----------------- Header Contents ---------------------//
	$activeSheet->setCellValue('A1', $empArr['lastname'].', '.$empArr['firstname']."'s 13th Month pay");

	$activeSheet->setCellValue('A2', $periodDisplay);
	$activeSheet->setCellValue('B2', 'Amount');
	if($period == "week")
	{
		$activeSheet->setCellValue('C2', 'Days Completed');
	}
	
	$oneThreeMonthBool = false;//for print button
	$thirteenthBool = true;// boolean for giving the "from to" date in the 13th month
	$remainderBool = false; // boolean for displaying the remainder once

	$printBool = false;//printable disabled

	//Check if employee have already received past 13th month pay
	$thirteenthChecker = "SELECT * FROM thirteenth_pay WHERE empid = '$empid' ORDER BY STR_TO_DATE(from_date, '%M %e, %Y ') DESC LIMIT 1";
	$thirteenthCheckQuery = mysql_query($thirteenthChecker) or die (mysql_error());
	$pastThirteenthDate = "";
	$thirteenthRemainder = 0;

	$noRemainderBool = false;
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
	else
	{
		$pastThirteenthDate = "AND STR_TO_DATE(date, '%M %e, %Y ') >= STR_TO_DATE('".$empArr['datehired']."', '%M %e, %Y ')";
		$noRemainderBool = true;
	}

	$rowCounter = 3;// start of displaying of data

	if($period == "week")
	{	
		$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
		$payrollQuery = mysql_query($payrollDate) or die (mysql_error());
		$dateLength = mysql_num_rows($payrollQuery);

		//adds the 13th month pay remainder if there is
		$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

		$remainderDateBool = false;
		if($remainderBool)
		{
			if($thirteenthRemainder != 0)
			{
				$printBool = true;//enable printable
				$activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
				$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));

				$remainderBool = false;
				$remainderDateBool = true;

			}
			
		}
		$monthNoRep = "";
		$yearNoRep = "";

		$cutoffBool = false;// Boolean for the suceeding week after the initial cutoff
		$cutoffClearPlaceholderBool = false;
		$cutoffInitialDate = '';// Placeholder for the start of the suceeding date after the cutoff

		//Evaluates the attendance and compute the 13th monthpay
		$overallDaysAttended = 0; // counter for total days attended
		while($payDateArr = mysql_fetch_assoc($payrollQuery))
		{
			if($thirteenthBool)
			{
				$pastToDateThirteenthPay = date('F d, Y', strtotime('-7 day', strtotime($payDateArr['date'])));
				$thirteenthBool = false;
			}
			$payDay = $payDateArr['date'];
			$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDateArr['date'])));
			$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

			// Check for early cutoff 
			$cutoffCheck = "SELECT * FROM early_payroll WHERE end = '$payDay' LIMIT 1";
			$cutoffQuery = mysql_query($cutoffCheck);
			if(mysql_num_rows($cutoffQuery) > 0)
			{
				$cutoffArr = mysql_fetch_assoc($cutoffQuery);
				$startDate = $cutoffArr['start'];
				$endDate = $cutoffArr['end'];

				$cutoffInitialDate = date('F d, Y', strtotime('+1 day', strtotime($cutoffArr['end'])));
			}

			if($cutoffBool == true)
			{
				$startDate = $cutoffInitialDate;
				$cutoffClearPlaceholderBool = true;// This is to reset the placeholder
				$cutoffBool = false;// Reset the cutoffBoolean
			}

			if($noRemainderBool)
			{
				$attendance = "SELECT date, workhours, attendance FROM attendance WHERE  
				empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('".$empArr['datehired']."', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";

				
				$noRemainderBool = false;
			}
			else
			{
				$attendance = "SELECT date, workhours, attendance FROM attendance WHERE  
				empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
			}
				
				

			$attChecker = mysql_query($attendance);
			$attQuery = mysql_query($attendance);

			$daysAttended = 0;//counter for days attended
			$daysCompleted = 0; // counter for days completed
			
			$arrayChecker = array();

			// Adds attendance array to array checker
			while($attArray = mysql_fetch_assoc($attChecker))
			{
				//exclude Holidays and Sundays

				//Check if holiday
				$holidayDateCheck = $attArray['date'];
				$holidayChecker = "SELECT * FROM holiday WHERE date = '$holidayDateCheck' LIMIT 1";
				$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());
				
				//Check if Sunday
				$date = $attArray['date'];
				$day = date('l', strtotime($date));// check what day of the week

				
				if(mysql_num_rows($holidayCheckQuery) > 0)
				{
					$checkHoliday = mysql_fetch_assoc($holidayCheckQuery);
					$regHolidayCheckBool = ($checkHoliday['type'] != "special" ? true : false);
				}
				else
				{
					$regHolidayCheckBool = true;
				}

				
				if($regHolidayCheckBool && $day != "Sunday")
					array_push($arrayChecker, $attArray);
			}

			// Removes duplicates from array checker
			$secondArrayChecker = array_unique($arrayChecker, SORT_REGULAR);

			//Computes the 13th month
			// $overallCounter = count($secondArrayChecker);
			$overallCounter = 20;
			// while($attArr = mysql_fetch_assoc($attQuery))
			for($count = 0; $count < $overallCounter; $count++ )
			{
				if(isset($secondArrayChecker[$count]['date']))
				{
					$date = $secondArrayChecker[$count]['date'];

					$workHrs = $secondArrayChecker[$count]['workhours'];

					$holidayChecker = "SELECT * FROM holiday WHERE date = '$date' LIMIT 1";
					$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

					if(mysql_num_rows($holidayCheckQuery) > 0)
					{
						$checkHoliday = mysql_fetch_assoc($holidayCheckQuery);
						$regHolidayCheckBool = ($checkHoliday['type'] != "special" ? true : false);
						$attendedHoliday = ($checkHoliday['type'] == "regular" ? true : false);
					}
					else
					{
						$attendedHoliday = false;
						$regHolidayCheckBool = true;
					}

					if($regHolidayCheckBool)// Include regular holidays. dont proceed if special holiday
					{
						// check if days are not duplicated
						if(isset($secondArrayChecker[$count]['attendance']) && $secondArrayChecker[$count]['attendance'] == '2')//check if employee is present
						{
							if($secondArrayChecker[$count]['workhours'] >= 8 || $attendedHoliday)//check if employee attended 8hours || regardless how many hours the employee rendered for that regular holiday
							{
								$daysAttended++;
								$daysCompleted += 1;
							}
							else
							{
								$daysCompleted += ($secondArrayChecker[$count]['workhours'] / 8);
							}
						}
					}
				}
			}

			$thirteenthMonth = ($daysCompleted * $empArr['rate']) / 12; 

			$printBool = true;//enable printable

			$activeSheet->setCellValue('A'.$rowCounter, $startDate." - ".$endDate);
			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));
			$activeSheet->setCellValue('C'.$rowCounter, numberExactFormat($daysCompleted, 2, '.', true));
			
			$rowCounter++;// increment row
			
			$overallDaysAttended = $daysCompleted + $overallDaysAttended;
			$overallPayment += $thirteenthMonth;

			// Early cutoff Reset
			if($cutoffClearPlaceholderBool == true)
			{
				$cutoffInitialDate = '';
				$cutoffClearPlaceholderBool = false;
			}
			if(mysql_num_rows($cutoffQuery) > 0)
			{
				$cutoffBool = true;// set to true, to trigger the next payroll that it has an extended attendance
			}
		}

		if($printBool)
		{
			//################
			// INCLUDE THE ATTENDANCE FROM THE LAST PAYROLL TO THE CURRENT DAY
			//###################
			//Gets the start payroll of the next payroll
			if(!$remainderDateBool || isset($endDate))
			{
				$dateToPresent = date('F d, Y', strtotime('+1 day', strtotime($endDate)));
			}
			else
			{
				$dateToPresent = $pastToDateThirteenthPay;
			}

			if($dateToPresent != $dateToday)
			{
				$checkLatestAtt = "SELECT * FROM attendance WHERE empid = '$empid' AND STR_TO_DATE(date, '%M %e, %Y') >= STR_TO_DATE('$dateToPresent', '%M %e, %Y') ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC";
				$checkLatestQuery = mysql_query($checkLatestAtt);
				if(mysql_num_rows($checkLatestQuery) > 0)
				{
					$arrayChecker = array(); // Set array to check if there is duplicate dates
					$daysCompleted = 0;
					while($latestAttendanceArr = mysql_fetch_assoc($checkLatestQuery))
					{
						// Checks if date is already in the array. if it is then skip the computation for this date
						if(!in_array($latestAttendanceArr['date'], $arrayChecker))
						{
							array_push($arrayChecker, $latestAttendanceArr['date']);// Push date inside 
							$date = $latestAttendanceArr['date'];
							$day = date('l', strtotime($date));// check what day of the week

							$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
							$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

							if(mysql_num_rows($holidayCheckQuery) > 0)
							{
								$checkHoliday = mysql_fetch_assoc($holidayCheckQuery);
								$regHolidayCheckBool = ($checkHoliday['type'] != "special" ? true : false);
							}
							else
							{
								$regHolidayCheckBool = true;
							}

							if($regHolidayCheckBool && $day != "Sunday")
								array_push($arrayChecker, $attArray);

							if(mysql_num_rows($holidayCheckQuery) > 0)
							{
								$checkHoliday = mysql_fetch_assoc($holidayCheckQuery);
								$regHolidayCheckBool = ($checkHoliday['type'] != "special" ? true : false);
								$attendedHoliday = ($checkHoliday['type'] == "regular" ? true : false);
							}
							else
							{
								$attendedHoliday = false;
								$regHolidayCheckBool = true;
							}

							if($regHolidayCheckBool)// Include regular holidays. dont proceed if special holiday
							{
								if($latestAttendanceArr['attendance'] == '2')//check if student is present
								{
									if($latestAttendanceArr['workhours'] < 8)//check if employee attended 8hours
									{
										$daysCompleted += ($latestAttendanceArr['workhours']/8);
									}
									else if($latestAttendanceArr['workhours'] >= 8 || $attendedHoliday)
									{
										$daysCompleted++;
									}
								}
							}
						}
					}
					$thirteenthMonth = ($daysCompleted * $empArr['rate']) / 12;

					$activeSheet->setCellValue('A'.$rowCounter, $dateToPresent." - Present");
					$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));
					$activeSheet->setCellValue('C'.$rowCounter, numberExactFormat($daysCompleted, 2, '.', true));

					$overallDaysAttended = $daysCompleted + $overallDaysAttended;
					$overallPayment += $thirteenthMonth;
					$rowCounter++;// increment row
				}
			}
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
				$printBool = true;//enable printable
				$activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
				$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));

				$remainderBool = false;
			}
		}

		$arrayChecker = array(); // Set array to check if there is duplicate dates
		//Computes 13th monthpay per month
		while($attDate = mysql_fetch_assoc($attQuery))
		{
			
			if($thirteenthBool)
			{
				$pastToDateThirteenthPay = $attDate['date'];
				$thirteenthBool = false;
			}

			$dateExploded = explode(" ", $attDate['date']);
			$month = $dateExploded[0];
			$year = $dateExploded[2];

			if ($noRepeat != $month.$year  || $noRepeat == null)
			{
				$attMonth = "SELECT * FROM attendance WHERE empid = '$empid' AND (date LIKE '$month%' AND date LIKE '%$year') $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
				$attMonthQuery = mysql_query($attMonth);

				$thirteenthMonth = 0;
				$daysAttended = 0;
				$daysCompleted = 0;
				$checkBool = true;
				$checkCounter = 0;
				$checkCount = mysql_num_rows($attMonthQuery);
				//Computes 13th month per day of the month
				while($attArr = mysql_fetch_assoc($attMonthQuery))
				{ 
					// Checks if date is already in the array. if it is then skip the computation for this date
					$sundayCheck = date('l', strtotime($attArr['date']));
					$isSundayCheck = ($sundayCheck == "Sunday" ? false : true);

					if(!in_array($attArr['date'], $arrayChecker) && $isSundayCheck)
					{
						array_push($arrayChecker, $attArr['date']);// Push date inside the array 
						$date = $attArr['date'];
						$day = date('l', strtotime($date));// check what day of the week

						$workHrs = $attArr['workhours'];

						$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
						$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

						if(mysql_num_rows($holidayCheckQuery) > 0)
						{
							$checkHoliday = mysql_fetch_assoc($holidayCheckQuery);
							$regHolidayCheckBool = ($checkHoliday['type'] != "special" ? true : false);
							$attendedHoliday = ($checkHoliday['type'] == "regular" ? true : false);
						}
						else
						{
							$attendedHoliday = false;
							$regHolidayCheckBool = true;
						}

						if($regHolidayCheckBool)// Include regular holidays. dont proceed if special holiday
						{
							if($attArr['attendance'] == '2')//check if student is present
							{
								if($attArr['workhours'] >= 8 || $attendedHoliday)
								{
									$daysCompleted++;
								}
								else if($attArr['workhours'] < 8)//check if employee attended 8hours
								{
									$daysCompleted += ($attArr['workhours']/8);
								}
							}
						}
					}	
				}
				// echo "<script>console.log('".$daysCompleted."')</script>";
				$thirteenthMonth = ($daysCompleted * $empArr['rate']) / 12; 
				$printBool = true;//enable printable

				$activeSheet->setCellValue('A'.$rowCounter, $month." ".$year);
				$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));
				$rowCounter++;// increment row
				$overallPayment += $thirteenthMonth;
			}
			
			$noRepeat = $month.$year;
		}
	}
	else if($period == "year")
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
				$printBool = true;//enable printable
				$activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
				$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));

				$remainderBool = false;

			}
			
		}
		$arrayChecker = array(); // Set array to check if there is duplicate dates
		//Computes 13th monthpay per month
		while($attDate = mysql_fetch_assoc($attQuery))
		{
			
			//Computes 13th monthpay per month
			while($attDate = mysql_fetch_assoc($attQuery))
			{
				if($thirteenthBool)
				{

					$pastToDateThirteenthPay = $attDate['date'];
					$thirteenthBool = false;
				}
				$dateExploded = explode(" ", $attDate['date']);
				$year = $dateExploded[2];

				if ($noRepeat != $year  || $noRepeat == null)
				{
					$attYear = "SELECT * FROM attendance WHERE empid = '$empid' AND date LIKE '%$year' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
					$attMonthQuery = mysql_query($attYear);

					$thirteenthMonth = 0;
					$daysAttended = 0;
					$daysCompleted = 0;

					//Computes 13th month per day of the month
					while($attArr = mysql_fetch_assoc($attMonthQuery))
					{ 
						// Checks if date is already in the array. if it is then skip the computation for this date
						$sundayCheck = date('l', strtotime($attArr['date']));
						$isSundayCheck = ($sundayCheck == "Sunday" ? false : true);

						if(!in_array($attArr['date'], $arrayChecker) && $isSundayCheck)
						{
							array_push($arrayChecker, $attArr['date']);// Push date inside the array 
							$date = $attArr['date'];
							$day = date('l', strtotime($date));// check what day of the week

							$workHrs = $attArr['workhours'];

							$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
							$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

							if(mysql_num_rows($holidayCheckQuery) > 0)
							{
								$checkHoliday = mysql_fetch_assoc($holidayCheckQuery);
								$regHolidayCheckBool = ($checkHoliday['type'] != "special" ? true : false);
								$attendedHoliday = ($checkHoliday['type'] == "regular" ? true : false);
							}
							else
							{
								$attendedHoliday = false;
								$regHolidayCheckBool = true;
							}

							if($regHolidayCheckBool)// Include regular holidays. dont proceed if special holiday
							{
								if($attArr['attendance'] == '2')//check if student is present
								{
									if($attArr['workhours'] >= 8 || $attendedHoliday)
									{
										$daysCompleted++;
									}
									else if($attArr['workhours'] < 8)//check if employee attended 8hours
									{
										$daysCompleted += ($attArr['workhours']/8);
									}
								}
							}
						}	
					}
					$thirteenthMonth = ($daysCompleted * $empArr['rate']) / 12; 
					$printBool = true;//enable printable
					$activeSheet->setCellValue('A'.$rowCounter, 'Total');
					$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($overallPayment, 2, '.', true));

					$activeSheet->setCellValue('A'.$rowCounter, $year);
					$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));

					$rowCounter++;// increment row
					$overallPayment += $thirteenthMonth;
				}
				
				$noRepeat = $year;
			}
		}
		//------------------------------------------------------------

		// $attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
		// $attQuery = mysql_query($attendance);

		// $daysAttended = 0;//counter for days attended
		// $noRepeat = null;
		// //adds the 13th month pay remainder if there is
		// $overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

		// if($remainderBool)
		// {
		// 	if($thirteenthRemainder != 0)
		// 	{
		// 		$printBool = true;//enable printable
		// 		// $activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
		// 		// $activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));

		// 		$remainderBool = false;

		// 	}
			
		// }
		// //Computes 13th monthpay per month
		// while($attDate = mysql_fetch_assoc($attQuery))
		// {
		// 	$attendance = "SELECT DISTINCT date FROM attendance WHERE empid = '$empid' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
		// 	$attQuery = mysql_query($attendance);

		// 	$daysAttended = 0;//counter for days attended
		// 	$noRepeat = null;
		// 	//adds the 13th month pay remainder if there is
		// 	$overallPayment = ($thirteenthRemainder != 0 ? $thirteenthRemainder : 0);

		// 	if($remainderBool)
		// 	{
		// 		if($thirteenthRemainder != 0)
		// 		{
		// 			$printBool = true;//enable printable
		// 			$activeSheet->setCellValue('A'.$rowCounter, '13th Month Pay remaining balance');
		// 			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthRemainder, 2, '.', true));

		// 			$remainderBool = false;
		// 		}
		// 	}

		// 	$arrayChecker = array(); // Set array to check if there is duplicate dates
		// 	//Computes 13th monthpay per month
		// 	while($attDate = mysql_fetch_assoc($attQuery))
		// 	{
		// 		if($thirteenthBool)
		// 		{

		// 			$pastToDateThirteenthPay = $attDate['date'];
		// 			$thirteenthBool = false;
		// 		}
		// 		$dateExploded = explode(" ", $attDate['date']);
		// 		$year = $dateExploded[2];

		// 		if ($noRepeat != $year  || $noRepeat == null)
		// 		{
		// 			$attYear = "SELECT * FROM attendance WHERE empid = '$empid' AND date LIKE '%$year' $pastThirteenthDate ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
		// 			$attMonthQuery = mysql_query($attYear);

		// 			$thirteenthMonth = 0;
		// 			$daysAttended = 0;

		// 			//Computes 13th month per day of the month
		// 			while($attArr = mysql_fetch_assoc($attMonthQuery))
		// 			{ 
		// 				// Checks if date is already in the array. if it is then skip the computation for this date
		// 				if(!in_array($attArr['date'], $arrayChecker))
		// 				{
		// 					array_push($arrayChecker, $attArr['date']);// Push date inside the array 
		// 					$date = $attArr['date'];
		// 					$day = date('l', strtotime($date));// check what day of the week

		// 					$workHrs = $attArr['workhours'];

		// 					$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
		// 					$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

		// 					if(mysql_num_rows($holidayCheckQuery) == 0 && $day != "Sunday")
		// 					{
		// 						if($attArr['attendance'] == '2')//check if student is present
		// 						{
		// 							if($attArr['workhours'] < 8)//check if employee attended 8hours
		// 							{
		// 								$daysAttended += ($attArr['workhours']/8);
		// 							}
		// 							else
		// 							{
		// 								$daysAttended++;
		// 							}
		// 						}
		// 					}
		// 				}	
		// 			}
		// 			$thirteenthMonth = ($daysAttended * $empArr['rate']) / 12; 
		// 			$printBool = true;//enable printable
		// 			$activeSheet->setCellValue('A'.$rowCounter, 'Total');
		// 			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($overallPayment, 2, '.', true));

		// 			$activeSheet->setCellValue('A'.$rowCounter, $year);
		// 			$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($thirteenthMonth, 2, '.', true));
		// 			$overallPayment += $thirteenthMonth;
		// 			$rowCounter++;// increment row
		// 		}
				
		// 		$noRepeat = $year;
		// 	}
		// }
	}
					

				
				
$activeSheet->setCellValue('A'.$rowCounter, 'Total');
$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($overallPayment, 2, '.', true));

if($period == "week")
{
	$activeSheet->setCellValue('C'.$rowCounter, numberExactFormat($overallDaysAttended, 2, '.', false));
}
					
					
				

$activeSheet->setCellValue('A'.$rowCounter, 'Total');
$activeSheet->setCellValue('B'.$rowCounter, numberExactFormat($overallPayment, 2, '.', true));

//Style for the Spreadsheet
$activeSheet->getStyle('A1:'.$columnLet.'2')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A3:'.$columnLet.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('A1:'.$columnLet.$rowCounter)->applyFromArray($align_center);//Centered header text
$activeSheet->getStyle('A'.$rowCounter.':'.$columnLet.$rowCounter)->applyFromArray($font_bold);// Make total value bold

$activeSheet->getColumnDimension('A')->setAutoSize(false);
$activeSheet->getColumnDimension('B')->setAutoSize(false);
$activeSheet->getColumnDimension('C')->setAutoSize(false);
$activeSheet->getColumnDimension('A')->setWidth('30');
$activeSheet->getColumnDimension('B')->setWidth('30');
$activeSheet->getColumnDimension('C')->setWidth('30');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













