<?php
	include('directives/session.php');
	require_once('directives/db.php');

	$user = $_POST['adminUser'];

	$restrictionSet = "";
	$restrictionBool = false;
	//Employee Tab
	if(isset($_POST['res_listOfEmployeesEdit']))
	{
		$restrictionSet .= "1";
		$restrictionBool = true;
	}
	if(isset($_POST['res_listOfLoanAppEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "2";
		$restrictionBool = true;
	}
	if(isset($_POST['res_listOfAbsenceEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "3";
		$restrictionBool = true;
	}
	if(isset($_POST['res_listOfSiteManageEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "4";
		$restrictionBool = true;
	}
	//attendance
	if(isset($_POST['res_AttedanceEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "5";
		$restrictionBool = true;
	}
	//Payroll
	if(isset($_POST['res_PayrollEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "6";
		$restrictionBool = true;
	}
	//Reports
	if(isset($_POST['res_EarningsReportEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "7";
		$restrictionBool = true;
	}
	if(isset($_POST['res_ContributionsReportEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "8";
		$restrictionBool = true;
	}
	if(isset($_POST['res_LoansReportEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "9";
		$restrictionBool = true;
	}
	if(isset($_POST['res_AttendanceReportEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "10";
		$restrictionBool = true;
	}
	if(isset($_POST['res_CompanyExpensesReportEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "11";
		$restrictionBool = true;
	}
	//Options
	if(isset($_POST['res_SiteManageEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "12";
		$restrictionBool = true;
	}
	if(isset($_POST['res_PositionManageEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "13";
		$restrictionBool = true;
	}
	if(isset($_POST['res_BankManageEdit']))
	{
		if($restrictionSet != "")
			$restrictionSet .= "-";

		$restrictionSet .= "14";
		$restrictionBool = true;
	}

	if($restrictionBool == false)//if this is true then admin didn't choose any restrictions
	{
		Print "<script>alert('Please choose restrictions for this employee\'s account.')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}

	$updateRestrict = "UPDATE administrator SET restrictions = '$restrictionSet' WHERE username = '$user'";

	if($restrictionBool)
		mysql_query($updateRestrict);

	$admin = "SELECT * FROM administrator WHERE username = '$user'";
	$adminQuery = mysql_query($admin);
	$adminArr = mysql_fetch_assoc($adminQuery);

	$adminName = $adminArr['lastname'].", ".$adminArr['firstname'];
	Print "<script>alert('Successfully updated ".$adminName."\'s restrictions.')</script>";
	Print "<script>window.location.assign('options.php')</script>";

?>