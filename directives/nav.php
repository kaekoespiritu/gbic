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
		<a href="employees.php?site=null&position=null" class="flipbtn">
			<img src="Images/engineer.png" class="center-block">
			<h6 class="text-center">EMPLOYEES <span class="caret"></span></h6>
		</a>
		<div class="flipdown-menu">
			<a href="employees.php?site=null&position=null">List of Employees</a>
			<a href="loans_landing.php">Loan Applications</a>
			<a href="applications.php">Absence Notifications</a>
			<a href="site_landing.php">Site movement</a>
		</div>
	</div>
	</div>
	<!-- ATTENDANCE BUTTON -->
	<div id="attendance" class="col-md-1 navibutton">
	<div class="flipdown">
		<a href="attendance.php">
			<img src="Images/attendance.png" class="center-block">
			<h6 class="text-center">ATTENDANCE</h6>
		</a>
	</div>
	</div>
	<!-- PAYROLL BUTTON -->
	<div id="payroll" class="col-md-1 navibutton">
	<a href="payroll_login.php" class="payroll">
		<img src="Images/cash-pay.png" class="center-block">
		<h6 class="text-center">PAYROLL</h6>
	</a>
	</div>
	<!-- REPORTS BUTTON -->
	<div id="reports" class="col-md-1 navibutton">
	<div class="flipdown">
		<a  class="reports flipbtn">
			<img src="Images/tax.png" class="center-block">
			<h6 class="text-center">REPORTS <span class="caret"></span></h6>
		</a>
		<div class="flipdown-menu">
			<div class="sub-flipdown">
				<div class="sub-flipdown-menu">
					<a href="reports_individual_earnings.php?type=Earnings&period=week&site=null&position=null"> Individual</a>
					<a href="reports_overall_earnings.php?type=Earnings&period=Weekly"> Overall</a>
				</div>
				<a class="subflipbtn">Earnings</a>
				
			</div>
			<div class="sub-flipdown">
				<div class="sub-flipdown-menu">
					<a href="reports_individual_contributions.php?type=Contributions&period=week&site=null&position=null"> Individual</a>
					<a href="reports_overall_contributions.php?type=Contributions&period=Weekly"> Overall</a>
				</div>
				<a class="subflipbtn">Contributions</a>
			</div>
			<div class="sub-flipdown">
				<div class="sub-flipdown-menu">
					<a href="reports_individual_loans.php?type=Loans&period=week&site=null&position=null"> Individual</a>
					<a href="reports_overall_loans.php?type=Loans&period=Weekly"> Overall</a>
				</div>
				<a class="subflipbtn">Loans</a>
				
			</div>
			<div class="sub-flipdown">
				<div class="sub-flipdown-menu">
					<a href="reports_individual_attendance.php?type=Attendance&period=week&site=null&position=null"> Individual</a>
					<a href="reports_overall_attendance.php?type=Attendance&period=Weekly"> Overall</a>
				</div>
				<a class="subflipbtn">Attendance</a>
			</div>
			<div class="sub-flipdown">
				<a class="subflipbtn" href="reports_payslip_individual.php?type=Payslip&period=week&site=null&position=null">Payslip</a>
			</div>
			<div class="sub-flipdown">
				<a class="subflipbtn" href="reports_company_expenses.php?type=Expenses&period=Weekly">Company Expenses</a>
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