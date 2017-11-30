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
			<h6 class="text-center">REPORTS</h6>
		</a>
		<div class="flipdown-menu">
			<div class="sub-flipdown">
				<a class="subflipbtn">Earnings</a>
				<div class="sub-flipdown-menu">
					<a href="reports_individual.php?type=earnings">Individual</a>
					<a>Overall</a>
				</div>
			</div>
			<div class="sub-flipdown">
				<a class="subflipbtn">Contributions</a>
				<div class="sub-flipdown-menu">
					<a href="reports_individual.php?type=contributions">Individual</a>
					<a>Overall</a>
				</div>
			</div>
			<div class="sub-flipdown">
				<a class="subflipbtn">Loans</a>
				<div class="sub-flipdown-menu">
					<a href="reports_individual.php?type=loans">Individual</a>
					<a>Overall</a>
				</div>
			</div>
			<div class="sub-flipdown">
				<a class="subflipbtn">Attendance</a>
				<div class="sub-flipdown-menu">
					<a href="reports_individual.php?type=attendance">Individual</a>
					<a>Overall</a>
				</div>
			</div>
			<div class="sub-flipdown">
				<a class="subflipbtn">Payroll</a>
				<div class="sub-flipdown-menu">
					<a href="reports_individual.php?type=payroll">Individual</a>
					<a>Overall</a>
				</div>
			</div>
			<div class="sub-flipdown">
				<a class="subflipbtn">Payslip</a>
				<div class="sub-flipdown-menu">
					<a href="reports_individual.php?type=payslip">Individual</a>
					<a>Overall</a>
				</div>
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