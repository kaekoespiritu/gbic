<?php
	include('directives/session.php');
	include('directives/db.php');
	$time = strftime("%X");//TIME

	// $date = strftime("%B %d, %Y");
	$date = "May 2, 2018";
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
		$sundayBool = true;
		$sunWorkHrs = $_POST['sunWorkHrs'];
		if($WorkHrsArr != "")
			$WorkHrsArr .= ","; 
		$WorkHrsArr .= $sunWorkHrs;

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
	$sunday_Att = 0;//Preset the sunday attendance to filter out the overal to the sunday
	foreach($workHrs as $hrsCheck)
	{
		if($sundayBool)
		{
			if($hrsCheck < 8)
			{
				$overallWorkDays = ($hrsCheck / 8) + $overallWorkDays;
			}
			else
			{
				// $overallWorkDays++;
				$sunday_Att = 1;
			}
			$sundayBool = false;
		}
		else if($hrsCheck < 8)
		{
			$overallWorkDays = ($hrsCheck / 8) + $overallWorkDays;
		}
		else
		{
			$overallWorkDays++;

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

//COLA incrementation -----------------------------------------------------------------dito
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
			if($holidayNum == 1)//if there is only one Holiday in the week
			{	
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
						// $overallWorkDays++;//increment workdays 
						if($holidayType == "special")//Special Holiday
							$speHolNum++;
						else//Regular Holiday
							$regHolNum++;
					}
				}

				$holidayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$holidayDate'");
				$holidayArr = mysql_fetch_assoc($holidayChecker);
				if($holidayArr['attendance'] == 2)
				{
					if($holidayType == "special")//Special Holiday
					{
						$addHoliday = $speHolidayInc;
						// $overallWorkDays++;//increment workdays 
						$speHolNum++;
					}
					else//Regular Holiday
					{
						$addHoliday = $regHolidayInc;
						// $overallWorkDays++;//increment workdays 
						$regHolNum++;
					}
				}
			}
	// ----------------------------------------
			else if($holidayNum > 1)// if there is more than 1 holidays in the week
			{
				for($count = 0; $count < $holidayNum; $count++)
				{
					$boolHoliday = true;//if employee did not appear to work the day before holiday

					if($boolHoliday)
					{
						$holidayName = $_POST['holidayName'][$count];
						$holidayType = $_POST['holidayType'][$count];
						$holidayDate = $_POST['holidayDate'][$count];
						$holidayChecker = mysql_query("SELECT * FROM attendance WHERE empid = '$empid' AND date = '$holidayDate'") or die (mysql_error());
						$holdayArr = mysql_fetch_assoc($holidayChecker);
						if($holdayArr['attendance'] == '2')
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
	}
	
//Computation for Allowance -----------------------------------------------------------

	$dailyAllowance = 0;
	$extraAllowance = 0;

	if(!empty($_POST['allowance']))
		$dailyAllowance = $_POST['allowance'];// for database use
	if(!empty($_POST['extra_allowance']))
		$extraAllowance = $_POST['extra_allowance'];

	$compAllowance = (($overallWorkDays*$dailyAllowance)  + $extraAllowance);
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
	if(!empty($_POST['toolname'][0]))
	{
		$toolNum = count($_POST['toolname']);
		
		$totalToolCost = 0;
		$BoolTool = false; //Boolean to if there is more than 2 tools
		if($toolNum > 1)
		{
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
		else if(!empty($_POST['toolprice'][0]) && !empty($_POST['toolname'][0]))
		{
			$toolname = $_POST['toolname'][0];
			$toolprice = $_POST['toolprice'][0];

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
				//Print "<script>alert('".$toolprice."')</script>";
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
	$totalAllowance = $overallWorkDays * $dailyAllowance;
	$totalOvertime = $OtRatePerHour * $totalOT;
	$totalRatePerDay = $overallWorkDays * $dailyRate;
	$xAllowance = $extraAllowance;

	$totalCola = $cola * $overallWorkDays;
	$totalEarnings = $totalRegularHolidayRate + $totalSpecialHolidayRate + $totalSundayRate + $totalNightDifferential + $totalAllowance + $totalOvertime + $totalRatePerDay + $xAllowance + $totalCola;
	Print "<script>console.log('payComp - totalRegularHolidayRate: ".abs($totalRegularHolidayRate)." | totalSpecialHolidayRate: ".abs($totalSpecialHolidayRate)." | totalSundayRate: ".abs($totalSundayRate)." | totalNightDifferential: ".$totalNightDifferential." | totalAllowance: ".$totalAllowance." | totalOvertime: ".$totalOvertime." | totalRatePerDay: ".$totalRatePerDay." | xAllowance: ".$xAllowance." | totalCola: ".$totalCola."')</script>";

	$contributions = $pagibig + $philhealth + $sss + $tax;

	$totalLoans = $loan_pagibig + $loan_sss + $loan_oldVale + $loan_newVale;
	Print "<script>console.log('toDB - totalEarnings: ".abs($totalEarnings)." | contributions: ".abs($contributions)." | totalLoans: ".abs($totalLoans)." | tools_paid: ".abs($tools_paid)."')</script>";
	$grandTotal = abs($totalEarnings) - abs($contributions) - abs($totalLoans) - abs($tools_paid);
	$grandTotal = abs($grandTotal);




	$query = "INSERT INTO payroll(	empid,
									rate,
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
														'$compAllowance',
														'$extraAllowance',
														'$cola',
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
									comp_allowance = '$compAllowance',
									x_allowance = '$extraAllowance',
									cola = '$cola',
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



























