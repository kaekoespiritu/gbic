<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
if(!isset($_GET['site']) && !isset($_GET['position']))
{
	header("location:payroll_login.php");
}

$site = $_GET['site'];
$position = $_GET['position'];
$date = strftime("%B %d, %Y");

?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="js/timepicker/jquery.timepicker.min.css">

</head>
<body style="font-family: QuicksandMed">
	<div class="container-fluid">
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<div class="row" style="z-index: 101">
			<!-- BREAD CRUMBS -->
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li><a href="payroll_position.php?site=<?php Print $site?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a></li>

					<li class='active'>Payroll table</li>

					<h4 class="pull-right">
						<?php
						Print $position . "s at " . $site
						?>
					</h4>
				</ol>

			</div>

			<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
			<div class="col-md-3 col-md-offset-1">
				<form method="post" action="" id="search_form">
					<div class="form-group">
						<input type="text" placeholder="Search" id="search_box" name="txt_search" onkeypress="enter(enter)" class="form-control">
					</div>
				</form>
			</div>
			<!-- Date -->
			<div class="col-md-4">
				<h3 style="margin-top:0px"><?php 
				date_default_timezone_set('Asia/Hong_Kong');
				$date = date('F d, Y', time());
				echo $date;
				?></h3>
			</div>
			<div class="col-md-3 pull-left">
				Filter by:
				<!-- Documents status DROPDOWN -->
				<div class="btn-group">
					<select class="form-control" id="documents">
						<option hidden>Documents</option>
						<option value="complete">Complete</option>
						<option value="incomplete">Incomplete</option>
					</select>
				</div>
				<!-- Payroll status DRODOWN -->
				<div class="btn-group">
					<select class="form-control" id="documents">
						<option hidden>Payroll status</option>
						<option value="complete">Complete</option>
						<option value="incomplete">Incomplete</option>
					</select>
				</div>

			</div>
			<!-- Payroll table -->
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-condensed table-bordered" style="background-color:white;">
					<tr>
						<td>Employee ID</td>
						<td style='width:200px !important;'>Name</td>
						<td>Payroll status</td>
						<td>Incomplete documents</td>
						<td>Loans</td>
						<td>Action</td>
					</tr>
					<?php
					$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'AND position = '$position'";

					//$employee = "SELECT * FROM employee WHERE employment_status = 1 ";
					$employeeQuery = mysql_query($employee);


					while($row = mysql_fetch_assoc($employeeQuery))
					{
						$empid = $row['empid'];//Employee ID

						if($row['complete_doc'] == 1)// For employee document
						{
							$document = "Complete";
						}
						else
						{
							$bool = false;
							$document = "Incomplete - ";
							if($row['sss'] == 0)//Checks if SSS is not complete yet
							{
								$document .= "SSS";
								$bool = true;
							}
							if($bool)//for commas
							{
								$document .= ", ";
								$bool = false;
							}
							if($row['pagibig'] == 0)
							{
								$document .= "PAGIBIG";
								$bool = true;
							}
							if($bool)//for commas
							{
								$document .= ", ";
								$bool = false;
							}
							if($row['philhealth'] == 0)
							{
								$document .= "PhilHealth";
							}
							$document = trim($document);
							$commaChecker = substr($document, -1); 

							if($commaChecker == ",") // Removes the comma if there is no following value
							{
								$document = substr($document, 0, -1);
							}
							
							
						}
						// LOANS
						$getSSS = "SELECT sss FROM loans WHERE empid = '$empid' AND sss IS NOT NULL ORDER BY date DESC";
						$getPAGIBIG = "SELECT pagibig FROM loans WHERE empid = '$empid' AND pagibig IS NOT NULL ORDER BY date DESC";
						$getVALE = "SELECT vale FROM loans WHERE empid = '$empid' AND vale IS NOT NULL ORDER BY date DESC";

						$sssQuery = mysql_query($getSSS);
						$pagibigQuery = mysql_query($getPAGIBIG);
						$valeQuery = mysql_query($getVALE);
						$sss = "";
						$pagibig = "";
						$vale = "";
						if($sssQuery)
						{
							while($sssLatest = mysql_fetch_assoc($sssQuery))
							{
								if($sssLatest['sss'] != NULL)
								{
									$sss = "SSS";
									break 1;
								}
								else
								{
									$sss = "";
								}
							}
						}
						else
						{
							$sss = "";
						}
						if($pagibigQuery)
						{
							while($pagibigLatest = mysql_fetch_assoc($pagibigQuery))
							{
								if($pagibigLatest['pagibig'] != NULL)
								{
									$pagibig = "PAGIBIG";
									break 1;
								}
								else
								{
									$pagibig = "";
								}
							}
						}
						else
						{
							$pagibig = "";
						}
						if($valeQuery)
						{
							while($valeLatest = mysql_fetch_assoc($valeQuery))
							{
								if($valeLatest['vale'] != NULL)
								{
									$vale = "Vale";
									break 1;
								}
								else
								{
									$vale = "";
								}
							}
						}
						else
						{
							$vale = "";
						}
						$loan = "";
						$comma = false;
						$bool_loan = true;
						if($sss != "")//Checks if there is Loan on SSS
						{
							$loan .= $sss; 
							$comma = true;
							$bool_loan = false;
						}
						if($comma)
						{
							$loan .= ", "; 
							$comma = false;
						}
						if($pagibig != "")//Checks if there is Loan on PAGIBIG
						{
							$loan .= $pagibig; 
							$comma = true;
							$bool_loan = false;
						}
						if($comma)
						{
							$loan .= ", "; 
							$comma = false;
						}
						if($vale != "")//Checks if there is Loan on VALE
						{
							$loan .= $vale; 
							$bool_loan = false;
						}
						if($bool_loan)
						{
							$loan = "None";
						}
						$loan = trim($loan);
						$commaChecker = substr($loan, -1); 

						if($commaChecker == ",") // Removes the comma if there is no following value
						{
							$loan = substr($loan, 0, -1);
						}
						//$loans = "SELECT * FROM loans WHERE empid = '$empid'";
						//$loansQuery = mysql_query($loans);

						Print "	<tr id=".$empid.">
						<td>".$empid."</td>
						<td>".$row['lastname'].", ".$row['firstname']."</td>
						<td class='payrollStatus'>Incomplete</td>
						<td>". $document ."</td>
						<td>". $loan ."</td>
						<td><a class='btn btn-primary' href='payroll.php'>View Payroll</a></td>
						</tr>";
					}
					?>

				</table>
			</div>
		</div>
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/timepicker/jquery.timepicker.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");

		window.onload =	function completePayroll(){
			var status = document.getElementsByClassName('payrollStatus');
			for(var i = 0; i < status.length; i++){
				if(status[i].innerText == 'Complete'){// Changing color of row to green when status is complete
					status[i].parentNode.setAttribute('class','success');
				}
				else if(status[i].innerText == 'Incomplete'){// Change button label if incomplete
					status[i].nextElementSibling.nextElementSibling.nextElementSibling.innerHTML = '<a class="btn btn-primary" href="payroll.php">Start Payroll</a>';
				}
			}


		}


	</script>
</body>
</html>