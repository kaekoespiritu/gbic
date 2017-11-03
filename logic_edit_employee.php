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
		mysql_query("UPDATE employee SET address = '$address' WHERE empid = '$empid'");
	}
	if($_POST['contactnum'] != null)
	{
		$contactnum = mysql_real_escape_string($_POST['contactnum']);
		mysql_query("UPDATE employee SET contactnum = '$contactnum' WHERE empid = '$empid'");	
	}
	if($_POST['dob'] != null)
	{
		$dob = mysql_real_escape_string($_POST['dob']);
		mysql_query("UPDATE employee SET dob = '$dob' WHERE empid = '$empid'");	
	}
	if($_POST['civilstatus'] != null)
	{
		$civilstatus = mysql_real_escape_string($_POST['civilstatus']);
		mysql_query("UPDATE employee SET civilstatus = '$civilstatus' WHERE empid = '$empid'");	
	}
	if($_POST['datehired'] != null)
	{
		$datehired = mysql_real_escape_string($_POST['datehired']);
		mysql_query("UPDATE employee SET datehired = '$datehired' WHERE empid = '$empid'");	
	}
	if($_POST['position'] != null)
	{
		$position = mysql_real_escape_string($_POST['position']);
		mysql_query("UPDATE employee SET position = '$position' WHERE empid = '$empid'");
		//Set historical for this change
		mysql_query("INSERT INTO position_history(empid, position, date, admin) VALUES('$empid', '$position', '$date', '$adminName')");	
	}
	if($_POST['site'] != null)
	{
		$site = mysql_real_escape_string($_POST['site']);
		mysql_query("UPDATE employee SET site = '$site' WHERE empid = '$empid'");	
		//Set historical for this change
		mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES('$empid', '$site', '$date', '$adminName')");
	}
	if($_POST['salary'] != null)
	{
		$salary = mysql_real_escape_string($_POST['salary']);
		mysql_query("UPDATE employee SET salary = '$salary' WHERE empid = '$empid'");	
	}
	if($_POST['rate'] != null)
	{
		$rate = mysql_real_escape_string($_POST['rate']);
		mysql_query("UPDATE employee SET rate = '$rate' WHERE empid = '$empid'");	
	}
	if($_POST['allowance'] != null)
	{
		$allowance = mysql_real_escape_string($_POST['allowance']);
		mysql_query("UPDATE employee SET allowance = '$allowance' WHERE empid = '$empid'");	
	}
	if($_POST['sss'] != null)
	{
		$sss = mysql_real_escape_string($_POST['sss']);
		mysql_query("UPDATE employee SET sss = '$sss' WHERE empid = '$empid'");	
	}
	if($_POST['philhealth'] != null)
	{
		$philhealth = mysql_real_escape_string($_POST['philhealth']);
		mysql_query("UPDATE employee SET philhealth = '$philhealth' WHERE empid = '$empid'");	
	}
	if($_POST['pagibig'] != null)
	{
		$pagibig = mysql_real_escape_string($_POST['pagibig']);
		mysql_query("UPDATE employee SET pagibig = '$pagibig' WHERE empid = '$empid'");	
	}
	//header('location: editEmployee.php?empid='.$empid);
//employee document checker
	$checker = "SELECT sss, philhealth, pagibig FROM employee WHERE empid = '$empid' AND employment_status = '1' ";
	$checker_query = mysql_query($checker);
	$row = mysql_fetch_assoc($checker_query);
	
	if($row['sss'] == 0 && $row['philhealth'] == 0 && $row['pagibig'] == 0)
	{	
		$not_complete = "UPDATE employee SET complete_doc = '0'";
		mysql_query($not_complete);
	}
	else
	{
		$complete = "UPDATE employee SET complete_doc = '1'";
		mysql_query($complete);
	}
	Print "<script>window.location.assign('editEmployee.php?empid=".$empid."')</script>";
?>









