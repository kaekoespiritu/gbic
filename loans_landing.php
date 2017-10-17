<!DOCTYPE html>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">

		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- Modal pop up s-->
		<?php
		require_once("directives/modals/addLoan.php");
		?>

		<!-- Breadcrumbs -->
		<div class="row">
			<div class="col-md-10 col-md-offset-1 pull-down">
				<ol class="breadcrumb text-left">
					<li>
						<a href="employees.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Employees</a>
					</li>
					<li class="active">Loan Applications</li>
				</ol>
			</div>
		</div>

		<!-- Add new loan -->
		<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h4>To add a new loan, search for an employee:</h4>
						<div class="form-group col-md-6 col-md-offset-3" style="float:none">
							<input placeholder="Search for employee (can look up any part of employees name)" class="form-control" id="search_text">
						</div>

					</div>
				</div>

				<div id="search_result"></div>
				 

		</div>

		<!-- View loans -->
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>To view employee loans, first select a loan type:</h4>
					<a class="btn btn-success btn-lg" href="loans_view.php">SSS</a>
					<a class="btn btn-success btn-lg" href="loans_view.php">PagIBIG</a>
					<a class="btn btn-success btn-lg" href="loans_view.php">Old Vale</a>
					<a class="btn btn-success btn-lg" href="loans_view.php">New Vale</a>
				</div>
			</div>
		</div>

		<!-- Dashboard -->
		<div class="row col-md-10 col-md-offset-1">
				<table class="table table-bordered table-responsive" style="color: white; font-family:Quicksand">
					<tr>
						<td style="background-color:chocolate">
							<h2><br>
								<u>NUMBER</u>
							</h2><br>
							<h3>Employees with VALE</h3>
						</td>
						<td style="background-color: darkgray">
							<h3 class="text-center">OLD VALE loaned to Employees</h3>
							<br><br>
							<h2 class="text-center"><u>AMOUNT in PESO</u></h2><br>
						</div>
					</td>
					<td style="background-color: cornflowerblue">
						<h3 class="text-center">NEW VALE loaned to Employees</h3>
						<br><br>
						<h2 class="text-center"><u>AMOUNT in PESO</u></h2><br>
					</td>
				</tr>
			</table>
	</div>

</div>
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		// Setting active color of menu to Employees
		document.getElementById("employees").setAttribute("style", "background-color: #10621e;");

		$(document).ready(function(){
		 function load_data(query)
		 {
		  $.ajax({
		   url:"loans_livesearch.php",
		   method:"POST",
		   data:{query:query},
		   success:function(data)
		   {
		    $('#search_result').html(data);
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
		});

		function sendToModal(id){
			var parent = document.getElementById(id);
			// Getting values from the searched employee
			var firstname = parent.querySelector('#firstname').value;
			var lastname = parent.querySelector('#lastname').value;
			var address = parent.querySelector('#address').value;
			var contactnum = parent.querySelector('#contactnum').value;
			var position = parent.querySelector('#position').value;
			var site = parent.querySelector('#site').value;
			var monthly = parent.querySelector('#monthly').value;
			var rate = parent.querySelector('#rate').value;
			// Move values to modal
			document.getElementById('fname').value = firstname;
			document.getElementById('lname').value = lastname;
			document.getElementById('address').value = address;
			document.getElementById('contact').value = contactnum;
			document.getElementById('position&site').innerHTML = "<h5>"+position+" at "+ site;
			document.getElementById('position').value = position;
			document.getElementById('site').value = site;
			document.getElementById('monthlysalary').value = monthly;
			document.getElementById('rate').value = rate;

		}
	</script>
</body>
</html>



