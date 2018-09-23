<?php
	include_once('directives/db.php');

	$employees = "SELECT * FROM employee";
	$empQuery = mysql_query($employees);

	while($empArr = mysql_fetch_assoc($empQuery))
	{
		$explodeEmpid = explode('-', $empArr['empid']);

		$year = $explodeEmpid[0];
		$randNum = $explodeEmpid[1];

		$checkEmpid = "SELECT * FROM employee WHERE empid LIKE '%$randNum'";
		// Print $checkEmpid;

		$checkEmpQuery = mysql_query($checkEmpid);
		if(mysql_num_rows($checkEmpQuery) > 1)
		{
			while($empidArr = mysql_fetch_assoc($checkEmpQuery))
			{
				$empid = $empidArr['empid'];
				$explodeDuplicateEmpid = explode('-', $empid);

				$duplicateEmpYear = $explodeDuplicateEmpid[0];
				
				Print $empidArr['firstname']." ".$empidArr['lastname']." <br>";
				$success = false;
				do
				{
					$random_number = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9); // random 7 digit 
					$checkForSameRandomNum = mysql_query("SELECT * FROM employee WHERE empid LIKE '%$random_number'");
					if(mysql_num_rows($checkForSameRandomNum) == 0)
					{
						$success = true;
					}
				}while($success == false);

				$newEmpid = $duplicateEmpYear.'-'.$random_number;
				// Query for loans
				mysql_query("UPDATE loans SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());	
				// Query for attendance
				mysql_query("UPDATE attendance SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());	
				// Query for awol employee
				mysql_query("UPDATE awol_employees SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());
				// Query for employee	
				mysql_query("UPDATE employee SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());
				// Query for payroll
				mysql_query("UPDATE payroll SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());
				// Query for payroll_adjustment
				mysql_query("UPDATE payroll_adjustment SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());	
				// Query for position_history
				mysql_query("UPDATE position_history SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());	
				// Query for site_history
				mysql_query("UPDATE site_history SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());
				// Query for thirteenth_pay
				mysql_query("UPDATE thirteenth_pay SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());	
				// Query for tools
				mysql_query("UPDATE tools SET empid = '$newEmpid' WHERE empid = '$empid'") or die (mysql_error());	
					}

		}
	}


?>
