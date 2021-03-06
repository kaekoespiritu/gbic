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

$siteFilter = 'null';
$positionFilter = 'null';
$pageFilter = 1;
if(isset($_GET['site']))
	$siteFilter = $_GET['site'];
if(isset($_GET['position']))
	$positionFilter = $_GET['position'];
if(isset($_GET['page']))
	$pageFilter = $_GET['page'];
$overallPayable = 0;

$dateNewLoan = (isset($_SESSION['loanviewdate']) ? $_SESSION['loanviewdate'] : $date);
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
								<input name="txt_attendance" type="text" size="10" class="form-control" value = "<?php
							Print $dateNewLoan;
							?>" id="dtpkr_loan" placeholder="mm-dd-yyyy" readonly>
							</div>
							<div class="col-md-12 pull-down">
								<input type="text" class="form-control pull-right input-sm" placeholder="Search Employee to add loan" id="search_text">
							</div>
							<div class="row">
								<div id="search_result_loans" style="right:220px; top: 231px;"></div>
							</div>
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
									<input type="hidden" name="inOrOut" value="insideLoans">
									<div class="row">
										<div class="col-md-3 col-lg-3">
											<label for="fname">First name</label>
										</div>
										<div class="col-md-9 col-lg-9">
											<input onkeypress="validateletter(event)" type="text" class="form-control" id="fname" name="firstname" readonly>
											<input type="hidden" id="empidModal" name="empid" value>
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

									<h4 class="modal-title">New Loan Details for [<?php echo (isset($_SESSION["loanviewdate"]) ? $_SESSION["loanviewdate"] : strftime("%B %d, %Y")) ?>]</h4><hr>
									<input type="hidden" id="loandate" name="loandate" value="<?php echo (isset($_SESSION['loanviewdate']) ? $_SESSION['loanviewdate'] : strftime('%B %d, %Y'))?>" >
									<input type="hidden" name="loanShortcut" value="<?php Print $loanType?>">

									<div class="form-group" id="loanform">
										<div class="row">
											<div class="form-group col-md-4 col-lg-4 col-md-push-1 col-lg-push-1">
												<input type="text" class="form-control" value="<?php echo $displayLoan ?>" readonly>
												<input type="hidden" class="form-control" name="loanType" value="<?php echo $loanType ?>">
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
											<label for="rateModal">Rate Per Day</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="rate" type="text" class="form-control" id="rateModal" readonly>
										</div>
									</div><br>

									<h4 class="modal-title">Pending loans</h4><hr>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="oldvaleModal">Old vale</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_oldVale"  type="text" class="form-control" id="oldvaleModal" placeholder="--" readonly>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="newvaleModal">New vale</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_newVale"  type="text" class="form-control" id="newvaleModal" placeholder="--" readonly>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="sssModal">SSS</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_SSS"  type="text" class="form-control" id="sssModal" placeholder="--" readonly>
										</div>
									</div><br>
									<div class="row">
										<div class="col-md-5 col-lg-5">
											<label for="pagibigModal">PagIBIG</label>
										</div>
										<div class="col-md-5 col-lg-5">
											<input name="txt_pagibig"  type="text" class="form-control" id="pagibigModal" placeholder="--" readonly>
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
						<input type="text" placeholder="Search then press Enter" id="search_box" name="txt_search" class="form-control">
					</div>
				</div>

				<div class="col-md-3 col-lg-3 text-left">
					<h3>Overall Payable: <span id="overallPlacing"></span></h3>
				</div>

				<div class="col-md-4 col-lg-4 text-right">
					<span class="col-md-2">Filter:</span>

					<!-- Filter by POSITION -->

					<div class="btn-group">
						<select class="form-control" id="position" onchange="position(this.value)">

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
						<select class="form-control" id="site" onchange="site(this.value)">

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
					<button type="button" class="btn btn-danger col-md-3" onclick="clearFilter()">Clear Filters</button>
				</div>
			</div>

		</div>


		<br>


		<!-- EMPLOYEE TABLE -->
		<div class="row">
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
				<!-- SEARCH result -->
				<div id="search_result" class="col-md-12 col-lg-12 search-results-table">
				</div>
			</form>
		</div>	
	</div>
</div>
<input type="hidden" id="toTransfer" value="0">
<input type="hidden" id="siteFilter" value="<?php Print $siteFilter ?>">
<input type="hidden" id="positionFilter" value="<?php Print $positionFilter ?>">
<input type="hidden" id="loanTypeFilter" value="<?php Print $loanType ?>">
<input type="hidden" id="pageFilter" value="<?php Print $pageFilter ?>">

<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/accounting.min.js"></script>
<script rel="javascript" src="js/jquery-ui/external/jquery/jquery.js"></script>
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/jquery-ui/jquery-ui.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
function getOverallPayable() {
	var overall = document.getElementById("overallPayable").value;
	document.getElementById("overallPlacing").innerHTML = overall;
}
function transfer() {
		var overall = $('#toTransfer').val();
		$('#overallPlacing').val(overall);
	}

$(document).ready(function(){


	//-------------------
	var site = $('#siteFilter').val();
	var position = $('#positionFilter').val();
	var loanType = $('#loanTypeFilter').val();
	var page = $('#pageFilter').val();;

	load_employees("", site, position, loanType, page);// the "" is the search
	function load_employees(search, site, position, loanType, page)
	{
	  	$.ajax({
	   		url:"livesearch_loans_employees.php",
	   		method:"POST",
	   		data:{
	   			search : search,
	   			site : site,
	   			loanType : loanType,
	   			position_page : position,
	   			page : page
	   		},
	   		success:function(data)
	   		{
	    		$('#search_result').html(data);
	   		}
	  	});
	  	// getOverallPayable();// get overall payable to display
	}
	$('#search_box').keyup(function(){
		console.log($(this).val())
	  	var search = $(this).val();
	   	load_employees(search, site, position, loanType, page);
	  	
	});
	//-------------------
	
	
	function load_data(query){
	  	$.ajax({
	   		url:"livesearch_loans.php",
		   	method:"POST",
		   	data:{query:query},
		   	success:function(data){
	    		$('#search_result_loans').html(data);
	   		}
	  	});
	  	// alert("yo");
	  	
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
	var currentDate = "<?php echo $dateNewLoan; ?>";
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
		window.location.href = "date_loan.php?loanviewdate="+date+"&type=<?php Print $loanType?>";
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
function site(val) {
	window.location.assign("loans_view.php?site="+val+"&position=<?php Print $positionFilter?>&type=<?php Print $loanType?>");
}
// Position filter
function position(pos) {
	window.location.assign("loans_view.php?site=<?php Print $siteFilter?>&position="+pos+"&type=<?php Print $loanType?>");
}
// Setting active color of menu to Employees
document.getElementById("employees").setAttribute("style", "background-color: #10621e;");


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

function sendToModal(id){
			var parent = document.getElementById(id);
			// Getting values from the searched employee
			var empid = parent.querySelector('#empid').value;
			var firstname = parent.querySelector('#firstname').value;
			var lastname = parent.querySelector('#lastname').value;
			var address = parent.querySelector('#address').value;
			var contactnum = parent.querySelector('#contactnum').value;
			var position = parent.querySelector('#position').value;
			var site = parent.querySelector('#site').value;
			var rate = parent.querySelector('#rate').value;
			var sss = parent.querySelector('#sss').value;
			var pagibig = parent.querySelector('#pagibig').value;
			var oldvale = parent.querySelector('#oldvale').value;
			var newvale = parent.querySelector('#newvale').value;
			var loandate = parent.querySelector('#loandate').value;

			// Move values to modal
			document.getElementById('empidModal').value = empid;
			document.getElementById('fname').value = firstname;
			document.getElementById('lname').value = lastname;
			document.getElementById('address').value = address;
			document.getElementById('contact').value = contactnum;
			document.getElementById('position&site').innerHTML = "<h5>"+position+" at "+ site;
			document.getElementById('position').value = position;
			document.getElementById('site').value = site;
			document.getElementById('rateModal').value =  accounting.formatNumber(rate, 2, ",");
			document.getElementById('loandate').value = "<?php echo $date; ?>";
			// console.log(loandate);
			//done display if value is equal to Zero
			if(sss != 0)
			document.getElementById('sssModal').value = accounting.formatNumber(sss, 2, ",");
			if(pagibig != 0)
			document.getElementById('pagibigModal').value = accounting.formatNumber(pagibig, 2, ",");
			if(oldvale != 0)
			document.getElementById('oldvaleModal').value = accounting.formatNumber(oldvale, 2, ",");
			if(newvale != 0)
			document.getElementById('newvaleModal').value = accounting.formatNumber(newvale, 2, ",");
		}
</script>
</body>
</html>