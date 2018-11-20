<?php
//Checks and delete the loans if ever there is already a deducted loan
	include('directives/session.php');
	require_once('directives/db.php');

	$empid = $_GET['e'];
	$date = (isset($_SESSION['payrollDate']) ? $_SESSION['payrollDate'] : strftime("%B %d, %Y")); // Gets the payroll date if admin didn't finish the payroll for the week
	// $date = "November 07, 2018";
	// $date = "October 10, 2018";
	// $date = "July 11, 2018";


	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);
	$empArr = mysql_fetch_assoc($empQuery);
	$position = $empArr['position'];
	$site = $empArr['site'];

	$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND date = '$date' AND action = '0'";
	$loansQuery = mysql_query($loanChecker);

	// $toolsChecker = "SELECT * FROM tools WHERE empid = '$empid' AND date = '$date'";
	// $toolsQuery = mysql_query($toolsChecker);

	if(mysql_num_rows($loansQuery) != 0)// Delete Loans
		mysql_query("DELETE FROM loans WHERE empid = '$empid' AND date = '$date'");
	// if(mysql_num_rows($toolsQuery) != 0)// Delete tools
	// 	mysql_query("DELETE FROM tools WHERE empid = '$empid' AND date = '$date'");
// Delete payroll
	mysql_query("DELETE FROM payroll WHERE empid = '$empid' AND date = '$date'");
// Delete Adjusted attendance
	$adjustedPayrollAttendance = "SELECT * FROM payroll_adjustment WHERE empid = '$empid' AND payroll_date = '$date'";

	$adjAttQuery = mysql_query($adjustedPayrollAttendance);
	// Print "<script>alert('".mysql_num_rows($adjAttQuery)."')</script>";
	if(mysql_num_rows($adjAttQuery) != 0)
	{
		// // Print "<script>alert('yow')</script>";
		// $adjustedArr = mysql_fetch_assoc($adjAttQuery);

		// $adjustedDates = explode('+',$adjustedArr['dates']);
		// $appendAdjQuery = "(date = ";

		// $adjDatesNum = count($adjustedDates);
		// for($counter = 0; $counter < $adjDatesNum; $counter++)
		// {
		// 	if($appendAdjQuery != "(date = ")
		// 		$appendAdjQuery .= " OR date = ";
		// 	$appendAdjQuery .= "'".$adjustedDates[$counter]."'";
			
		// }
		// $appendAdjQuery .= ")";
		// // Print $appendAdjQuery;
		// mysql_query("DELETE FROM attendance WHERE empid = '$empid' AND $appendAdjQuery");
		// mysql_query("DELETE FROM payroll_adjustment WHERE empid = '$empid' AND payroll_date = '$date'");
	}
		
	Print "<script>window.location.assign('payroll.php?site=".$site."&position=".$position."&empid=".$empid."')</script>";
?>
