<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$location = $_GET['site'];
	$req = $_GET['req'];//requirements
	$site = "SELECT * FROM site WHERE location = '$location'";
	$siteQuery = mysql_query($site);

	if(mysql_num_rows($siteQuery) == 0)
	{
		header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
	}


?>
<html>
<head>
	<title>Payroll</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">

	<!-- For pagination -->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />
</head>
<body style="font-family: Quicksand;" onload="enableDropdown()">

	<div class="container-fluid">

		<?php
		require_once("directives/nav.php");
		?>

		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_overall_earnings.php?type=Earnings&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Overall Payroll Report for <?php Print $location?></li>
					</ol>
				</div>
			</div>

		<div class="row">
			<div class="col-md-3 col-lg-3 col-md-offset-3 col-lg-offset-3">
				<h4>Step 1: Select Requirements</h4>
				<select onchange="payrollRequirements(this.value)" class="form-control" id="step1">
					<?php 
					if($req == 'null')
						Print "<option selected>-- All / With / Without --</option>";
					if($req == 'all')
						Print "<option value='all' selected>All</option>";
					else
						Print "<option value='all'>All</option>";
					if($req == 'withReq')
						Print "<option value='withReq' selected>With Requirements</option>";
					else
						Print "<option value='withReq'>With Requirements</option>";
					if($req == 'withOReq')
						Print "<option value='withOReq' selected>W/o Requirements</option>";
					else
						Print "<option value='withOReq'>W/o Requirements</option>";

					?>
				</select>
			</div>
			<div class="col-md-3 col-lg-3">
					<h4>Step 2: Select Payroll Dates</h4>
					<select onchange="payrollDates(this.value)" class="form-control" id="step2" disabled>
						<option hidden>Select date</option>
						<?php
						$payrollDays = "SELECT DISTINCT date FROM Payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";//gets non repeatable dates
						$payrollDaysQuery = mysql_query($payrollDays);

						if(mysql_num_rows($payrollDaysQuery))
						{
							while($PdaysOptions = mysql_fetch_assoc($payrollDaysQuery))
							{
								$payDay = $PdaysOptions['date'];
								$endDate = date('F d, Y', strtotime('-1 day', strtotime($PdaysOptions['date'])));
								$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

								if(isset($_POST['payrollDate']))
								{
									if($_POST['payrollDate'] == $payDay)
									{
										Print "<option value='".$payDay."' selected>".$startDate." - ".$endDate."</option>";
									}
									else
									{
										Print "<option value='".$payDay."'>".$startDate." - ".$endDate."</option>";
									}
								}
								else
								{
									Print "<option value='".$payDay."'>".$startDate." - ".$endDate."</option>";
								}
							}
						}
						else
						{
							Print "<option>No payroll date</option>";
						}
						?>
					</select>
			</div>
    	</div>
    </div>


			<?php 

				if(isset($_POST['payrollDate'])) {
					Print '<div class="row pull-down">
					<div class="col-md-1 col-lg-12 pull-down">
								<a class="btn btn-default" id="printPayroll" href="print_overall_payroll.php?site='.$location.'&date='.$_POST['payrollDate'].'&req='.$req.'">
									Print Payroll
								</a>
								<a class="btn btn-default" id="printPayslip" onclick="printPayslips()">
									Print Payslips
								</a>
								</div>
							</div>';
					if($req == 'all')
						$reqMessage = "All ".$location." employees";
					else if($req == 'withReq')
						$reqMessage = $location." W/ Requirements";
					else if($req == 'withOReq')
						$reqMessage = $location." W/o Requirements";
					Print '<div class="row">
							<div class="col-md-1 col-lg-12 overflow">
							<table class="table table-bordered pull-down">
								<tr>
									<td colspan="6">
										'.$reqMessage .'
									</td>
									<td colspan="23" rowspan="2" class="vertical-align">
										PAYROLL
									</td>
								</tr>
								<tr>
									<td colspan="6">
										Date covered: '.$startDate.' - '.$endDate.'
									</td>
								</tr>
								<tr>
									<td>
										#
									</td>
									<td>
										Name
									</td>
									<td>
										Position
									</td>
									<td>
										Rate
									</td>
									<td>
										# of days
									</td>
									<td>
										O.T.
									</td>
									<td>
										# of hours
									</td>
									<td>
										Allow.
									</td>
									<td>
										COLA
									</td>
									<td>
										Sun
									</td>
									<td>
										D
									</td>
									<td>
										hrs
									</td>
									<td>
										N.D.
									</td>
									<td>
										#
									</td>
									<td>
										Reg. Hol
									</td>
									<td>
										#
									</td>
									<td>
										Spe. Hol
									</td>
									<td>
										#
									</td>
									<td>
										X All.
									</td>
									<td>
										SSS
									</td>
									<td>
										Philhealth
									</td>
									<td>
										PagIBIG
									</td>
									<td>
										Old vale
									</td>
									<td>
										vale
									</td>
									<td>
										SSS loan
									</td>
									<td>
										P-ibig loan
									</td>
									<td>
										Ins.
									</td>
									<td>
										tools
									</td>
									<td>
										Total Salary
									</td>
								</tr>';
							
							$date = $_POST['payrollDate'];

							if($req == 'all')
							{
								$employee = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' ORDER BY lastname ASC, position ASC";
							}
							else if($req == 'withReq')
							{
								$employee = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' AND complete_doc = '1'ORDER BY lastname ASC, position ASC";
							}
							else if($req == 'withOReq')
							{
								$employee = "SELECT * FROM employee WHERE site = '$location' AND employment_status = '1' AND complete_doc = '0'ORDER BY lastname ASC, position ASC";
							}

						$printBool = 1;
						$dataBool = true;//Boolean if there is data for the payroll
						$employeeQuery = mysql_query($employee);
						if(mysql_num_rows($employeeQuery) >= 1)
						{
							$color = "#ECF0F1";//for alternating color
							$rowNum = 1;
							
							while($employeeArr = mysql_fetch_assoc($employeeQuery))
							{
								$emplid = $employeeArr['empid'];
								$payroll = "SELECT * FROM payroll WHERE date = '$date' AND empid='$emplid'";
								$payrollQuery = mysql_query($payroll);
								if(mysql_num_rows($payrollQuery) >= 1)
								{
									$dataBool = false;
									$payrollArr = mysql_fetch_assoc($payrollQuery);

									//Gets the actual holiday num
									if($payrollArr['reg_holiday_num'] > 1)
									{
										$holidayRegChecker = "SELECT * FROM holiday AS h INNER JOIN attendance AS a ON h.date = a.date WHERE a.empid = '$emplid' AND a.attendance = '2' AND h.type = 'regular'";
										$holidayRegQuery = mysql_query($holidayRegChecker);
										$regHolidayNum = mysql_num_rows($holidayRegQuery);
									}
									else if($payrollArr['reg_holiday_num'] == 1)
									{
										$regHolidayNum = 1;
									}
									else
									{
										$regHolidayNum = 0;
									}

									$color = ($rowNum % 2 == 0 ? "#ECF0F1" : "#FDFEFE");//alternating color

									Print '	<tr bgcolor="'.$color.'">
												<td><!-- # -->
													'.$rowNum.'
												</td>
												<td align="left"><!-- Name -->
													'.$employeeArr['lastname'].', '.$employeeArr['firstname'].'
												</td>
												<td><!-- Position -->
													'.$employeeArr['position'].'
												</td>
												<td><!-- Rate -->
													'.$payrollArr['rate'].'
												</td>
												<td><!-- # of days -->
													'.$payrollArr['num_days'].'
												</td>
												<td><!-- OT -->
													'.$payrollArr['overtime'].'
												</td>
												<td><!-- # of hours -->
													'.$payrollArr['ot_num'].'
												</td>
												<td><!-- Allow -->
													'.$payrollArr['allow'].'
												</td>
												<td><!-- COLA -->
													'.$payrollArr['cola'].'
												</td>
												<td><!-- Sun -->
													'.$payrollArr['sunday_rate'].'
												</td>
												<td><!-- D -->
													'.$payrollArr['sunday_att'].'
												</td>
												<td><!-- hrs -->
													'.$payrollArr['sunday_hrs'].'
												</td>
												<td><!-- ND -->
													'.$payrollArr['nightdiff_rate'].'
												</td>
												<td><!-- # -->
													'.$payrollArr['nightdiff_num'].'
												</td>
												<td><!-- Reg.hol -->
													'.$payrollArr['reg_holiday'].'
												</td>
												<td><!-- # -->
													'.$regHolidayNum.'
												</td>
												<td><!-- Spe. hol -->
													'.$payrollArr['spe_holiday'].'
												</td>
												<td><!-- # -->
													'.$payrollArr['spe_holiday_num'].'
												</td>
												<td><!-- X.All -->
													'.$payrollArr['x_allowance'].'
												</td>
												<td><!-- SSS -->
													'.$payrollArr['sss'].'
												</td>
												<td><!-- Philhealth -->
													'.$payrollArr['philhealth'].'
												</td>
												<td><!-- Pagibig -->
													'.$payrollArr['pagibig'].'
												</td>
												<td><!-- Old vale -->
													'.$payrollArr['old_vale'].'
												</td>
												<td><!-- vale -->
													'.$payrollArr['new_vale'].'
												</td>
												<td><!-- sss loan -->
													'.$payrollArr['loan_sss'].'
												</td>
												<td><!-- pagibig loan -->
													'.$payrollArr['loan_pagibig'].'
												</td>
												<td><!-- insurance -->
													'.$payrollArr['insurance'].'
												</td>
												<td><!-- tools -->
													'.$payrollArr['tools_paid'].'
												</td>
												<td><!-- Total Salary -->
													'.numberExactFormat($payrollArr['total_salary'],2,".", true).'
												</td>
											</tr>';
										
										$rowNum++;//increment the row number
								}
									
							}


								
						}
							
						
						else
						{
							$printBool = 0;
							$dataBool = false;
							Print '	<tr>
										<td colspan="27">
											<h4>No Payroll data at the moment</h4>
										</td>
									</tr>';
						}

						if($dataBool)
						{
							$printBool = 0;
							Print '	<tr>
										<td colspan="27">
											<h4>No Payroll data at the moment</h4>
										</td>
									</tr>';
						}
						Print '</table>';
						
			}
			else
			{
				Print "<h4 class='pull-down-more col-md-1 col-lg-12'>Select Requirements and Date to view Payroll report.</h4>";
			}
			?>
			</div>

	</div>
</div>
	<form id="dynamicForm" method="POST" action="reports_overall_payroll.php?req=<?php Print $req?>&site=<?php Print $location?>">
		<input type="hidden" name="payrollDate" id="payrollDate">
	</form>

	<input type="hidden" id="printButton" value="<?php Print $printBool?>">
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		$( document ).ready(function() {
		   	if($('#printButton').val() == 1)
		   	{
		   		$('#printPayslip').removeClass('disabletotally');
		   		$('#printPayroll').removeClass('disabletotally');
		   	}
		   	else
		   	{
		   		$('#printPayslip').addClass('disabletotally');
		   		$('#printPayroll').addClass('disabletotally');
		   	}	
		});
	</script>
	<script>

		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function payrollRequirements(req) {
			window.location.assign("reports_overall_payroll.php?req="+req+"&site=<?php Print $location?>");
		}

		function payrollDates(date) {
			document.getElementById('payrollDate').value = date;
			document.getElementById('dynamicForm').submit();
		}

		function enableDropdown(item) {
			// check if step1 has a selection
			// enable dropdown if step1 has a selected value
			var item = document.getElementById('step1');
			var step1 = item.options[item.selectedIndex].text;
			if(step1 !== '-- All / With / Without --') {
				document.getElementById('step2').disabled = false;
			}
		}

		function printPayslips() {
			var req = document.getElementById('step1').value;
			var date = document.getElementById('step2').value;

			window.location.assign("print_overall_payslip.php?req="+req+"&date="+date+"&site=<?php Print $location?>");
		}
	</script>
</body>
</html>