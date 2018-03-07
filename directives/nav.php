<?php
	################ LEGEND ################
	########################################
	#									   #
	#	1 - List of employees			   #
	#	2 - list of loan applications	   #
	#	3 - list of absence notification   #
	#	4 - list of site management  	   #	
	#	5 - attendance access 			   #
	#	6 - payroll access 				   #
	#	7 - earnings report 			   #
	#	8 - contributions report 		   #
	#	9 - loans report 				   #
	#	10 - attendance report 			   #
	#	11 - company expenses report 	   #
	#	12 - site management	           #
	#	13 - position management	       #
	#									   #
	########################################

	$adminLoggedIn = $_SESSION['user_logged_in'];//gets the logged in admin

	$restrictCheck = "SELECT * FROM administrator WHERE username = '$adminLoggedIn'";
	$restrictQuery = mysql_query($restrictCheck) or die(mysql_error());

	$adminRestriction = mysql_fetch_assoc($restrictQuery);
	$restrictions = explode("-" ,$adminRestriction['restrictions']);
	$resCount = count($restrictions);

	//Preset variables
	$ListOfEmployees = "";// 1
	$listOfLoanApplications = "";// 2
	$listOfAbsenceNotification = "";// 3  
	$listOfSiteManagement = "";// 4  	   
	$attendanceAccess = "";// 5 			   
	$payrollAccess = "";// 6 				   
	$earningsReport = "";// 7 			   
	$contributionsReport = "";// 8 		   
	$loansReport = "";// 9 				   
	$attendanceReport = "";// 10 			   
	$companyExpensesReport = "";// 11 	   
	$siteManagement = "";// 12	           
	$positionManagement = "";// 13	
	//Main tabs
	$employeesTab = "";
	$reportsTab = "";

	if($adminRestriction['role'] == "Employee")
	{
		for($count = 0; $count < $resCount; $count++)
		{
			switch($restrictions[$count])
			{
				case "1": $ListOfEmployees = ""; break;
				case "2": $listOfLoanApplications = ""; break;
				case "3": $listOfAbsenceNotification = ""; break;
				case "4": $listOfSiteManagement = ""; break;
				case "5": $attendanceAccess = "";break;
				case "6": $payrollAccess = ""; break;
				case "7": $earningsReport = ""; break;
				case "8": $contributionsReport = ""; break;
				case "9": $loansReport = ""; break;
				case "10": $attendanceReport = ""; break;
				case "11": $companyExpensesReport = ""; break;
				case "12": $siteManagement = ""; break;
				case "13": $positionManagement = ""; break;
			}
		}

		if($ListOfEmployees != "" && $listOfLoanApplications != "" && $listOfAbsenceNotification != "" && $listOfSiteManagement != "")
		{
			$employeesTab = "";//Disable the whole employees tab if employee dont have access in it
		}
		if($earningsReport != "" && $contributionsReport != "" && $loansReport != "" && $attendanceReport != "" && $companyExpensesReport != "")
		{
			$reportsTab = "";//Disable the whole employees tab if employee dont have access in it
		}
	}
	

?>


<div class="row">
<div class="menubar navibar">
	<!-- HOME BUTTON -->
	<div id="home" class="col-md-1 navibutton">
	<a href="index.php">
		<img src="Images/house.png" class="center-block">
		<h6 class="text-center">HOME</h6>
	</a>
	</div>
	<!-- EMPLOYEES BUTTON -->
	<div id="employees" class="col-md-1 navibutton">
	<div class="flipdown">
		<a href="employees.php?site=null&position=null" class="flipbtn <?php Print $employeesTab?>">
			<img src="Images/engineer.png" class="center-block">
			<h6 class="text-center">EMPLOYEES <span class="caret"></span></h6>
		</a>
		<div class="flipdown-menu">
			<a href="employees.php?site=null&position=null" class="<?php Print $ListOfEmployees?>">List of Employees</a>
			<a href="loans_landing.php" class="<?php Print $listOfLoanApplications?>">Loan Applications</a>
			<a href="applications.php" class="<?php Print $listOfAbsenceNotification?>">Absence Notifications</a>
			<a href="site_landing.php" class="<?php Print $listOfSiteManagement?>">Site movement</a>
		</div>
	</div>
	</div>
	<!-- ATTENDANCE BUTTON -->
	<div id="attendance" class="col-md-1 navibutton">
	<div class="flipdown">
		<a href="attendance.php" class="<?php Print $attendanceAccess?>>
			<img src="Images/attendance.png" class="center-block">
			<h6 class="text-center">ATTENDANCE</h6>
		</a>
	</div>
	</div>
	<!-- PAYROLL BUTTON -->
	<div id="payroll" class="col-md-1 navibutton">
	<a href="payroll_login.php" class="payroll <?php Print $payrollAccess?>">
		<img src="Images/cash-pay.png" class="center-block">
		<h6 class="text-center">PAYROLL</h6>
	</a>
	</div>
	<!-- REPORTS BUTTON -->
	<div id="reports" class="col-md-1 navibutton">
	<div class="flipdown">
		<a  class="reports flipbtn <?php Print $ListOfEmployees?>">
			<img src="Images/tax.png" class="center-block">
			<h6 class="text-center">REPORTS <span class="caret"></span></h6>
		</a>
		<div class="flipdown-menu">
			<div class="sub-flipdown">
				<div class="sub-flipdown-menu">
					<a href="reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null" > Individual</a>
					<a href="reports_overall_earnings.php?type=Earnings&period=Weekly"> Overall</a>
				</div>
				<a class="subflipbtn <?php Print $earningsReport?>">Earnings</a>
				
			</div>
			<div class="sub-flipdown">
				<div class="sub-flipdown-menu">
					<a href="reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null"> Individual</a>
					<a href="reports_overall_contributions.php?type=Contributions&period=Weekly"> Overall</a>
				</div>
				<a class="subflipbtn <?php Print $contributionsReport?>">Contributions</a>
			</div>
			<div class="sub-flipdown">
				<div class="sub-flipdown-menu">
					<a href="reports_individual_loans.php?type=Loans&period=week&site=null&position=null"> Individual</a>
					<a href="reports_overall_loans.php?type=Loans&period=Weekly"> Overall</a>
				</div>
				<a class="subflipbtn <?php Print $loansReport?>">Loans</a>
				
			</div>
			<div class="sub-flipdown">
				<div class="sub-flipdown-menu">
					<a href="reports_individual_attendance.php?type=Attendance&period=week&site=null&position=null"> Individual</a>
					<a href="reports_overall_attendance.php?type=Attendance&period=Weekly"> Overall</a>
				</div>
				<a class="subflipbtn <?php Print $attendanceReport?>">Attendance</a>
			</div>
			<div class="sub-flipdown">
				<a class="subflipbtn <?php Print $companyExpensesReport?>" href="reports_company_expenses.php?type=Expenses&period=Weekly">Company Expenses</a>
			</div>
		</div>
	</div>
	</div>
	<!-- OPTIONS BUTTON-->
	<div id="adminOptions" class="col-md-1 navibutton">
	<a href="options.php" class="adminOptions">
		<span>	
			<img src="Images/admin-with-cogwheels.png" class="center-block">
			<h6 class="text-center">OPTIONS</h6>
		</span>
	</a>
	</div>
	<!-- LOGOUT BUTTON -->
	<div class="col-md-1 navibutton">
	<a href="logout.php" class="">
		<span>
			<img src="Images/power-button-symbol.png" class="center-block">
			<h6 class="text-center">LOGOUT</h6>
		</span>
	</a>
	</div>
</div>
</div>