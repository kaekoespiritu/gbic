<?php
	include('directives/session.php');
	include('directives/db.php');

	$date = $_GET['date'];
	$empid = $_GET['empid'];

	//Get the original value of earnings then deduct it to the total salary
	$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$date'";
	$payrollQuery = mysql_query($payroll);
	$payrollArr = mysql_fetch_assoc($payrollQuery);

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$employeeQuery = mysql_query($employee);
	$empArr = mysql_fetch_assoc($employeeQuery);

	//Total Salary
	$totalSalary = $payrollArr['total_salary'];

	//NumDays
	$ratePerDaySub = $payrollArr['num_days'];//for computation
	$subTotalRatePerDay = $ratePerDaySub * $empArr['rate'];
	$totalRatePerDay = $subTotalRatePerDay;//for the Subtotal of Earnings

	//Allowance
	$allowDays =  $payrollArr['allow_days'];
	$subTotalAllowance = $empArr['allowance'] * $allowDays;
	$totalAllowance = $subTotalAllowance;//for the Subtotal of Earnings

	//xAllowance
	$xAllowance = 0;
	if($payrollArr['x_allowance'] != 0)
		$xAllowance = $payrollArr['x_allowance'];

	//xAllow Daily
	$xAllowanceDaily = 0;
	$overallXAllowDaily = 0;
	if($payrollArr['x_allow_daily'] != 0)
		$overallXAllowDaily = $allowDays * $payrollArr['x_allow_daily'];

	//xAllow Weekly
	$xAllowanceWeekly = 0;
	if($payrollArr['x_allow_weekly'] != 0)
		$xAllowanceWeekly = $payrollArr['x_allow_weekly'];
	
	//Over time
	$subTotalOvertime = $payrollArr['ot_num']*$payrollArr['overtime'];
	$totalOvertime = $subTotalOvertime;//for the Subtotal of Earnings

	//Nightdiff 
	$subTotalNightDifferential = $payrollArr['nightdiff_rate'] * $payrollArr['nightdiff_num'];
	$totalNightDifferential = $subTotalNightDifferential;//for the Subtotal of Earnings

	//Sunday rate
	$subTotalSundayRate = $payrollArr['sunday_rate'] * $payrollArr['sunday_hrs'];
	$totalSundayRate = $subTotalSundayRate;//for the Subtotal of Earnings
	
	//Regular holiday
	if($payrollArr['reg_holiday_num'] > 1)
	{
		$holidayRegChecker = "SELECT * FROM holiday AS h INNER JOIN attendance AS a ON h.date = a.date WHERE a.empid = '$empid' AND a.attendance = '2' AND h.type = 'regular'";
		$holidayRegQuery = mysql_query($holidayRegChecker);
		$regHolidayNum = mysql_num_rows($holidayRegQuery);
	}
	else if($payrollArr['reg_holiday_num'] == 1)
	{
		$regHolidayNum = 1;
	}
	else
	{
		$regHolidayNum = 0;
	}
	$subTotalRegularHolidayRate = ($payrollArr['reg_holiday_num'] * $payrollArr['reg_holiday']) ;
	$totalRegularHolidayRate = $subTotalRegularHolidayRate;//for the Subtotal of Earnings
	
	//Special Holiday
	if($payrollArr['spe_holiday_num'] > 0)
		$subTotalSpecialHolidayRate = ($payrollArr['spe_holiday_num'] * $payrollArr['spe_holiday']);
	else
		$subTotalSpecialHolidayRate = 0;
	$totalSpecialHolidayRate = $subTotalSpecialHolidayRate;//for the Subtotal of Earnings

	//Cola
	$totalCola = $payrollArr['cola'];

	$totalOldEarnings = $totalRegularHolidayRate + $totalSpecialHolidayRate + $totalSundayRate + $totalNightDifferential + $totalAllowance + $totalOvertime + $totalRatePerDay + $xAllowance + $totalCola + $overallXAllowDaily + $xAllowanceWeekly;
	$totalOldSalary = $totalSalary - $totalOldEarnings;// Deduct the old

//Get the New value of earnings

	$rateDays = mysql_real_escape_string($_POST['rateDays']);
	$otDays = mysql_real_escape_string($_POST['otDays']);
	$ndDays = mysql_real_escape_string($_POST['ndDays']);
	$sunDays = mysql_real_escape_string($_POST['sunDays']);
	$regHolDays = mysql_real_escape_string($_POST['regHolDays']);
	$speHolDays = mysql_real_escape_string($_POST['speHolDays']);
	$allowDays = mysql_real_escape_string($_POST['allowDays']);

	//NumDays
	$ratePerDaySub = $rateDays;//for computation
	$subTotalRatePerDay = $ratePerDaySub * $empArr['rate'];
	$totalRatePerDay = $subTotalRatePerDay;//for the Subtotal of Earnings

	//Allowance
	$allowDays =  $allowDays;
	$subTotalAllowance = $empArr['allowance'] * $allowDays;
	$totalAllowance = $subTotalAllowance;//for the Subtotal of Earnings

	//xAllowance -- 
	$xAllowance = 0;
	if($payrollArr['x_allowance'] != 0)
		$xAllowance = $payrollArr['x_allowance'];

	//xAllow Daily -- 
	$xAllowanceDaily = 0;
	$overallXAllowDaily = 0;
	if($payrollArr['x_allow_daily'] != 0)
		$overallXAllowDaily = $allowDays * $payrollArr['x_allow_daily'];

	//xAllow Weekly -- 
	$xAllowanceWeekly = 0;
	if($payrollArr['x_allow_weekly'] != 0)
		$xAllowanceWeekly = $payrollArr['x_allow_weekly'];
	
	//Over time
	$subTotalOvertime = $otDays * $payrollArr['overtime'];
	$totalOvertime = $subTotalOvertime;//for the Subtotal of Earnings

	//Nightdiff 
	$subTotalNightDifferential = $payrollArr['nightdiff_rate'] * $ndDays;
	$totalNightDifferential = $subTotalNightDifferential;//for the Subtotal of Earnings

	//Sunday rate
	$subTotalSundayRate = $payrollArr['sunday_rate'] * $sunDays;
	$totalSundayRate = $subTotalSundayRate;//for the Subtotal of Earnings
	$sundayAtt = ($sunDays > 0 ? 1 : 0);
	
	$addedHoliday = 0;
	//Regular holiday
	$regHolidayNum = $regHolDays;
	
	$subTotalRegularHolidayRate = ($regHolDays * $payrollArr['reg_holiday']) ;
	$totalRegularHolidayRate = $subTotalRegularHolidayRate;//for the Subtotal of Earnings
	$addedHoliday += $subTotalRegularHolidayRate;
	
	//Special Holiday
	$subTotalSpecialHolidayRate = ($speHolDays * $payrollArr['spe_holiday']);
	$totalSpecialHolidayRate = $subTotalSpecialHolidayRate;//for the Subtotal of Earnings
	$addedHoliday += $totalSpecialHolidayRate;

	//Cola
	$totalCola = $payrollArr['cola'];

	$totalNewEarnings = $totalRegularHolidayRate + $totalSpecialHolidayRate + $totalSundayRate + $totalNightDifferential + $totalAllowance + $totalOvertime + $totalRatePerDay + $xAllowance + $totalCola + $overallXAllowDaily + $xAllowanceWeekly;

	$totalNewSalary = $totalOldSalary + $totalNewEarnings;// Add the new salary

	echo "<script>console.log('".$totalOldEarnings." | ".$totalNewEarnings."')</script>";
	$updatePayroll = "UPDATE payroll SET 	num_days = '$rateDays', 
											ot_num = '$otDays', 
											ot_comp = '$totalOvertime', 
											sunday_hrs = '$sunDays', 
											sunday_att = '$sundayAtt', 
											nightdiff_num = '$ndDays', 
											nightdiff = '$totalNightDifferential', 
											holiday_added = '$addedHoliday',
											spe_holiday_num = '$speHolDays', 
											reg_holiday_num = '$regHolidayNum',
											allow_days = '$allowDays',
											total_salary = '$totalNewSalary' WHERE empid = '$empid' AND date = '$date'";
	mysql_query($updatePayroll);

	//Go back to the computation
	Print "
		<form method='post' action='payroll_computation.php' id='tempForm'>
			<input type='hidden' name='date' value='".$date."'>
			<input type='hidden' name='empid' value='".$empid."'>
		</form>

		<script>
			document.getElementById('tempForm').submit();
		</script>";
?>




















