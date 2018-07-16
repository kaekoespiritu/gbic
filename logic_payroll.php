<?php
	include('directives/session.php');
	include('directives/db.php');
	$time = strftime("%X");//TIME

	// $date = strftime("%B %d, %Y");
	$date = "July 11, 2018";
	// $date = "May 9, 2018";
//Employee ID
	$empid = $_POST['employeeID'];

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);
	$empArr = mysql_fetch_assoc($empQuery);

//Admin Info
	$adminUser = $_SESSION['user_logged_in'];
	$admin = "SELECT * FROM administrator WHERE username = '$adminUser'";
	$adminQuery = mysql_query($admin) or die(mysql_error());
	if(mysql_num_rows($adminQuery) != 0)
	{
		$adminArr = mysql_fetch_assoc($adminQuery);
		$adminName = $adminArr['firstname']." ".$adminArr['lastname'];
	}
	else
	{
		Print "<script>window.location.assign('login.php')</script>";
	}

//Daily rate of employee
	$dailyRate = $empArr['rate'];

//Days attended
	$daysAttended = $_POST['daysAttended'];

//Daily Workhours ----------------------------------------------------------------------
//if employee is absent on these days Post value will not be available
	$WorkHrsArr = "";//This is to array all these values
	if(!empty($_POST['wedWorkHrs']))
	{
		$wedWorkHrs = $_POST['wedWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $wedWorkHrs;
	}
	if(!empty($_POST['thuWorkHrs']))
	{
		$thuWorkHrs = $_POST['thuWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $thuWorkHrs;
	}
	if(!empty($_POST['friWorkHrs']))
	{
		$friWorkHrs = $_POST['friWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $friWorkHrs;
	}
	if(!empty($_POST['satWorkHrs']))
	{
		$satWorkHrs = $_POST['satWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $satWorkHrs;
	}
	$compSunday = 0;//Pre set value for Sunday Computation
	$SundayRatePerHour = (($dailyRate + ($dailyRate * .30))/8);//Sunday Hourly Rate
	$sunWorkHrs = 0;
	$sundayBool = false;//Boolean to filter the sunday from the work days
	if(!empty($_POST['sunWorkHrs']))
	{
		Print "<script>console.log('sunday pasok')</script>";
		$sundayBool = true;
		$sunExplode = explode('.',$_POST['sunWorkHrs']);
		if(count($sunExplode) > 1)
		{
			$sunHrs = $sunExplode[0];
			$sunMins = $sunExplode[1] / 60;

			$sunWorkHrs = $sunHrs+$sunMins;
		}
		else
		{
			$sunWorkHrs = $sunExplode[0];
		}

		
		// if($WorkHrsArr != "")
		// 	$WorkHrsArr .= ","; 
		// $WorkHrsArr .= $sunWorkHrs;

//Computation for Sunday --------------------------------------------------------------
		
		$compSunday = $SundayRatePerHour * $sunWorkHrs;
	}
	if(!empty($_POST['monWorkHrs']))
	{
		$monWorkHrs = $_POST['monWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $monWorkHrs;
	}
	if(!empty($_POST['tueWorkHrs']))
	{
		$tueWorkHrs = $_POST['tueWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $tueWorkHrs;
	}

//Computes the Overall Work Days ------------------------------------------------------
	$workHrs = explode("," ,$WorkHrsArr);
	$overallWorkDays = 0;
	$overallAllowance = 0;
	$sunday_Att = 0;//Preset the sunday attendance to filter out the overal to the sunday
	if($sundayBool)
	{
		$sunday_Att = 1;
		$sundayBool = false;
	}

	foreach($workHrs as $hrsCheck)
	{
		Print "<script>console.log('workhrs: ".$hrsCheck."')</script>";
		// if($sundayBool)
		// {
		// 	// if($hrsCheck < 8)
		// 	// {
		// 	// 	$overallWorkDays = ($hrsCheck / 8) + $overallWorkDays;
		// 	// 	Print '<script>console.log("less than 8hrs on a Sunday:"'.$overallWorkDays.');</script>';
		// 	// }
		// 	// else
		// 	// {
		// 	// 	$overallWorkDays++;
		// 		$sunday_Att = 1;
		// 	// }
		// 	$sundayBool = false;
		// 	$overallWorkDays++;
		// }
		if($hrsCheck < 8)
		{
			Print "<script>console.log('under: ".$hrsCheck."')</script>";
			$overallWorkDays = ($hrsCheck / 8) + $overallWorkDays;
			$overallWorkDays = numberExactFormat($overallWorkDays,2,'.', false);
			// $overallWorkDays++;
			$overallAllowance = ($hrsCheck / 8) + $overallAllowance;
			$overallAllowance = numberExactFormat($overallAllowance,2,'.', false);
		}
		else
		{
			$overallWorkDays++;
			$overallAllowance++;
		}
	}

//Computation for OVER TIME -----------------------------------------------------------
	$compOT = 0;
	$totalOT = 0;
	$OtRatePerHour = (($dailyRate + ($dailyRate * .25))/8);//Overtime Hourly Rate
	$OtRatePerHour = numberExactFormat($OtRatePerHour, 2, '.', true);
	if(!empty($_POST['totalOverTime']))
	{
		$totalOT = $_POST['totalOverTime'];//Total Overtime by employee
		Print "<script>console.log('totalOT: ".$totalOT."')</script>";
		$compOT = $totalOT * $OtRatePerHour;//Computed Overtime
	}

//Computation for Night Differential --------------------------------------------------
	$compND = 0;
	$NdRatePerHour = (($dailyRate / 8)*.10);//NightDiff Hourly Rate
	$totalND = 0;
	if(!empty($_POST['totalNightDiff']))
	{
		$totalND = $_POST['totalNightDiff'];

		$compND = $totalND * $NdRatePerHour;//Computed Night Differential
	}



//Computation for Deductions ----------------------------------------------------------
	//Pre set values for database
	$tax = 0;
	$sss = 0;
	$pagibig = 0;
	$philhealth = 0;

	//if values are not empty
	if(!empty($_POST['tax']))
		$tax = $_POST['tax'];
	if(!empty($_POST['sss']))
		$sss = $_POST['sss'];
	if(!empty($_POST['pagibig']))
		$pagibig = $_POST['pagibig'];
	if(!empty($_POST['philhealth']))
		$philhealth = $_POST['philhealth'];

	//employer contribution
	$sssER = $empArr['sss_er'] / 4;
	$pagibigER = $empArr['pagibig_er'] / 4;
	$philhealthER = $empArr['philhealth_er'] / 4;

	$compDeductions = $tax + $sss + $pagibig + $philhealth;

//COLA incrementation -----------------------------------------------------------------
	$cola = 0;
	if($_POST['cola'] != "N/A")
	{
		$cola = $_POST['cola'];
	}

//Holiday Computation ----------------------------------------------------- 

	$regHolidayInc = $dailyRate;//Computation for Regular Holiday 
	$speHolidayInc = $dailyRate * .30;//Special Holiday 
	$addHoliday = 0;//Preset Additional holiday value for the grand total
	$regHolNum = 0;//Preset number of regular holiday this payroll period
	$speHolNum = 0;//Preset number of special holiday this payroll period
	
	if($empArr['complete_doc'] == 1) 
	{

		if(isset($_POST['holidayName']) && isset($_POST['holidayType']) && isset($_POST['holidayDate']))
		{
			$holidayNum = count($_POST['holidayDate']);//counts the number of holiday in that week
			$holidaysTogether = false;

			// Check if multiple holidays are beside each other
			if($holidayNum > 1)
			{
				for($count = 0; $count < $holidayNum; $count++)
				{
					if($count != $holidayNum - 1)
					{
						$checker = $_POST['holidayDate'][$count + 1]; 
						$dayBefore = date('F d, Y', strtotime('-1 day', strtotime($_POST['holidayDate'][$count])));

						if($dayBefore == $checker)
						{
							$holidaysTogether = true;
						}
						else
						{
							$holidaysTogether = false;
							break;
						}
					}
				}
			}

			if($holidayNum == 1)//if there is only one Holiday in the week
			{	
				Print '<script>console.log("One holiday in the week.")</script>';
				$holidayName = $_POST['holidayName'][0];
				$holidayType = $_POST['holidayType'][0];
				$holidayDate = $_POST['holidayDate'][0];

				$dayBefore = date('F d, Y', strtotime('-1 day', strtotime($holidayDate)));
				$dayBeforeChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$dayBefore'");
				$sameDayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$date'");
				if(mysql_num_rows($dayBeforeChecker) == 1 && mysql_num_rows($sameDayChecker) == 0)
				{
					$dayBeforeArr = mysql_fetch_assoc($dayBeforeChecker);
					if($dayBeforeArr['attendance'] == '2' )//2 if employee is present on the day before the holiday
					{
						if($holidayType != "special")
							$regHolNum++;
					}
				}

				$holidayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$holidayDate'");
				$holidayArr = mysql_fetch_assoc($holidayChecker);
				if($holidayArr['attendance'] == 2)
				{
					if($holidayType == "special")//Special Holiday
					{
						Print '<script>console.log("speHolidayInc3: '.$speHolidayInc.'")</script>';
						$addHoliday += $speHolidayInc; 
						$speHolNum++;
					}
					else//Regular Holiday
					{
						$addHoliday += $regHolidayInc; 
						// $regHolNum++;
					}
				}
				// Print '<script>console.log("Total overall holidays: '.$regHolNum.'")</script>';
			}
	// ----------------------------------------
			else if($holidayNum > 1 && $holidaysTogether)// if there is more than 1 holidays in the week & they are together
			{
				Print '<script>console.log("Multiple holidays in the week and they are together.")</script>';

				$holidayType = $_POST['holidayType'][0];
				$holidayDate = $_POST['holidayDate'][0];

				$dayBefore = date('F d, Y', strtotime('-1 day', strtotime($holidayDate)));
				$dayAfter = date('F d, Y', strtotime('1 day', strtotime($holidayDate)));
				$dayBeforeChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$dayBefore'");
				$dayAfterChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$dayAfter'");
				$sameDayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$date'");
				if(mysql_num_rows($dayBeforeChecker) == 1 && mysql_num_rows($sameDayChecker) == 0)
				{
					$dayBeforeArr = mysql_fetch_assoc($dayBeforeChecker);
					if($dayBeforeArr['attendance'] == '2' )//2 if employee is present on the day before the holiday
					{
						// $overallWorkDays++;//increment workdays 
						if($holidayType != "special")//Special Holiday
						{
							Print '<script>console.log("special3")</script>';
							// check if employee went to work the next day
							$dayAfterArr = mysql_fetch_assoc($dayAfterChecker);
							if($dayAfterArr['attendance'] == '2') // If employee went to work on holiday
							{
								$regHolNum++;
								Print '<script>console.log("Went to work the day before: '.$regHolNum.'")</script>';
							}
						}	
							
					}
				}

				for($count = 0; $count < $holidayNum; $count++)
				{
					$holidayClass = $_POST['holidayType'][$count];
					$holidayIndex = $_POST['holidayDate'][$count];
					$holidayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$holidayIndex'") or die (mysql_error());
					$holdayArr = mysql_fetch_assoc($holidayChecker);
					if($holdayArr['attendance'] == '2') // If employee went to work on holiday
					{
						Print '<script>console.log("Employee went to work on holiday.")</script>';
						if($holidayClass == "special")//Special Holiday
						{
							Print '<script>console.log("speHolidayInc2: '.$speHolidayInc.'")</script>';
							$addHoliday += $speHolidayInc;
							$speHolNum++;
						}
						else//Regular Holiday
						{
							$addHoliday += $regHolidayInc;
							// $regHolNum+=2;
							Print '<script>console.log("Went to work on the holiday: '.$regHolNum.'")</script>';
						}
					}
					Print '<script>console.log("Total overall holidays: '.$regHolNum.'")</script>';
				}
			}

			else if($holidayNum > 1 && !$holidaysTogether)// if there is more than 1 holiday and they are not together
			{
				Print '<script>console.log("Multiple holidays in the week and they are not together.")</script>';
				for($count = 0; $count < $holidayNum; $count++)
				{

					$holidayName = $_POST['holidayName'][$count];
					$holidayType = $_POST['holidayType'][$count];
					$holidayDate = $_POST['holidayDate'][$count];

					// Print '<script>console.log("Date: '.$holidayDate.'")</script>';	

					$dayBefore = date('F d, Y', strtotime('-1 day', strtotime($holidayDate)));
					$dayHoliday = date('F d, Y', strtotime('1 day', strtotime($holidayDate)));
					$dayBeforeChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$dayBefore'");
					$dayHolidayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$dayHoliday'");
					$sameDayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$date'");
					if(mysql_num_rows($dayBeforeChecker) == 1 && mysql_num_rows($sameDayChecker) == 0)
					{
						$dayBeforeArr = mysql_fetch_assoc($dayBeforeChecker);
						if($dayBeforeArr['attendance'] == '2' )//2 if employee is present on the day before the holiday
						{
							// $overallWorkDays++;//increment workdays 
							if($holidayType != "special")//Special Holiday
							{
								$dayHolidayArr = mysql_fetch_assoc($dayHolidayChecker);
								if($dayHolidayArr['attendance'] == '2')
								{
									$regHolNum++;
									// Print '<script>console.log("Went to work the day before: '.$regHolNum.'")</script>';	
								}
							}
						}
					}

					$holidayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$holidayDate'");
					$holidayArr = mysql_fetch_assoc($holidayChecker);
					if($holidayArr['attendance'] == 2)
					{
						// Print '<script>console.log("Employee went to work on a holiday.")</script>';
						if($holidayType == "special")//Special Holiday
						{
							Print '<script>console.log("speHolidayInc1: '.$speHolidayInc.'")</script>';
							$addHoliday += $speHolidayInc;
							$speHolNum++;
						}
						else//Regular Holiday
						{
							$addHoliday += $regHolidayInc;
							// $overallWorkDays++;//increment workdays 
							$regHolNum+=2;
						}
					}
					// Print '<script>console.log("Total overall holidays: '.$regHolNum.'")</script>';
				}
			}
		}
	}
	
//Computation for Allowance -----------------------------------------------------------

	$dailyAllowance = 0;
	$extraAllowance = 0;

	if(!empty($_POST['allowance']))
		$dailyAllowance = $_POST['allowance'];// for database use
	if(!empty($_POST['extra_allowance']))
		$extraAllowance = $_POST['extra_allowance'];

	$daysAllowance = $overallAllowance;
	$checkRoundAllowance = explode(".", $daysAllowance);
	// if(count($checkRoundAllowance) > 1)
	// {

	// 	// if($checkRoundAllowance[1] != 0)
	// 	// 	$daysAllowance = $checkRoundAllowance[0] + 1;
	// 	// else
	// 	// 	$daysAllowance = $checkRoundAllowance[0];
	// }

	if(!empty($_POST['sunWorkHrs']))
	{
		Print "<script>console.log('allowDays1: ".$daysAllowance."')</script>";
		if($_POST['sunWorkHrs'] < 8)
		{
			$daysAllowance = ($_POST['sunWorkHrs'] / 8) + $daysAllowance;
			$daysAllowance = numberExactFormat($daysAllowance,2,'.', false);
		}
		else
		{
			$daysAllowance++;
		}	
		Print "<script>console.log('allowDays2: ".$daysAllowance."')</script>";
	}

	$compAllowance = (($daysAllowance * $dailyAllowance)  + $extraAllowance);
//Loans deduction --------------------------------------------------------------------- Incomplete
//*query to loans table the deduction
	function loanQuery($loanType , $empid, $DeductedLoan, $date, $admin) //function for loans query
	{
		
		$time = strftime("%X");//TIME

		//Check if there is an existing query for this loan to avoid duplication
		$loanChecker = mysql_query("SELECT * FROM loans WHERE date = '$date' AND empid='$empid' AND type='$loanType'");
		if(mysql_num_rows($loanChecker) > 0)
		{
			mysql_query("DELETE FROM loans WHERE date='$date' AND empid='$empid' AND type='$loanType'");
		}


		$Loan = "SELECT * FROM loans WHERE type='$loanType' AND empid='$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1";
		$Query = mysql_query($Loan);
		$loanArr = mysql_fetch_assoc($Query);
		$LoanBalance = $DeductedLoan - $loanArr['balance'];
		$LoanBalance = abs($LoanBalance);//make it positive if ever it is negative

		

		$Update = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action, admin) 
						VALUES('$empid', '$loanType', '$LoanBalance', '$DeductedLoan', 'deducted', '$date', '$time', '0', '$admin')";
		mysql_query($Update);
	}


	$loan_oldVale = 0;
	$loan_sss = $_POST['sssDeduct'];
	$loan_pagibig = $_POST['pagibigDeduct'];
	$loan_oldVale = $_POST['oldValeDeduct'];
	//$loan_newVale = $_POST['newValeAdded'];

	if(!empty($_POST['sssDeduct']))//if SSS loan textbox in payroll has value
	{
		loanQuery('SSS', $empid, $loan_sss, $date, $adminName);
	}
	if(!empty($_POST['pagibigDeduct']))//if Pagibig loan textbox in payroll has value
	{
		loanQuery('PagIBIG', $empid, $loan_pagibig, $date, $adminName);
	}

	$loan_newVale = 0;//preset the newvale
	if(!empty($_POST['newValeAdded']))//if newVale loan textbox in payroll has value
	{
		//Check if there is an existing query for this loan to avoid duplication
		$loanChecker = mysql_query("SELECT * FROM loans WHERE date='$date' AND empid='$empid' AND type='newVale'");
		if(mysql_num_rows($loanChecker) > 0)
		{
			mysql_query("DELETE FROM loans WHERE date='$date' AND empid='$empid' AND type='newVale'");
		}

		$Loan = "SELECT * FROM loans WHERE type='newVale' AND empid='$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1";
		$newValeQuery = mysql_query($Loan);
		$DeductedLoan = $_POST['newValeAdded'];
		$remarks = $_POST['newValeRemarks'];

		if(mysql_num_rows($newValeQuery) > 0)
		{
			$loanArr = mysql_fetch_assoc($newValeQuery);
			//Loaned
			$newValeBalance = $loanArr['balance'];
			$LoanAdded = $DeductedLoan + $loanArr['balance'];
			//Deducted loan
			$LoanBalance = $DeductedLoan - $loanArr['balance'];
			
			$LoanBalance = abs($LoanBalance);//make it positive if ever it is negative
			$Update1 = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action, admin) VALUES('$empid', 'newVale', '$LoanBalance', '$LoanAdded', '$remarks', '$date', '$time', '1', '$adminName')";
			
			$loanCheck = "SELECT * FROM loans WHERE type='newVale' AND empid='$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1";

			mysql_query($Update1);

			$time = date('H:i:s', strtotime('+1 seconds'));//Adds 1 seconds to the time
			$payNewVale = mysql_query($loanCheck) or die (mysql_error());
			$newValeArr = mysql_fetch_assoc($payNewVale);
			$newBalance = $newValeArr['balance'];

			$Update2 = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action, admin) 
							VALUES('$empid', 'newVale', '0', '$newBalance', 'deducted', '$date', '$time', '0', '$adminName')";
			mysql_query($Update2);
		}
		else//Employee has no newvale balance but added newvale in the payroll
		{
			$loanArr = mysql_fetch_assoc($newValeQuery);
			//Deducted loan
			$LoanAdded = $DeductedLoan;
			
			$Update1 = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action, admin) 
							VALUES('$empid', 'newVale', '$DeductedLoan', '$DeductedLoan', '$remarks', '$date', '$time', '1', '$adminName')";

			$loanCheck = "SELECT * FROM loans WHERE type='newVale' AND empid='$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1";
			mysql_query($Update1);

			$time = date('H:i:s', strtotime('+1 seconds'));//Adds 1 seconds to the time
			$payNewVale = mysql_query($loanCheck) or die (mysql_error());
			$newValeArr = mysql_fetch_assoc($payNewVale);
			$newBalance = $newValeArr['balance'];

			$Update2 = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action, admin) 
							VALUES('$empid', 'newVale', '0', '$newBalance', 'deducted', '$date', '$time', '0', '$adminName')";
			mysql_query($Update2);
		}
		

		$loan_newVale = $LoanAdded;
	}
	else if(!empty($_POST['newVale']))//if employee did't add any new vales but have previous newvale
	{
		$loanChecker = mysql_query("SELECT * FROM loans WHERE date='$date' AND empid='$empid' AND type='newVale'");
		if(mysql_num_rows($loanChecker) > 0)
		{
			mysql_query("DELETE FROM loans WHERE date='$date' AND empid='$empid' AND type='newVale'");
		}
		$loan_newVale = $_POST['newVale'];
		$Update = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action, admin) 
							VALUES('$empid', 'newVale', '0', '$loan_newVale', 'deducted', '$date', '$time', '0', '$adminName')";
		mysql_query($Update)  or die (mysql_error());
	}
	if(!empty($_POST['oldValeDeduct']))//if SSS loan textbox in payroll has value
	{
		loanQuery('oldVale', $empid, $loan_oldVale, $date, $adminName);
	}
	$compLoan = $loan_sss + $loan_pagibig + $loan_oldVale + $loan_newVale;

//Tools Computation -------------------------------------------------------------------
	$tools_paid = 0;
	$outStandingBalance = 0;

	if(!empty($_POST['toolname'][0])) // If there are more than 1 tools saved
	{
		$toolNum = count($_POST['toolname']);
		
		$totalToolCost = 0;
		$BoolTool = false; //Boolean to if there is more than 2 tools
		if($toolNum > 1)
		{
			$toolQuery = "INSERT INTO tools(empid, tools, cost, quantity, date) VALUES";
			for($counter = 0; $counter < $toolNum; $counter++)
			{
				$toolname = $_POST['toolname'][$counter];
				$toolprice = $_POST['toolprice'][$counter];
				$toolquantity = $_POST['toolquantity'][$counter];
				$totalToolCost += ($toolprice * $toolquantity);//gets the total tool cost
				Print "<script>console.log('toolquantity: ".$toolquantity."')</script>";

				if($toolQuery != "INSERT INTO tools(empid, tools, cost, quantity, date) VALUES")
				{
					$toolQuery .= ",";//Add comma after every additional values
				}
				$toolQuery .= "('$empid',
								'$toolname',
								'$toolprice',
								'$toolquantity',
								'$date')"; 
			}

			if(!empty($_POST['previousPayable']))
			{
				//Gets the new Previous payable
				if(($_POST['previousPayable'] + $totalToolCost) != $_POST['amountToPay'])
				{
					$outStandingBalance = ($_POST['previousPayable'] + $totalToolCost) - $_POST['amountToPay'];
					$outStandingBalance = abs($outStandingBalance);
				}
			}
			else if($totalToolCost != $_POST['amountToPay'])
			{
				$outStandingBalance = $totalToolCost - $_POST['amountToPay'];
				$outStandingBalance = abs($outStandingBalance);
			}
		}
		else if(!empty($_POST['toolprice'][0]) && !empty($_POST['toolname'][0]) && !empty($_POST['toolquantity'][0]))// If there is only one tool saved
		{
			$toolname = $_POST['toolname'][0];
			$toolprice = $_POST['toolprice'][0];
			$toolquantity = $_POST['toolquantity'][0];
			
			if(!empty($_POST['previousPayable']))
			{
				//Gets the new Previous payable
				if(($_POST['previousPayable'] + $toolprice) != $_POST['amountToPay'])
				{
					$outStandingBalance = ($_POST['previousPayable'] + ($toolprice * $toolquantity)) - $_POST['amountToPay'];
					$outStandingBalance = abs($outStandingBalance);
				}
			}
			else if($toolprice != $_POST['amountToPay'])
			{
				$outStandingBalance = ($toolprice * $toolquantity) - $_POST['amountToPay'];
				$outStandingBalance = abs($outStandingBalance);
			}
			$toolQuery = "INSERT INTO tools(empid, tools, cost, quantity, date) VALUES(	'$empid',
																				'$toolname',
																				'$toolprice',
																				'$toolquantity',
																				'$date')"; 

		}
		else if(!empty($_POST['previousPayable']))// If admin did not have any tools but have outstanding balance
		{
			if($_POST['previousPayable'] != $_POST['amountToPay'])
			{
				$outStandingBalance = $_POST['previousPayable'] - $_POST['amountToPay'];
				$outStandingBalance = abs($outStandingBalance);
			}
		}

		if(isset($toolQuery))
		{
			$toolsChecker = mysql_query("SELECT * FROM tools WHERE empid='$empid' AND date='$date'");
			if(mysql_num_rows($toolsChecker) == 0)
			{
				mysql_query($toolQuery);
			}
			else//if employee has tools already
			{//replace the old tools that the employee made 
				mysql_query("DELETE FROM tools WHERE empid='$empid' AND date = '$date'");
				mysql_query($toolQuery);
			}
		}
		$tools_paid = $_POST['amountToPay'];
	}
	else if(!empty($_POST['previousPayable']))//if Employee has no tools but has outstanding payable
	{
		//if precious payable is not equal to amountpaid it means the employee still has outstanding payable 
		if($_POST['previousPayable'] != $_POST['amountToPay'])
		{
			$outStandingBalance = $_POST['previousPayable'] - $_POST['amountToPay'];
			$outStandingBalance = abs($outStandingBalance);
		}
		$tools_paid = $_POST['amountToPay'];
	}
	else//if they did not input any tools when they get back
	{
		mysql_query("DELETE FROM tools WHERE empid='$empid' AND date = '$date'");
	}
	

// --------------------------------- GRAND TOTAL -----------------------------------------

//Grand Total Computation

	$totalRegularHolidayRate = ($regHolNum * $regHolidayInc);
	$totalSpecialHolidayRate = ($speHolNum * $speHolidayInc);
	$totalSundayRate = $SundayRatePerHour * $sunWorkHrs;
	$totalNightDifferential = $NdRatePerHour * $totalND;
	$totalAllowance = $compAllowance;
	$totalOvertime = $OtRatePerHour * $totalOT;
	$totalRatePerDay = $overallWorkDays * $dailyRate;
	Print "<script>console.log('overallWorkDays: ". $overallWorkDays." | dailyRate: ".$dailyRate."')</script>";
	$xAllowance = $extraAllowance;

	$totalCola = $cola * $daysAllowance;
	$totalEarnings = $totalRegularHolidayRate + $totalSpecialHolidayRate + $totalSundayRate + $totalNightDifferential + $totalAllowance + $totalOvertime + $totalRatePerDay + $xAllowance + $totalCola;
	Print "<script>console.log('logic_payroll - totalRegularHolidayRate: ".abs($totalRegularHolidayRate)." | totalSpecialHolidayRate: ".abs($totalSpecialHolidayRate)." | totalSundayRate: ".abs($totalSundayRate)." | totalNightDifferential: ".$totalNightDifferential." | totalAllowance: ".$totalAllowance." | totalOvertime: ".$totalOvertime." | totalRatePerDay: ".$totalRatePerDay." | xAllowance: ".$xAllowance." | totalCola: ".$totalCola."')</script>";

	$contributions = $pagibig + $philhealth + $sss + $tax;

	$totalLoans = $loan_pagibig + $loan_sss + $loan_oldVale + $loan_newVale;
	Print "<script>console.log('toDB - totalEarnings: ".abs($totalEarnings)." | contributions: ".abs($contributions)." | totalLoans: ".abs($totalLoans)." | tools_paid: ".abs($tools_paid)."')</script>";
	$grandTotal = abs($totalEarnings) - abs($contributions) - abs($totalLoans) - abs($tools_paid);
	$grandTotal = abs(numberExactFormat($grandTotal, 2, '.', false));




	$query = "INSERT INTO payroll(	empid,
									rate,
									num_days,
									overtime,
									ot_num,
									ot_comp,
									allow,
									allow_days,
									comp_allowance,
									x_allowance,
									cola,
									sunday_rate,
									sunday_hrs,
									sunday_att,
									nightdiff_rate,
									nightdiff_num,
									nightdiff,
									reg_holiday,
									spe_holiday,
									holiday_added,
									spe_holiday_num,
									reg_holiday_num,
									tax,
									sss,
									pagibig,
									philhealth,
									sss_er,
									pagibig_er,
									philhealth_er,
									tools,
									tools_paid,
									tools_outstanding,
									total_salary,
									date,
									loan_sss,
									loan_pagibig,
									new_vale,
									old_vale) VALUES(	'$empid',
														'$dailyRate',
														'$overallWorkDays',
														'$OtRatePerHour',
														'$totalOT',
														'$compOT',
														'$dailyAllowance',
														'$daysAllowance',
														'$compAllowance',
														'$extraAllowance',
														'$totalCola',
														'$SundayRatePerHour',
														'$sunWorkHrs',
														'$sunday_Att',
														'$NdRatePerHour',
														'$totalND',
														'$compND',
														'$regHolidayInc',
														'$speHolidayInc',
														'$addHoliday',
														'$speHolNum', 
														'$regHolNum',
														'$tax',
														'$sss',
														'$pagibig',
														'$philhealth',
														'$sssER',
														'$pagibigER',
														'$philhealthER',
														'$date',
														'$tools_paid',
														'$outStandingBalance',
														'$grandTotal',
														'$date',
														'$loan_sss',
														'$loan_pagibig',
														'$loan_newVale',
														'$loan_oldVale')";


	$updateQuery = "UPDATE payroll SET	
									rate = '$dailyRate',
									num_days = '$overallWorkDays',
									overtime = '$OtRatePerHour',
									ot_num = '$totalOT',
									ot_comp = '$compOT',
									allow = '$dailyAllowance',
									allow_days = '$daysAllowance',
									comp_allowance = '$compAllowance',
									x_allowance = '$extraAllowance',
									cola = '$totalCola',
									sunday_rate = '$SundayRatePerHour',
									sunday_hrs = '$sunWorkHrs',
									sunday_att = '$sunday_Att',
									nightdiff_rate = '$NdRatePerHour',
									nightdiff_num = '$totalND',
									nightdiff = '$compND',
									reg_holiday = '$regHolidayInc',
									spe_holiday = '$speHolidayInc',
									holiday_added = '$addHoliday',
									spe_holiday_num = '$speHolNum',
									reg_holiday_num = '$regHolNum',
									tax = '$tax',
									sss = '$sss',
									pagibig = '$pagibig',
									philhealth = '$philhealth',
									sss_er = '$sssER',
									pagibig_er = '$pagibigER',
									philhealth_er = '$philhealthER',
									tools = '$date',
									tools_paid = '$tools_paid',
									tools_outstanding = '$outStandingBalance',
									total_salary = '$grandTotal',
									loan_sss = '$loan_sss',
									loan_pagibig = '$loan_pagibig',
									new_vale = '$loan_newVale',
									old_vale = '$loan_oldVale' WHERE empid = '$empid' AND date = '$date'";
	
	$mainChecker = mysql_query("SELECT * FROM payroll WHERE empid='$empid' AND date='$date'");
	if(mysql_num_rows($mainChecker) == 0)
	{
		mysql_query($query)or die(mysql_error());;
	}					
	else
	{
		mysql_query($updateQuery)or die(mysql_error());;
	}
	 

	Print "	<form method = 'POST' action='payroll_computation.php' id='logicPayrollForm'>
				<input type='hidden' name='empid' value='".$empid."'>
				<input type='hidden' name='date' value='".$date."'>
			</form>
			<script rel='javascript' src='js/jquery.min.js'></script>
			<script>
			$( document ).ready(function(){
				$('#logicPayrollForm').submit();
			});
			</script>
			
			";

	// $url = "http://localhost/gbic/payroll_computation.php";

	// $post_data = array(
	// 	'method' => 'post',
	// 	'empid' => $empid,
	// 	'date' => $date
	// );

	// $ch = curl_init();

	// //URL to submit to
	// curl_setopt($ch, CURLOPT_URL, $url);

	// //Return output instead of outputting it
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// //Post request
	// curl_setopt($ch, CURLOPT_POST, 1);

	// //Adding the post variables to the request
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

	// //Execute the request and fetch the response to check for errors
	// $output = curl_exec($ch);

	// if($output === false) {
	// 	echo "cURL Error: ".curl_error($ch);
	// }

	// //close and free up the curl handle
	// curl_close($ch);

	// //Display the row output
	// print_r($output);
?>



























