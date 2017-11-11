<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');
if(!isset($_GET['site']) && !isset($_GET['position']))
{
	header("location:payroll_login.php");
}

$site = $_GET['site'];
$position = $_GET['position'];
// $date = strftime("%B %d, %Y"); 
$date = "October 24, 2017";//Test date
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
						Print $position . "s at " . $site;
						?>
					</h4>
				</ol>
			</div>


			<!-- Search bar -->
			<div class="col-md-3 col-md-offset-1">
				<form method="post" action="" id="search_form">
					<div class="form-group">
						<form method="post" action="" id="search_form">
							<input type="text" placeholder="Search" id="search_box" name="txt_search" onkeypress="enter(enter)" class="form-control">
						</form>
					</div>
				</form>
			</div>


			<!-- Date -->
			<div class="col-md-3">
				<h3 style="margin-top:0px"><?php 
				echo $date;
				?></h3>
			</div>


			<!-- Filters -->
			<div class="col-md-4 pull-left">
				Filter by:


				<!-- Documents status DROPDOWN -->
				<div class="btn-group">Documents
					<select class="form-control" id="documents" onchange="documents()">
						<option hidden>--</option>
						<?php 
							if(isset($_GET['document']))
							{
								if($_GET['document'] != "null")
								{
									if($_GET['document'] == "complete")
									Print '	<option value="complete" selected>Complete</option>
											<option value="incomplete" >Incomplete</option>';
									else
									Print '	<option value="complete">Complete</option>
											<option value="incomplete" selected>Incomplete</option>';
								}
								else//default
								{
									Print '	<option value="complete">Complete</option>
											<option value="incomplete">Incomplete</option>';
								}
								
							}
							else//default
							{
								Print '	<option value="complete">Complete</option>
										<option value="incomplete">Incomplete</option>';
							}
							
						?>
					</select>
				</div>


				<!-- Payroll status DRODOWN -->
				<div class="btn-group">Payroll Status
					<select class="form-control" id="status" onchange="status()">
						<option hidden>--</option>
						<?php 
							if(isset($_GET['status']))
							{
								if($_GET['status'] != "null")
								{
									if($_GET['status'] == "complete")
										Print '	<option value="complete" selected>Complete</option>
												<option value="incomplete" >Incomplete</option>';
									else
										Print '	<option value="complete">Complete</option>
												<option value="incomplete" selected>Incomplete</option>';
								}
								else//default
								{
									Print '	<option value="complete">Complete</option>
											<option value="incomplete">Incomplete</option>';
								}
							}
							else//default
							{
								Print '	<option value="complete">Complete</option>
										<option value="incomplete">Incomplete</option>';
							}
						?>
					</select>
				</div>

				<!-- Clear Filters button -->
				<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filters</button>
			</div>


			<!-- Payroll table -->
			<div class="col-md-10 col-md-offset-1 pull-down">
				<table class="table table-condensed table-bordered" style="background-color:white;">
					<tr>
						<td>Employee ID</td>
						<td style='width:200px !important;'>Name</td>
						<td>Payroll status</td>
						<td>Document status</td>
						<td>Loans</td>
						<td>Action</td>
					</tr>
					<?php
					if(isset($_POST['txt_search']))
					{
						Print "<script>alert('1')</script>";
						$find = $_POST['txt_search'];
						$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND position = '$position' AND (empid LIKE '%$find%' OR
							firstname LIKE '%$find%' OR
							lastname LIKE '%$find%') ORDER BY position";

					}
					// Document Filter and Status Filter
					else if(isset($_GET['document']) && isset($_GET['status']))
					{
						if($_GET['document'] == "complete")
						{
							$documentFilter = 1;
						}
						else
						{
							$documentFilter = 0;
						}
						//Print "<script>alert('".$documentFilter."')</script>";
						$statusFilter = $_GET['document'];

						if($_GET['document'] == "complete" || $_GET['document'] == "incomplete")
						{
							Print "<script>console.log('1')</script>";
							if($_GET['status'] == "complete")
							{
								Print "<script>console.log('2')</script>";
								$employee = "SELECT e.empid, e.complete_doc, e.sss, e.pagibig, e.philhealth, e.firstname, e.lastname, e.position, e.site FROM employee AS e INNER JOIN payroll AS p ON e.empid = p.empid WHERE e.site = '$site' AND e.position = '$position' AND e.complete_doc = '$documentFilter'";
							}
							else
							{
								Print "<script>console.log('3')</script>";
								$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'AND position = '$position' AND complete_doc = '$documentFilter'";
							}
						}
						else if($_GET['status'] == "complete" || $_GET['status'] == "incomplete")
						{
							Print "<script>console.log('4')</script>";
							if($_GET['document'] == "complete")
							{
								Print "<script>console.log('5')</script>";
								$employee = "SELECT e.empid, e.complete_doc, e.sss, e.pagibig, e.philhealth, e.firstname, e.lastname, e.position, e.site FROM employee AS e INNER JOIN payroll AS p ON e.empid = p.empid WHERE e.site = '$site' AND e.position = '$position' AND e.complete_doc = '$documentFilter'";
							}
							else
							{
								Print "<script>console.log('6')</script>";
								$employee = "SELECT e.empid, e.complete_doc, e.sss, e.pagibig, e.philhealth, e.firstname, e.lastname, e.position, e.site FROM employee AS e INNER JOIN payroll AS p ON e.empid = p.empid WHERE e.site = '$site' AND e.position = '$position'";
							}
						}
					}
					//Default
					else 
					{
						$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'AND position = '$position'";
					}
					

					//$employee = "SELECT * FROM employee WHERE employment_status = 1 ";
					$employeeQuery = mysql_query($employee);
					if(!$employeeQuery)
					{
						Print "<script>alert('fail')</script>";
					}
					Print "<script>alert('num: ".mysql_num_rows($employeeQuery)."')</script>";
					if(mysql_num_rows($employeeQuery) > 0)
					{
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
							//Payroll Status
							$payrollChecker = "SELECT * FROM payroll WHERE empid = '$empid' AND date='$date'";
							$payrollQuery = mysql_query($payrollChecker);
							$payrollStatus = "Incomplete";
							Print '<script>console.log("'.$payrollChecker.'")</script>';
							if(mysql_num_rows($payrollQuery) > 0)
							{
								$payrollStatus = "Complete";	
							}
							Print "	<tr id=".$empid.">
										<td>".$empid."</td>
										<td>".$row['lastname'].", ".$row['firstname']."</td>
										<td class='payrollStatus'>".$payrollStatus."</td>
										<td>". $document ."</td>
										<td>". $loan ."</td>
										<td><a class='btn btn-primary' href='payroll.php?site=". $site ."&position=". $position ."&empid=".$empid."'>Start Payroll</a></td>
									</tr>";
						}
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
		// SEARCHING DATABASE VIA ENTER KEYPRESS 
		function enter(e) {
			if (e.keyCode == 13) {
			document.getElementById('search_form').submit();
			}
		}
		// STATUS FILTER 
		function status() {
			if(document.URL.match(/documents=([0-9]+)/))
			{
				var arr = document.URL.match(/status=([0-9]+)/)
				var siteUrl = arr[1];
				if(siteUrl)
				{
				localStorage.setItem("counter", 0);
				}
				else if(localStorage.getItem('counter') > 2)
				{
					localStorage.clear();
				}
			}
			var status = document.getElementById("status").value;
			var statusReplaced = status.replace(/\s/g , "+");
			localStorage.setItem("glob_status", statusReplaced);
			window.location.assign("payroll_table.php?position=<?Print $position ?>&site=<?Print $site ?>&status="+statusReplaced+"&document="+localStorage.getItem('glob_document'));
		}

		// DOCUMENTS FILTER 
		function documents() {
			if(document.URL.match(/documents=([0-9]+)/))
			{
				var arr = document.URL.match(/documents=([0-9]+)/)
				var documentUrl = arr[1];
				if(documentUrl)
				{
					localStorage.setItem("counter", 0);
				}
				else if(localStorage.getItem('counter') > 2)
				{
					localStorage.clear();
				}
			}
			var documents = document.getElementById("documents").value;
			var documentReplaced = documents.replace(/\s/g , "+");
			localStorage.setItem("glob_document", documentReplaced);
			window.location.assign("payroll_table.php?position=<?Print $position ?>&site=<?Print $site ?>&status="+localStorage.getItem("glob_status")+"&document="+documentReplaced);
		}


		window.onload =	function completePayroll(){
			//var checker = document.querySelector('.payrollStatus');
			if(document.querySelector('.payrollStatus') != null)
			{
				//alert('yea');
				var status = document.getElementsByClassName('payrollStatus');

				for(var i = 0; i < status.length; i++){
					if(status[i].innerText == 'Complete'){// Changing color of row to green when status is complete
						status[i].parentNode.setAttribute('class','success');
					}
					// else if(status[i].innerText == 'Incomplete'){// Change button label if incomplete
					// 	status[i].nextElementSibling.nextElementSibling.nextElementSibling.innerHTML = '<a class="btn btn-primary" href="payroll.php?site=<?php //Print $site?>&position=<?php //Print $position?>&empid=<?php //Print $empid?>">Start Payroll</a>';
					// }
				}
			}
		}

		// Clearing filters
		function clearFilter() {
			localStorage.clear();
			window.location.assign("payroll_table.php?position=<?php Print $position?>&site=<?php Print $site?>");
		}
	</script>
</body>
</html>