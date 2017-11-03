	
<?php
error_reporting(0);
	include('directives/session.php');
	include_once('directives/db.php');

	$date = strftime("%B %d, %Y");//Get the current date
	//Gets the admin who change the site and position
	$user = $_SESSION['user_logged_in'];
	$admin = "SELECT * FROM administrator WHERE username = '$user'";
	$adminQuery = mysql_query($admin);
	$adminArr = mysql_fetch_assoc($adminQuery);
	$adminName = $adminArr['firstname']." ".$adminArr['lastname'];

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
		$sss = mysql_real_escape_string($_POST['chkbox_addSSS']);
		$philhealth = mysql_real_escape_string($_POST['chkbox_addPhilHealth']);
		$pagibig = mysql_real_escape_string($_POST['txt_addPagibig']);
		$salary = mysql_real_escape_string($_POST['txt_addMonthlySalary']);
		//debug

		$firstName = ucwords($firstName);
		$lastName = ucwords($lastName);

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
		//SSS contribution computation

		$sssContribution = 0;
		if(isset($_POST['chkbox_addSSS']))
		{
	
			$sss_bool = true;//checker for the complete documented employee
			//1,000 ~ 1,249.9 = 36.30
			if($monthlySalary >= 1000 && $monthlySalary <= 1249.9)
			$sssContribution = 36.30;
			//1250 ~ 1749.9 = 54.50
			else if($monthlySalary >= 1250 && $monthlySalary <= 1749.9)
			$sssContribution = 54.50;
			//1750 ~ 2249.9 = 72.70
			else if($monthlySalary >= 1750 && $monthlySalary <= 2249.9)
			$sssContribution = 72.70;
			//2250 ~ 2749.9 = 90.80
			else if($monthlySalary >= 2250 && $monthlySalary <= 2749.9)
			$sssContribution = 90.80;
			//2750 ~ 3249.9 = 109.0
			else if($monthlySalary >= 2750 && $monthlySalary <= 3249.9)
			$sssContribution = 109.00;
			//3250 ~ 3749.9 = 127.20
			else if($monthlySalary >= 3250 && $monthlySalary <= 3749.9)
			$sssContribution = 127.20;
			//3750 ~ 4249.9 = 145.30
			else if($monthlySalary >= 3750 && $monthlySalary <= 4249.9)
			$sssContribution = 145.30;
			//4250 ~ 4749.9 = 163.50
			else if($monthlySalary >= 4250 && $monthlySalary <= 4749.9)
			$sssContribution = 163.50;
			//4750 ~ 5249.9 = 181.70
			else if($monthlySalary >= 4750 && $monthlySalary <= 5249.9)
			$sssContribution = 181.70;
			//5250 ~ 5749.9 = 199.80
			else if($monthlySalary >= 5250 && $monthlySalary <= 5749.9)
			$sssContribution = 199.80;
			//5750 ~ 6249.9 = 218.0
			else if($monthlySalary >= 5750 && $monthlySalary <= 6249.9)
			$sssContribution = 218.00;
			//6250 ~ 6749.9 = 236.20
			else if($monthlySalary >= 6250 && $monthlySalary <= 6749.9)
			$sssContribution = 236.20;
			//6750 ~ 7249.9 = 254.30
			else if($monthlySalary >= 6750 && $monthlySalary <= 7249.9 )
			$sssContribution = 254.30;
			//7250 ~ 7749.9 = 272.50
			else if($monthlySalary >= 7250 && $monthlySalary <= 7749.9 )
			$sssContribution = 272.50;
			//7750 ~ 8249.9 = 290.70
			else if($monthlySalary >= 7750 && $monthlySalary <= 8249.9)
			$sssContribution = 290.70;
			//8250 ~ 8749.9 = 308.80
			else if($monthlySalary >= 8250 && $monthlySalary <= 8749.9)
			$sssContribution = 308.80;
			//8750 ~ 9249.9 = 327.0
			else if($monthlySalary >= 8750 && $monthlySalary <= 9249.9 )
			$sssContribution = 327.00;
			//9250 ~ 9749.9 = 345.20
			else if($monthlySalary >= 9250 && $monthlySalary <= 9749.9)
			$sssContribution = 345.20;
			//9750 ~ 10249.9 = 363.30
			else if($monthlySalary >= 9750 && $monthlySalary <= 10249.9)
			$sssContribution = 363.30;
			//10250 ~ 10749.9 = 381.50
			else if($monthlySalary >= 10250 && $monthlySalary <= 10749.9)
			$sssContribution = 381.50;
			//10750 ~ 11249.9 = 399.70
			else if($monthlySalary >= 10750 && $monthlySalary <= 11249.9)
			$sssContribution = 399.70;
			//11250 ~ 11749.9 = 417.80
			else if($monthlySalary >= 11250 && $monthlySalary <= 11749.9)
			$sssContribution = 417.80;
			//11750 ~ 12249.9 = 436.0
			else if($monthlySalary >= 11750 && $monthlySalary <= 12249.9)
			$sssContribution = 436.00;
			//12250 ~ 12749.9 = 454.20
			else if($monthlySalary >= 12250 && $monthlySalary <= 12749.9)
			$sssContribution = 454.20;
			//12750 ~ 13249.9 = 472.30
			else if($monthlySalary >= 12750 && $monthlySalary <= 13249.9)
			$sssContribution = 472.30;
			//13250 ~ 13749.9 = 490.50
			else if($monthlySalary >= 13250 && $monthlySalary <= 13749.9)
			$sssContribution = 490.50;
			//13750 ~ 14249.9 = 508.70
			else if($monthlySalary >= 13750 && $monthlySalary <= 14249.9)
			$sssContribution = 508.70;
			//14250 ~ 14749.9 = 526.80
			else if($monthlySalary >= 14250 && $monthlySalary <= 14749.9)
			$sssContribution = 526.80;
			//14750 ~ 15249.9 = 545.0
			else if($monthlySalary >= 14750 && $monthlySalary <= 15249.9)
			$sssContribution = 545.00;
			//15250 ~ 15749.9 = 563.20
			else if($monthlySalary >= 15250 && $monthlySalary <= 15749.9)
			$sssContribution = 563.20;
			//15750 ~ higher = 581.30
			else if($monthlySalary >= 15750)
			$sssContribution = 581.30;
		}		
		$philhealthContribution = 0;
		if(isset($_POST['chkbox_addPhilHealth']))
		{
	
			$philhealth_bool=true;//checker for the complete documented employee
			//below ~ 8999.9 = 200
			if($monthlySalary >= 1 && $monthlySalary <= 8999.9)
			$philhealthContribution = 200.00;
			//9000 ~ 9999.9 = 225
			else if($monthlySalary >= 9000 && $monthlySalary <= 9999.9)
			$philhealthContribution = 225.00;
			//10000 ~ 10999.9 = 250
			else if($monthlySalary >= 10000 && $monthlySalary <= 10999.9)
			$philhealthContribution = 250.00;
			//11000 ~ 11999.9 = 275
			else if($monthlySalary >= 11000 && $monthlySalary <= 11999.9)
			$philhealthContribution = 275.00;
			//12000 ~ 12999.9 = 300
			else if($monthlySalary >= 12000 && $monthlySalary <= 12999.9)
			$philhealthContribution = 300.00;
			//13000 ~ 13999.9 = 325
			else if($monthlySalary >= 13000 && $monthlySalary <= 13999.9)
			$philhealthContribution = 325.00;
			//14000 ~ 14999.9 = 350
			else if($monthlySalary >= 14000 && $monthlySalary <= 14999.9)
			$philhealthContribution = 350.00;
			//15000 ~ 15999.9 = 375
			else if($monthlySalary >= 15000 && $monthlySalary <= 15999.9)
			$philhealthContribution = 375.00;
			//16000 ~ 16999.9 = 400
			else if($monthlySalary >= 16000 && $monthlySalary <= 16999.9)
			$philhealthContribution = 400.00;
			//17000 ~ 17999.9 = 425
			else if($monthlySalary >= 17000 && $monthlySalary <= 17999.9)
			$philhealthContribution = 425.00;
			//18000 ~ 18999.9 = 450
			else if($monthlySalary >= 18000 && $monthlySalary <= 18999.9)
			$philhealthContribution = 450.00;
			//19000 ~ 19999.9 = 475
			else if($monthlySalary >= 19000 && $monthlySalary <= 19999.9)
			$philhealthContribution = 475.00;
			//20000 ~ 20999.9 = 500
			else if($monthlySalary >= 20000 && $monthlySalary <= 20999.9)
			$philhealthContribution = 500.00;
			//21000 ~ 21999.9 = 525
			else if($monthlySalary >= 21000 && $monthlySalary <= 21999.9)
			$philhealthContribution = 525.00;
			//22000 ~ 22999.9 = 550
			else if($monthlySalary >= 22000 && $monthlySalary <= 22999.9)
			$philhealthContribution = 550.00;
			//23000 ~ 23999.9 = 575
			else if($monthlySalary >= 23000 && $monthlySalary <= 23999.9)
			$philhealthContribution = 575.00;
			//24000 ~ 24999.9 = 600
			else if($monthlySalary >= 24000 && $monthlySalary <= 24999.9)
			$philhealthContribution = 600.00;
			//25000 ~ 25999.9 = 625
			else if($monthlySalary >= 25000 && $monthlySalary <= 25999.9)
			$philhealthContribution = 625.00;
			//26000 ~ 26999.9 = 650
			else if($monthlySalary >= 26000 && $monthlySalary <= 26999.9)
			$philhealthContribution = 650.00;
			//27000 ~ 27999.9 = 675
			else if($monthlySalary >= 27000 && $monthlySalary <= 27999.9)
			$philhealthContribution = 675.00;
			//28000 ~ 28999.9 = 700
			else if($monthlySalary >= 28000 && $monthlySalary <= 28999.9)
			$philhealthContribution = 700.00;
			//29000 ~ 29999.9 = 725
			else if($monthlySalary >= 29000 && $monthlySalary <= 29999.9)
			$philhealthContribution = 725.00;
			//30000 ~ 30999.9 = 750
			else if($monthlySalary >= 30000 && $monthlySalary <= 30999.9)
			$philhealthContribution = 750.00;
			//31000 ~ 31999.9 = 775
			else if($monthlySalary >= 31000 && $monthlySalary <= 31999.9)
			$philhealthContribution = 775.00;
			//32000 ~ 32999.9 = 800
			else if($monthlySalary >= 32000 && $monthlySalary <= 32999.9)
			$philhealthContribution = 800.00;
			//33000 ~ 339999.9 = 825
			else if($monthlySalary >= 33000 && $monthlySalary <= 339999.9)
			$philhealthContribution = 825.00;
			//34000 ~ 349999.9 = 850
			else if($monthlySalary >= 34000 && $monthlySalary <= 349999.9)
			$philhealthContribution = 850.00;
			//35000 ~ higher = 875
			else if($monthlySalary >= 35000)
			$philhealthContribution = 875.00;
		}
		if($pagibig == null)
		{
			$pagibig = 0;
		}
		if(($sss_bool && $philhealth_bool) && (isset($_POST['txt_addPagibig'])))//checks if the employee has all the documents needed
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
												complete_doc) VALUES('$empid',
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
																	'$sssContribution',
																	'$philhealthContribution',
																	'$pagibig',
																	'$employment_status',
																	'$complete_doc')");//adds values to employee table
		//Set historical for Position
		mysql_query("INSERT INTO position_history(empid, position, date, admin) VALUES('$empid', '$position', '$date', '$adminName')");	
		//Set historical for Site
		mysql_query("INSERT INTO site_history(empid, site, date, admin) VALUES('$empid', '$site', '$date', '$adminName')");
	
		Print "<script>alert('You have successfully added an employee.')</script>";
		Print "<script>window.location.assign('employees.php?site=null&position=null')</script>";


?>