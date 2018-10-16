<?php
	include('directives/session.php');
	include_once('directives/db.php');

	$date = strftime("%B %d, %Y");//Get the current date
	//Gets the admin who change the site and position
	$user = $_SESSION['user_logged_in'];
	$admin = "SELECT * FROM administrator WHERE username = '$user'";
	$adminQuery = mysql_query($admin);
	$adminArr = mysql_fetch_assoc($adminQuery);
	$adminName = $adminArr['firstname']." ".$adminArr['lastname'];

	$empid = $_GET['empid'];
	if($_POST['lastname'] != null)
	{
		$lastname = mysql_real_escape_string($_POST['lastname']);
		mysql_query("UPDATE employee SET lastname = '$lastname' WHERE empid = '$empid'") or die (mysql_error());
	}
	if($_POST['firstname'] != null)
	{
		$firstname = mysql_real_escape_string($_POST['firstname']);
		mysql_query("UPDATE employee SET firstname = '$firstname' WHERE empid = '$empid'") or die (mysql_error());
	}
	if($_POST['address'] != null)
	{
		$address = mysql_real_escape_string($_POST['address']);
		mysql_query("UPDATE employee SET address = '$address' WHERE empid = '$empid'") or die (mysql_error());
	}
	if($_POST['contactnum'] != null)
	{
		$contactnum = mysql_real_escape_string($_POST['contactnum']);
		mysql_query("UPDATE employee SET contactnum = '$contactnum' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['dob'] != null)
	{
		$dob = mysql_real_escape_string($_POST['dob']);
		mysql_query("UPDATE employee SET dob = '$dob' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['civilstatus'] != null)
	{
		$civilstatus = mysql_real_escape_string($_POST['civilstatus']);
		mysql_query("UPDATE employee SET civilstatus = '$civilstatus' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['datehired'] != null)
	{
		$datehired = mysql_real_escape_string($_POST['datehired']);
		mysql_query("UPDATE employee SET datehired = '$datehired' WHERE empid = '$empid'") or die (mysql_error());	
		// Update all date of hire base on the random number
		$explodeEmpid = explode('-', $empid);
		$randomNum = $explodeEmpid[0];
		$explodeDateHire = explode(' ', $datehired);
		$year = $explodeEmpid[1];
		$newEmpid = $year."-".$randomNum;

		// Query for loans
		mysql_query("UPDATE loans SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());	
		// Query for attendance
		mysql_query("UPDATE attendance SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());	
		// Query for awol employee
		mysql_query("UPDATE awol_employees SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());
		// Query for employee	
		mysql_query("UPDATE employee SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());
		// Query for payroll
		mysql_query("UPDATE payroll SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());
		// Query for payroll_adjustment
		mysql_query("UPDATE payroll_adjustment SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());	
		// Query for position_history
		mysql_query("UPDATE position_history SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());	
		// Query for site_history
		mysql_query("UPDATE site_history SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());
		// Query for thirteenth_pay
		mysql_query("UPDATE thirteenth_pay SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());	
		// Query for tools
		mysql_query("UPDATE tools SET empid = '$newEmpid' WHERE empid LIKE '%$randomNum'") or die (mysql_error());		
	}
	if($_POST['position'] != null)
	{
		$position = mysql_real_escape_string($_POST['position']);
		mysql_query("UPDATE employee SET position = '$position' WHERE empid = '$empid'") or die (mysql_error());
		//Set historical for this change
		mysql_query("INSERT INTO position_history(empid, position, date, admin) VALUES('$empid', '$position', '$date', '$adminName')") or die (mysql_error());	
	}
	if($_POST['site'] != null)
	{
		$site = mysql_real_escape_string($_POST['site']);
		mysql_query("UPDATE employee SET site = '$site' WHERE empid = '$empid'") or die (mysql_error());	
		//Set historical for this change
		mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES('$empid', '$site', '$date', '$adminName')") or die (mysql_error());
	}
	
	if($_POST['rate'] != null)
	{
		$rate = mysql_real_escape_string($_POST['rate']);
		mysql_query("UPDATE employee SET rate = '$rate' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['allowance'] != null)
	{
		$allowance = mysql_real_escape_string($_POST['allowance']);
		mysql_query("UPDATE employee SET allowance = '$allowance' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['xAllowanceDaily'] != null)
	{
		$xAllowanceDaily = mysql_real_escape_string($_POST['xAllowanceDaily']);
		mysql_query("UPDATE employee SET x_allow_daily = '$xAllowanceDaily' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['xAllowanceWeekly'] != null)
	{
		$xAllowanceWeekly = mysql_real_escape_string($_POST['xAllowanceWeekly']);
		mysql_query("UPDATE employee SET x_allow_weekly = '$xAllowanceWeekly' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['cola'] != null)
	{
		$cola = mysql_real_escape_string($_POST['cola']);
		mysql_query("UPDATE employee SET cola = '$cola' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['insurance'] != null)
	{
		$insurance = mysql_real_escape_string($_POST['insurance']);
		mysql_query("UPDATE employee SET insurance = '$insurance' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['bank'] != null)
	{
		$bank = mysql_real_escape_string($_POST['bank']);
		mysql_query("UPDATE employee SET bank = '$bank' WHERE empid = '$empid'") or die (mysql_error());	
	}


	$sssEE = mysql_real_escape_string($_POST['sssEE']);
	$sssER = mysql_real_escape_string($_POST['sssER']);
	if(isset($_POST['sssCheckbox']))
	{
		if($_POST['sssEE'] != null || $_POST['sssER'] != null)
		{
			if($_POST['sssEE'] != null && $_POST['sssER'] == null)
			{
				mysql_query("UPDATE employee SET sss = '$sssEE' WHERE empid = '$empid'") or die (mysql_error());	
			}
			
			else if($_POST['sssEE'] == null && $_POST['sssER'] != null) 
			{
				mysql_query("UPDATE employee SET sss_er = '$sssER' WHERE empid = '$empid'") or die (mysql_error());
			}
			else 
			{
				mysql_query("UPDATE employee SET sss = '$sssEE', sss_er = '$sssER' WHERE empid = '$empid'") or die (mysql_error());	
			}
		}
		
	}
	else
		mysql_query("UPDATE employee SET sss = '0', sss_er = '0' WHERE empid = '$empid'") or die (mysql_error());	

	$philhealthEE = mysql_real_escape_string($_POST['philhealthEE']);
	$philhealthER = mysql_real_escape_string($_POST['philhealthER']);
	if(isset($_POST['philhealthCheckbox']))
	{
		if($_POST['philhealthEE'] != null || $_POST['philhealthER'] != null)//if they inputted a value
		{
			if($_POST['philhealthEE'] != null && $_POST['philhealthER'] == null)
					mysql_query("UPDATE employee SET philhealth = '$philhealthEE' WHERE empid = '$empid'") or die (mysql_error());	
			else if($_POST['philhealthEE'] == null && $_POST['philhealthER'] != null)
					mysql_query("UPDATE employee SET philhealth_er = '$philhealthER' WHERE empid = '$empid'") or die (mysql_error());	
			else
				mysql_query("UPDATE employee SET philhealth = '$philhealthEE' ,philhealth_er = '$philhealthER' WHERE empid = '$empid'") or die (mysql_error());	
		}
		//if not then dont do anything
	}
	else
		mysql_query("UPDATE employee SET philhealth = '0', philhealth_er = '0' WHERE empid = '$empid'") or die (mysql_error());	

	$pagibigEE = mysql_real_escape_string($_POST['pagibigEE']);
	$pagibigER = mysql_real_escape_string($_POST['pagibigER']);
	if(isset($_POST['pagibigCheckbox']))
	{
		if($_POST['pagibigEE'] != null || $_POST['pagibigER'] != null) 
		{
			if($_POST['pagibigEE'] != null && $_POST['pagibigER'] == null)
			mysql_query("UPDATE employee SET pagibig = '$pagibigEE' WHERE empid = '$empid'") or die (mysql_error());	
			else if($_POST['pagibigEE'] == null && $_POST['pagibigER'] != null)
				mysql_query("UPDATE employee SET pagibig_er = '$pagibigER' WHERE empid = '$empid'") or die (mysql_error());
			else
				mysql_query("UPDATE employee SET pagibig = '$pagibigEE', pagibig_er = '$pagibigER' WHERE empid = '$empid'") or die (mysql_error());	
		}
		//if not then dont do anything
	}
	else{
		mysql_query("UPDATE employee SET pagibig = '0', pagibig_er = '0' WHERE empid = '$empid'") or die (mysql_error());	
	}


	if($_POST['emergencyContact'] != null)
	{
		$characterReference = mysql_real_escape_string($_POST['emergencyContact']);
		mysql_query("UPDATE employee SET emergency = '$characterReference' WHERE empid = '$empid'") or die (mysql_error());	
	}
	if($_POST['characterReference'] != null)
	{
		$characterReference = mysql_real_escape_string($_POST['characterReference']);
		mysql_query("UPDATE employee SET reference = '$characterReference' WHERE empid = '$empid'") or die (mysql_error());	
	}
	//header('location: editEmployee.php?empid='.$empid);
//employee document checker
	$checker = "SELECT sss, philhealth, pagibig FROM employee WHERE empid = '$empid' AND employment_status = '1' ";
	$checker_query = mysql_query($checker) or die (mysql_error());
	$row = mysql_fetch_assoc($checker_query);
	
	if(isset($_POST['sssCheckbox']) && isset($_POST['philhealthCheckbox']) && isset($_POST['pagibigCheckbox']))
	{	
		$not_complete = "UPDATE employee SET complete_doc = '1' WHERE empid = '$empid'";
		mysql_query($not_complete) or die (mysql_error());
	}
	else
	{
		$complete = "UPDATE employee SET complete_doc = '0' WHERE empid = '$empid'";
		mysql_query($complete) or die (mysql_error());
	}
	Print "<script>window.location.assign('editEmployee.php?empid=".$empid."')</script>";
?>









