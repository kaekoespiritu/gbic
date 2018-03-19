<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$site = $_GET['site'];
	$require = $_GET['req'];
	$position = $_GET['position'];
	$period = $_GET['period'];

	//Checks if site in HTTP is altered by user manually
	$siteChecker = "SELECT * FROM site WHERE location = '$site'";
	//Checks if position in HTTP is altered by user manually 
	$positionChecker = "SELECT * FROM job_position WHERE position = '$position'";

	$siteCheckerQuery = mysql_query($siteChecker);
	$positionCheckerQuery = mysql_query($positionChecker);
	if(mysql_num_rows($siteCheckerQuery) == 0)
	{
		header("location:reports_company_expenses.php?type=Expenses&period=Weekly");
	}
	if($position != 'all')
	{
		if(mysql_num_rows($positionCheckerQuery) == 0)
		{
			header("location:reports_company_expenses.php?type=Expenses&period=Weekly");
		}
	}
		
	
	// Checks if requirement in HTTP is altered by user manually 
	switch($require) {
		case "null":break;
		case "all":break;
		case "withReq":break;
		case "withOReq":break;
		default: header("location:reports_company_expenses.php?type=Expenses&period=Weekly");;
	}
	//Checks if period in HTTP is altered by user manually 
	switch($period) {
		case "week": $periodDisplay = "Weekly";break;
		case "month": $periodDisplay = "Monthly";break;
		case "year": $periodDisplay = "Yearly";break;
		default: header("location:reports_company_expenses.php?type=Expenses&period=Weekly");;
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
<body style="font-family: Quicksand;">

	<div class="container-fluid">

		<?php
		require_once("directives/nav.php");
		?>

		<!-- 
			Add the following:
			Filter selection if necessary
			Table for expenses
		-->

		<div class="col-md-10 col-md-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_company_expenses.php?type=Expenses&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Expenses</a></li>
						<li>Overall Company Expense Report for <?php Print $site?></li>
					</ol>
				</div>
			</div>
			<div class="form-inline">
				<h4>Select Period</h4>
				<select onchange="periodChange(this.value)" class="form-control">
					<?php 
						if($period == "week")
							Print "<option value='week' selected>Weekly</option>";
						else
							Print "<option value='week'>Weekly</option>";
						if($period == "month")
							Print "<option value='month'selected>Monthly</option>";
						else
							Print "<option value='month'>Monthly</option>";
						if($period == "year")
							Print "<option value='year' selected>Yearly</option>";
						else
							Print "<option value='year'>Yearly</option>";
					?>
				</select>
				<h4>Select <?php Print $period?></h4>
					<select class="form-control" onchange="changeDate(this.value)">
						<option hidden>Choose a <?php Print $period?></option>
					<?php
						

							
							$payrollDates = "SELECT DISTINCT date FROM payroll";
							$payrollDQuery = mysql_query($payrollDates) or die(mysql_error());
							
							if(mysql_num_rows($payrollDQuery) > 0)//check if there's payroll
							{
								$monthNoRep = "";
								$yearNoRep = "";
								while($payrollDateArr = mysql_fetch_assoc($payrollDQuery))
								{
									
									if($_GET['period'] == 'week')
									{
										$payrollEndDate = $payrollDateArr['date'];
										$payrollStartDate = date('F j, Y', strtotime('-6 day', strtotime($payrollEndDate)));
										if(isset($_POST['date']))
										{
											if($_POST['date'] == $payrollEndDate)
											{
												Print "<option value = '".$payrollEndDate."' selected>".$payrollStartDate." - ".$payrollEndDate."</option>";
											}
											else
											{
												Print "<option value = '".$payrollEndDate."'>".$payrollStartDate." - ".$payrollEndDate."</option>";
											}
										}
										else
										{
											Print "<option value = '".$payrollEndDate."'>".$payrollStartDate." - ".$payrollEndDate."</option>";
										}
									}
									else if($_GET['period'] == 'month')
									{
										$payrollArrDate = explode(" ", $payrollDateArr['date']);
										$payrollMonth = $payrollArrDate[0];
										$payrollYear = $payrollArrDate[2];

										if($monthNoRep != $payrollMonth." ".$payrollYear)
										{	
											if(isset($_POST['date']))
											{
												if($_POST['date'] == $payrollMonth." ".$payrollYear)
												{
													Print "<option value = '".$payrollMonth." ".$payrollYear."' selected>".$payrollMonth." ".$payrollYear."</option>";
												}
												else
												{
													Print "<option value = '".$payrollMonth." ".$payrollYear."'>".$payrollMonth." ".$payrollYear."</option>";
												}
											}
											else
											{
												Print "<option value = '".$payrollMonth." ".$payrollYear."'>".$payrollMonth." ".$payrollYear."</option>";
											}
										}
										$monthNoRep = $payrollMonth." ".$payrollYear;
									}
									else if($_GET['period'] == 'year')
									{
										$payrollArrDate = explode(" ", $payrollDateArr['date']);
										$payrollYear = $payrollArrDate[2];
										$yearBef = $payrollYear -1;//gets the year before

										if($yearNoRep != $payrollYear)
										{	
											if(isset($_POST['date']))
											{
												if($_POST['date'] == $payrollYear)
												{
													Print "<option value = '".$payrollYear."' selected>".$yearBef." - ".$payrollYear."</option>";
												}
												else
												{
													Print "<option value = '".$payrollYear."'>".$yearBef." - ".$payrollYear."</option>";
												}
											}
											else
											{
												Print "<option value = '".$payrollYear."'>".$yearBef." - ".$payrollYear."</option>";
											}
											
										}
										$yearNoRep = $payrollYear;
									}
									
								}
							}
					
					?>
				</select>
				<h5>Filter:</h5>
				<h4>Position</h4>
				<select onchange="positionChange(this.value)" class="form-control">
					<option hidden>position</option>
					<?php 
						$position_dd = "SELECT * FROM job_position WHERE active = '1'";
						$posQuery = mysql_query($position_dd);

						while($posArr = mysql_fetch_assoc($posQuery))
						{
							if($position == $posArr['position'])
								Print "<option value = '".$posArr['position']."' selected>".$posArr['position']."</option>";
							else
								Print "<option value = '".$posArr['position']."'>".$posArr['position']."</option>";
						}
					?>
				</select>
				<h4>Requirement</h4>
				<select onchange="requirementChange(this.value)" class="form-control">
					<?php 
						if($require == "all")
							Print "<option value='all' selected>All</option>";
						else
							Print "<option value='all'>All</option>";
						if($require == "withReq")
							Print "<option value='withReq'selected>W/ Requirements</option>";
						else
							Print "<option value='withReq'>W/ Requirements</option>";
						if($require == "withOReq")
							Print "<option value='withOReq' selected>W/o Requirements</option>";
						else
							Print "<option value='withOReq'>W/o Requirements</option>";
					?>
				</select>
				<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filters</button>
			</div>
		</div>

		<?php
		$printDate = "";
		if(isset($_POST['date']))
		{
			$printDate = $_POST['date'];//for printable

			Print "<script>console.log('".$_POST['date']."')</script>";
			Print "
				<button class='btn btn-default pull-down' onclick='printReport()'>
					Print ".$periodDisplay." Expense Report
				</button>
				";
			

			Print "<table class='table table-bordered pull-down'>
					<tr>
						<td colspan='12'>
							".$periodDisplay." Expenses for ".$site."
						</td>
					</tr>
					<tr>
						<td rowspan='3'>
							Name
						</td>
						<td rowspan='3'>
							Position
						</td>
						
						<td rowspan='3'>
							Salary Received(W/o Contributions)
						</td>
						<td colspan='6'>
							Contributions
						</td>
						<td colspan='2' rowspan='2'>
							Vale
						</td>
						<td rowspan='3'>
							Total
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							SSS
						</td>
						<td colspan='2'>
							Pag-ibig
						</td>
						<td colspan='2'>
							PhilHealth
						</td>
					</tr>
					<tr>
						<td>
							Employee
						</td>
						<td>
							Employer
						</td>
						<td>
							Employee
						</td>
						<td>
							Employer
						</td>
						<td>
							Employee
						</td>
						<td>
							Employer
						</td>
						<td>
							Old
						</td>
						<td>
							New
						</td>
					</tr>";

			//filters
			$filter = "";
			if($require != "withReq")
				$filter .= "AND complete_doc = '1' ";
			else if($require != "withOReq")
				$filter .= "AND complete_doc = '0' ";
			if($position != "all")
			{
				if($filter != "")
					$filter .= "AND";

				$filter .= " position = '".$position."' ";
			}


			$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' $filter ORDER BY lastname ASC, position ASC";

			$empQuery = mysql_query($employee);

			$TotalBool = false;//boolean for displaying grandtotal
			$date = $_POST['date'];
			if(mysql_num_rows($empQuery) != 0)
			{
				//Checker to know what the employer chose (weekly, monthly, yearly)
				$numChecker = explode(" ",$_POST['date']);
				$countChecker = count($numChecker); // 3 = weekly | 2 = monthly | 1 = yearly

				$GrandTotal = 0;
				while($empArr = mysql_fetch_assoc($empQuery))
				{
					$employeeTotal = 0;
					$empid = $empArr['empid'];
					//Create Query for Monthly and yearly
					if($countChecker == 3)//Weekly
					{
						$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$date'";
					}
					else if($countChecker == 2)//Monthly
					{
						$month = $numChecker[0];
						$year = $numChecker[1];

						$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND (date LIKE '$month%' AND date LIKE '%$year')";

					}
					else if($countChecker == 1)//Yearly
					{
						$year = $numChecker[0];

						$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date LIKE '%$year'";
					}
					$payrollQuery = mysql_query($payroll);

					if(mysql_num_rows($payrollQuery) != 0)
					{ 
						$TotalBool = true;//there is data
						$counter = 0;
						$numPayrollEmp = mysql_num_rows($payrollQuery);

						$totalSalary = 0;
						$NewValeBalance = 0;
						$OldValeBalance = 0;
						$sssEE = 0;
						$sssER = 0;
						$philhealthEE = 0;
						$philhealthER = 0;
						$pagibigEE = 0;
						$pagibigER = 0;

						while($payrollArr = mysql_fetch_assoc($payrollQuery))
						{
							$counter++;//counter for getting the payroll and compiling all the data
							
							//contributions

							$subTotalSalary = $payrollArr['total_salary'] - $payrollArr['sss'] - $payrollArr['philhealth'] - $payrollArr['pagibig'];
							$subTotalSalary = abs($subTotalSalary);

							$totalSalary += $subTotalSalary;
							$sssEE += $payrollArr['sss'];
							$sssER += $payrollArr['sss_er'];
							$philhealthEE += $payrollArr['philhealth'];
							$philhealthER += $payrollArr['philhealth_er'];
							$pagibigEE += $payrollArr['pagibig'];
							$pagibigER += $payrollArr['pagibig_er'];

							$startDate = date('F j, Y', strtotime('-6 day', strtotime($payrollArr['date'])));
							$endDate = $payrollArr['date'];

							$loanCheckNew = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'newVale' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC";
							$loanCheckOld = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC";
							
							$loanQueryNew = mysql_query($loanCheckNew);
							$loanQueryOld = mysql_query($loanCheckOld);

							$NewValeBalance = 0;
							while($loanArrNew = mysql_fetch_assoc($loanQueryNew));
							{
								if($loanArrNew['action'] == '1')//loaned
									$NewValeBalance += $loanArrNew['amount'];
								else
									$NewValeBalance -= $loanArrNew['amount'];
								$NewValeBalance = abs($NewValeBalance);//absolute value
							}
							$OldValeBalance = 0;
							while($loanArrOld = mysql_fetch_assoc($loanQueryOld));
							{
								if($loanArrOld['action'] == '1')//loaned
									$OldValeBalance += $loanArrOld['amount'];
								else
									$OldValeBalance -= $loanArrOld['amount'];
								$OldValeBalance = abs($OldValeBalance);//absolute value
							}


							if($counter == $numPayrollEmp)
							{
								$employeeTotal = $totalSalary + $sssEE + $sssER + $pagibigEE + $pagibigER + $philhealthEE + $philhealthER + $OldValeBalance  + $NewValeBalance;
								Print "
									<tr>
										<td>
											".$empArr['lastname'].", ".$empArr['firstname']."
										</td>
										<td>
											".$empArr['position']."
										</td>
										<td>
											".numberExactFormat($totalSalary, 2, '.', true)."
										</td>
										<td>
											".numberExactFormat($sssEE, 2, '.', true)."
										</td>
										<td>
											".numberExactFormat($sssER, 2, '.', true)."
										</td>
										<td>
											".numberExactFormat($pagibigEE, 2, '.', true)."
										</td>
										<td>
											".numberExactFormat($pagibigER, 2, '.', true)."
										</td>
										<td>
											".numberExactFormat($philhealthEE, 2, '.', true)."
										</td>
										<td>
											".numberExactFormat($philhealthER, 2, '.', true)."
										</td>
										<td>
											".numberExactFormat($OldValeBalance, 2, '.', true) ."
										</td>
										<td>
											".numberExactFormat($NewValeBalance, 2, '.', true) ."
										</td>
										<td>
											".numberExactFormat($employeeTotal, 2, '.', true)."
										</td>
									</tr>
									";
									$GrandTotal += $employeeTotal;
								}
						}
					}
				}
			}
			else
			{
				Print "
					<tr>
						<td colspan ='12'>
							No employee as of the moment.
						</td>
					</tr>
					";
			}

			if($TotalBool)
			{
				Print "
					<tr>
						<td colspan ='10'>
							Grand Total:
						</td>
						<td>
							Grand Total:
						</td>
						<td>
							".numberExactFormat($GrandTotal, 2, '.', true)."
						</td>
					</tr>
					";
			}
			Print "
					</table>
				";	
			
		}
		
		?>

	</div>

	<form method='post' action="reports_company_expenses_overall.php?site=<?php Print $site?>&period=<?php Print $period?>&position=<?php Print $position?>&req=<?php Print $require?>" id="dynamicForm">
		<input type='hidden' name='date'>
	</form>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function periodChange(period) {
			window.location.assign("reports_company_expenses_overall.php?site=<?php Print $site?>&period="+period+"&position=<?php Print $position?>&req=<?php Print $require?>");
		}

		function requirementChange(req) {
			window.location.assign("reports_company_expenses_overall.php?site=<?php Print $site?>&period=<?php Print $period?>&position=<?php Print $position?>&req="+req);
		}

		function positionChange(position) {
			window.location.assign("reports_company_expenses_overall.php?site=<?php Print $site?>&period=<?php Print $period?>&position="+position+"&req=<?php Print $require?>");
		}
		function changeDate(date) {
			document.getElementsByName('date')[0].value = date;
			document.getElementById('dynamicForm').submit();
		}

		function clearFilter() {
			window.location.assign("reports_company_expenses_overall.php?site=<?php Print $site?>&period=<?php Print $period?>&position=all&req=all");
		}

		function printReport(){
			window.location.assign("print_company_expenses.php?site=<?php Print $site?>&period=<?php Print $period?>&position=<?php Print $position?>&req=<?php Print $require?>&date=<?php Print $printDate?>");
		}

	</script>
</body>
</html>