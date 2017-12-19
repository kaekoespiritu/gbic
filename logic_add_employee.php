	
<?php
error_reporting(0);
	include('directives/session.php');
	include_once('directives/db.php');
	include('directives/admin_historical.php');

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
		$sss = mysql_real_escape_string($_POST['txt_addSSS']);
		$philhealth = mysql_real_escape_string($_POST['txt_addPhilhealth']);
		$pagibig = mysql_real_escape_string($_POST['txt_addPagibig']);
		$salary = mysql_real_escape_string($_POST['txt_addMonthlySalary']);
		$emergencyContact = mysql_real_escape_string($_POST['txt_emergencyContact']);
		$characterReference = mysql_real_escape_string($_POST['txt_characterReference']);
		Print "<script>console.log('".$emergencyContact." || ".$characterReference."')</script>";
		//debug

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


		$monthlySalary = $ratePerDay * 25;//6days working days * 4 weeks
		
		//Contributions
		if($sss == null)
			$pagibig = 0;

		if($philhealth == null)
			$philhealth = 0;

		if($pagibig == null)
			$pagibig = 0;

		if($sss && $philhealth && $pagibig)//checks if the employee has all the documents needed
		{
			$complete_doc = 1;
		}
		else
		{
			$complete_doc = 0;
		}

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
												salary,
												rate,
												allowance,
												site,
												sss,
												philhealth,
												pagibig,
												employment_status,
												complete_doc,
												reference,
												emergency) VALUES('$empid',
																	'$firstName',
																	'$lastName',
																	'$address',
																	'$contactNum',
																	'$dob',
																	'$civilStatus',
																	'$dateHired',
																	'$position',
																	'$salary',
																	'$ratePerDay',
																	'$allowance',
																	'$site',
																	'$sss',
																	'$philhealth',
																	'$pagibig',
																	'$employment_status',
																	'$complete_doc',
																	'$characterReference',
																	'$emergencyContact')") or die(mysql_error());//adds values to employee table
		//Set historical for Position
		mysql_query("INSERT INTO position_history(empid, position, date, admin) VALUES('$empid', '$position', '$date', '$adminName')");	
		//Set historical for Site
		mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES('$empid', '$site', '$date', '$adminName')");
	
		Print "<script>alert('You have successfully added an employee.')</script>";
		Print "<script>window.location.assign('employees.php?site=null&position=null')</script>";


?>