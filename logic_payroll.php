<?php
	include('directives/db.php');
	include('directives/session.php');
	//Print "<script>console.log('".$overtimeRate1."')</script>";

	//$date = strftime("%B %d, %Y");
	$date = "October 24, 2017";
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
		Print "<script>console.log('compSunday: ".$compSunday."')</script>";
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
	Print "<script>console.log('overallWorkDays: ".$overallWorkDays."')</script>";

//Computation for OVER TIME -----------------------------------------------------------
	$compOT = 0;
	$totalOT = 0;
	$OtRatePerHour = (($dailyRate + ($dailyRate * .25))/8);//Overtime Hourly Rate
	if(!empty($_POST['totalOverTime']))
	{
		$totalOT = $_POST['totalOverTime'];//Total Overtime by employee

		$compOT = $totalOT * $OtRatePerHour;//Computed Overtime
	}
	Print "<script>console.log('compOT: ".$compOT."')</script>";

//Computation for Night Differential --------------------------------------------------
	$compND = 0;
	$NdRatePerHour = (($dailyRate / 8)*.10);//NightDiff Hourly Rate
	$totalND = 0;
	if(!empty($_POST['totalNightDiff']))
	{
		$totalND = $_POST['totalNightDiff'];

		$compND = $totalND * $NdRatePerHour;//Computed Night Differential
	}
	Print "<script>console.log('compND: ".$compND."')</script>";

//Computation for Allowance -----------------------------------------------------------
	$dailyAllowance = $_POST['allowance'];// for database use
	$extraAllowance = $_POST['extra_allowance'];

	$allowance = $_POST['OverallAllowance'] + $extraAllowance;
	$compAllowance = $daysAttended * $allowance;
	Print "<script>console.log('compAllowance: ".$compAllowance."')</script>";

//Computation for Deductions ----------------------------------------------------------
	$tax = $_POST['tax'];
	$sss = $_POST['sss'];
	$pagibig = $_POST['pagibig'];
	$philhealth = $_POST['philhealth'];
	$compDeductions = $tax + $sss + $pagibig + $philhealth;
	Print "<script>console.log('compDeductions: ".$compDeductions."')</script>";

//COLA incrementation -----------------------------------------------------------------
	$cola = 0;
	if(!empty($_POST['COLA']))
	{
		$cola = $_POST['COLA'];
	}

//Computation for Regular Holiday ----------------------------------------------------- Incomplete
	$regHolidayInc = $dailyRate * 2;


//Special Holiday --------------------------------------------------------------------- Incomplete
	$speHolidayInc = (($dailyRate * .30) + $dailyRate);


//Loans deduction --------------------------------------------------------------------- Incomplete
//*query to loans table the deduction
	$loan_sss = $_POST['sssDeduct'];
	$loan_pagibig = $_POST['pagibigDeduct'];
	$loan_oldVale = 0;
	if(isset($_POST['oldValeDeduct']))
		$loan_oldVale = $_POST['oldValeDeduct'];
	$loan_newVale = $_POST['newValeAdded'];

	$compLoan = $loan_sss + $loan_pagibig + $loan_oldVale + $loan_newVale;

//Tools Computation -------------------------------------------------------------------

	$toolNum = count($_POST['toolname']);
	$outStandingBalance = 0;
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
		$BoolTool = true;//True to query the update 
		$toolname = $_POST['toolname'][0];
		$toolprice = $_POST['toolprice'][0];

		Print "<script>console.log('toolname: ".$toolname."')</script>";
		Print "<script>console.log('toolprice: ".$toolprice."')</script>";
		Print "<script>console.log('amountToPay: ".$_POST["amountToPay"]."')</script>";

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


// --------------------------------- GRAND TOTAL -----------------------------------------

//Grand Total Computation
	$GrandTotal = ((($dailyRate * $overallWorkDays) + $compAllowance + $compND + $compOT + $cola) - $compDeductions - $compLoan - $tools_paid); 
//Print "<script>console.log('SubTotal: ".$SubTotal."')</script>";


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
														'$daysAttended',
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
	//Print "<script>console.log('".$query."')</script>";
	$mainChecker = mysql_query("SELECT * FROM payroll WHERE empid='$empid' AND date='$date'");
	if(mysql_num_rows($mainChecker) == 0)
	{
		mysql_query($query);
	}					
	
	//holiday
	// $_POST['holidayName[]'];
	// $_POST['holidayType[]'];
	// $_POST['holidayDate[]'];

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



























