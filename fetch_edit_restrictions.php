<?php
include('directives/db.php');

//employee History
$user = $_POST['username'];

$admin = "SELECT * FROM administrator WHERE username = '$user'";
$adminQuery = mysql_query($admin);

$adminArr = mysql_fetch_assoc($adminQuery);

$output = "";
if($adminArr['role'] == "Administrator")
{
	$output = '<ul class="list-unstyled text-left">
					<li>
						<label>
							<input type="checkbox" id="restrictEmployeesTab" onchange="restrictEmployees(this.value)" disabled>
							Employees Tab
						</label>
						<ul style="list-style: none;">
							<li>
								<label>
									<input type="checkbox" name="res_listOfEmployees" disabled>
									Access to list of employees
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_listOfLoanApp" disabled>
									Access to list of loan applications
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_listOfAbsence" disabled>
									Access to list of absence notifications
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_listOfSiteManage" disabled>
									Access to list of site management
								</label>
							</li>
						</ul>
					</li>
					<li>
						<label>
							<input type="checkbox" name="res_Attedance" disabled>
							Attendance Access
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="res_Payroll" disabled>
							Payroll Access
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="res_Reports" onchange="restrictReports()" disabled>
							Reports
						</label>
						<ul style="list-style: none;">
							<li>
								<label>
									<input type="checkbox" name="res_EarningsReport" disabled>
									Access Earnings
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_ContributionsReport" disabled>
									Access Contributions
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_LoansReport" disabled>
									Access Loans
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_AttendanceReport" disabled>
									Access Attendance
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_CompanyExpensesReport" disabled>
									Access Company Expenses
								</label>
							</li>
						</ul>
					</li>
					<li>
						<label>
							<input type="checkbox" name="res_Options" onchange="restrictOptions()" disabled>
							Options
						</label>
						<ul style="list-style: none;">
							<li>
								<label>
									<input type="checkbox" name="res_SiteManage" disabled>
									Access to site management
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_PositionManage" disabled>
									Access to position management
								</label>
							</li>
						</ul>
					</li>
				</ul>
				<input type="hidden" name="adminUser" value="'.$user.'">';
}
else//admin role is Employee
{

	$restriction = explode("-",$adminArr['restrictions']);

	$listOfEmployees = "";// 1 - List of employees
	$listOfLoanApp = "";// 2 - list of loan applications
	$listOfAbsence = "";// 3 - list of absence notification
	$listOfSiteManage = "";// 4 - list of site manage
	$attendanceAccess = "";// 5 - attendance access
	$payrollAccess = "";// 6 - payroll access
	$EarningsReport = "";// 7 - earnings report
	$ContributionsReport = "";// 8 - contributions report
	$LoansReport = "";// 9 - loans report
	$AttendanceReport = "";// 10 - attendance report
	$CompanyExpensesReport = "";// 11 - company expenses report
	$SiteManage = "";// 12 - site management
	$PositionManage = "";// 13 - position management

	$employeeTab = "";
	$reportsTab = "";
	$optionsTab = "";

	foreach($restriction as $restrict)
	{

		switch($restrict)
		{
			case "1": $listOfEmployees = "checked";break;
			case "2": $listOfLoanApp = "checked";break;
			case "3": $listOfAbsence = "checked";break;
			case "4": $listOfSiteManage = "checked";break;
			case "5": $attendanceAccess = "checked";break;
			case "6": $payrollAccess = "checked";break;
			case "7": $EarningsReport = "checked";break;
			case "8": $ContributionsReport = "checked";break;
			case "9": $LoansReport = "checked";break;
			case "10": $AttendanceReport = "checked";break;
			case "11": $CompanyExpensesReport = "checked";break;
			case "12": $SiteManage = "checked";break;
			case "13": $PositionManage = "checked";break;
		}
	}

	if($listOfEmployees == "checked" && $listOfLoanApp == "checked" && $listOfAbsence == "checked" && $listOfSiteManage == "checked")
	{
		$employeeTab = "checked";
	}
	if($EarningsReport == "checked" && $ContributionsReport == "checked" && $LoansReport == "checked" && $AttendanceReport == "checked" && $CompanyExpensesReport == "checked")
	{
		$reportsTab = "checked";
	}
	if($SiteManage == "checked" && $PositionManage == "checked")
	{
		$optionsTab = "checked";
	}
	


	$output = '<ul class="list-unstyled text-left">
					<li>
						<label>
							<input type="checkbox" id="restrictEmployeesTabEdit" onchange="restrictEmployeesEdit()" '.$employeeTab.' >
							Employees Tab
						</label>
						<ul style="list-style: none;">
							<li>
								<label>
									<input type="checkbox" name="res_listOfEmployeesEdit" '.$listOfEmployees.' >
									Access to list of employees
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_listOfLoanAppEdit" '.$listOfLoanApp.' >
									Access to list of loan applications
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_listOfAbsenceEdit" '.$listOfAbsence.' >
									Access to list of absence notifications
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_listOfSiteManageEdit" '.$listOfSiteManage.' >
									Access to list of site management
								</label>
							</li>
						</ul>
					</li>
					<li>
						<label>
							<input type="checkbox" name="res_AttedanceEdit" '.$attendanceAccess.' >
							Attendance Access
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="res_PayrollEdit" '.$payrollAccess.' >
							Payroll Access
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" name="res_ReportsEdit" onchange="restrictReports()" '.$reportsTab.'>
							Reports
						</label>
						<ul style="list-style: none;">
							<li>
								<label>
									<input type="checkbox" name="res_EarningsReportEdit" '.$EarningsReport.'>
									Access Earnings
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_ContributionsReportEdit" '.$ContributionsReport.'>
									Access Contributions
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_LoansReportEdit" '.$LoansReport.'>
									Access Loans
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_AttendanceReportEdit" '.$AttendanceReport.'>
									Access Attendance
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_CompanyExpensesReportEdit" '.$CompanyExpensesReport.'>
									Access Company Expenses
								</label>
							</li>
						</ul>
					</li>
					<li>
						<label>
							<input type="checkbox" name="res_OptionsEdit" onchange="restrictOptionsEdit()" '.$optionsTab.'>
							Options
						</label>
							<li>
								<label>
									<input type="checkbox" name="res_SiteManageEdit" '.$SiteManage.'>
									Access to site management
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" name="res_PositionManageEdit" '.$PositionManage.'>
									Access to position management
								</label>
							</li>
						</ul>
					</li>
				</ul>
				<input type="hidden" name="adminUser" value="'.$user.'">';
}

echo $output;
?>












