<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');
if(!isset($_GET['site']) && !isset($_GET['position']))
{
	header("location:payroll_login.php");
}

if(isset($_GET['site']))
{
	$site = $_GET['site'];
	$siteCheck = "SELECT * FROM site WHERE location = '$site'";
	$siteCheckerQuery = mysql_query($siteCheck);
	if(mysql_num_rows($siteCheckerQuery) == 0)
	{
		Print "<script>window.location.assign('index.php')</script>";
	}
}
else
{
	Print "<script>window.location.assign('index.php')</script>";
}

$position = (isset($_GET['position']) ? $_GET['position'] : "null");
$documents = (isset($_GET['document']) ? $_GET['document'] : "null");
$status = (isset($_GET['status']) ? $_GET['status'] : "null");

// $date = (isset($_SESSION['payrollDate']) ? $_SESSION['payrollDate'] : strftime("%B %d, %Y")); // Gets the payroll date if admin didn't finish the payroll for the week
// $date = "July 25, 2018";
// $date = "May 9, 2018";
$date = "September 5, 2018";
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css" type="text/css">
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
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li><a href="payroll_site.php?site=<?php Print $site?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Sites</a></li>

					<li class='active'>Payroll table</li>

				</ol>
			</div>


			<!-- Search bar -->
			<div class="col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1">
				<form method="post" action="" id="search_form">
					<div class="form-group">
						<form method="post" action="" id="search_form">
							<input type="text" placeholder="Search then press Enter" id="search_box" name="txt_search" onkeypress="enter(enter)" class="form-control">
						</form>
					</div>
				</form>
			</div>


			<!-- Date -->
			<div class="col-md-3 col-lg-3">
				<h3 style="margin-top:0px"><?php 
				echo $date;
				?></h3>
			</div>


			<!-- Filters -->
			<div class="col-md-5 col-lg-5 pull-left col-md-pull-1 col-lg-pull-1">
				Filter by:

				<!-- Documents status POSITION -->
				<div class="btn-group">Position
					<select class="form-control" id="documents" onchange="position(this.value)">
						<option hidden>--</option>
						<?php 
							$positions = "SELECT * FROM job_position WHERE active = '1' ORDER BY position ASC";
							$posQuery = mysql_query($positions);
							while($posArray = mysql_fetch_array($posQuery))
							{
								if($position == $posArray['position'])
									Print '	<option value="'.$posArray['position'].'" selected>'.$posArray['position'].'</option>';
								else
									Print '	<option value="'.$posArray['position'].'">'.$posArray['position'].'</option>';
							}
						?>
					</select>
				</div>
				<!-- Documents status DROPDOWN -->
				<div class="btn-group">Documents
					<select class="form-control" id="documents" onchange="documents(this.value)">
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
					<select class="form-control" id="status" onchange="status(this.value)">
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
				<button type="button" class="btn btn-danger" onclick="clearFilter()" style="margin-top: 20px;">Clear Filters</button>
			</div>


			<!-- Payroll table -->
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">
				<table class="table table-condensed table-bordered" style="background-color:white;">
					<tr>
						<td>Employee ID</td>
						<td style='width:200px !important;'>Name</td>
						<td>Position</td>
						<td>Payroll status</td>
						<td>Document status</td>
						<td>Action</td>
					</tr>
					<?php
					if(isset($_POST['txt_search']))
					{
						$find = $_POST['txt_search'];
						$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' AND (empid LIKE '%$find%' OR
							firstname LIKE '%$find%' OR
							lastname LIKE '%$find%' OR
							position LIKE '%$find%') ORDER BY lastname";
					}
					// Document Filter and Status Filter
					else if(isset($_GET['document']) && isset($_GET['status']) && isset($_GET['position']))
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
						
						//========


						if($_GET['status'] != "null")
						{
							$appendQuery = "";
							if($_GET['position'] != "null")
							{
								$appendQuery .= "AND e.position = '$position' ";
							}
							if($_GET['document'] != "null")	
							{
								$appendQuery .= "AND e.complete_doc = '$documentFilter' ";
							}
							if($_GET['status'] == 'complete')
								$employee = "SELECT e.empid, e.complete_doc, e.sss, e.pagibig, e.philhealth, e.firstname, e.lastname, e.position, e.site FROM employee AS e INNER JOIN payroll AS p ON e.empid = p.empid WHERE e.employment_status = '1' AND e.site = '$site' $appendQuery AND p.date = '$date' ORDER BY lastname ASC";
							else
								$employee = "SELECT e.empid, e.complete_doc, e.sss, e.pagibig, e.philhealth, e.firstname, e.lastname, e.position, e.site FROM employee AS e LEFT OUTER JOIN payroll AS p ON e.empid != p.empid WHERE e.employment_status = '1' AND e.site = '$site' $appendQuery AND p.date = '$date' ORDER BY lastname ASC";
							Print '<script>console.log("'.$employee.'")</script>';
						}
						else
						{
							$appendQuery = "";
							if($_GET['position'] != "null")
							{
								$appendQuery .= "AND position = '$position' ";
							}
							if($_GET['document'] != "null")	
							{
								$appendQuery .= "AND complete_doc = '$documentFilter' ";
							}
							$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' $appendQuery ORDER BY lastname ASC";
						}

						//========

						// if($_GET['document'] == "complete" || $_GET['document'] == "incomplete")
						// {
						// 	if($_GET['status'] == "complete")
						// 	{
						// 		$employee = "SELECT e.empid, e.complete_doc, e.sss, e.pagibig, e.philhealth, e.firstname, e.lastname, e.position, e.site FROM employee AS e INNER JOIN payroll AS p ON e.empid = p.empid WHERE e.site = '$site' AND e.position = '$position' AND e.complete_doc = '$documentFilter' AND p.date = '$date' ORDER BY lastname ASC";
						// 	}
						// 	else if($_GET['status'] == "complete")
						// 	{
						// 		$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'AND position = '$position' AND complete_doc = '$documentFilter' ORDER BY lastname ASC";
						// 	}
						// 	else
						// 	{
						// 		$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'AND position = '$position' AND complete_doc = '$documentFilter' ORDER BY lastname ASC";
						// 	}
						// }
						// else if($_GET['status'] == "complete" || $_GET['status'] == "incomplete")
						// {
						// 	if($_GET['document'] == "complete" || $_GET['document'] == "incomplete")
						// 	{
						// 		$employee = "SELECT * FROM employee WHERE empid NOT IN (SELECT empid FROM payroll WHERE date = '$date') AND site = '$site' AND position = '$position' AND complete_doc = '$documentFilter' AND date = '$date' ORDER BY lastname ASC";
						// 	}
						// 	else if($_GET['status'] == "incomplete")
						// 	{
						// 		$employee = "SELECT * FROM employee WHERE empid NOT IN (SELECT empid FROM payroll WHERE date = '$date') AND site = '$site' AND position = '$position' ORDER BY lastname ASC";
						// 	}
						// 	//status = Complete
						// 	else 
						// 	{
						// 		$employee = "SELECT e.empid, e.complete_doc, e.sss, e.pagibig, e.philhealth, e.firstname, e.lastname, e.position, e.site FROM employee AS e INNER JOIN payroll AS p ON e.empid = p.empid WHERE e.site = '$site' AND e.position = '$position' AND p.date = '$date' ORDER BY lastname ASC";
						// 	}


						// }
					}
					//Default
					else 
					{
						$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site' ORDER BY lastname ASC";
					}
					

					//$employee = "SELECT * FROM employee WHERE employment_status = 1 ";
					$employeeQuery = mysql_query($employee) or die(mysql_error());
					if(mysql_num_rows($employeeQuery) > 0)
					{
						while($row = mysql_fetch_assoc($employeeQuery))
						{

							$empid = $row['empid'];//Employee ID

							if($row['complete_doc'] == 1)// For employee document
							{
								$document = "Complete Documents";
							}
							else
							{
								$bool = false;
								$document = "Incomplete Documents - ";
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
							
							//$loans = "SELECT * FROM loans WHERE empid = '$empid'";
							//$loansQuery = mysql_query($loans);
							//Payroll Status
							$payrollChecker = "SELECT * FROM payroll WHERE empid = '$empid' AND date='$date'";
							$payrollQuery = mysql_query($payrollChecker);
							$payrollStatus = "Incomplete";
							if(mysql_num_rows($payrollQuery) > 0)
							{
								$payrollStatus = "Complete";	
							}
							Print "	<tr id=".$empid.">
										<td>".$empid."</td>
										<td align='left'>".$row['lastname'].", ".$row['firstname']."</td>
										<td>".$row['position']."</td>
										<td class='payrollStatus'>".$payrollStatus."</td>
										<td>". $document ."</td>
										<td>";
							if($payrollStatus == "Complete") 
							{
								Print		"<button class='btn btn-cornflowerblue' onclick='viewPayrollComp(\"".$empid."\",\"".$date."\")'>View Payroll Computation</button>";
							}
							else
							{
								Print		"<a class='btn btn-primary' href='payroll.php?site=". $site ."&position=". $position ."&empid=".$empid."'>Start Payroll</a>";
							}
							

							Print 		"</td>
									</tr>";
									// <a class='btn btn-cornflowerblue' href='payroll.php?site=". $site ."&position=". $position ."&empid=".$empid."'>View Payroll Computation</a></td>
									// For completed
						}
					}
					else
					{
						Print "	<tr>
									<td colspan='6'><h3>No records found</h3></td>
								</tr>";
					}
					
					?>

				</table>
			</div>
		</div>
	</div>
	<!-- This is for the hidden form that that will be use to pass data to payroll computation -->
	<div id="hiddenFormDiv"></div>
	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script rel="javascript" src="js/jquery-ui/external/jquery/jquery.js"></script>
	<script rel="javascript" src="js/jquery-ui/jquery-ui.js"></script>
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

		//Position filter
		function position(pos) {
			window.location.assign("payroll_table.php?position="+pos+"&site=<?php Print $site ?>&status=<?php Print $status ?>&document=<?php Print $documents ?>");
		}

		// STATUS FILTER 
		function status(stat) {
			window.location.assign("payroll_table.php?position=<?php Print $position ?>&site=<?php Print $site ?>&status="+stat+"&document=<?php Print $documents ?>");
		}

		// DOCUMENTS FILTER 
		function documents(doc) {
			window.location.assign("payroll_table.php?position=<?php Print $position ?>&site=<?php Print $site ?>&status=<?php Print $status ?>&document="+doc);
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
				}
			}
		}

		// Clearing filters
		function clearFilter() {
			localStorage.clear();
			window.location.assign("payroll_table.php?position=null&site=<?php Print $site?>");
		}
		//View the payroll computation
		function viewPayrollComp(id, date) {
			var empid = id;
				//Create form to pass the values through POST and not GET
				var form = document.createElement("form");
				form.setAttribute("method","post");
				form.setAttribute("action","payroll_computation.php");
				form.setAttribute("id","payrollCompForm");

				var user = document.createElement("input");
				user.setAttribute("type","hidden");
				user.setAttribute("name","empid");
				user.setAttribute("value",empid);

				var day = document.createElement("input");
				day.setAttribute("type","hidden");
				day.setAttribute("name","date");
				day.setAttribute("value",date);
				
				//append User inside form
				form.appendChild(user);
				form.appendChild(day);

				document.getElementById('hiddenFormDiv').appendChild(form);
				document.getElementById('payrollCompForm').submit();
		}
	</script>
</body>
</html>