<?php
	include_once('directives/db.php');
	include('directives/session.php');

	$firstname = mysql_real_escape_string($_POST['n_firstname']);
	$lastname = mysql_real_escape_string($_POST['n_lastname']);
	$username = mysql_real_escape_string($_POST['n_username']);
	$password = mysql_real_escape_string($_POST['n_password']);
	$Cpassword = mysql_real_escape_string($_POST['n_confirmPassword']);
	$security = $_POST['n_security'];
	$answer = mysql_real_escape_string($_POST['n_answer']);
	$role = $_POST['n_role'][0];

	$firstname = ucwords($firstname);
	$lastname = ucwords($lastname);

	if($password == $Cpassword)
	{
		$checker = "SELECT * FROM administrator WHERE username = '$username'";
		$checkerQuery = mysql_query($checker);
		if(mysql_num_rows($checkerQuery) > 0)
		{
			Print "<script>alert('Username already taken, please choose another username.')</script>";
			Print "<script>window.location.assign('options.php')</script>";
		}
		
		$restrictionBool = ($role == "Employee" ? true : false);//boolean if type of role is employee and admin didn't choose any restrictions

		$restrictionSet = "";

	//Employee Tab
		if(isset($_POST['res_listOfEmployees']))
		{
			$restrictionSet .= "1";
			$restrictionBool = false;
		}
		if(isset($_POST['res_listOfLoanApp']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "2";
			$restrictionBool = false;
		}
		if(isset($_POST['res_listOfAbsence']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "3";
			$restrictionBool = false;
		}
		if(isset($_POST['res_listOfSiteManage']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "4";
			$restrictionBool = false;
		}
	//attendance
		if(isset($_POST['res_Attedance']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "5";
			$restrictionBool = false;
		}
	//Payroll
		if(isset($_POST['res_Payroll']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "6";
			$restrictionBool = false;
		}
	//Reports
		if(isset($_POST['res_EarningsReport']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "7";
			$restrictionBool = false;
		}
		if(isset($_POST['res_ContributionsReport']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "8";
			$restrictionBool = false;
		}
		if(isset($_POST['res_LoansReport']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "9";
			$restrictionBool = false;
		}
		if(isset($_POST['res_AttendanceReport']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "10";
			$restrictionBool = false;
		}
		if(isset($_POST['res_CompanyExpensesReport']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "11";
			$restrictionBool = false;
		}
	//Options
		if(isset($_POST['res_SiteManage']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "12";
			$restrictionBool = false;
		}
		if(isset($_POST['res_PositionManage']))
		{
			if($restrictionSet != "")
				$restrictionSet .= "-";

			$restrictionSet .= "13";
			$restrictionBool = false;
		}

		if($restrictionBool)//if this is true then admin didn't choose any restrictions
		{
			Print "<script>alert('Please choose restrictions for this employee\'s account.')</script>";
			Print "<script>window.location.assign('options.php')</script>";
		}

		$account = "INSERT INTO administrator(	firstname,
												lastname,
												role,
												username,
												password,
												secret_question,
												answer,
												restrictions) VALUES(	'$firstname',
																'$lastname',
																'$role',
																'$username',
																'$password',
																'$security',
																'$answer',
																'$restrictionSet')";
		if($restrictionBool == false)
			mysql_query($account);
		Print "<script>alert('Successfully added ".$lastname.", ".$firstname." to ".$role." Accounts')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}
	else
	{
		Print "<script>alert('Password and confirm password do not match.')</script>";
		Print "<script>window.location.assign('options.php')</script>";
	}


?>