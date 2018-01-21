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

		<div class="col-md-10 col-md-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_overall_earnings.php?type=Earnings&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Overall Payroll Report for <?php Print $location?></li>
					</ol>
				</div>
			</div>

		<div class="row">
			<div class="col-md-3 col-md-offset-3">
				<h4>Step 1: Select Requirements</h4>
				<select onchange="payrollRequirements(this.value);" class="form-control" id="step1">
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
			<div class="col-md-3">
					<h4>Step 2: Select Payroll Dates</h4>
					<select onchange="payrollDates(this.value)" class="form-control" id="step2" disabled>
						<option selected>Select date</option>
						<?php
						$payrollDays = "SELECT DISTINCT date FROM Payroll ORDER BY date ASC";//gets non repeatable dates
						$payrollDaysQuery = mysql_query($payrollDays);

						if(mysql_num_rows($payrollDaysQuery))
						{
							while($PdaysOptions = mysql_fetch_assoc($payrollDaysQuery))
							{

								$startDate = date('F j, Y', strtotime('-6 day', strtotime($PdaysOptions['date'])));

								if(isset($_POST['payrollDate']))
								{
									if($_POST['payrollDate'] == $PdaysOptions['date'])
									{
										Print "<option value='".$PdaysOptions['date']."' selected>".$startDate." - ".$PdaysOptions['date']."</option>";
									}
									else
									{
										Print "<option value='".$PdaysOptions['date']."'>".$startDate." - ".$PdaysOptions['date']."</option>";
									}
								}
								else
								{
									Print "<option value='".$PdaysOptions['date']."'>".$startDate." - ".$PdaysOptions['date']."</option>";
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

	
		<?php 

			if(isset($_POST['payrollDate'])) {
				Print '<div class="row pull-down">
							<a class="btn btn-default" href="print_payroll.php?site='.$location.'&date='.$_POST['payrollDate'].'">
								Print Payroll
							</a>
						</div>';
				if($req == 'all')
					$reqMessage = "All ".$location." employees";
				else if($req == 'withReq')
					$reqMessage = $location." W/ Requirements";
				else if($req == 'withOReq')
					$reqMessage = $location." W/o Requirements";
				Print '
						<table class="table table-bordered pull-down">
							<tr>
								<td colspan="6">
									'.$reqMessage .'
								</td>
								<td colspan="21" rowspan="2" class="vertical-align">
									PAYROLL
								</td>
							</tr>
							<tr>
								<td colspan="6">
									Date covered: '.$startDate.' - '.$_POST['payrollDate'].'
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
									tools
								</td>
								<td>
									Total Salary
								</td>
							</tr>';
						
						$date = $_POST['payrollDate'];

						if($req == 'all')
						{
							$employee = "SELECT * FROM employee WHERE site = '$location' ORDER BY lastname ASC, position ASC";
						}
						else if($req == 'withReq')
						{
							$employee = "SELECT * FROM employee WHERE site = '$location' AND complete_doc = '1'ORDER BY lastname ASC, position ASC";
						}
						else if($req == 'withOReq')
						{
							$employee = "SELECT * FROM employee WHERE site = '$location' AND complete_doc = '0'ORDER BY lastname ASC, position ASC";
						}

						

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
									$payrollArr = mysql_fetch_assoc($payrollQuery);

									$color = ($rowNum % 2 == 0 ? "#ECF0F1" : "#FDFEFE");//alternating color

									Print '	<tr bgcolor="'.$color.'">
												<td><!-- # -->
													'.$rowNum.'
												</td>
												<td><!-- Name -->
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
													'.$payrollArr['reg_holiday_num'].'
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
												<td><!-- tools -->
													'.$payrollArr['tools_paid'].'
												</td>
												<td><!-- Total Salary -->
													'.numberExactFormat($payrollArr['total_salary'],2,".").'
												</td>
											</tr>';
										
										$rowNum++;//increment the row number
								}
								
							}
							Print '</table>';
						}
						else
						{
							Print '	<tr>
										<td colspan="27">
											<h4>No Payroll data at the moment</h4>
										</td>
									</tr>';
						}
						
			}
			else {
				Print "<h4 class='pull-down-more'>Select Requirements and Date to view Payroll report.</h4>";
			}
		?>
		

	</div>
</div>
	<form id="dynamicForm" method="POST" action="reports_overall_payroll.php?req=<?php Print $req?>&site=<?php Print $location?>">
		<input type="hidden" name="payrollDate" id="payrollDate">
	</form>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
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
			console.log(step1);
			if(step1 !== '-- All / With / Without --') {
				document.getElementById('step2').disabled = false;
			}
		}
	</script>
</body>
</html>