<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$dateToday = strftime("%B %d, %Y");
	// $dateToday = "March 20, 2019";

	$empid = $_GET['empid'];
	$period = $_GET['per'];
	$employeeChecker = "SELECT * FROM employee WHERE empid = '$empid'";
	$employeeCheckerQuery = mysql_query($employeeChecker);

	if(mysql_num_rows($employeeCheckerQuery))
	{
		$empArr = mysql_fetch_assoc($employeeCheckerQuery);
		$employeeInfo = $empArr['lastname'].", ".$empArr['firstname']." | ".$empArr['position']." at ".$empArr['site'];
	}
	else//empid on http is altered manually
		header("location: reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null");
	
	if($period == "week" || $period == "month" || $period == "year")
	{}
	else
		header("location: reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null");

	$payrollChecker = "SELECT * FROM payroll WHERE empid = '$empid'";
	$payrollCheckQuery = mysql_query($payrollChecker);
	$give13Bool = 0;
	if(mysql_num_rows($payrollCheckQuery) > 0)
		$give13Bool = 1;
?>
<html>
<head>
	<title>Payroll</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<!-- For pagination -->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />


</head>
<body style="font-family: Quicksand;">

	<div class="container-fluid">

		<?php
		require_once("directives/nav.php");
		?>

		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>13th Month Pay Report for <?php Print $employeeInfo?></li>
						<button class='btn btn-success pull-right' id="give13thpay" data-toggle="modal" data-target="#give13thmonthpay">
							Give 13th Month Pay
						</button>
						<button class='btn btn-danger pull-right' data-toggle="modal" data-target="#13thmonthhistory">
							13th Month Pay History
						</button>
					</ol>
				</div>
			</div>

		<div class="form-inline">
			<h4>Select view</h4>
			<select onchange="periodChange(this.value)" class="form-control">
				<?php 
					if($period == "week")
						Print "<option value='week' selected>Weekly</option>";
					else
						Print "<option value='week'>Weekly</option>";
					if($period == "month")
						Print "<option value='month'selected>Monthly</option>";
					else
						Print "<option value='month'>Monthly</option>";
					if($period == "year")
						Print "<option value='year' selected>Yearly</option>";
					else
						Print "<option value='year'>Yearly</option>";
				?>
			</select>
			
		</div>

		<div class="pull-down">
			
				
			<div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
				
				<?php
				 switch($period)
				 {
				 	case 'week': $printButton = "Weekly";break;
				 	case 'month': $printButton = "Monthly";break;
				 	case 'year': $printButton = "Yearly";break;
				 }
				?>
				<button class="btn btn-primary" id="printButton" onclick="Print13thMonth()">
					Print <?php Print $printButton?>
				</button>
				

				<table class="table table-bordered pull-down">
				<tr>
					<td colspan="<?php Print ($period == "week" ? 3 : 2) ?>">
						13th Month pay
					</td>
				</tr>	
				<tr>
					<td>
						<?php Print $printButton?>
					</td>
					<td>
						Amount
					</td>
					<?php
						if($period == "week")
						{
							Print '	<td>
										Days Completed
									</td>';
						}
					?>
				</tr>
				<?php
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

					$earlyCuttoff = '';// for printable
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
								Print "
								<tr>
									<td>
										13th Month Pay remaining balance
									</td>
									<td>
										".numberExactFormat($thirteenthRemainder, 2, '.', true)."
									</td>
									<td>
										--
									</td>
								</tr>";

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
							// $overallCounter = 20;
							$overallCounter = count(array_filter($secondArrayChecker));
							echo "  |". $overallCounter."|  ";
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
							Print "
									<tr>
										<td>
											".$startDate." - ".$endDate."
										</td>
										<td>
											".numberExactFormat($thirteenthMonth, 2, '.', true)."
										</td>
										<td>
											".numberExactFormat($daysCompleted, 2, '.', true)."
										</td>
									</tr>";
							
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
									Print "
										<tr>
											<td>
												".$dateToPresent." - Present
											</td>
											<td>
												".numberExactFormat($thirteenthMonth, 2, '.', true)."
											</td>
											<td>
												".numberExactFormat($daysCompleted, 2, '.', true)."
											</td>
										</tr>";
									$overallDaysAttended = $daysCompleted + $overallDaysAttended;
									$overallPayment += $thirteenthMonth;
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
								Print "
								<tr>
									<td>
										13th Month Pay remaining balance
									</td>
									<td>
										".numberExactFormat($thirteenthRemainder, 2, '.', true)."
									</td>
								</tr>";

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
								Print "
										<tr>
											<td>
												".$month." ".$year."
											</td>
											<td>
												".numberExactFormat($thirteenthMonth, 2, '.', true)."
											</td>
										</tr>";
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
								Print "
								<tr>
									<td>
										13th Month Pay remaining balance
									</td>
									<td>
										".numberExactFormat($thirteenthRemainder, 2, '.', true)."
									</td>
								</tr>";

								$remainderBool = false;

							}
							
						}
						//Computes 13th monthpay per month
						while($attDate = mysql_fetch_assoc($attQuery))
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
									Print "
									<tr>
										<td>
											13th Month Pay remaining balance
										</td>
										<td>
											".numberExactFormat($thirteenthRemainder, 2, '.', true)."
										</td>
									</tr>";

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
									Print "
											<tr>
												<td>
													".$year."
												</td>
												<td>
													".numberExactFormat($thirteenthMonth, 2, '.', true)."
												</td>
											</tr>";
									$overallPayment += $thirteenthMonth;
								}
								
								$noRepeat = $year;
							}
						}
					}
					

				?>
				

				<tr>
					<td>
						Total
					</td>
					<td>
						<?php Print numberExactFormat($overallPayment, 2, '.', true)?>
					</td>
					<?php
					if($period == "week")
					{
						Print "	<td>
									".numberExactFormat($overallDaysAttended, 2, '.', false)."
								</td>";
					}
					?>
					
				</tr>
				</table>
			</div>

		</div>

	</div>

	<!-- Modals -->
	<div class="modal fade" id="give13thmonthpay">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel" align='left'><?php Print $empArr['lastname'].", ".$empArr['firstname']?>'s 13th Month Pay</h4>
	      </div>
	      <div class="modal-body">

			<div class="row">
				<div class="col-md-4 col-lg-4 col-md-offset-1">
					<input name="txt_13_from" type="text" size="10" class="form-control" id="dtpkr_13thmonthpay_from" placeholder="mm-dd-yyyy" required readonly>
				</div>
				<span class="col-md-2 col-lg-2">to</span>
				<div class="col-md-4 col-lg-4">
					<input name="txt_13_to" type="text" size="10" class="form-control" id="dtpkr_13thmonthpay_to" placeholder="mm-dd-yyyy" required readonly>
				</div>
			</div>

			<!-- DISPLAY TABLE HERE -->
			<div id="13thmonthpay_table">
			</div>

	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#enter13thmonthpay">Give 13th Month Pay</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="enter13thmonthpay">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body">
	      	<div class="row">
		      	<div class="col-md-6 col-lg-6">
		      		<h4>13th Month Pay Amount:</h4>
		      			<b id="displayDesired13th"></b>
		      	</div>
		      	<div class="col-md-6 col-lg-6">
		      		<h4>Amount to Give:</h4> <input type="text" id="amountToGive"><br>
		        	<input type="checkbox" id="cb_amountToGive"> Copy overall amount
		      	</div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary" onclick="give13thPay()">Give 13th Monthpay</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="13thmonthhistory">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"><?php Print $empArr['lastname'].", ".$empArr['firstname']?>'s 13th Month Pay History</h4>
	      </div>
	      <div class="modal-body">
	        <table class='table table-bordered'>
	        	<tr>
	        		<td>
	        			From Date
	        		</td>
	        		<td>
	        			To Date
	        		</td>
	        		<td>
	        			13th Month Pay Amount
	        		</td>
	        		<td>
	        			Amount given
	        		</td>
	        	</tr>
	        	<?php
	        		$thirteenthHist = "SELECT * FROM thirteenth_pay WHERE empid = '$empid' ORDER BY STR_TO_DATE(from_date, '%M %e, %Y') ASC";
	        		$thirteenthHistQuery = mysql_query($thirteenthHist) or die(mysql_error()) ;

	        		$histBool = false;//historical print disabled
	        		if(mysql_num_rows($thirteenthHistQuery) != 0)
	        		{
	        			$histBool = true;//historical print enabled
	        			while($histRow = mysql_fetch_assoc($thirteenthHistQuery))
	        			{
	        				Print "
		        				<tr>
					        		<td>
					        			".$histRow['from_date']."
					        		</td>
					        		<td>
					        			".$histRow['to_date']."
					        		</td>
					        		<td>
					        			".$histRow['amount']."
					        		</td>
					        		<td>
					        			".$histRow['received']."
					        		</td>
					        	</tr>
		        				";
	        			}
	        			
	        		}
	        		else
	        		{
	        			Print "
	        				<tr>
				        		<td colspan='4'>
				        			No 13th Month pay history as of the moment.
				        		</td>
				        	</tr>";
	        		}

	        	?>
	        </table>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" id="historyButton" onclick="printHistory()">Print</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- 13th month pay giving printable -->
	<input type="hidden" id="give13Pay" value="<?php Print $give13Bool?>">

	<!-- Historical printable -->
	<input type="hidden" id="HistoricalPrint" value="<?php Print $histBool?>">
	<input type="hidden" id="Print" value="<?php Print $printBool?>">

	<input type="hidden" id="empid" value="<?php Print $empid?>">
	<input type="hidden" id="overallPayment" value="<?php Print $overallPayment?>">
	<input type="hidden" id="fromDate" value="<?php Print $pastToDateThirteenthPay?>">
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/jquery-ui.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
	<script>

		$( document ).ready(function() {
		   	if($('#HistoricalPrint').val() == 1)
		   		$('#historyButton').removeClass('disabletotally');
		   	else
		   		$('#historyButton').addClass('disabletotally');

		   	if($('#give13Pay').val() == 1)
		   		$('#give13thpay').removeClass('disabletotally');
		   	else
		   		$('#give13thpay').addClass('disabletotally');

		   	if($('#Print').val() == 1)
		   		$('#printButton').removeClass('disabletotally');
		   	else
		   		$('#printButton').addClass('disabletotally');

		   	if($( "#dtpkr_13thmonthpay_from").val() == "")
		   		$( "#dtpkr_13thmonthpay_to" ).datepicker( "option", "disabled", true);
		});


		/* DATE PICKER CONFIGURATIONS*/
		$( "#dtpkr_13thmonthpay_from").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'MM dd, yy',
			showAnim: 'blind',
			// maxDate: new Date(),
			//minDate: $("#datePickerMin").val(), 
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			},
			onClose: function(selectedDate){
				if(selectedDate !== "")
					$( "#dtpkr_13thmonthpay_to" ).datepicker( "option", "disabled", false);
			}
		});

		$( "#dtpkr_13thmonthpay_to" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'MM dd, yy',
			showAnim: 'blind',
			// maxDate: new Date(),
			//minDate: $("#datePickerMin").val(), 
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			}
		});

		$( "#dtpkr_13thmonthpay_to" ).change(function(){
			var to_date = $("#dtpkr_13thmonthpay_to").val();
			var from_date = $("#dtpkr_13thmonthpay_from").val();
			var empid = $("#empid").val();

			if(to_date !== "")
				var UNIXto = parseInt((new Date(to_date).getTime() / 1000).toFixed(0));
			if(from_date !== "")
				var UNIXfrom = parseInt((new Date(from_date).getTime() / 1000).toFixed(0));

			var notMore = true;

			if(UNIXfrom >= UNIXto)
			{
				$("#dtpkr_13thmonthpay_from").datepicker("setDate", null);
				$("#dtpkr_13thmonthpay_to").datepicker("setDate", null);
				alert("Invalid date range, please try again.");
				notMore = false;
			}

			if((from_date !== "" && to_date !== "") && notMore) {
				load_data(from_date, to_date, empid);
			}
			else
			{
				$('#13thmonthpay_table').html("");
			}
		});

		$( "#dtpkr_13thmonthpay_from" ).change(function(){
			var from_date = $("#dtpkr_13thmonthpay_from").val();
			var to_date = $("#dtpkr_13thmonthpay_to").val();
			var empid = $("#empid").val();
			if(to_date !== "")
				var UNIXto = parseInt((new Date(to_date).getTime() / 1000).toFixed(0));
			if(from_date !== "")
				var UNIXfrom = parseInt((new Date(from_date).getTime() / 1000).toFixed(0));

			var notMore = true;

			if(UNIXto <= UNIXfrom)
			{
				$("#dtpkr_13thmonthpay_from").datepicker("setDate", null);
				$("#dtpkr_13thmonthpay_to").datepicker("setDate", null);
				alert("Invalid date range, please try again.");
				notMore = false;
			}

			if((from_date !== "" && to_date !== "") && notMore) {
				load_data(from_date, to_date, empid);
			}
			else
			{
				$('#13thmonthpay_table').html("");
			}
			
		});

		function load_data(fromDate, toDate, empid)
		{
			$.ajax({
		   	url:"fetch_13thmonthpay_table.php",
		   	method:"POST",
		   	data:{
		   		fromDate: fromDate,
		   		toDate: toDate,
		   		empid: empid
		   	},
		   	success:function(table)
		   	{
		    		$('#13thmonthpay_table').html(table);
		   	}
		  	});
		}

		$('#enter13thmonthpay').on('show.bs.modal', function (event) {
		  var modal = $(this);
		  var overallPayment = $("#custompayment").val();
		  modal.find('#displayDesired13th').html(overallPayment);
		  modal.find('#cb_amountToGive').attr("onclick", "copyAmount("+overallPayment+")");
		})

		$('#enter13thmonthpay').on('hide.bs.modal', function (event) {
		  var modal = $(this);
		  modal.find('#cb_amountToGive').prop('checked', false);
		  modal.find('#amountToGive').val("");
		})

		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function copyAmount(amount) {
			amount = String(amount);
			
			document.getElementById("amountToGive").value = amount;
		}

		function give13thPay() {
			console.log(document.getElementById("amountToGive").value);
			var amount = document.getElementById("amountToGive").value;
			var thirteenth = document.getElementById("displayDesired13th").innerHTML;
			var fromDate = document.getElementById("dtpkr_13thmonthpay_from").value;
			var toDate = document.getElementById("dtpkr_13thmonthpay_to").value;
			var splitThirteenth = thirteenth.split('.');
			if(splitThirteenth.length != 1)
				thirteenth = splitThirteenth[0]+"."+splitThirteenth[1].substring(0,2);
			var a = confirm("Are you sure you want to give this employee's 13th month pay?")
			if(a) 
			{
				if(amount > thirteenth || amount == 0 || thirteenth == 0)
					alert("Please input proper amount.");
				else
				{
					window.location.assign("logic_reports_individual_13thmonth.php?empid=<?php Print $empid?>&amount="+amount+"&pay="+thirteenth+"&fromDate="+fromDate+"&toDate="+toDate);
				}
			}
		}
		function periodChange(period) {
			window.location.assign('reports_individual_13thmonthpay.php?empid=<?php Print $empid?>&per='+period);
		}

		function Print13thMonth() {
			window.location.assign('print_individual_13thmonth.php?empid=<?php Print $empid?>&per=<?php Print $period?>');
		}

		function printHistory() {
			window.location.assign('print_individual_historical_13thmonth.php?empid=<?php Print $empid?>');
		}
	</script>
</body>
</html>