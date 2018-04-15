<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$site = $_GET['site'];
	$position = $_GET['position'];

	$dateToday = strftime("%B %d, %Y");//date today

	//Checks if site in HTTP is altered by user manually
	$siteChecker = "SELECT * FROM site WHERE location = '$site'";

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


		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href="reports_overall_13thmonthpay.php?position=all&req=all&site=<?php Print $site ?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Back to table</a></li>
						<li>Overall 13th Month Pay Report for <?php Print $site?></li>
						<button class="btn btn-primary pull-right" onclick="submitForm()">
							Give 13th Month Pay
						</button>
					</ol>
				</div>
			</div>

			<form method="post" action="logic_reports_13thmonthpay_overall.php" id="dynamicForm">
				<input type='hidden' value='<?php Print $site?>' name='site'>
				<table class="table table-bordered pull-down">
					<tr>
						<td>
							Name
						</td>
						<td>
							Position
						</td>
						<td>
							From - To Date
						</td>
						<td>
							13th Month Pay Amount
						</td>
						<td>
							Amount to give
						</td>
						<td>
							Copy full amount
						</td>
					</tr>
					<?php
						$overallPayment = 0;

						$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'ORDER BY lastname ASC, position ASC";
						$employeeQuery = mysql_query($employee);

						while($empArr = mysql_fetch_assoc($employeeQuery))
						{
							$amountLeft = 0;//this the amount left in the 13th month pay just incase the employer did not consume all of the 13th month pay of the employee

							$empid = $empArr['empid'];
							//Evaluates the attendance and compute the 13th monthpay
							
							// previous 13th Month pay checker
							//Gets the most previous 13th month pay given date
							$onetriChecker = "SELECT * FROM thirteenth_pay WHERE empid = '$empid' ORDER BY STR_TO_DATE(to_date, '%M %e, %Y') DESC LIMIT 1";
							$onetriQuery = mysql_query($onetriChecker);
							if(mysql_num_rows($onetriQuery) != 0)
							{
								$onetriArr = mysql_fetch_assoc($onetriQuery);
								$startDate = $onetriArr['to_date'];
								if($onetriArr['amount'] != $onetriArr['received'])
								{
									$amountLeft = $onetriArr['amount'] - $onetriArr['received'];
									$amountLeft = abs($amountLeft);
								}
							}
							else
							{
								//Gets the first attendance of the employee if 13th month isn't previously given
								$attendanceChecker = "SELECT * FROM attendance WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC LIMIT 1";
								$attCheckerQuery = mysql_query($attendanceChecker);
								$attChecerArr = mysql_fetch_assoc($attCheckerQuery);
								$startDate = $attChecerArr['date'];
							}
							$endDate = $dateToday;//Current date

							
							$attendance = "SELECT * FROM attendance WHERE  empid = '$empid' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							$attQuery = mysql_query($attendance);

							$daysAttended = 0;//counter for days attended
							//Computes the 13th month
							while($attArr = mysql_fetch_assoc($attQuery))
							{
								$date = $attArr['date'];

								$workHrs = $attArr['workhours'];

								$holidayChecker = "SELECT * FROM holiday WHERE date = '$date'";
								$holidayCheckQuery = mysql_query($holidayChecker) or die (mysql_error());

								if(mysql_num_rows($holidayCheckQuery) == 0)
								{
									if($attArr['attendance'] == '2')//check if student is present
									{
										if($attArr['workhours'] >= 8)//check if employee attended 8hours
										{
											$daysAttended++;
										}
									}
								}
							}
							$thirteenthMonth = (($daysAttended * $empArr['rate']) / 12)+$amountLeft; 
							Print "
									<tr id='".$empArr['empid']."'>
										<input type='hidden' value='".$empArr['empid']."' name='empid[]'>
										<input type='hidden' value='".numberExactFormat($thirteenthMonth, 2, '.',false)."' name='onetri[]'>
										<input type='hidden' value='".$startDate."' name='startDate[]'>
										<input type='hidden' value='".$endDate."' name='endDate[]'>
										<td>
											".$empArr['lastname'].", ".$empArr['firstname']."
										</td>
										<td>
											".$empArr['position']."
										</td>
										<td>
											".$startDate." - ".$endDate."
										</td>
										<td class='onetriAmount'>
											".numberExactFormat($thirteenthMonth, 2, '.',true)."
										</td>
										<td>
											<input type='number' class='amount' name='amount[]' onchange='amountInput(\"".$empArr['empid']."\")'>
										</td>
										<td>
											<input type='checkbox' onchange='copyAmount(\"".$empArr['empid']."\")'>
										</td>
									</tr>";

							$overallPayment += $thirteenthMonth;
							
						}
					
						
						if(mysql_num_rows($employeeQuery) == 0)	
						{
							Print "
									<tr bgcolor='#E74C3C'>
										<td colspan='5'>
											No 13th month pay report as of the moment.
										</td>
									</tr>
									";
						}	
					?>

					
				</table>
			</form>
		</div>

			
	</div>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		function amountInput(id) {
			var employee = document.getElementById(id);
			var amount = employee.querySelector('.amount');
			var onetriAmount = employee.querySelector('.onetriAmount').innerHTML;
			var amountSplit = onetriAmount.split(',');
			var amountOverall = onetriAmount;
			if(amountSplit.length > 1)
			{
				amountOverall = amountSplit[0]+amountSplit[1];
			}
			if(amount.value <= 0 || parseFloat(amountOverall) < amount.value)
			{
				alert("Invalid Input");
				amount.value = "";
			}
		}

		function copyAmount(id) {
			var employee = document.getElementById(id);
			var onetriAmount = employee.querySelector('.onetriAmount').innerHTML;
			var amount = onetriAmount.split(',');
			var amountOverall = onetriAmount;
			if(amount.length > 1)
			{
				amountOverall = amount[0]+amount[1];
			}
			employee.querySelector('.amount').value = parseFloat(amountOverall).toFixed(2);
		}
		function submitForm() {
			var a = confirm("Are you sure you want to give 13th Month pay to employees?");
			if(a)
			{
				document.getElementById('dynamicForm').submit();
			}
		}
	</script>
</body>
</html>