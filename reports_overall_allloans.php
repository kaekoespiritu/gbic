<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$site = $_GET['site'];
	$period = $_GET['period'];

//Middleware
	//site Checker 
	$siteCheck = "SELECT * FROM site WHERE location = '$site'";
	$siteQuery = mysql_query($siteCheck);
	if(mysql_num_rows($siteQuery) == 0)
		header("location: index.php");

	//period Checker
	switch($period)
	{
		case "all": $periodDisplay = "All"; break;
		case "week": $periodDisplay = "Weekly"; break;
		case "month": $periodDisplay = "Monthly"; break;
		case "year": $periodDisplay = "Yearly"; break;
		default: header("location: index.php");
	}

	$loansBool = false;//boolean for post all

	if(isset($_POST['date']))
	{
		if($_POST['date'] == "all")
		{
			$loansBool = true;
		}
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

		<div class="col-md-10 col-md-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_overall_loans.php?type=Loans&period=Weekly' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Earnings</a></li>
						<li>Overall Loans Report for employees at <?php Print $site?></li>
					</ol>
				</div>
			</div>

			<div>

				<div class="col-md-6">
						<h4>Select Period</h4>
						<select onchange="periodChange(this.value)" class="form-control" id="period">
							<?php 
								if($period == "all")
									Print "<option value='all' selected>All</option>";
								else
									Print "<option value='all'>All</option>";
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
					</div>
					<?php
					if($period != "all") 
					{
						Print '<div class="col-md-6">
									<h4>Select <?php Print $period?></h4>
									<select class="form-control" onchange="changeDate(this.value)">
										<option hidden>Choose a '.$period.'</option>';

							
							$payrollDates = "SELECT DISTINCT date FROM payroll";
							$payrollDQuery = mysql_query($payrollDates) or die(mysql_error());
							if(isset($_POST['date']))
							{
								if($_POST['date'] == 'all')
									Print "<option value='all' selected>All</option>";
								else
									Print "<option value='all'>All</option>";
							}
							else
									Print "<option value='all'>All</option>";

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
					
							
						
							
						Print '		</select>
								</div>';
					}
					?>
				<button class="btn btn-default pull-down">
					Print <?php Print $periodDisplay?>
				</button>

				<table class="table table-bordered pull-down">
					<tr>
						<?php 
						if(!isset($_POST['date']) || $loansBool)
							Print "	<td colspan='3'>
										Period: All
									</td>";
						else
							Print "	<td colspan='3'>
										Period: ".$periodDisplay."
									</td>";
						?>
						
						<td colspan="5" rowspan="2">
							Loan Type Report
						</td>
					</tr>
					<tr>
						<?php 
						if(isset($_POST['date']))
						{
							if($_POST['date'] == "all")
							{}
							else if($_POST['numLen'] == 3)//Weekly
							{
								$startDate = date('F j, Y', strtotime('-6 day', strtotime($_POST['date'])));
								$endDate = $_POST['date'];
								Print "	<td colspan='3'>
											Date: ".$startDate." - ".$endDate."
										</td>";
							}
							else if($_POST['numLen'] == 2)//Monthly
							{
								$dateSplit = explode(' ', $_POST['date']);
								$month = $dateSplit[0];
								$year = $dateSplit[1];
								Print "	<td colspan='3'>
											Date: ".$month." ".$year."
										</td>";
							}
							else if($_POST['numLen'] == 1)//Yearly
							{
								$year = $_POST['date'];
								Print "	<td colspan='3'>
											Date: ".$year."
										</td>";
							}

						}
						
						?>
						
					</tr>
					<tr>
						<td>
							Name
						</td>
						<td>
							Site
						</td>
						<td>
							Position
						</td>
						<td>
							SSS
						</td>
						<td>
							PagIBIG
						</td>
						<td>
							Old Vale
						</td>
						<td>
							New Vale
						</td>
					</tr>
					<?php
						$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' ORDER BY lastname ASC, position ASC";

						$empQuery = mysql_query($employee) or die (mysql_error());

						$sssGrandTotal = 0;
						$PagibigGrandTotal = 0;
						$newValeGrandTotal = 0;
						$oldValeGrandTotal = 0;
						
						
						while($empArr = mysql_fetch_assoc($empQuery))
						{
							$empid = $empArr['empid'];
							//check if employee has past loans
							for($counter = 0; $counter <= 3 ;$counter++)
							{
								switch($counter) 
								{
									case 0: $loanType = 'PagIBIG';break;
									case 1: $loanType = 'SSS';break;
									case 2: $loanType = 'NewVale';break;
									case 3: $loanType = 'OldVale';break;
								}
								if(isset($_POST['date']))
								{
									if($_POST['date'] == "all")
										$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC, time DESC LIMIT 1";

									else if($_POST['numLen'] == 3)//weekly
										$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC, time DESC LIMIT 1";

									else if($_POST['numLen'] == 2)//monthly
										$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' AND (date LIKE '$month%' AND date LIKE '%$year') ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC, time DESC LIMIT 1";

									else if($_POST['numLen'] == 1)//yearly
										$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' AND date LIKE '%$year' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC, time DESC LIMIT 1";
								}
								else
									$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC, time DESC LIMIT 1";
								
								
								$loanCheckQuery = mysql_query($loanChecker) or die (mysql_error());
								switch($counter) 
								{
									case 0: $pagibigLoan = mysql_fetch_assoc($loanCheckQuery);break;
									case 1: $sssLoan = mysql_fetch_assoc($loanCheckQuery);break;
									case 2: $newValeLoan = mysql_fetch_assoc($loanCheckQuery);break;
									case 3: $oldValeLoan = mysql_fetch_assoc($loanCheckQuery);break;
								}
							}
							if(	$pagibigLoan['balance'] != 0 || 
								$sssLoan['balance'] != 0 || 
								$newValeLoan['balance'] != 0 || 
								$oldValeLoan['balance'] != 0)
							{
								Print "
										<tr>
											<td>
												".$empArr['lastname'].", ".$empArr['firstname']."
											</td>
											<td>
												".$empArr['site']."
											</td>
											<td>
												".$empArr['position']."
											</td>";
								if($sssLoan['balance'] != 0)
									Print	"<td>
												".numberExactFormat($sssLoan['balance'], 2, '.', true)."
											</td>";
								else
									Print	"<td>
												N/A
											</td>";	
								if($pagibigLoan['balance'] != 0)
									Print	"<td>
												".numberExactFormat($pagibigLoan['balance'], 2, '.', true)."
											</td>";
								else
									Print	"<td>
												N/A
											</td>";	
								if($oldValeLoan['balance'] != 0)
									Print	"<td>
												".numberExactFormat($oldValeLoan['balance'], 2, '.', true)."
											</td>";
								else
									Print	"<td>
												N/A
											</td>";	
								if($newValeLoan['balance'] != 0)
									Print	"<td>
												".numberExactFormat($newValeLoan['balance'], 2, '.', true)."
											</td>";
								else
									Print	"<td>
												N/A
											</td>";	
							

							}
							

							
							$sssGrandTotal += $sssLoan['balance'];
							$PagibigGrandTotal += $pagibigLoan['balance'];
							$newValeGrandTotal += $newValeLoan['balance'];
							$oldValeGrandTotal += $oldValeLoan['balance'];
						}
						

						if(	$sssGrandTotal != 0 || 
							$PagibigGrandTotal != 0 || 
							$newValeGrandTotal != 0 || 
							$oldValeGrandTotal != 0)
						{
						Print "
							<tr>
								<td colspan = '3'>
									Total Overall Loans
								</td>
								<td>
									".numberExactFormat($sssGrandTotal, 2, '.', true)."
								</td>
								<td>
									".numberExactFormat($PagibigGrandTotal, 2, '.', true)."
								</td>
								<td>
									".numberExactFormat($oldValeGrandTotal, 2, '.', true)."
								</td>
								<td>
									".numberExactFormat($newValeGrandTotal, 2, '.', true)."
								</td>
							</tr>
								";
						$govGrandtotal = $sssGrandTotal + $PagibigGrandTotal;
						Print "
							<tr>
								<td colspan = '3'>
									Grand Total Government Loans
								</td>
								<td colspan = '2'>
									".numberExactFormat($govGrandtotal, 2, '.', true)."
								</td>
								<td colspan = '2'>
								</td>
							</tr>
								";
						$companyGrandtotal = $newValeGrandTotal + $oldValeGrandTotal;
						Print "
							<tr>
								<td colspan = '3'>
									Grand Total Government Loans
								</td>
								<td colspan = '2'>
								</td>
								<td colspan = '2'>
									".numberExactFormat($companyGrandtotal, 2, '.', true)."
								</td>
							</tr>
								";
						}
						else
						{
							Print "
							<tr>
								<td colspan = '7'>
									No loans report as of the moment.
								</td>
							</tr>
								";
						}
					?>
					
				</table>
			</div>
		</div>

	</div>
	<form id="changeDateForm" method="post" action="reports_overall_allloans.php?site=<?php Print $site?>&period=<?php Print $period?>">
		<input type="hidden" name="date">
		<input type="hidden" name="numLen">
	</form>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");

		function periodChange(period) {
			window.location.assign("reports_overall_allloans.php?site=<?php Print $site?>&period="+period);
		}
		function changeDate(date) {
			var period = date.split(' ');
			var numLen = period.length;
			document.getElementsByName('date')[0].value = date;
			document.getElementsByName('numLen')[0].value = numLen;
			document.getElementById('changeDateForm').submit();
		}
	</script>
</body>
</html>