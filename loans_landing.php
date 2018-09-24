<?php
include_once('directives/db.php');
include('directives/session.php');
require_once("directives/modals/addLoan.php");
?>
		<!DOCTYPE html>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css" type="text/css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">

		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- Modal pop up s-->
		

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li>
						<a href="employees.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a>
					</li>
					<li class="active">Loan Applications</li>
				</ol>
			</div>
		</div>

		<!-- Add new loan -->
		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h4>To add a new loan, search for an employee:</h4>
						<div class="form-group col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3" style="float:none">
							<input placeholder="Search for employee (can look up any part of employees name)" class="form-control" id="search_text">
						</div>
						 <h4>Loan date:</h4>
						 <div class="form-group col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3" style="float:none">
						 	<input name="txt_attendance" type="text" size="10" class="form-control" value = <?php
							if(isset($_SESSION['date']))
							{
								$date = $_SESSION['date'];
								Print "'". $date ."'";
							}
							else
							{
								$date = strftime("%B %d, %Y");
								Print '""';
							}
							?> id="dtpkr_loan" placeholder="mm-dd-yyyy">
						</div>
					</div>
				</div>

				<div id="search_result_loans" class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1"></div>
				 
				

		</div>

		<!-- View loans -->
		<div class="col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>To view employee loans, first select a loan type:</h4>
					<a class="btn btn-success btn-lg" href="loans_view.php?type=SSS">SSS</a>
					<a class="btn btn-success btn-lg" href="loans_view.php?type=PAGIBIG">PagIBIG</a>
					<a class="btn btn-success btn-lg" href="loans_view.php?type=oldVale">Old Vale</a>
					<a class="btn btn-success btn-lg" href="loans_view.php?type=newVale">New Vale</a>
				</div>
			</div>
		</div>
		<?php
		// Algorithm for Dashboard
		function loanDashboard($type)
		{
			if($type == 'empVale')//Employees with vale
			$empVale = "SELECT DISTINCT loans.empid FROM loans INNER JOIN employee ON loans.empid = employee.empid WHERE loans.type = 'oldVale' OR loans.type = 'newVale'";
			if($type == 'newVale')//Employees with new vale
			$empVale = "SELECT DISTINCT loans.empid FROM loans INNER JOIN employee ON loans.empid = employee.empid WHERE loans.type = 'newVale'";
			if($type == 'oldVale')//Employees with old vale
			$empVale = "SELECT DISTINCT loans.empid FROM loans INNER JOIN employee ON loans.empid = employee.empid WHERE loans.type = 'oldVale'";

			$empValeQuery = mysql_query($empVale)or die(mysql_error());
			$ValeNum = mysql_num_rows($empValeQuery);
			$counter = 0;//Counter for the employees with vale
			$newValeComputation = 0;
			$oldValeComputation = 0;
			if(!empty($ValeNum))
			{
				while($row = mysql_fetch_assoc($empValeQuery))
				{
					$empid = $row['empid'];
					
					if($type == 'empVale')//Employees with vale
					{
						Print "<script>console.log('".$empid."')</script>";
						$checkerQuery = mysql_query("SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' OR type = 'newVale' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1") or die(mysql_error());
					}
					else if($type == 'newVale')//Employees with new vale
						$checkerQuery = mysql_query("SELECT * FROM loans WHERE empid = '$empid' AND type = 'newVale' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1") or die(mysql_error());
					else if($type == 'oldVale')//Employees with old vale
						$checkerQuery = mysql_query("SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC LIMIT 1")or die(mysql_error());

					// Print "<script>console.log('checkerQuery: ".mysql_num_rows($checkerQuery)."')</script>";

					if(mysql_num_rows($checkerQuery) != 0)
					{

						$checkRow = mysql_fetch_assoc($checkerQuery);
						if($type == 'empVale' && $checkRow['balance'] != 0)//Employees with vale
							$counter++;
						else if($type == 'newVale')//Company cost to newvale
							$newValeComputation += $checkRow['balance'];
						else if($type == 'oldVale')//Company cost to oldvale
							$oldValeComputation += $checkRow['balance'];
					}
				}
			}
			if($type == 'empVale')//Employees with vale
				$output = $counter;
			else if($type == 'newVale')//Company cost to newvale
				$output = $newValeComputation;
			else if($type == 'oldVale')//Company cost to oldvale
				$output = $oldValeComputation;
			return $output;
		}
		
		$counter = loanDashboard("empVale");
		$newValeComputation = loanDashboard("newVale");
		$oldValeComputation = loanDashboard("oldVale");

		//New vale loaned to employees
		?>
		<!-- Dashboard -->
		<div class="row col-md-1 col-lg-10 col-md-offset-1 col-lg-offset-1">
				<table class="table table-bordered table-responsive" style="color: white; font-family:Quicksand">
					<tr>
						<td style="background-color:chocolate">
							<h3 class="text-center">Employees with VALE</h3>
							<h2 class="text-center"><br>
								<?php  Print $counter ?>
							</h2>
						</td>
						<td style="background-color: darkgray">
							<h3 class="text-center">OLD VALE loaned to Employees</h3>
							<br><br>
							<h2 class="text-center"><?php Print "₱" . number_format($oldValeComputation, 2, '.', ','); ?></h2><br>
						</div>
					</td>
					<td style="background-color: cornflowerblue">
						<h3 class="text-center">NEW VALE loaned to Employees</h3>
						<br><br>
						<h2 class="text-center"><?php Print "₱" . number_format($newValeComputation, 2, '.', ','); ?></h2><br>
					</td>
				</tr>
			</table>
	</div>

</div>
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script rel="javascript" src="js/accounting.min.js"></script>
	<script rel="javascript" src="js/timepicker/jquery.timepicker.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/multiple-select.js"></script>
	<script>
		// Setting active color of menu to Employees
		document.getElementById("employees").setAttribute("style", "background-color: #10621e;");

		$(document).ready(function(){
		 function load_data(query)
		 {
		  $.ajax({
		   url:"livesearch_loans.php",
		   method:"POST",
		   data:{query:query},
		   success:function(data)
		   {
		    $('#search_result_loans').html(data);
		   }
		  });
		 }
		 $('#search_text').keyup(function(){
		  var search = $(this).val();
		  if(search != '')
		  {
		   load_data(search);
		  }
		  else
		  {
		   load_data();
		  }
		 });

		 $("select").multipleSelect({
			placeholder: "Select site for attendance&#9662;",
			selectAll: false,
			width: 200,
			multiple: true,
			multipleWidth: 200
		});

		var currentDate = "<?php Print "$date"; ?>";
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

		});

		function validateLoanFields(row) {
			var addMoreLoans = document.getElementById('add_more_loans');
			var loanType = document.getElementsByName('loanType[]');
			var element;
			var loanTypeSelected = [];

			for(var a = 0; a < loanType.length; a++) { 
				element = document.getElementsByName('loanType[]')[a];
				loanTypeSelected[a] = element.options[element.selectedIndex].value;
				console.log('Selected values: '+loanTypeSelected);
			}

			var sorted_arr = loanTypeSelected.slice().sort();

			var results = [];
			for (var i = 0; i < sorted_arr.length - 1; i++) {
			    if (sorted_arr[i + 1] == sorted_arr[i]) {
			        results.push(sorted_arr[i]);
			    }
			}

			console.log('Duplicate values: '+results);

			if(loanType.length >= 1 && loanType.length < 4 && results.length === 0) {
				console.log('You have just enough forms.');
				addMoreLoans.removeAttribute('disabled');
			} if(loanType.length == 4) {
				console.log('You have reached the maximum amount of loans to add for today.');
				addMoreLoans.setAttribute('disabled', '');
			} if(results.length > 0 && results.every(function(element) {return !!element;})) {
				alert('You have duplicate loan types: ' + results);
				if(row.id == 'loanType')
				   	row.value = '';
			}
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
			// var monthly = parent.querySelector('#monthly').value;
			var rate = parent.querySelector('#rate').value;
			var sss = parent.querySelector('#sss').value;
			var pagibig = parent.querySelector('#pagibig').value;
			var oldvale = parent.querySelector('#oldvale').value;
			var newvale = parent.querySelector('#newvale').value;
			// Move values to modal
			document.getElementById('empid').value = empid;
			document.getElementById('fname').value = firstname;
			document.getElementById('lname').value = lastname;
			document.getElementById('address').value = address;
			document.getElementById('contact').value = contactnum;
			document.getElementById('position&site').innerHTML = "<h5>"+position+" at "+ site;
			document.getElementById('position').value = position;
			document.getElementById('site').value = site;
			document.getElementById('rate').value = accounting.formatNumber(rate, 2, ",");
			//done display if value is equal to Zero
			if(sss != 0)
			document.getElementById('sss').value = accounting.formatNumber(sss, 2, ",");
			if(pagibig != 0)
			document.getElementById('pagibig').value = accounting.formatNumber(pagibig, 2, ",");
			if(oldvale != 0)
			document.getElementById('oldvale').value = accounting.formatNumber(oldvale, 2, ",");
			if(newvale != 0)
			document.getElementById('newvale').value = accounting.formatNumber(newvale, 2, ",");

		}

		function addRow() {
			var loansLength = document.getElementsByName('loanAmount[]').length;

			var ct = parseInt(loansLength);
		
			var div1 = document.createElement('div');
			div1.id = ct;
			div1.setAttribute('name','loansRow[]');

			if(ct != 4) {// If added loans is not equal to 3 
			
			var delLink = '<div class="col-md-1 col-lg-1 nopadding">'+
			'<button class="btn-sm btn btn-danger" name="rowDelete[]" onclick="deleteRow('+ ct +')">'+
			'<span class="glyphicon glyphicon-minus"></span>'+
			'</button>'+
			'</div>';

			var template = '<div class="row">'  +
									'<div class="form-group col-md-4 col-lg-4">' +
										"<select class='form-control check-input' name='loanType[]' required id='loanType' onchange='validateLoanFields(this)'>" +
											'<option disabled value="" selected>Loan type</option>' +
											'<option value="SSS">SSS</option>' +
											'<option value="PagIBIG">PagIBIG</option>' +
											'<option value="oldVale">Old vale</option>' +
											'<option value="newVale">New vale</option>' +
										'</select>' +
									'</div>' +
									'<div class="col-md-5 col-lg-5">' +
										"<input type='text' class='form-control check-input' required name='loanAmount[]' id='loanAmount' placeholder='Amount of loan' onchange='validateLoanFields(this)' onblur='formcheck()'>" +
									'</div>' +
								'</div>' +
								'<div class="row">' +
									'<div class="col-md-offset-1 col-lg-offset-1">' +
										"<textarea class='form-control check-input' rows='2' required id='reason' name='reason[]' placeholder='Reason for getting a loan' onchange='validateLoanFields(this)' onblur='formcheck()'></textarea>" +
									'</div><br>' +
								'</div>';
			div1.innerHTML = delLink + template;
			document.getElementById('loanform').appendChild(div1);								
								
			} else {
				alert("You have reached the limit for adding loans");
			}
		}

		function deleteRow(eleId) {
			var ele = document.getElementById(eleId);
			var parentEle = document.getElementById('loanform');
			parentEle.removeChild(ele);

			var toolsLength = document.getElementsByName('loansRow[]').length;
			if(toolsLength > 1)
			{
				for(var count = 0; count < toolsLength; count++)
				{

					document.getElementsByName('loansRow[]')[count].setAttribute('id',count+1);
					document.getElementsByName('rowDelete[]')[count].setAttribute('onclick','deleteRow('+(count+1)+')');
					document.getElementById('add_more_loans').removeAttribute('disabled');
				}
			}
		}

	</script>
</body>
</html>



