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
	if($_POST['salary'] != null)
	{
		$salary = mysql_real_escape_string($_POST['salary']);
		mysql_query("UPDATE employee SET salary = '$salary' WHERE empid = '$empid'") or die (mysql_error());	
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


	$sss = mysql_real_escape_string($_POST['sss']);
	if($_POST['sss'] != null)
	{
		if(isset($_POST['sssCheckbox']))
		{
			mysql_query("UPDATE employee SET sss = '$sss' WHERE empid = '$empid'") or die (mysql_error());	
		}
		else
			mysql_query("UPDATE employee SET sss = '0' WHERE empid = '$empid'") or die (mysql_error());	
	}
	else if(isset($_POST['sssCheckbox'])){
		if($sss != ""){
			mysql_query("UPDATE employee SET sss = '$sss' WHERE empid = '$empid'") or die (mysql_error());	
		}
	}
	else
		mysql_query("UPDATE employee SET sss = '0' WHERE empid = '$empid'") or die (mysql_error());	

	$philhealth = mysql_real_escape_string($_POST['philhealth']);
	if($_POST['philhealth'] != null)
	{	
		if(isset($_POST['philhealthCheckbox']))
			mysql_query("UPDATE employee SET philhealth = '$philhealth' WHERE empid = '$empid'") or die (mysql_error());	
		else
			mysql_query("UPDATE employee SET philhealth = '0' WHERE empid = '$empid'") or die (mysql_error());	
	}
	else if(isset($_POST['philhealthCheckbox'])){
		if($philhealth != ""){
			mysql_query("UPDATE employee SET philhealth = '$philhealth' WHERE empid = '$empid'") or die (mysql_error());	
		}
	}
	else
		mysql_query("UPDATE employee SET philhealth = '0' WHERE empid = '$empid'") or die (mysql_error());	

	$pagibig = mysql_real_escape_string($_POST['pagibig']);
	if($_POST['pagibig'] != null)
	{
		if(isset($_POST['pagibigCheckbox']))
		{
			mysql_query("UPDATE employee SET pagibig = '$pagibig' WHERE empid = '$empid'") or die (mysql_error());	
		}
		else
		{	
			mysql_query("UPDATE employee SET pagibig = '0' WHERE empid = '$empid'") or die (mysql_error());	
		}
	}
	else if(isset($_POST['pagibigCheckbox'])){
		if($pagibig != ""){
			mysql_query("UPDATE employee SET pagibig = '$pagibig' WHERE empid = '$empid'") or die (mysql_error());
		}
	}
	else{
		mysql_query("UPDATE employee SET pagibig = '0' WHERE empid = '$empid'") or die (mysql_error());	
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
	
	if($row['sss'] == 0 && $row['philhealth'] == 0 && $row['pagibig'] == 0)
	{	
		$not_complete = "UPDATE employee SET complete_doc = '0'";
		mysql_query($not_complete) or die (mysql_error());
	}
	else
	{
		$complete = "UPDATE employee SET complete_doc = '1'";
		mysql_query($complete) or die (mysql_error());
	}
	Print "<script>window.location.assign('editEmployee.php?empid=".$empid."')</script>";
?>









