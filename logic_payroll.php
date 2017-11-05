<?php
	require_once('directives/session.php');
	require_once('directives/db.php');
	//Print "<script>console.log('".$overtimeRate1."')</script>";


	//$date = strftime("%B %d, %Y");
	$date = "October 24, 2017";
	$time = strftime("%X");//TIME
//Employee ID
	$empid = $_POST['employeeID'];

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);
	$empArr = mysql_fetch_assoc($empQuery);

//Daily rate of employee
	$dailyRate = $empArr['rate'];

//Days attended
	$daysAttended = $_POST['daysAttended'];

//Daily Workhours ----------------------------------------------------------------------
//if employee is absent on these days Post value will not be available
	$WorkHrsArr = "";//This is to array all these values
	if(isset($_POST['wedWorkHrs']))
	{
		$wedWorkHrs = $_POST['wedWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $wedWorkHrs;
	}
	if(isset($_POST['thuWorkHrs']))
	{
		$thuWorkHrs = $_POST['thuWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $thuWorkHrs;
	}
	if(isset($_POST['friWorkHrs']))
	{
		$friWorkHrs = $_POST['friWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $friWorkHrs;
	}
	if(isset($_POST['satWorkHrs']))
	{
		$satWorkHrs = $_POST['satWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $satWorkHrs;
	}
	$compSunday = 0;//Pre set value for Sunday Computation
	$SundayRatePerHour = (($dailyRate + ($dailyRate * .30))/8);//Sunday Hourly Rate
	$sunWorkHrs = 0;
	if(isset($_POST['sunWorkHrs']))
	{
		$sunWorkHrs = $_POST['sunWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $sunWorkHrs;

//Computation for Sunday --------------------------------------------------------------
		
		$compSunday = $SundayRatePerHour * $sunWorkHrs;
// Print "<script>console.log('sunWorkHrs: ".$sunWorkHrs."')</script>";
	}
	if(isset($_POST['monWorkHrs']))
	{
		$monWorkHrs = $_POST['monWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $monWorkHrs;
	}
	if(isset($_POST['tueWorkHrs']))
	{
		$tueWorkHrs = $_POST['tueWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $tueWorkHrs;
	}

//Computes the Overall Work Days ------------------------------------------------------
	$workHrs = explode("," ,$WorkHrsArr);
	$overallWorkDays = 0;
	foreach($workHrs as $hrsCheck)
	{
		if($hrsCheck < 8)
		{
			$overallWorkDays = (($hrsCheck / 8) + $overallWorkDays);
		}
		else
		{
			$overallWorkDays++;
		}
	}
	//Print "<script>console.log('overallWorkDays: ".$overallWorkDays."')</script>";

//Computation for OVER TIME -----------------------------------------------------------
	$compOT = 0;
	$totalOT = 0;
	$OtRatePerHour = (($dailyRate + ($dailyRate * .25))/8);//Overtime Hourly Rate
	if(!empty($_POST['totalOverTime']))
	{
		$totalOT = $_POST['totalOverTime'];//Total Overtime by employee

		$compOT = $totalOT * $OtRatePerHour;//Computed Overtime
	}
	//Print "<script>console.log('compOT: ".$compOT."')</script>";

//Computation for Night Differential --------------------------------------------------
	$compND = 0;
	$NdRatePerHour = (($dailyRate / 8)*.10);//NightDiff Hourly Rate
	$totalND = 0;
	if(!empty($_POST['totalNightDiff']))
	{
		$totalND = $_POST['totalNightDiff'];

		$compND = $totalND * $NdRatePerHour;//Computed Night Differential
	}
	// Print "<script>console.log('totalND: ".$totalND."')</script>";



//Computation for Deductions ----------------------------------------------------------
	$tax = $_POST['tax'];
	$sss = $_POST['sss'];
	$pagibig = $_POST['pagibig'];
	$philhealth = $_POST['philhealth'];
	$compDeductions = $tax + $sss + $pagibig + $philhealth;
	//Print "<script>console.log('compDeductions: ".$compDeductions."')</script>";

//COLA incrementation -----------------------------------------------------------------
	$cola = 0;
	if(!empty($_POST['COLA']))
	{
		$cola = $_POST['COLA'];
	}

//Holiday Computation ----------------------------------------------------- Incomplete

	$regHolidayInc = $dailyRate;//Computation for Regular Holiday 
	$speHolidayInc = $dailyRate * .30;//Special Holiday 
	$addHoliday = 0;//Preset Additional holiday value for the grand total
	$regHolNum = 0;//Preset number of regular holiday this payroll period
	$speHolNum = 0;//Preset number of special holiday this payroll period
	
	if(isset($_POST['holidayName']) && isset($_POST['holidayType']) && isset($_POST['holidayDate']))
	{
		//Print "<script>console.log('pasok1')</script>";
		$holidayNum = count($_POST['holidayDate']);
		if($holidayNum == 1)//if there is only one Holiday in the week
		{	
			$holidayName = $_POST['holidayName'][0];
			$holidayType = $_POST['holidayType'][0];
			$holidayDate = $_POST['holidayDate'][0];

			$dayBefore = date('F j, Y', strtotime('-1 day', strtotime($holidayDate)));
			$dayBeforeChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$dayBefore'");
			if(mysql_num_rows($dayBeforeChecker) > 0)
			{
				$dayBeforeArr = mysql_fetch_assoc($dayBeforeChecker);
				if($dayBeforeArr['attendance'] == '2')//2 if employee is present on the day before the holiday
				{
					//Print "<script>console.log('".$overallWorkDays."')</script>";
					$overallWorkDays++;//increment workdays 
					//Print "<script>console.log('".$overallWorkDays."')</script>";
				}
			}

			$holidayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$holidayDate'");
			$holidayArr = mysql_fetch_assoc($holidayChecker);
			if($holidayArr['attendance'] == 2)
			{
				if($holidayType == "special")//Special Holiday
				{
					$addHoliday = $speHolidayInc;
					$speHolNum++;
				}
				else//Regular Holiday
				{
					$addHoliday = $regHolidayInc;
					$regHolNum++;
				}
			}
		}
		else if($holidayNum > 1)// if there is more than 1 holidays in the week
		{
			$boolHoliday = true;//if employee didnot appear to work the day before holiday
			for($count = 0; $count < $holidayNum; $count++)
			{
				//Checker if employee is present the day before the holiday
				$holidayStartingDate = $_POST['holidayDate'][0];
				$dayBefore = date('F j, Y', strtotime('-1 day', strtotime($holidayDate)));
				$dayBeforeChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$dayBefore'");
				if(mysql_num_rows($dayBeforeChecker) > 0)
				{
					$dayBeforeArr = mysql_fetch_assoc($dayBeforeChecker);
					if($dayBeforeArr['attendance'] == '2')//2 if employee is present on the day before the holiday
					{
						$overallWorkDays++;//increment workdays 
					}
					else
					{
						$boolHoliday = false;
					}
				}
				else
				{
					$boolHoliday = false;
				}

				if($boolHoliday)
				{
					$holidayName = $_POST['holidayName'][$count];
					$holidayType = $_POST['holidayType'][$count];
					$holidayDate = $_POST['holidayDate'][$count];
					$holidayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$holidayDate'");
					$holdayArr = mysql_fetch_assoc($holidayChecker);

					if($holdayArr['attendance'] == 2)
					{
						if($holidayType == "special")//Special Holiday
						{
							$addHoliday += $speHolidayInc;
							$speHolNum++;
						}
						else//Regular Holiday
						{
							$addHoliday += $regHolidayInc;
							$regHolNum++;
						}
					}
				}
			}
		}
	}
	
//Computation for Allowance -----------------------------------------------------------
	$dailyAllowance = $_POST['allowance'];// for database use
	$extraAllowance = $_POST['extra_allowance'];

	$compAllowance = (($overallWorkDays*$dailyAllowance)  + $extraAllowance);
//Loans deduction --------------------------------------------------------------------- Incomplete
//*query to loans table the deduction
	function loanQuery($loanType , $empid, $DeductedLoan) //function for loans query
	{
		$date = "October 24, 2017";
		$time = strftime("%X");//TIME

		//Check if there is an existing query for this loan to avoid duplication
		$loanChecker = mysql_query("SELECT * FROM loans WHERE date='$date' AND empid='$empid' AND type='$loanType'");
		if(mysql_num_rows($loanChecker) > 0)
		{
			mysql_query("DELETE FROM loans WHERE date='$date' AND empid='$empid' AND type='$loanType'");
		}


		$Loan = "SELECT * FROM loans WHERE type='$loanType' AND empid='$empid' ORDER BY date DESC, time DESC LIMIT 1";
		$Query = mysql_query($Loan);
		$loanArr = mysql_fetch_assoc($Query);
		$LoanBalance = $DeductedLoan - $loanArr['balance'];
		$LoanBalance = abs($LoanBalance);//make it positive if ever it is negative

		

		$Update = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action) 
						VALUES('$empid', '$loanType', '$LoanBalance', '$DeductedLoan', 'deducted', '$date', '$time', '0')";
		mysql_query($Update);
	}


	$loan_oldVale = 0;
	$loan_sss = $_POST['sssDeduct'];
	$loan_pagibig = $_POST['pagibigDeduct'];
	$loan_oldVale = $_POST['oldValeDeduct'];
	//$loan_newVale = $_POST['newValeAdded'];

	if(!empty($_POST['sssDeduct']))//if SSS loan textbox in payroll has value
	{
		Print "<script>console.log('sssDeduct')</script>";
		loanQuery('SSS', $empid, $loan_sss);
	}
	if(!empty($_POST['pagibigDeduct']))//if SSS loan textbox in payroll has value
	{
		Print "<script>console.log('pagibigDeduct')</script>";
		loanQuery('PagIBIG', $empid, $loan_pagibig);
	}

	$loan_newVale = 0;//preset the newvale
	if(!empty($_POST['newValeAdded']))//if SSS loan textbox in payroll has value
	{
		Print "<script>console.log('newValeAdded')</script>";
		//Check if there is an existing query for this loan to avoid duplication
		$loanChecker = mysql_query("SELECT * FROM loans WHERE date='$date' AND empid='$empid' AND type='newVale'");
		if(mysql_num_rows($loanChecker) > 0)
		{
			mysql_query("DELETE FROM loans WHERE date='$date' AND empid='$empid' AND type='newVale'");
		}

		$Loan = "SELECT * FROM loans WHERE type='newVale' AND empid='$empid' ORDER BY date DESC, time DESC LIMIT 1";
		$newValeQuery = mysql_query($Loan);
		$DeductedLoan = $_POST['newValeAdded'];
		
		if(mysql_num_rows($newValeQuery) > 0)
		{
			Print "<script>console.log('newValeAdded1')</script>";
			$loanArr = mysql_fetch_assoc($Query);
			//Loaned
			$newValeBalance = $loanArr['balance'];
			$LoanAdded = $DeductedLoan + $loanArr['balance'];
			//Deducted loan
			$LoanBalance = $DeductedLoan - $loanArr['balance'];
			
			$LoanBalance = abs($LoanBalance);//make it positive if ever it is negative
			$Update1 = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action) 
							VALUES('$empid', 'newVale', '$newValeBalance', '$LoanAdded', 'loaned', '$date', '$time', '1')";
			$Update2 = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action) 
							VALUES('$empid', 'newVale', '$LoanBalance', '$DeductedLoan', 'deducted', '$date', '$time', '0')";
			mysql_query($Update1);
			mysql_query($Update2);
		}
		else//Employee has no newvale balance but added newvale in the payroll
		{
			Print "<script>console.log('newValeAdded2')</script>";
			$loanArr = mysql_fetch_assoc($Query);
			//Deducted loan
			$LoanAdded = $DeductedLoan;
			
			$Update1 = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action) 
							VALUES('$empid', 'newVale', '$DeductedLoan', '$DeductedLoan', 'loaned', '$date', '$time', '1')";
			$Update2 = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action) 
							VALUES('$empid', 'newVale', '0', '$DeductedLoan', 'deducted', '$date', '$time', '0')";
			mysql_query($Update1);
			mysql_query($Update2);
		}
		

		$loan_newVale = $LoanAdded;
	}
	else if(!empty($_POST['newVale']))//if employee didnot add any new vales but have previous newvale
	{
		$loanChecker = mysql_query("SELECT * FROM loans WHERE date='$date' AND empid='$empid' AND type='newVale'");
		if(mysql_num_rows($loanChecker) > 0)
		{
			mysql_query("DELETE FROM loans WHERE date='$date' AND empid='$empid' AND type='newVale'");
		}
		Print "<script>console.log('newVale')</script>";
		$loan_newVale = $_POST['newVale'];
		$Update = "INSERT INTO loans(empid, type, balance, amount, remarks, date, time, action) 
							VALUES('$empid', 'newVale', '0', '$loan_newVale', 'deducted', '$date', '$time', '0')";
		mysql_query($Update);
	}
	if(!empty($_POST['oldValeDeduct']))//if SSS loan textbox in payroll has value
	{
		Print "<script>console.log('oldValeDeduct')</script>";
		loanQuery('oldVale', $empid, $loan_oldVale);
	}
	$compLoan = $loan_sss + $loan_pagibig + $loan_oldVale + $loan_newVale;

//Tools Computation -------------------------------------------------------------------
	$tools_paid = 0;
	$outStandingBalance = 0;
	if(!empty($_POST['toolname'][0]))
	{
		Print "<script>console.log('lala: ".$_POST['toolname']."')</script>";
		$toolNum = count($_POST['toolname']);
		
		$totalToolCost = 0;
		$BoolTool = false; //Boolean to if there is more than 2 tools
		Print "<script>console.log('toolNum: ".$toolNum."')</script>";
		if($toolNum > 1)
		{
			Print "<script>console.log('More')</script>";
			$toolQuery = "INSERT INTO tools(empid, tools, cost, date) VALUES";
			for($counter = 0; $counter < $toolNum; $counter++)
			{
				$toolname = $_POST['toolname'][$counter];
				$toolprice = $_POST['toolprice'][$counter];
				$totalToolCost += $toolprice;//gets the total tool cost

				if($toolQuery != "INSERT INTO tools(empid, tools, cost, date) VALUES")
				{
					$toolQuery .= ",";//Add comma after every additional values
				}
				$toolQuery .= "('$empid',
								'$toolname',
								'$toolprice',
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
		else if(!empty($_POST['toolprice']) && !empty($_POST['toolname']))
		{
			Print "<script>console.log('One')</script>";
			$BoolTool = true;//True to query the update 
			$toolname = $_POST['toolname'];
			$toolprice = $_POST['toolprice'];

			//Print "<script>console.log('toolname: ".$toolname."')</script>";
			//Print "<script>console.log('toolprice: ".$toolprice."')</script>";
			//Print "<script>console.log('amountToPay: ".$_POST["amountToPay"]."')</script>";

			if(!empty($_POST['previousPayable']))
			{
				//Gets the new Previous payable
				if(($_POST['previousPayable'] + $toolprice) != $_POST['amountToPay'])
				{
					$outStandingBalance = ($_POST['previousPayable'] + $toolprice) - $_POST['amountToPay'];
					$outStandingBalance = abs($outStandingBalance);
				}
			}
			else if($toolprice != $_POST['amountToPay'])
			{
				$outStandingBalance = $toolprice - $_POST['amountToPay'];
				$outStandingBalance = abs($outStandingBalance);
			}
			$toolQuery = "INSERT INTO tools(empid, tools, cost, date) VALUES(	'$empid',
																				'$toolname',
																				'$toolprice',
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
	else//if they did not input any tools when they get back
	{
		mysql_query("DELETE FROM tools WHERE empid='$empid' AND date = '$date'");
	}
	

// --------------------------------- GRAND TOTAL -----------------------------------------

//Grand Total Computation
	$GrandTotal = ((($dailyRate * $overallWorkDays) + abs($compAllowance) + abs($compND) + abs($compOT) + abs($cola) + abs($addHoliday)) - abs($compDeductions) - abs($compLoan) - abs($tools_paid)); 

	$ast = ($dailyRate * $overallWorkDays);
	$bnd = abs($compAllowance) + abs($compND) + abs($compOT) + abs($cola) + abs($addHoliday) + abs($compSunday);
	$crd = abs($compDeductions) + abs($compLoan) + abs($tools_paid);
// Print "<script>console.log('1: ".$ast."')</script>";
// Print "<script>console.log('2: ".$bnd."')</script>";
// Print "<script>console.log('compAllowance: ".$compAllowance."')</script>";
// Print "<script>console.log('compND: ".$compND."')</script>";
// Print "<script>console.log('compOT: ".$compOT."')</script>";
// Print "<script>console.log('cola: ".$cola."')</script>";
// Print "<script>console.log('compSunday: ".$compSunday."')</script>";
// Print "<script>console.log('addHoliday: ".$addHoliday."')</script>";

// Print "<script>console.log('3: ".$crd."')</script>";


	$query = "INSERT INTO payroll(	empid,
									num_days,
									overtime,
									ot_num,
									ot_comp,
									allow,
									comp_allowance,
									x_allowance,
									cola,
									sunday_rate,
									sunday_hrs,
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
									tools,
									tools_paid,
									tools_outstanding,
									total_salary,
									date,
									loan_sss,
									loan_pagibig,
									new_vale,
									old_vale) VALUES(	'$empid',
														'$overallWorkDays',
														'$OtRatePerHour',
														'$totalOT',
														'$compOT',
														'$dailyAllowance',
														'$compAllowance',
														'$extraAllowance',
														'$cola',
														'$SundayRatePerHour',
														'$sunWorkHrs',
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
														'$date',
														'$tools_paid',
														'$outStandingBalance',
														'$GrandTotal',
														'$date',
														'$loan_sss',
														'$loan_pagibig',
														'$loan_newVale',
														'$loan_oldVale')";
	$updateQuery = "UPDATE payroll SET	
									num_days = '$overallWorkDays',
									overtime = '$OtRatePerHour',
									ot_num = '$totalOT',
									ot_comp = '$compOT',
									allow = '$dailyAllowance',
									comp_allowance = '$compAllowance',
									x_allowance = '$extraAllowance',
									cola = '$cola',
									sunday_rate = '$SundayRatePerHour',
									sunday_hrs = '$sunWorkHrs',
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
									tools = '$date',
									tools_paid = '$tools_paid',
									tools_outstanding = '$outStandingBalance',
									total_salary = '$GrandTotal',
									loan_sss = '$loan_sss',
									loan_pagibig = '$loan_pagibig',
									new_vale = '$loan_newVale',
									old_vale = '$loan_oldVale' WHERE empid = '$empid' AND date = '$date'";
	//Print "<script>console.log('".$query."')</script>";
	$mainChecker = mysql_query("SELECT * FROM payroll WHERE empid='$empid' AND date='$date'");
	if(mysql_num_rows($mainChecker) == 0)
	{
		mysql_query($query);
	}					
	else
	{
		mysql_query($updateQuery);
	}
	
	

	// //Nightdiff
	// $_POST['wedNDHrs'];
	// $_POST['thuNDHrs'];
	// $_POST['friNDHrs'];
	// $_POST['satNDHrs'];
	// $_POST['sunNDHrs'];
	// $_POST['monNDHrs'];
	// $_POST['tueNDHrs'];

	// //Allowance
	// $_POST['allowance'];
	// $_POST['OverallAllowance'];
	// $_POST['extra_allowance'];

	$url = "http://localhost/gbic/payroll_computation.php";

	$post_data = array(
		'method' => 'post',
		'empid' => $empid,
		'date' => $date
	);

	$ch = curl_init();

	//URL to submit to
	curl_setopt($ch, CURLOPT_URL, $url);

	//Return output instead of outputting it
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	//Post request
	curl_setopt($ch, CURLOPT_POST, 1);

	//Adding the post variables to the request
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

	//Execute the request and fetch the response to check for errors
	$output = curl_exec($ch);

	if($output === false) {
		echo "cURL Error: ".curl_error($ch);
	}

	//close and free up the curl handle
	curl_close($ch);

	//Display the row output
	print_r($output);
?>



























