	
<?php
error_reporting(0);
	include('directives/session.php');
	include_once('directives/db.php');
	include('directives/admin_historical.php');

	function getMonth($month)
	{
		switch($month)
		{
			case "01": $output = "January";break;
			case "02": $output = "February";break;
			case "03": $output = "March";break;
			case "04": $output = "April";break;
			case "05": $output = "May";break;
			case "06": $output = "June";break;
			case "07": $output = "July";break;
			case "08": $output = "August";break;
			case "09": $output = "September";break;
			case "10": $output = "October";break;
			case "11": $output = "November";break;
			case "12": $output = "December";break;
		}
		return $output;
	}
	

	$date = strftime("%B %d, %Y");//Get the current date

		$firstName = mysql_real_escape_string($_POST['txt_addFirstName']);
		$lastName = mysql_real_escape_string($_POST['txt_addLastName']);
		$address = mysql_real_escape_string($_POST['txt_addAddress']);
		$contactNum = mysql_real_escape_string($_POST['txt_addContactNum']);
		$dob = mysql_real_escape_string($_POST['txt_addDOB']);
		$civilStatus = mysql_real_escape_string($_POST['txt_addCivilStatus']);
		$dateHired = mysql_real_escape_string($_POST['txt_addDateHired']);
		$position = mysql_real_escape_string($_POST['dd_addPosition']);
		$ratePerDay = mysql_real_escape_string($_POST['txt_addRatePerDay']);
		$allowance = mysql_real_escape_string($_POST['txt_addAllowance']);
		$site = mysql_real_escape_string($_POST['dd_site']);
		$sssEE = mysql_real_escape_string($_POST['txt_addSSSEE']);
		$sssER = mysql_real_escape_string($_POST['txt_addSSSER']);
		$philhealthEE = mysql_real_escape_string($_POST['txt_addPhilhealthEE']);
		$philhealthER = mysql_real_escape_string($_POST['txt_addPhilhealthER']);
		$pagibigEE = mysql_real_escape_string($_POST['txt_addPagibigEE']);
		$pagibigER = mysql_real_escape_string($_POST['txt_addPagibigER']);
		$emergencyContact = mysql_real_escape_string($_POST['txt_emergencyContact']);
		$characterReference = mysql_real_escape_string($_POST['txt_characterReference']);
		$cola = mysql_real_escape_string($_POST['txt_cola']);
		$insurance = mysql_real_escape_string($_POST['txt_insurance']);

		$firstName = ucwords($firstName);
		$lastName = ucwords($lastName);
		$address = ucwords($address);

		$yearHired = substr($dateHired, -4); //get the year 


		$random_number = $yearHired."-".rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9); // random(ish) 7 digit 
		
		$empidChecker = "SELECT empid FROM employee WHERE empid = '$random_number'";
		$queryChecker = mysql_query($empidChecker);
//empid
		$success = false;
		do
		{
			
			if($queryChecker)
			{
				
				$exist = mysql_num_rows($queryChecker);
				do
				{	
					
					if($exist > 0)
					{
					
						$random_number = $yearHired."-".rand(1,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
						$empidChecker = "SELECT empid FROM employee WHERE empid = '$random_number'";
						$queryChecker = mysql_query($empidChecker);
						$exist = mysql_num_rows($queryChecker);
					}
					else
					{
					
						$success = true;
						$empid = $random_number;
						
					}
				}while($success == false);
			}
			else
			{
				$success = true;
				$empid = $random_number;

			}
		}while($success == false);


		// $monthlySalary = $ratePerDay * 25;//6days working days * 4 weeks
		
		//Contributions
		if(isset($_POST['sssCheckbox']) && isset($_POST['philhealthCheckbox']) && isset($_POST['pagibigCheckbox']))//checks if the employee has all the documents needed
			$complete_doc = 1;
		else
			$complete_doc = 0;
	

		$employment_status = 1;//1 for active employee and 0 for resigned or inactive

		mysql_query("INSERT INTO 	employee(	empid, 
												firstname,
												lastname,
												address,
												contactnum,
												dob,
												civilstatus,
												datehired,
												position,
												rate,
												allowance,
												site,
												sss,
												sss_er,
												philhealth,
												philhealth_er,
												pagibig,
												pagibig_er,
												cola,
												employment_status,
												complete_doc,
												reference,
												emergency,
												insurance) VALUES('$empid',
																	'$firstName',
																	'$lastName',
																	'$address',
																	'$contactNum',
																	'$dob',
																	'$civilStatus',
																	'$dateHired',
																	'$position',
																	'$ratePerDay',
																	'$allowance',
																	'$site',
																	'$sssEE',
																	'$sssER',
																	'$philhealthEE',
																	'$philhealthER',
																	'$pagibigEE',
																	'$pagibigER',
																	'$cola',
																	'$employment_status',
																	'$complete_doc',
																	'$characterReference',
																	'$emergencyContact',
																	'$insurance')") or die(mysql_error());//adds values to employee table
		// //Set historical for Position
		mysql_query("INSERT INTO position_history(empid, position, date, admin) VALUES('$empid', '$position', '$date', '$adminName')");	
		//Set historical for Site
		mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES('$empid', '$site', '$date', '$adminName')");
	
		Print "<script>alert('You have successfully added an employee.')</script>";
		Print "<script>window.location.assign('employees.php?site=null&position=null')</script>";


?>