	
<?php
// error_reporting(0);
	include('directives/session.php');
	include_once('directives/db.php');
	include('directives/admin_historical.php');

	$firstname = mysql_real_escape_string($_POST['txt_addFirstName']);
	$lastname = mysql_real_escape_string($_POST['txt_addLastName']);

	$empid = mysql_real_escape_string($_POST['empid']);
	$address = mysql_real_escape_string($_POST['txt_addAddress']);
	$contactNum = mysql_real_escape_string($_POST['txt_addContactNum']);
	$dob = mysql_real_escape_string($_POST['txt_addDOB']);
	$civilStatus = mysql_real_escape_string($_POST['txt_addCivilStatus']);
	$dateHired = mysql_real_escape_string($_POST['txt_addDateHired']);
	
	$position = mysql_real_escape_string($_POST['dd_addPosition']);
	$site = mysql_real_escape_string($_POST['dd_site']);
	
	// $salary = mysql_real_escape_string($_POST['txt_addMonthlySalary']);
	$ratePerDay = mysql_real_escape_string($_POST['txt_addRatePerDay']);
	$allowance = mysql_real_escape_string($_POST['txt_addAllowance']);
	
	$sssEE = mysql_real_escape_string($_POST['txt_addSSSEE']);
	$sssER = mysql_real_escape_string($_POST['txt_addSSSER']);
	$philhealthEE = mysql_real_escape_string($_POST['txt_addPhilhealthEE']);
	$philhealthER = mysql_real_escape_string($_POST['txt_addPhilhealthER']);
	$pagibigEE = mysql_real_escape_string($_POST['txt_addPagibigEE']);
	$pagibigER = mysql_real_escape_string($_POST['txt_addPagibigER']);
	
	$emergencyContact = mysql_real_escape_string($_POST['txt_emergencyContact']);
	$characterReference = mysql_real_escape_string($_POST['txt_characterReference']);
	//debug

	$date = strftime("%B %d, %Y");//date today
	$yearHired = substr($dateHired, -4); //get the year 

	// $monthlySalary = $ratePerDay * 25;//6days working days * 4 weeks
	//SSS contribution computation

	
	if(($sssEE && $philhealthEE && $pagibigEE))//checks if the employee has all the documents needed
	{
		$complete_doc = 1;
	}
	else
	{
		$complete_doc = 0;
	}

	$employment_status = 1;//1 for active employee and 0 for resigned or inactive

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$empQuery = mysql_query($employee);
	$empArr = mysql_fetch_assoc($empQuery);


	//Set historical for Position
	if($empArr['position'] != $position){
		mysql_query("INSERT INTO position_history(empid, position, date, admin) VALUES('$empid', '$position', '$date', '$adminName')");	
	}
	//Set historical for Site
	if($empArr['site'] != $site){
		mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES('$empid', '$site', '$date', '$adminName')");
	}
	

	mysql_query("UPDATE employee SET 		address = '$address',
											contactnum = '$contactNum',
											dob = '$dob',
											civilstatus = '$civilStatus',
											datehired = '$date',
											position = '$position',
											rate = '$ratePerDay',
											allowance = '$allowance',
											site = '$site',
											sss = '$sssEE',
											philhealth = '$philhealthEE',
											pagibig = '$pagibigEE',
											sss_er = '$sssER',
											philhealth_er = '$philhealthER',
											pagibig_er = '$pagibigER',
											employment_status = '1',
											complete_doc = '$complete_doc',
											reference = '$characterReference',
											emergency = '$emergencyContact' WHERE empid = '$empid'") or die(mysql_error());//adds values to employee table
	

	Print "<script>alert('You successfully rehired ".$lastname.", ".$firstname.".')</script>";
	Print "<script>window.location.assign('employees.php?site=null&position=null')</script>";


?>