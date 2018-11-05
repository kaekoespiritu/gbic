<!DOCTYPE html>
<?php
include('directives/session.php');
include_once('directives/db.php');
include("pagination/loans_function.php");//For pagination

$date = strftime("%B %d, %Y");

if(!isset($_GET['type']))
{
	header("location: index.php");
}
$loanType = $_GET['type'];

//For display on breadcrum
$displayLoan = $loanType;
if($loanType == "oldVale")
	$displayLoan = "Old Vale";
else if($loanType == "newVale")
	$displayLoan = "New Vale";
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css" type="text/css">
	<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">

		<!-- Add loan modal -->
		<div class="modal fade" id="addLoanModal">
			<div class="modal-dialog" role="document">
			    <div class="modal-content" >
			      	<div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			        		<span aria-hidden="true">&times;</span>
			        	</button>
			        	<h4 class="modal-title" id="myModalLabel">Add Loans</h4>
			      	</div>
			      	<div class="modal-body">
			        	<div class="row">
							<div class="col-md-12">
								<input name="txt_attendance" type="text" size="10" class="form-control" value = <?php
							echo $date;
							?> id="dtpkr_loan" placeholder="mm-dd-yyyy" readonly>
							</div>
							<div class="col-md-12 pull-down">
								<input type="text" class="form-control pull-right input-sm" placeholder="Search Employee to add loan" >
							</div>
							<button class="btn btn-success pull-down" data-toggle="modal" data-target="#addLoan">Add loan</button>
			          	</div>
			        </div>
			    </div>
			</div>
		</div>

		<!-- ADDING LOAN -->
		<div class="modal fade" id="addLoan" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-7 col-lg-7">
							<h4 class="modal-title text-right">Add new employee loan</h4>
						</div>
						<div class="col-md-5 col-lg-5">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
					<div class="modal-body">
						<form class="horizontal" method="POST" action="logic_loans.php">
							<div class="row">
								<div class="col-md-6 col-lg-6">
									<h4 class="modal-title">Personal Information</h4><hr>

									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="fname">First name</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<input onkeypress="validateletter(event)" type="text" class="form-control" id="fname" name="firstname" readonly>
											<input type="hidden" id="empid" name="empid">
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="lname">Last name</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<input onkeypress="validateletter(event)" type="text" class="form-control" id="lname"  name="lastname"readonly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="address">Address</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<input onkeypress="validateletter(event)" type="text" class="form-control" id="address" name="address" readonly>
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="contact">Contact number</label>
										</div>
										<div class="col-md-4 col-lg-4">
											<input onkeypress="validatenumber(event)" type="text" class="form-control" id="contact" name="contactnum" readonly>
										</div>
									</div><br>

									<h4 class="modal-title">New Loan Details for [<?php echo (isset($_SESSION["loandate"]) ? $_SESSION["loandate"] : strftime("%B %d, %Y")) ?>]</h4><hr>
									<input type="hidden" id="loandate" name="loandate" value="<?php echo (isset($_SESSION['loandate']) ? $_SESSION['loandate'] : strftime('%B %d, %Y'))?>" >

									<div class="form-group" id="loanform">
										<div class="row">
											<div class="form-group col-md-4 col-lg-4 col-md-push-1 col-lg-push-1">
												<select class="form-control check-input" name="loanType[]" required id="loanType" onchange="validateLoanFields(this)">
													<option disabled value="" selected>Loan type</option>
													<option class="dd_sss sss1" value="SSS">SSS</option>
													<option class="dd_pagibig pagibig1" value="PagIBIG">PagIBIG</option>
													<option class="dd_oldvale oldvale1" value="oldVale">Old vale</option>
													<option class="dd_newvale newvale1" value="newVale">New vale</option>
												</select>
											</div>
											<div class="col-md-5 col-lg-5 col-md-push-1 col-lg-push-1">
												<input type="text" class="form-control check-input" required name="loanAmount[]" id="loanAmount" placeholder="Amount of loan" onchange="validateLoanFields(this)" onblur="formcheck()">
											</div>
										</div>
										<div class="row">
											<div class="col-md-offset-1 col-lg-offset-1">
												<textarea class="form-control check-input" rows="2" required id="reason" name="reason[]" placeholder="Reason for getting a loan" onchange="validateLoanFields(this)" onblur="formcheck()"></textarea>
											</div><br>
										</div>
									</div>
									
								</div>

								<div class="col-md-6 col-lg-6">
									<h4 class="modal-title">Job details</h4><hr>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="position" class="text-right">Position & Site</label>
											<input type="hidden" id="position" name="position">
											<input type="hidden" id="site" name="site">
										</div>
										<div class="col-md-5 col-lg-5" id="position&site">
										</div>
									</div><br>

									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="rate">Rate Per Day</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="rate" type="text" class="form-control" id="rate"  readonly>
										</div>
									</div><br>

									<h4 class="modal-title">Pending loans</h4><hr>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="rate">Old vale</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_oldVale"  type="text" class="form-control" id="oldvale" placeholder="--" readonly>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="rate">New vale</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_newVale"  type="text" class="form-control" id="newvale" placeholder="--" readonly>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="rate">SSS</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_SSS"  type="text" class="form-control" id="sss" placeholder="--" readonly>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="rate">PagIBIG</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_pagibig"  type="text" class="form-control" id="pagibig" placeholder="--" readonly>
										</div>
									</div><br>
							</div>

						</div>	
						<div class="modal-footer">
							<input type="submit" name="add_submit" id="add_submit" class="btn btn-primary" value="Add new loan">
						</div>			
						</form>
					</div>
					
				</div>
			</div>
		</div>

		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		require_once("directives/modals/addLoan.php");
		?>

		<!-- Modal for viewing loans history -->
		<div class="modal fade" id="viewLoanHistory" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div id="dynamicTable">
					</div>
				</div>	
			</div>
		</div>

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-12 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">
				<ol class="breadcrumb text-left add-loans">
					<li>
						<a href="loans_landing.php" class="btn btn-primary">
							<span class="glyphicon glyphicon-arrow-left"></span> 
							Loans Application
						</a>
					</li>
					<li class="active">
						Viewing loans for <?Print $displayLoan?>
					</li>

					<button class="btn btn-success pull-right" data-toggle="modal" data-target="#addLoanModal">Add loan</button>

				</ol>
				
			</div>

			<div class="col-md-12 pull-down">
				<div class="col-md-3 col-lg-3 col-md-offset-1 col-lg-offset-1">
					<div class="form-group">
						<input type="text" placeholder="Search then press Enter" id="search_box" name="txt_search" onkeyup="enter(event)" class="form-control">
					</div>
				</div>

				<div class="col-md-3 col-md-offset-1 col-lg-3 text-left">
					<h3>Overall Payable: <span id="overallPlacing"></span></h3>
				</div>

				<div class="col-md-1 col-lg-3 text-right">
					Filter by:


					<!-- Filter by POSITION -->
					<div class="btn-group">
						<select class="form-control" id="position" onchange="position()">
							<option hidden>Position</option>
							<?php
							$position = "SELECT * FROM job_position WHERE active = '1'";
							$position_query = mysql_query($position);

							while($row_position = mysql_fetch_assoc($position_query))
							{
								$positionReplaced = str_replace('/+/', ' ', $_GET['position']);
								$position = mysql_real_escape_string($row_position['position']);
								if($position == $positionReplaced)
								{
									Print '<option value="'. $position .'" selected="selected">'. $position .'</option>';
								}
								else
								{
									Print '<option value="'. $position .'">'. $position .'</option>';
								}
							}
							?>
						</select>
					</div>


					<!-- Filter by LOCATION -->
					<div class="btn-group">
						<select class="form-control" id="site" onchange="site()">
							<option hidden>Site</option>
							<?php
							$site = "SELECT * FROM site WHERE active = '1'";
							$site_query = mysql_query($site);

							while($row_site = mysql_fetch_assoc($site_query))
							{
								$siteReplaced = str_replace('/+/', ' ', $_GET['site']);
								if($row_site['location'] == $siteReplaced)
								{
									Print '<option value="'. $row_site['location'] .'" selected="selected">'. $row_site['location'] .'</option>';
								}
								else
								{
									Print '<option value="'. $row_site['location'] .'">'. $row_site['location'] .'</option>';
								}
							}
							?>
						</select>
					</div>

					<!-- Clear Filters button -->
					<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filters</button>
				</div>
			</div>

		</div>


		<br>


		<!-- EMPLOYEE TABLE -->
		<div class="row">
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">

			<table class="table table-bordered table-condensed" style="background-color:white;">
				<tr>
					<td style='width:130px !important;'>ID</td>
					<td style='width:200px !important;'>Name</td>
					<td>Position</td>
					<td>Site</td>
					<td><?php Print ($loanType == 'SSS' || $loanType == 'PAGIBIG' ? "Monthly Dues" : "Amount to be paid")?></td>
					<td>Actions</td>
				</tr>
				<?php 
					$loans = "SELECT DISTINCT * FROM loans l INNER JOIN employee e ON l.empid = e.empid WHERE type = '$loanType' AND e.employment_status = '1' GROUP BY l.empid ORDER BY e.lastname ASC, STR_TO_DATE(l.date, '%M %e, %Y') ASC, l.id ASC ";
					$loansQuery = mysql_query($loans) or die (mysql_error());
					$noLoanChecker = true;
					if(mysql_num_rows($loansQuery) > 0)
					{
						//Print "<script>alert('1')</script>";
						$noLoanChecker = true;
						$overallPayable = 0;
						while($row = mysql_fetch_assoc($loansQuery))
						{
							$empid = $row['empid'];
							if(isset($_GET['position']) && isset($_GET['site']))
							{
								$position = $_GET['position'];
								$site = $_GET['site'];

								if($_GET['site'] != "null")
								{
									if($_GET['position'] != "null")
									{
										$employees = "employee WHERE empid = '$empid' AND site = '$site' AND position = '$position' AND employment_status = '1' ORDER BY lastname ASC";
									}
									else
									{
										$employees = "employee WHERE empid = '$empid' AND site = '$site' AND employment_status = '1' ORDER BY lastname ASC";
									}
								}
								else if($_GET['position'] != "null")
								{
									if($_GET['site'] != "null")
									{
										$employees = "employee WHERE empid = '$empid' AND site = '$site' AND position = '$position' AND employment_status = '1' ORDER BY lastname ASC";
									}
									else
									{
										$employees = "employee WHERE empid = '$empid' AND position = '$position' AND employment_status = '1' ORDER BY lastname ASC";
									}
								}
							}
							else
							{
								$employees = "employee WHERE empid = '$empid' AND employment_status = '1' ORDER BY lastname ASC";
							}
							//---

							//Print "<script>alert('default')</script>";
							$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
					    	$limit = 20; //if you want to dispaly 10 records per page then you have to change here
					    	$startpoint = ($page * $limit) - $limit;
					        $statement = $employees;

							$employeeQuery = mysql_query("SELECT * FROM {$statement} LIMIT {$startpoint}, {$limit}");

							//---
							$empArr = mysql_fetch_assoc($employeeQuery);
							//Print "<script>alert(".mysql_num_rows($employeeQuery).")</script>";
							//Check if employee has already fully paid his/her loan
							$checker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, id DESC LIMIT 1";
				
							$checkerQuery = mysql_query($checker);
							$checkerArr = mysql_fetch_assoc($checkerQuery);

							
							if(mysql_num_rows($employeeQuery) != 0)
							{
								if($loanType == 'SSS' || $loanType == 'PAGIBIG')
								{
									if($checkerArr['action'] == 1)
									{
										$overallPayable += $checkerArr['monthly'];// Accumulates the overall monthly
										Print "
											<tr>
												<input type='hidden' name='empid[]' value='". $empid ."'>
												<td style='vertical-align: inherit'>
													".$empid."
												</td>
												<td style='vertical-align: inherit; text-align: left;' >
													".$empArr['lastname'].", ".$empArr['firstname']."
												</td>
												<td style='vertical-align: inherit'>
													".$empArr['position']."
												</td>
												<td style='vertical-align: inherit'>
													".$empArr['site']."
												</td>
												<td style='vertical-align: inherit'>
													".numberExactFormat($checkerArr['monthly'], 2, '.', true)."
												</td>
												<td>";
												if($loanType == 'SSS' || $loanType == 'PAGIBIG')
												{
													Print	"<a class='btn btn-danger' onclick='endLoan(\"".$empid."\", \"".$loanType."\")'><span class='glyphicon glyphicon-minus-sign'></span> End Loan</a>";
												}


												Print	
													"&nbsp<a class='btn btn-primary' data-toggle='modal' data-target='#viewLoanHistory' onclick='load_history(\"".$empid."\", \"".$loanType."\")'><span class='glyphicon glyphicon-list-alt'></span> History</a>
												
												</td>
											</tr>
											";
										$noLoanChecker = false;
									}
								}
								else
								{

									if($checkerArr['balance'] > 0)
									{
										$overallPayable += $checkerArr['balance'];// Accumulates the overall monthly
										Print "
											<tr>
												<input type='hidden' name='empid[]' value='". $empid ."'>
												<td style='vertical-align: inherit'>
													".$empid."
												</td>
												<td style='vertical-align: inherit; text-align: left;'>
													".$empArr['lastname'].", ".$empArr['firstname']."
												</td>
												<td style='vertical-align: inherit'>
													".$empArr['position']."
												</td>
												<td style='vertical-align: inherit'>
													".$empArr['site']."
												</td>
												<td style='vertical-align: inherit'>
													".numberExactFormat($checkerArr['balance'], 2, '.', true)."
												</td>
												<td>";
												if($loanType == 'SSS' || $loanType == 'PAGIBIG')
												{
													Print	"<a class='btn btn-danger' onclick='endLoan(\"".$empid."\", \"".$loanType."\")'><span class='glyphicon glyphicon-minus-sign'></span> End Loan</a>";
												}


												Print	
													"&nbsp<a class='btn btn-primary' data-toggle='modal' data-target='#viewLoanHistory' onclick='load_history(\"".$empid."\", \"".$loanType."\")'><span class='glyphicon glyphicon-list-alt'></span> History</a>
												
												</td>
											</tr>
											";
										$noLoanChecker = false;
									}
								}
									
							}
						}
						if($noLoanChecker)
						{
							Print 
							"
							<tr><td colspan='6'><h3>No records found.</h3></td></tr>
							";
						}
					}
					else
					{
						$statement = "";
						$limit = "";
						$page = "";
						Print 
						"
						<tr><td colspan='6'><h3>No records found.</h3></td></tr>
						";
					}
				?>
				
			</table>
			</form>
		</div>	
	</div>
	<?php
			echo "<div id='pagingg' >";
			if($statement && $limit && $page)
				echo pagination($statement,$limit,$page);
			echo "</div>";
		?>
</div>

<input type="hidden" id="overallPayable" value="<?php Print numberExactFormat($overallPayable, 2, '.', true) ?>">

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

<script rel="javascript" src="js/jquery-ui/external/jquery/jquery.js"></script>
<script rel="javascript" src="js/jquery-ui/jquery-ui.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
	function load_data(query){
	  	$.ajax({
	   		url:"livesearch_loans.php",
		   	method:"POST",
		   	data:{query:query},
		   	success:function(data){
	    		$('#search_result_loans').html(data);
	   		}
	  	});
	}
	$('#search_text').keyup(function(){
	  	var search = $(this).val();
	  	if(search != ''){
	   		load_data(search);
	  	}
	  	else{
	   		load_data();
	  	}
	});

	var currentDate = "<?php echo $date; ?>";
	var dateToday = new Date();
	var twoWeeksAgo = new Date(dateToday.getFullYear(), dateToday.getMonth(), dateToday.getDate() - 14);

	/* DATE PICKER CONFIGURATIONS*/
	$( "#dtpkr_loan" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'MM dd, yy',
		showAnim: 'blind',
		maxDate: dateToday,
		minDate: twoWeeksAgo,
		beforeShow: function(){    
			$(".ui-datepicker").css('font-size', 15) 
		}
	});

	$("#dtpkr_loan").datepicker("setDate", currentDate);

	$("#dtpkr_loan").change(function(){
		var date = $(this).val();
		window.location.href = "date_loan.php?loandate="+date;
	});

	});
// Regex for loan input fields
function numValidate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

// Site filter
function site() {
	if(document.URL.match(/site=([0-9]+)/))
	{
		var arr = document.URL.match(/site=([0-9]+)/)
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
	var site = document.getElementById("site").value;
	var siteReplaced = site.replace(/\s/g , "+");
	localStorage.setItem("glob_site", siteReplaced);
	window.location.assign("loans_view.php?site="+siteReplaced+"&position="+localStorage.getItem('glob_position')+"&type=<?php Print $loanType?>");
}

// Position filter
function position() {
	if(document.URL.match(/position=([0-9]+)/))
	{
		var arr = document.URL.match(/position=([0-9]+)/)
		var positionUrl = arr[1];
		if(positionUrl)
		{
			localStorage.setItem("counter", 0);
		}
		else if(localStorage.getItem('counter') > 2)
		{
			localStorage.clear();
		}
	}
	var position = document.getElementById("position").value;
	var positionReplaced = position.replace(/\s/g , "+");
	localStorage.setItem("glob_position", positionReplaced);
	window.location.assign("loans_view.php?site="+localStorage.getItem("glob_site")+"&position="+positionReplaced+"&type=<?php Print $loanType?>");
}


// Setting active color of menu to Employees
document.getElementById("employees").setAttribute("style", "background-color: #10621e;");

var overall = document.getElementById("overallPayable").value;
document.getElementById("overallPlacing").innerHTML = overall;//dito

// Clearing filters
function clearFilter() {
	localStorage.clear();
	window.location.assign("loans_view.php?type=<?php Print $loanType?>");
}


function load_history(id, type)
{
	$.ajax({
		url:"fetch_loan_history.php",
		method:"POST",
		data:{
				empid: id,
				type: type
			},
		success:function(data)
		{
		$('#dynamicTable').html(data);
		}
	});
}

function deleteLoan(id, loan) {
	var a = confirm("Are you sure you want to remove this loan?");
	if(a)
		window.location.assign("logic_loans_delete.php?id="+id+"&loan="+loan);
}

function endLoan(id, loan) {
	var a = confirm("Are you sure you want to end this employee's loan?")
	if(a)
		window.location.assign("logic_loans_end.php?id="+id+"&loan="+loan);
}
</script>
</body>
</html>